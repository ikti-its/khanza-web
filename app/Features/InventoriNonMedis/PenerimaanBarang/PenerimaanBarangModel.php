<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PenerimaanBarang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PenerimaanBarangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenerimaanBarangDatabase(),
            [
                'id_penerimaan'               => V::DEFAULT(),
                'tanggal'                     => V::DEFAULT(),
                'status'                      => V::DEFAULT(),
                'catatan'                     => V::DEFAULT(),
                'no_penerimaan'               => V::DEFAULT(),
                'no_masuk'                    => V::DEFAULT(),
            ],
            [
                'id_pengadaan'                => ['no_pengadaan'],
                'id_status_penerimaan_barang' => ['nama_status_penerimaan_barang'],
                'petugas'                     => ['id_orang' => ['nama']],
            ],
        );
    }

    // hanya tampilkan pengadaan yang masih punya sisa qty belum diterima (status Lengkap = 2)
    public function get_all_options(): array
    {
        $options = parent::get_all_options();

        if (isset($options['id_pengadaan'])) {
            $rows = $this->db->query("
                SELECT DISTINCT pbd.id_pengadaan
                FROM inventori_non_medis.pengadaan_barang_detail pbd
                WHERE pbd.id_barang > 0
                  AND pbd.qty > 0
                  AND (
                    SELECT COALESCE(SUM(prbd.qty_diterima), 0)
                    FROM inventori_non_medis.penerimaan_barang_detail prbd
                    JOIN inventori_non_medis.penerimaan_barang prb ON prbd.id_penerimaan = prb.id_penerimaan
                    WHERE prb.id_pengadaan = pbd.id_pengadaan
                      AND prbd.id_barang = pbd.id_barang
                      AND prb.id_status_penerimaan_barang = 2
                  ) < pbd.qty
            ")->getResultArray();

            $available = array_map(fn(array $r) => (string) $r['id_pengadaan'], $rows);

            $options['id_pengadaan'] = array_values(
                array_filter(
                    $options['id_pengadaan'],
                    fn(array $opt) => in_array($opt[1], $available, true),
                )
            );
        }

        return $options;
    }
}
