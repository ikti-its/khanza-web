<?php
declare(strict_types=1);

namespace App\Features\PenangananDonor\Pencekalan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

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
                [SHOW, REQUIRED, I::INDEX,  'id_petugas',          'Petugas'],
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

        $idKunjungan = $this->request->getGet('kunjungan');
        $idPetugas   = $this->request->getGet('petugas');
        $reaktif     = $this->request->getGet('reaktif');

        if ($idKunjungan !== null && is_numeric($idKunjungan)) {
            $modelKunjungan = new \App\Features\Donor\Kunjungan\KunjunganModel();
            $dataKunjungan  = $modelKunjungan->find($idKunjungan) ?? [];

            if (!empty($dataKunjungan)) {
                $mockBaris['id_kunjungan']    = $dataKunjungan['id_kunjungan'] ?? '';
                $mockBaris['nomor_kunjungan'] = $dataKunjungan['nomor_kunjungan'] ?? '';

                if (!empty($dataKunjungan['id_pendonor'])) {
                    $modelPendonor = new \App\Features\Role\Pendonor\PendonorModel();
                    $dataPendonor  = $modelPendonor->find($dataKunjungan['id_pendonor']) ?? [];

                    $mockBaris['nomor_pendonor'] = $dataPendonor['nomor_pendonor'] ?? '';

                    if (!empty($dataPendonor['id_orang'])) {
                        $modelOrang = new \App\Features\Person\Orang\OrangModel();
                        $dataOrang  = $modelOrang->find($dataPendonor['id_orang']) ?? [];

                        $mockBaris['nama'] = $dataOrang['nama'] ?? '';
                    }
                }
            }
        }

        if ($idPetugas !== null && is_numeric($idPetugas)) {
            $mockBaris['id_petugas'] = $idPetugas;

            $modelPetugas = new \App\Features\Role\Petugas\PetugasModel();
            $dataPetugas  = $modelPetugas->find($idPetugas) ?? [];

            if (!empty($dataPetugas['id_orang'])) {
                $modelOrangPetugas = new \App\Features\Person\Orang\OrangModel();
                $dataOrangPetugas  = $modelOrangPetugas->find($dataPetugas['id_orang']) ?? [];

                $mockBaris['nama_petugas'] = $dataOrangPetugas['nama'] ?? '';
            }
        }

        if (!empty($reaktif)) {
            $mockBaris['keterangan'] = 'Reaktif ' . str_replace(',', ', ', (string) $reaktif) . ' pada uji saring IMLTD';
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

    /**
     * OVERRIDE: Memproses simpan data pencekalan
     */
    #[\Override]
    public function create(): string|RedirectResponse
    {
        $response = parent::create();

        if ($response instanceof RedirectResponse) {
            session()->remove([
                'pencekalan_url',
                'pencekalan_message',
            ]);
        }

        return $response;
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Pencekalan
     */
    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $dataPencekalan = $this->model->find($id);
        if (!$dataPencekalan) {
            $dataPencekalan = [];
        }

        $dataKunjungan = [];
        $dataPendonor  = [];
        $dataOrang     = [];
        $dataPetugasMedis = [];

        if (!empty($dataPencekalan['id_kunjungan'])) {
            $modelKunjungan = new \App\Features\Donor\Kunjungan\KunjunganModel();
            $dataKunjungan  = $modelKunjungan->find($dataPencekalan['id_kunjungan']) ?? [];

            if (!empty($dataKunjungan['id_pendonor'])) {
                $modelPendonor = new \App\Features\Role\Pendonor\PendonorModel();
                $dataPendonor  = $modelPendonor->find($dataKunjungan['id_pendonor']) ?? [];

                if (!empty($dataPendonor['id_orang'])) {
                    $modelOrang = new \App\Features\Person\Orang\OrangModel();
                    $dataOrang  = $modelOrang->find($dataPendonor['id_orang']) ?? [];
                }
            }
        }

        if (!empty($dataPencekalan['id_petugas'])) {
            $modelPetugas = new \App\Features\Role\Petugas\PetugasModel();
            $petugasRow   = $modelPetugas->find($dataPencekalan['id_petugas']) ?? [];

            if (!empty($petugasRow['id_orang'])) {
                $modelOrangPetugas = new \App\Features\Person\Orang\OrangModel();
                $orangPetugasRow   = $modelOrangPetugas->find($petugasRow['id_orang']) ?? [];
                
                if (isset($orangPetugasRow['nama'])) {
                    $dataPetugasMedis['nama_petugas'] = $orangPetugasRow['nama'];
                }
            }
        }

        $baris = array_merge($dataOrang, $dataPendonor, $dataKunjungan, $dataPetugasMedis, $dataPencekalan);

        $controllerKunjungan = new \App\Features\Donor\Kunjungan\KunjunganController();
        $controllerPendonor  = new \App\Features\Role\Pendonor\PendonorController();
        $controllerOrang     = new \App\Features\Person\Orang\OrangController();

        $konfigPencekalan = $this->get_fields_with_options(false, true);
        $konfigKunjungan  = $controllerKunjungan->fields;
        $konfigPendonor   = $controllerPendonor->fields;
        $konfigOrang      = $controllerOrang->fields;

        $konfigGabungan = [];

        foreach ($konfigPencekalan as $fieldPencekalan) {
            $columnPencekalan = $fieldPencekalan[2];

            if ($columnPencekalan === 'id_pencekalan') {
                continue;
            }

            if ($columnPencekalan === 'id_kunjungan') {
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

            $konfigGabungan[] = $fieldPencekalan;
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

        return view('admin/penanganandonor/tambah_pencekalan', [
            'judul'          => 'Ubah ' . $this->title,
            'breadcrumbs'    => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'     => $this->get_uri_path(),
            'kolom_id'       => $this->model->primaryKey,
            'konfig'         => $konfigGabungan,
            'baris'          => $baris,
            'form_action'    => '/submitedit/' . $id,
        ]);
    }
}
