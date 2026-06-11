<?php
declare(strict_types=1);

namespace App\Features\PenangananDonor\Pencekalan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PencekalanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PencekalanModel(),
            [
                ['Penanganan Donor', 'penanganan_donor'],
                ['Pencekalan',       'pencekalan'],
            ],
            'Pencekalan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_pencekalan',       'ID Pencekalan'],
                [SHOW, REQUIRED, I::INDEX,  'id_kunjungan',        'ID Kunjungan'],
                [SHOW, REQUIRED, I::SELECT, 'id_jenis_pencekalan', 'Jenis Pencekalan'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_mulai',       'Tanggal Mulai'],
                [SHOW, OPTIONAL, I::DATE,   'tanggal_selesai',     'Tanggal Selesai'],
                [SHOW, REQUIRED, I::SELECT, 'id_shift',            'Shift'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas',          'ID Petugas'],
                [SHOW, REQUIRED, I::TEXT,   'keterangan',          'Keterangan'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Pencekalan
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $konfigPencekalan = $this->get_fields_with_options(false, true);

        $controllerKunjungan = new \App\Features\Donor\Kunjungan\KunjunganController();
        $controllerPendonor  = new \App\Features\Role\Pendonor\PendonorController();
        $controllerOrang     = new \App\Features\Person\Orang\OrangController();

        $konfigKunjungan = $controllerKunjungan->fields;
        $konfigPendonor  = $controllerPendonor->fields;
        $konfigOrang     = $controllerOrang->fields;

        $konfigGabungan = [];
        $mockBaris      = [];

        foreach ($konfigPencekalan as $fieldPencekalan) {
            $columnPencekalan = $fieldPencekalan[2];

            if ($columnPencekalan === 'id_pencekalan') {
                continue;
            }

            $mockBaris[$columnPencekalan] = '';

            if ($columnPencekalan === 'id_kunjungan') {
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

            $konfigGabungan[] = $fieldPencekalan;
        }

        return view('admin/penanganandonor/tambah_pencekalan', [
            'judul'          => 'Tambah ' . $this->title,
            'breadcrumbs'    => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'     => $this->get_uri_path(),
            'kolom_id'       => $this->model->primaryKey,
            'konfig'         => $konfigGabungan,
            'baris'          => $mockBaris,
            'form_action'    => '/submittambah',
        ]);
    }
}
