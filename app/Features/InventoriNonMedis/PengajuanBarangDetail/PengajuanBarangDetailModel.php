<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengajuanBarangDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PengajuanBarangDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengajuanBarangDetailDatabase(),
            [
                'id_detail'        => V::DEFAULT(),
                'nama_barang_baru' => V::DEFAULT(),
                'qty'              => V::DEFAULT(),
                'qty_disetujui'    => V::DEFAULT(),
                'harga'            => V::DEFAULT(),
                'subtotal'         => V::DEFAULT(),
            ],
            [
                'id_pengajuan' => [
                    'tanggal',
                    'id_status_pengajuan_barang' => ['nama_status_pengajuan_barang'],
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
            ->select('b.id_barang, b.nama_barang, b.harga_satuan, s.nama_satuan')
            ->get()->getResultArray();

        $satuan = array_column($rows, 'nama_satuan', 'id_barang');
        $harga  = array_column($rows, 'harga_satuan', 'id_barang');

        $options['id_barang'] = array_map(
            fn($row) => [
                $row['nama_barang'],
                (string) $row['id_barang'],
                [
                    '_autofill'      => ['nama_satuan' => 'satuan', 'harga' => 'harga'],
                    'satuan'         => $satuan[$row['id_barang']] ?? '',
                    'harga'          => (string) ($harga[$row['id_barang']] ?? ''),
                ],
            ],
            $rows
        );

        return $options;
    }
}
