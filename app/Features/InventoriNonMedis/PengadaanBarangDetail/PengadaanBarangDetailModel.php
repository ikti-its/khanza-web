<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengadaanBarangDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;
use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Database\BaseResult;

final class PengadaanBarangDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengadaanBarangDetailDatabase(),
            [
                'id_detail'    => V::DEFAULT(),
                'qty'          => V::DEFAULT(),
                'harga_satuan' => V::DEFAULT(),
                'subtotal'     => V::DEFAULT(),
            ],
            [
                'id_barang' => [
                    'nama_barang',
                    'kode_barang',
                    'id_satuan' => ['nama_satuan'],
                ],
            ],
        );
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function build_query(): BaseBuilder
    {
        return $this->db
            ->table('inventori_non_medis.pengadaan_barang_detail m')
            ->select('m.*')
            ->select('b.nama_barang, b.kode_barang')
            ->select('s.nama_satuan')
            ->select('pjd.qty_disetujui')
            ->join('inventori_non_medis.barang b', 'm.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->join('inventori_non_medis.pengadaan_barang pb', 'm.id_pengadaan = pb.id_pengadaan', 'left')
            ->join(
                'inventori_non_medis.pengajuan_barang_detail pjd',
                'pb.id_pengajuan = pjd.id_pengajuan AND pjd.id_barang = m.id_barang',
                'left',
            );
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function findAll(int|null $limit = 10, int $offset = 0): array
    {
        $builder = $this->build_query();
        foreach ($this->runtime_filters as $col => $val) {
            $builder->where("m.{$col}", $val);
        }
        if ($limit !== null && $limit > 0) {
            $builder->limit($limit, $offset);
        }
        $result = $builder->get();
        assert($result instanceof BaseResult);
        /** @var list<array<string, mixed>> */
        return $result->getResultArray();
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function find_one(int|string $id): array|null
    {
        $builder = $this->build_query();
        $builder->where("m.{$this->primaryKey}", $id);
        $result = $builder->get();
        assert($result instanceof BaseResult);
        $row = $result->getRowArray();
        /** @var array<string, mixed>|null */
        return is_array($row) ? $row : null;
    }
}
