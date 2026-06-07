<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\PemisahanKomponen;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PemisahanKomponenController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PemisahanKomponenModel(),
            [
                ['Inventaris Darah',   'inventaris_darah'],
                ['Pemisahan Komponen', 'pemisahan_komponen'],
            ],
            'Pemisahan Komponen',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                // A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_pemisahan',         'ID Pemisahan'],
                [SHOW, REQUIRED, I::INDEX, 'id_pengambilan_darah', 'ID Pengambilan Darah'],
                [SHOW, REQUIRED, I::DATE,  'tanggal_pemisahan',    'Tanggal Pemisahan'],
                [SHOW, REQUIRED, I::SELECT, 'id_shift',             'Shift'],
                [SHOW, REQUIRED, I::INDEX, 'id_petugas',           'ID Petugas'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Pemisahan Komponen & Penggunaan BHP
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $fieldsWithOptions = $this->get_fields_with_options(false, true);

        $controllerPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahController();
        $konfigPengambilan     = $controllerPengambilan->get_fields_with_options(false, true);

        $modelKomponen = new \App\Features\Darah\KomponenDarah\KomponenDarahModel(); 
        $masterKomponen = $modelKomponen->findAll();

        $modelBhpMedis    = new \App\Features\LogistikUTD\PengambilanMedis\PengambilanMedisModel();
        $modelBhpNonMedis = new \App\Features\LogistikUTD\PengambilanPenunjang\PengambilanPenunjangModel();

        $rawMedis    = $modelBhpMedis->get_katalog_dan_stok_ruangan();
        $rawPenunjang = $modelBhpNonMedis->get_katalog_dan_stok_ruangan();

        $masterBhpMedis = [];
        foreach ($rawMedis as $row) {
            $sisaStok = (int)$row['total_masuk'] - (int)$row['total_terpakai_donor'] - (int)$row['total_terpakai_pemisahan'] - (int)$row['total_terpakai_penyerahan'] - (int)$row['total_rusak'];
            
            if ((int)$row['total_masuk'] > 0) {
                $masterBhpMedis[] = [
                    'id_barang'   => $row['id_barang'],
                    'nama_barang' => $row['nama_barang'],
                    'harga'       => $row['harga'],
                    'stok'        => $sisaStok
                ];
            }
        }

        $masterBhpNonMedis = [];
        foreach ($rawPenunjang as $row) {
            $sisaStokNon = (int)$row['total_masuk'] - (int)$row['total_terpakai_donor'] - (int)$row['total_terpakai_pemisahan'] - (int)$row['total_terpakai_penyerahan'] - (int)$row['total_rusak'];
            
            if ((int)$row['total_masuk'] > 0) {
                $masterBhpNonMedis[] = [
                    'id_barang'   => $row['id_barang'],
                    'nama_barang' => $row['nama_barang'],
                    'harga'       => $row['harga'],
                    'stok'        => $sisaStokNon
                ];
            }
        }

        $mockBaris      = [];
        $konfigGabungan = [];

        foreach ($fieldsWithOptions as $field) {
            $namaKolom = $field[2];

            if ($namaKolom === 'id_pemisahan') {
                continue;
            }
            
            $isTanggal = ($field[3] === 'tanggal' || str_contains($namaKolom, 'tanggal'));
            $mockBaris[$namaKolom] = $isTanggal ? date('Y-m-d') : '';

            if ($namaKolom === 'id_pengambilan_darah') {
                foreach ($konfigPengambilan as $fPengambilan) {
                    if ($fPengambilan[2] === 'nomor_pengambilan') {
                        $mockBaris['nomor_pengambilan'] = '';
                        $konfigGabungan[] = $fPengambilan;
                        break;
                    }
                }
                continue;
            }

            $konfigGabungan[] = $field;
        }

        $id_pengambilan = $this->request->getGet('pengambilan');

        if ($id_pengambilan !== null && is_numeric($id_pengambilan)) {
            $modelPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
            $dataPengambilan  = $modelPengambilan->find($id_pengambilan);

            if ($dataPengambilan) {
                $mockBaris['id_pengambilan_darah'] = $dataPengambilan['id_pengambilan_darah'];
                $mockBaris['nomor_pengambilan']    = $dataPengambilan['nomor_pengambilan'];
            }
        }

        return view('admin/inventoridarah/tambah_pemisahankomponen', [
            'judul'             => 'Tambah ' . $this->title,
            'breadcrumbs'       => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'        => $this->get_uri_path(),
            'kolom_id'          => $this->model->primaryKey,
            'konfig'            => $konfigGabungan,
            'baris'             => $mockBaris,
            'master_komponen'   => $masterKomponen,
            'bhp_medis_options' => $masterBhpMedis,
            'bhp_non_options'   => $masterBhpNonMedis,
            'form_action'       => '/submittambah',
        ]);
    }
}
