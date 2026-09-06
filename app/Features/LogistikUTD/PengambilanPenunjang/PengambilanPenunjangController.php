<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PengambilanPenunjang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;

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
                [SHOW, REQUIRED, I::INDEX,  'id_barang',                'Kode Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah',                   'Jumlah'],
                [SHOW, REQUIRED, I::MONEY,  'harga_beli',               'Harga Beli'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas_gudang',        'Petugas Gudang'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_pengambilan',      'Tanggal Pengambilan'],
                [SHOW, REQUIRED, I::TEXT,   'keterangan',               'Keterangan'],
            ],
        );
    }

    /**
     * Menampilkan data modal BHP Non Medis (Penunjang) ruangan UTD
     * 
     * @throws DatabaseException
     */
    public function list(): ResponseInterface
    {
        $modelPengambilanPenunjang = new \App\Features\LogistikUTD\PengambilanPenunjang\PengambilanPenunjangModel();
        $rawPenunjang              = $modelPengambilanPenunjang->get_katalog_dan_stok_ruangan();

        $dataModal = [];
        foreach ($rawPenunjang as $row) {
            $sisaStok =
                (int) ($row['total_masuk'] ?? 0) - (int) ($row['total_terpakai_donor'] ?? 0) - (int) ($row['total_terpakai_pemisahan'] ?? 0)
                - (int) ($row['total_terpakai_penyerahan'] ?? 0)
                - (int) ($row['total_rusak'] ?? 0);

            if ((int) ($row['total_masuk'] ?? 0) > 0) {
                $dataModal[] = [
                    'id_barang'       => $row['id_barang'] ?? 0,
                    'kode_barang'     => $row['kode_barang'] ?? '-',
                    'nama_barang'     => $row['nama_barang'] ?? '-',
                    'harga'           => $row['harga'] ?? 0,
                    'harga_formatted' => 'Rp ' . number_format(is_numeric($row['harga'] ?? null) ? (float) $row['harga'] : 0.0, 0, ',', '.'),
                    'stok'            => $sisaStok,
                ];
            }
        }

        return $this->response->setJSON([
            'data' => $dataModal,
        ]);
    }
}
