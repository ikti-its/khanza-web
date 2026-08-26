<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengadaanBarang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PengadaanBarangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengadaanBarangDatabase(),
            [
                'id_pengadaan' => V::DEFAULT(),
                'no_pengadaan' => V::DEFAULT(),
                'tanggal'      => V::DEFAULT(),
                'catatan'      => V::DEFAULT(),
                'total_harga'  => V::DEFAULT(),
            ],
            [
                'id_pengajuan'               => ['no_pengajuan'],
                'id_suplier'                 => ['nama_suplier'],
                'id_status_pengadaan_barang' => ['nama_status_pengadaan_barang'],
            ],
        );
    }

    // narrows the query-result union (bool|Query|BaseResult) that mago infers
    // for ->get()/->query(), matching ModelTemplate::guarded_get() convention.
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function guarded(mixed $result): \CodeIgniter\Database\BaseResult
    {
        assert($result instanceof \CodeIgniter\Database\BaseResult, 'Query gagal dieksekusi.');
        return $result;
    }

    // hanya tampilkan pengajuan Disetujui yang masih punya sisa qty belum dipesan
    // dan tidak memiliki barang baru yang belum dipetakan ke master barang
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function get_all_options(): array
    {
        $options = parent::get_all_options();

        if (isset($options['id_pengajuan'])) {
            $rows = $this->guarded($this->db->query('
                SELECT DISTINCT pjd.id_pengajuan
                FROM inventori_non_medis.pengajuan_barang_detail pjd
                JOIN inventori_non_medis.pengajuan_barang pj ON pjd.id_pengajuan = pj.id_pengajuan
                WHERE pjd.id_barang > 0
                  AND pjd.qty_disetujui > 0
                  AND pj.id_status_pengajuan_barang = 2
                  AND (
                    SELECT COALESCE(SUM(pbd.qty), 0)
                    FROM inventori_non_medis.pengadaan_barang_detail pbd
                    JOIN inventori_non_medis.pengadaan_barang pb ON pbd.id_pengadaan = pb.id_pengadaan
                    WHERE pb.id_pengajuan = pjd.id_pengajuan
                      AND pbd.id_barang = pjd.id_barang
                  ) < pjd.qty_disetujui
                  AND NOT EXISTS (
                    SELECT 1
                    FROM inventori_non_medis.pengajuan_barang_detail unmapped
                    WHERE unmapped.id_pengajuan = pjd.id_pengajuan
                      AND unmapped.id_barang IS NULL
                      AND unmapped.qty_disetujui > 0
                  )
            '))->getResultArray();

            $available = array_map(fn(array $r) => (string) $r['id_pengajuan'], $rows);

            $options['id_pengajuan'] = array_values(array_filter($options['id_pengajuan'], fn(array $opt) => in_array(
                $opt[1],
                $available,
                true,
            )));
        }

        return $options;
    }
}
