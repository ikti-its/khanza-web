<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PengambilanPenunjang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PengambilanPenunjangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengambilanPenunjangModel(),
            [
                ['Logistik UTD',              'logistik_utd'],
                ['Pengambilan BHP Non Medis', 'pengambilan_bhp_non_medis'],
            ],
            'Pengambilan BHP Non Medis',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_pengambilan_penunjang', 'ID Pengambilan Penunjang'],
                [SHOW, REQUIRED, I::INDEX,  'id_barang',                'ID Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah',                   'Jumlah'],
                [SHOW, REQUIRED, I::MONEY,  'harga_beli',               'Harga Beli'],
                [HIDE, REQUIRED, I::INDEX,  'id_petugas_gudang',        'ID Petugas Gudang'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_pengambilan',      'Tanggal Pengambilan'],
                [HIDE, REQUIRED, I::TEXT,   'keterangan',               'Keterangan'],
            ],
        );
    }

    /**
     * Menampilkan data modal BHP Non Medis (Penunjang) ruangan UTD
     */
    public function list()
    {
        $modelPengambilanPenunjang = new \App\Features\LogistikUTD\PengambilanPenunjang\PengambilanPenunjangModel();
        $rawPenunjang = $modelPengambilanPenunjang->get_katalog_dan_stok_ruangan();

        $dataModal = [];
        foreach ($rawPenunjang as $row) {
            $sisaStok = (int)$row['total_masuk'] - 
                        (int)$row['total_terpakai_donor'] - 
                        (int)$row['total_terpakai_pemisahan'] - 
                        (int)$row['total_terpakai_penyerahan'] - 
                        (int)$row['total_rusak'];

            if ((int)$row['total_masuk'] > 0) {
                $dataModal[] = [
                    'id_barang'       => $row['id_barang'],
                    'kode_barang'     => $row['kode_barang'],
                    'nama_barang'     => $row['nama_barang'],
                    'harga'           => (float)($row['harga'] ?? 0),
                    'harga_formatted' => 'Rp ' . number_format((float)($row['harga'] ?? 0), 0, ',', '.'),
                    'stok'            => $sisaStok
                ];
            }
        }

        return $this->response->setJSON([
            'data' => $dataModal
        ]);
    }
}
