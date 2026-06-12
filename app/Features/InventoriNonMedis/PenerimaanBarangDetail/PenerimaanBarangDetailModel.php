<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PenerimaanBarangDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;
use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Database\BaseResult;

final class PenerimaanBarangDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenerimaanBarangDetailDatabase(),
            [
                'id_detail'     => V::DEFAULT(),
                'qty_diterima'  => V::DEFAULT(),
                'harga_satuan'  => V::DEFAULT(),
            ],
            [
                'id_penerimaan' => ['tanggal', 'status'],
                'id_barang' => [
                    'nama_barang',
                    'kode_barang',
                    'id_satuan' => ['nama_satuan'],
                ],
            ],
        );
    }

    private function build_query(): BaseBuilder
    {
        return $this->db
            ->table('inventori_non_medis.penerimaan_barang_detail m')
            ->select('m.*')
            ->select('b.nama_barang, b.kode_barang')
            ->select('s.nama_satuan')
            ->select('pbd.qty AS qty_pengadaan')
            ->join('inventori_non_medis.barang b',
                   'm.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s',
                   'b.id_satuan = s.id_satuan', 'left')
            ->join('inventori_non_medis.penerimaan_barang pb',
                   'm.id_penerimaan = pb.id_penerimaan', 'left')
            ->join('inventori_non_medis.pengadaan_barang_detail pbd',
                   'pb.id_pengadaan = pbd.id_pengadaan AND pbd.id_barang = m.id_barang', 'left');
    }

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
        return $result->getResultArray();
    }

    public function find_one(int|string $id): array|null
    {
        $builder = $this->build_query();
        $builder->where("m.{$this->primaryKey}", $id);
        $result = $builder->get();
        assert($result instanceof BaseResult);
        $row = $result->getRowArray();
        return is_array($row) ? $row : null;
    }
}
