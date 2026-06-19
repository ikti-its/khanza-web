<?php
declare(strict_types=1);

namespace App\Features\Donor\SkriningDonor;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class SkriningDonorController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SkriningDonorModel(),
            [
                ['Donor',          'donor'],
                ['Skrining Donor', 'skrining_donor'],
            ],
            'Skrining Donor',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_skrining',        'ID Skrining'],
                [SHOW, REQUIRED, I::INDEX,  'id_kunjungan',       'ID Kunjungan'],
                [SHOW, REQUIRED, I::FLOAT,  'berat_badan',        'Berat Badan'],
                [SHOW, REQUIRED, I::NUMBER, 'sistolik',           'Tekanan Sistolik'],
                [SHOW, REQUIRED, I::NUMBER, 'diastolik',          'Tekanan Diastolik'],
                [SHOW, REQUIRED, I::NUMBER, 'nadi',               'Denyut Nadi (x/menit)'],
                [SHOW, REQUIRED, I::TEMP,   'suhu_tubuh',         'Suhu'],
                [SHOW, REQUIRED, I::FLOAT,  'kadar_hemoglobin',   'Kadar Hemoglobin'],
                [SHOW, REQUIRED, I::SELECT, 'id_hasil_anamnesis', 'Hasil Anamnesis'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_skrining', 'Status Skrining'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Skrining Donor
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $controllerKunjungan = new \App\Features\Donor\Kunjungan\KunjunganController();
        $controllerPendonor  = new \App\Features\Role\Pendonor\PendonorController();
        $controllerOrang     = new \App\Features\Person\Orang\OrangController();

        $konfigSkrining  = $this->get_fields_with_options(false, true);
        $konfigKunjungan = $controllerKunjungan->fields;
        $konfigPendonor = $controllerPendonor->fields;
        $konfigOrang = $controllerOrang->fields;

        $mockBaris = [];
        $konfigGabungan = [];

        foreach ($konfigSkrining as $fieldSkrining) {
            $columnSkrining = $fieldSkrining[2];

            if ($columnSkrining === 'id_skrining') {
                continue;
            }

            $mockBaris[$columnSkrining] = '';

            if ($columnSkrining === 'id_kunjungan') {
                foreach ($konfigKunjungan as $fieldKunjungan) {
                    if ($fieldKunjungan[2] === 'nomor_kunjungan') {
                        $mockBaris['nomor_kunjungan'] = '';
                        $konfigGabungan[] = $fieldKunjungan;
                        break;
                    }
                }

                foreach ($konfigPendonor as $fieldPendonor) {
                    if ($fieldPendonor[2] === 'nomor_pendonor') {
                        $mockBaris['nomor_pendonor'] = '';
                        $konfigGabungan[] = $fieldPendonor;
                        break;
                    }
                }

                foreach ($konfigOrang as $fieldOrang) {
                    if ($fieldOrang[2] === 'nama') {
                        $mockBaris['nama'] = '';
                        $konfigGabungan[] = $fieldOrang;
                        break;
                    }
                }
                continue;
            }

            $konfigGabungan[] = $fieldSkrining;
        }

        return view('/admin/donor/tambah_skriningdonor', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $konfigGabungan,
            'baris'       => $mockBaris,
            'form_action' => '/submittambah',
        ]);
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Skrining Donor
     */
    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $dataSkrining = $this->model->find($id);
        if (!$dataSkrining) {
            $dataSkrining = [];
        }

        $dataKunjungan = [];
        $dataPendonor  = [];
        $dataOrang     = [];

        if (!empty($dataSkrining['id_kunjungan'])) {
            $modelKunjungan = new \App\Features\Donor\Kunjungan\KunjunganModel();
            $dataKunjungan  = $modelKunjungan->find($dataSkrining['id_kunjungan']) ?? [];

            if (!empty($dataKunjungan['id_pendonor'])) {
                $modelPendonor = new \App\Features\Role\Pendonor\PendonorModel();
                $dataPendonor  = $modelPendonor->find($dataKunjungan['id_pendonor']) ?? [];

                if (!empty($dataPendonor['id_orang'])) {
                    $modelOrang = new \App\Features\Person\Orang\OrangModel();
                    $dataOrang  = $modelOrang->find($dataPendonor['id_orang']) ?? [];
                }
            }
        }

        $baris = array_merge($dataOrang, $dataPendonor, $dataKunjungan, $dataSkrining);

        $controllerKunjungan = new \App\Features\Donor\Kunjungan\KunjunganController();
        $controllerPendonor  = new \App\Features\Role\Pendonor\PendonorController();
        $controllerOrang     = new \App\Features\Person\Orang\OrangController();

        $konfigSkrining = $this->get_fields_with_options(false, true);
        $konfigKunjungan = $controllerKunjungan->fields;
        $konfigPendonor = $controllerPendonor->fields;
        $konfigOrang = $controllerOrang->fields;

        $konfigGabungan = [];

        foreach ($konfigSkrining as $fieldSkrining) {
            $columnSkrining = $fieldSkrining[2];

            if ($columnSkrining === 'id_skrining') {
                continue;
            }

            if ($columnSkrining === 'id_kunjungan') {
                foreach ($konfigKunjungan as $fieldKunjungan) {
                    if ($fieldKunjungan[2] === 'nomor_kunjungan') {
                        $konfigGabungan[] = $fieldKunjungan;
                        break;
                    }
                }

                foreach ($konfigPendonor as $fieldPendonor) {
                    if ($fieldPendonor[2] === 'nomor_pendonor') {
                        $konfigGabungan[] = $fieldPendonor;
                        break;
                    }
                }

                foreach ($konfigOrang as $fieldOrang) {
                    if ($fieldOrang[2] === 'nama') {
                        $konfigGabungan[] = $fieldOrang;
                        break;
                    }
                }
                continue;
            }

            $konfigGabungan[] = $fieldSkrining;
        }

        foreach ($konfigGabungan as $field) {
            $namaKolom = $field[2];
            if (($baris[$namaKolom] ?? null) === null) {
                $baris[$namaKolom] = '';
            }
        }

        $breadcrumbs = [
            ['title' => 'Ubah', 'icon' => 'Ubah']
        ];

        return view('/admin/donor/tambah_skriningdonor', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $konfigGabungan,
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
        ]);
    }
}
