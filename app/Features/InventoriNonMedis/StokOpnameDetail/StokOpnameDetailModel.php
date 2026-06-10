<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\StokOpnameDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class StokOpnameDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StokOpnameDetailDatabase(),
            [
                'id_detail'   => V::DEFAULT(),
                'stok_sistem' => V::DEFAULT(),
                'stok_fisik'  => V::DEFAULT(),
                'selisih'     => V::DEFAULT(),
                'catatan'     => V::DEFAULT(),
            ],
            [
                'id_opname' => [
                    'tanggal',
                    'id_status_stok_opname' => ['nama_status_stok_opname'],
                ],
                'id_barang' => [
                    'nama_barang',
                    'kode_barang',
                    'id_satuan' => ['nama_satuan'],
                ],
            ],
        );
    }

    public function get_all_options(): array
    {
        $options = parent::get_all_options();

        $rows = $this->db
            ->table('inventori_non_medis.barang b')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->select('b.id_barang, b.nama_barang, b.kode_barang, b.stok, s.nama_satuan')
            ->get()->getResultArray();

        $options['id_barang'] = array_map(
            fn($row) => [
                $row['nama_barang'],
                (string) $row['id_barang'],
                [
                    '_autofill'   => [
                        'kode_barang' => 'kode_barang',
                        'nama_satuan' => 'nama_satuan',
                        'stok_sistem' => 'stok_sistem',
                    ],
                    'kode_barang' => $row['kode_barang'] ?? '',
                    'nama_satuan' => $row['nama_satuan'] ?? '',
                    'stok_sistem' => (string) ($row['stok'] ?? '0'),
                ],
            ],
            $rows
        );

        return $options;
    }
}
