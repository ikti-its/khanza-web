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
                A::DETAIL,
                A::FILTER,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_pencekalan',        'ID Pencekalan'],
                [SHOW, REQUIRED, I::INDEX,  'id_kunjungan',         'ID Kunjungan'],
                [SHOW, REQUIRED, I::SELECT, 'id_jenis_pencekalan',  'Jenis Pencekalan'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_mulai',        'Tanggal Mulai'],
                [SHOW, OPTIONAL, I::DATE,   'tanggal_selesai',      'Tanggal Selesai'],
                [SHOW, REQUIRED, I::SELECT, 'id_shift',             'Shift'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas',           'Petugas'],
                [SHOW, REQUIRED, I::TEXT,   'keterangan',           'Keterangan'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_pencekalan', 'Status Pencekalan'],
            ],
        );
    }

    /**
     * OVERRIDE: Menyinkronkan status pencekalan sebelum data ditampilkan
     */
    #[\Override]
    protected function before_read(): void
    {
        $this->model->sinkronkanStatusPencekalan();
    }

    /**
     * OVERRIDE: Menampilkan Halaman Utama Data Pencekalan
     */
    #[\Override]
    public function index(): string
    {
        $this->filters = [
            'aktif'   => 'Aktif',
            'selesai' => 'Selesai',
        ];

        $this->active_filter = $this->request->getGet('filter') ?: null;

        if ($this->active_filter !== null && array_key_exists($this->active_filter, $this->filters)) {
            $this->model->applyFilterStatus($this->active_filter);
        }

        $this->before_read();

        $currentPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage     = 10;
        $offset      = ($currentPage - 1) * $perPage;

        $totalRows  = $this->model->count_filtered();
        $data_tabel = $this->model->get_data_tabel($perPage, $offset);

        $konfig = [
            [1, 'Nomor Kunjungan', 'nomor_kunjungan',        'teks',   0],
            [1, 'Nama Pendonor',   'nama',                   'teks',   0],
            [1, 'Jenis Pencekalan','nama_jenis_pencekalan',  'teks',   0],
            [1, 'Tanggal Mulai',   'tanggal_mulai',          'tanggal',0],
            [1, 'Tanggal Selesai', 'tanggal_selesai',        'tanggal',0],
            [1, 'Status',          'nama_status_pencekalan', 'status', 0],
        ];

        return view('/layouts/data', [
            'judul'         => $this->title,
            'breadcrumbs'   => $this->breadcrumbs,
            'meta_data'     => ['page' => $currentPage, 'size' => count($data_tabel), 'total' => ceil($totalRows / $perPage)],
            'modul_path'    => $this->get_uri_path(),
            'kolom_id'      => $this->primary_key,
            'konfig'        => $konfig,
            'aksi'          => $this->actions,
            'tabel'         => $data_tabel,
            'row_alert'     => [],
            'child_link'    => null,
            'query_string'  => '',
            'filters'       => $this->filters,
            'active_filter' => $this->active_filter,
        ]);
    }

    /**
     * OVERRIDE: Menampilkan Form Pencekalan
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah'],
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
                        $konfigGabungan[]             = $fieldKunjungan;
                        break;
                    }
                }

                foreach ($konfigPendonor as $fieldPendonor) {
                    if ($fieldPendonor[2] === 'nomor_pendonor') {
                        $mockBaris['nomor_pendonor'] = '';
                        $konfigGabungan[]            = $fieldPendonor;
                        break;
                    }
                }

                foreach ($konfigOrang as $fieldOrang) {
                    if ($fieldOrang[2] === 'nama') {
                        $mockBaris['nama'] = '';
                        $konfigGabungan[]  = $fieldOrang;
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
            $mockBaris['keterangan'] =
                'Reaktif ' . str_replace(',', ', ', (string) $reaktif) . ' pada uji saring IMLTD';
        }

        return view('admin/penanganandonor/tambah_pencekalan', [
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
     * OVERRIDE: Mengatur status default pencekalan
     */
    #[\Override]
    protected function before_create(array &$postData): void
    {
        $this->model->setStatusAktif($postData);
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
                'pencekalan_title',
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
        if ($id == 0)
            return $this->index();

        $dataPencekalan = $this->model->find($id);
        if (!$dataPencekalan) {
            $dataPencekalan = [];
        }

        $dataKunjungan    = [];
        $dataPendonor     = [];
        $dataOrang        = [];
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
            ['title' => 'Ubah', 'icon' => 'Ubah'],
        ];

        return view('admin/penanganandonor/tambah_pencekalan', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $konfigGabungan,
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    /**
     * OVERRIDE: Menentukan ulang status pencekalan saat data diperbarui
     */
    #[\Override]
    protected function before_update(array &$postData, int|string $id): void
    {
        $postData['id_status_pencekalan'] = $this->model->tentukanStatusPencekalan(
            $postData['tanggal_selesai'] ?? null,
        );
    }

    /**
     * Menampilkan Halaman Detail Pencekalan
     */
    public function detail(int|string $id): string
    {
        if ($id == 0)
            return $this->index();

        $dataPencekalan = $this->model->find($id);
        if (!$dataPencekalan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data Pencekalan tidak ditemukan.');
        }

        $dataKunjungan    = [];
        $dataPendonor     = [];
        $dataOrang        = [];
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
        $konfigKunjungan  = $controllerKunjungan->get_fields_with_options(false, true);
        $konfigPendonor   = $controllerPendonor->get_fields_with_options(false, true);
        $konfigOrang      = $controllerOrang->get_fields_with_options(false, true);

        $konfigGabungan = array_merge($konfigOrang, $konfigPendonor, $konfigKunjungan, $konfigPencekalan);

        foreach ($konfigGabungan as $field) {
            $colName = $field[2];
            $options = $field[5] ?? [];

            if (!empty($options) && isset($baris[$colName])) {
                $idMentah = $baris[$colName];
                foreach ($options as $opt) {
                    if ((string) $opt[1] === (string) $idMentah) {
                        $baris[$colName] = $opt[0];
                        break;
                    }
                }
            }
        }

        foreach ($baris as $key => $value) {
            if ($value === null) {
                $baris[$key] = '';
            }
        }

        $breadcrumbs = [
            ['title' => 'Detail', 'icon' => 'detail'],
        ];

        return view('admin/penanganandonor/detail_pencekalan', [
            'judul'       => 'Detail ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'baris'       => $baris,
        ]);
    }
}
