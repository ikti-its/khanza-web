<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PengambilanMedis;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;

final class PengambilanMedisController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengambilanMedisModel(),
            [
                ['Logistik UTD',          'logistik_utd'],
                ['Pengambilan BHP Medis', 'pengambilan_bhp_medis'],
            ],
            'Pengambilan BHP Medis',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_pengambilan_medis', 'ID Pengambilan Medis'],
                [SHOW, REQUIRED, I::INDEX,  'id_barang',            'Kode Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah',               'Jumlah'],
                [SHOW, REQUIRED, I::MONEY,  'harga_beli',           'Harga Beli'],
                [SHOW, REQUIRED, I::TEXT,   'nama_bangsal',         'Nama Bangsal'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_pengambilan',  'Tanggal Pengambilan'],
                [HIDE, REQUIRED, I::TEXT,   'keterangan',           'Keterangan'],
                [HIDE, OPTIONAL, I::TEXT,   'nomor_batch',          'Nomor Batch'],
                [HIDE, OPTIONAL, I::TEXT,   'nomor_faktur',         'Nomor Faktur'],
            ],
        );
    }

    /**
     * Menampilkan data modal BHP medis ruangan UTD
     * 
     * @throws DatabaseException
     */
    public function list(): ResponseInterface
    {
        $modelPengambilanMedis = new \App\Features\LogistikUTD\PengambilanMedis\PengambilanMedisModel();
        $rawMedis              = $modelPengambilanMedis->get_katalog_dan_stok_ruangan();

        $dataModal = [];
        foreach ($rawMedis as $row) {
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
