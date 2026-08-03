<?php
declare(strict_types=1);

namespace App\Features\Role\Pendonor;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

final class PendonorController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PendonorModel(),
            [
                ['Role',     'role'],
                ['Pendonor', 'pendonor'],
            ],
            'Pendonor',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::DETAIL,
                A::PRINT,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_pendonor',            'ID Pendonor'],
                [SHOW, REQUIRED, I::TEXT,   'nomor_pendonor',         'Nomor Pendonor'],
                [SHOW, REQUIRED, I::INDEX,  'id_orang',               'ID Orang'],
                [SHOW, REQUIRED, I::SELECT, 'id_rhesus',              'Rhesus'],
                [SHOW, REQUIRED, I::TEXT,   'nomor_telepon',          'Nomor Telepon'],
                [SHOW, OPTIONAL, I::DATE,   'tanggal_donor_terakhir', 'Tanggal Donor Terakhir'],
            ],
        );
    }

    /**
     * @throws PageNotFoundException
     */
    #[\Override]
    public function print(int|string $id): string
    {
        $dataPendonor = $this->model->find($id);
        if (!$dataPendonor) {
            throw PageNotFoundException::forPageNotFound('Data Pendonor tidak ditemukan.');
        }

        $modelOrang = new \App\Features\Person\Orang\OrangModel();
        $idOrang    = $dataPendonor['id_orang'] ?? null;
        $dataOrang  = $idOrang ? $modelOrang->find($idOrang) : [];

        $baris = array_merge($dataOrang, $dataPendonor);

        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        $konfigOrang     = $controllerOrang->get_fields_with_options(false, true);
        $konfigPendonor  = $this->get_fields_with_options(false, true);
        $konfigGabungan  = array_merge($konfigOrang, $konfigPendonor);

        foreach ($konfigGabungan as $field) {
            $namaKolom = $field[2];
            $tipeField = $field[3];
            $options   = $field[5] ?? [];

            if ($tipeField === 'status' && !empty($options) && isset($baris[$namaKolom])) {
                $idMentah = $baris[$namaKolom];

                foreach ($options as $opt) {
                    if ($opt[1] == $idMentah) {
                        $baris[$namaKolom] = $opt[0];
                        break;
                    }
                }
            }

            if (($baris[$namaKolom] ?? null) === null) {
                $baris[$namaKolom] = '';
            }
        }

        return view('components/cetak/cetak_kartu', [
            'judul' => 'Cetak Kartu Pendonor',
            'baris' => $baris,
        ]);
    }

    /**
     * OVERRIDE: Halaman Utama Pendonor
     */
    #[\Override]
    final public function index(): string
    {
        $currentPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage     = 10;
        $offset      = ($currentPage - 1) * $perPage;

        $totalRows  = $this->model->count_filtered();
        $data_tabel = $this->model->get_data_tabel($perPage, $offset);

        $konfig = [
            [1, 'Nomor Pendonor', 'nomor_pendonor',         'teks',    0],
            [1, 'Nama Lengkap',   'nama',                   'teks',    0],
            [1, 'Golongan Darah', 'nama_golongan_darah',    'teks',    0],
            [1, 'Rhesus',         'kode_rhesus',            'teks',    0],
            [1, 'Nomor Telepon',  'nomor_telepon',          'teks',    0],
            [1, 'Donor Terakhir', 'tanggal_donor_terakhir', 'tanggal', 0],
        ];

        return view('/layouts/data', [
            'judul'        => $this->title,
            'breadcrumbs'  => $this->breadcrumbs,
            'meta_data'    => [
                'page'  => $currentPage,
                'size'  => count($data_tabel),
                'total' => ceil($totalRows / $perPage),
            ],
            'modul_path'   => $this->get_uri_path(),
            'kolom_id'     => $this->primary_key,
            'konfig'       => $konfig,
            'aksi'         => $this->actions,
            'tabel'        => $data_tabel,
            'row_alert'    => [],
            'child_link'   => null,
            'query_string' => '',
        ]);
    }

    /**
     * OVERRIDE: Menampilkan Form Pendonor
     */
    #[\Override]
    final public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon', 'tambah'],
        ];

        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        $fieldsOrang     = $controllerOrang->fields;
        $fieldsPendonor  = $this->fields;

        $modelOrang   = new \App\Features\Person\Orang\OrangModel();
        $opsiOrang    = $modelOrang->get_all_options();
        $opsiPendonor = $this->model->get_all_options();

        $terakhir   = $this->model->orderBy($this->model->primaryKey, 'DESC')->first();
        $nextNumber = 1;
        if ($terakhir !== null && isset($terakhir['nomor_pendonor'])) {
            $cleanNumber = str_replace('UTD', '', $terakhir['nomor_pendonor']);
            $nextNumber  = (int) $cleanNumber + 1;
        }
        $nomorPendonorOtomatis = 'UTD' . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);

        $mockBaris      = [];
        $konfigGabungan = [];

        foreach ($fieldsPendonor as $fieldPendonor) {
            $columnPendonor = $fieldPendonor[2];

            if ($columnPendonor === 'nomor_pendonor') {
                $mockBaris[$columnPendonor] = $nomorPendonorOtomatis;
                $fieldPendonor[3]           = 'indeks';
            } elseif ($columnPendonor !== 'id_pendonor') {
                $mockBaris[$columnPendonor] = '';
            }

            if ($columnPendonor === 'id_orang') {
                foreach ($fieldsOrang as $fieldOrang) {
                    $columnOrang = $fieldOrang[2];

                    if ($columnOrang === 'id_orang') {
                        continue;
                    }

                    $mockBaris[$columnOrang] = '';

                    if (isset($opsiOrang[$columnOrang])) {
                        $fieldOrang[5] = $opsiOrang[$columnOrang];
                    }
                    $konfigGabungan[] = $fieldOrang;
                }
                continue;
            }

            if ($columnPendonor === 'id_pendonor') {
                continue;
            }

            if (isset($opsiPendonor[$columnPendonor])) {
                $fieldPendonor[5] = $opsiPendonor[$columnPendonor];
            }

            $konfigGabungan[] = $fieldPendonor;
        }

        $mockBaris['redirect_to'] = $this->request->getGet('redirect_to') ?? '';

        return view('/admin/role/tambah_pendonor', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $konfigGabungan,
            'baris'       => $mockBaris,
            'form_action' => '/submittambah/',
        ]);
    }

    /**
     * OVERRIDE: Memproses Simpan Data Form Gabungan
     */
    #[\Override]
    final public function create(): string|RedirectResponse
    {
        $modelAlamat = new \App\Features\Lokasi\Alamat\AlamatModel();
        $modelOrang  = new \App\Features\Person\Orang\OrangModel();
        $rawPost     = $this->request->getPost();

        $dataPendonor = $this->get_post_data_custom();

        $this->model->db->transStart();

        try {
            $this->model->validasiUsiaMinimal($rawPost['tanggal_lahir']);

            $dataAlamat = [
                'alamat_lengkap' => $rawPost['alamat_lengkap'] ?? null,
                'id_provinsi'    => isset($rawPost['id_provinsi']) ? (int) $rawPost['id_provinsi'] : null,
                'id_kota_lokal'  => isset($rawPost['id_kota_lokal']) ? (int) $rawPost['id_kota_lokal'] : null,
                'id_kec_lokal'   => isset($rawPost['id_kec_lokal']) ? (int) $rawPost['id_kec_lokal'] : null,
                'id_desa_lokal'  => isset($rawPost['id_desa_lokal']) ? (int) $rawPost['id_desa_lokal'] : null,
            ];

            if (!$modelAlamat->insert($dataAlamat)) {
                throw new \RuntimeException('Sistem gagal memproses data Alamat baru.');
            }

            $idAlamatBaru = $modelAlamat->insertID();

            $dataOrang = [];
            foreach ($modelOrang->allowedFields as $field) {
                $value = $rawPost[$field] ?? '';

                if ($value === '') {
                    $value = null;
                } else if (is_numeric($value) && (str_contains($field, 'id_') || $field === 'tempat_lahir_kota')) {
                    $value = (int) $value;
                }

                $dataOrang[$field] = $value;
            }

            $dataOrang['id_alamat'] = $idAlamatBaru;

            if (!$modelOrang->insert($dataOrang)) {
                throw new \RuntimeException('Sistem gagal menyimpan identitas Orang.');
            }

            $idOrang                  = $modelOrang->insertID();
            $dataPendonor['id_orang'] = $idOrang;

            if (!$this->model->insert($dataPendonor)) {
                throw new \RuntimeException('Sistem gagal menyimpan entitas Pendonor.');
            }

            $idPendonor = $this->model->insertID();
            $this->model->setTanggalDonorTerakhir($idPendonor, $dataPendonor['tanggal_donor_terakhir'] ?? null);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal menyimpan data pendonor.');
            }

            session()->setFlashdata('success', 'Data pendonor berhasil disimpan.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
            return redirect()->back()->withInput();
        }

        $redirect_to = $this->request->getPost('redirect_to');
        return $redirect_to ? redirect()->to($redirect_to) : $this->home();
    }

    /**
     * CUSTOM HELPER: Menyaring dan mengambil data POST murni untuk kebutuhan tabel Pendonor saja
     */
    private function get_post_data_custom(): array
    {
        $postData = [];
        $rawPost  = $this->request->getPost();

        $fieldsPendonor = $this->fields;

        foreach ($fieldsPendonor as $field) {
            $column = $field[2];
            $type   = $field[3];

            if (in_array($column, [$this->model->primaryKey, 'id_orang'])) {
                continue;
            }

            $value = $rawPost[$column] ?? '';

            if ($value === '') {
                $value = null;
            } else if (str_contains($column, 'id_') || $type === 'indeks') {
                $value = (int) $value;
            }

            $postData[$column] = $value;
        }

        return $postData;
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Form Gabungan
     */
    #[\Override]
    final public function update_page(int|string $id): string
    {
        if ($id == 0)
            return $this->index();

        $dataPendonor = $this->model->find($id);
        if (!$dataPendonor) {
            $dataPendonor = [];
        }

        $dataOrang  = [];
        $dataAlamat = [];

        if (!empty($dataPendonor['id_orang'])) {
            $modelOrang = new \App\Features\Person\Orang\OrangModel();
            $dataOrang  = $modelOrang->find($dataPendonor['id_orang']) ?? [];

            if (!empty($dataOrang['id_alamat'])) {
                $modelAlamat = new \App\Features\Lokasi\Alamat\AlamatModel();
                $dataAlamat  = $modelAlamat->get_detail_wilayah($dataOrang['id_alamat']) ?? [];
            }

            if (!empty($dataOrang['tempat_lahir_kota'])) {
                $modelKotaLahir = new \App\Features\Lokasi\Kota\KotaModel();
                $kotaLahir      = $modelKotaLahir->find($dataOrang['tempat_lahir_kota']);
                if ($kotaLahir) {
                    $dataAlamat['nama_kota'] = $kotaLahir['nama_kota'] ?? '';
                }
            }
        }

        $baris = array_merge($dataAlamat, $dataOrang, $dataPendonor);

        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        $konfigOrang     = $controllerOrang->get_fields_with_options(false, true);
        $konfigPendonor  = $this->get_fields_with_options(false, true);

        $konfigGabungan = [];

        foreach ($konfigPendonor as $field) {
            $namaKolom = $field[2];

            if ($namaKolom === 'id_orang') {
                $konfigGabungan = array_merge($konfigGabungan, $konfigOrang);
            } else {
                if ($namaKolom === 'nomor_pendonor') {
                    $field[3] = 'indeks';
                }
                $konfigGabungan[] = $field;
            }
        }

        foreach ($konfigGabungan as $field) {
            $namaKolom = $field[2];

            if (($baris[$namaKolom] ?? null) === null) {
                $baris[$namaKolom] = '';
            }
        }

        $breadcrumbs = [
            ['title' => 'Ubah', 'icon', 'Ubah'],
        ];

        return view('/admin/role/tambah_pendonor', [
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
     * OVERRIDE: Mengeksekusi Simpan Perubahan Data Pendonor
     */
    #[\Override]
    final public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->index();

        $rawPost = $this->request->getPost();

        $dataPendonorLama = $this->model->find($id);
        $idOrang          = $dataPendonorLama['id_orang'];

        $modelAlamat = new \App\Features\Lokasi\Alamat\AlamatModel();
        $modelOrang  = new \App\Features\Person\Orang\OrangModel();

        $dataOrangLama = $modelOrang->find($idOrang);
        $idAlamatLama  = $dataOrangLama['id_alamat'] ?? null;

        $this->model->db->transStart();

        try {
            $this->model->validasiUsiaMinimal($rawPost['tanggal_lahir']);

            $dataAlamat = [
                'alamat_lengkap' => $rawPost['alamat_lengkap'] ?? null,
                'id_provinsi'    => isset($rawPost['id_provinsi']) ? (int) $rawPost['id_provinsi'] : null,
                'id_kota_lokal'  => isset($rawPost['id_kota_lokal']) ? (int) $rawPost['id_kota_lokal'] : null,
                'id_kec_lokal'   => isset($rawPost['id_kec_lokal']) ? (int) $rawPost['id_kec_lokal'] : null,
                'id_desa_lokal'  => isset($rawPost['id_desa_lokal']) ? (int) $rawPost['id_desa_lokal'] : null,
            ];

            if (!empty($idAlamatLama)) {
                $modelAlamat->update($idAlamatLama, $dataAlamat);
                $idAlamatFinal = $idAlamatLama;
            } else {
                $modelAlamat->insert($dataAlamat);
                $idAlamatFinal = $modelAlamat->insertID();
            }

            $dataOrang = [];
            foreach ($modelOrang->allowedFields as $field) {
                $value = $rawPost[$field] ?? '';

                if ($value === '') {
                    $value = null;
                } else if (is_numeric($value) && (str_contains($field, 'id_') || $field === 'tempat_lahir_kota')) {
                    $value = (int) $value;
                }

                $dataOrang[$field] = $value;
            }
            $dataOrang['id_alamat'] = $idAlamatFinal;

            $modelOrang->update($idOrang, $dataOrang);

            $dataPendonor             = $this->get_post_data_custom();
            $dataPendonor['id_orang'] = $idOrang;

            $tanggalDonorLama = $dataPendonorLama['tanggal_donor_terakhir'] ?? null;
            $tanggalDonorBaru = $dataPendonor['tanggal_donor_terakhir'] ?? null;

            $this->model->update($id, $dataPendonor);

            if (($tanggalDonorLama ?: null) !== ($tanggalDonorBaru ?: null)) {
                $this->model->setTanggalDonorTerakhir($id, $tanggalDonorBaru);
            }

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal memperbarui data ' . $this->title . '.');
            }

            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
            return redirect()->back()->withInput();
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * OVERRIDE: Menghapus data pendonor
     */
    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->home();

        $pendonor = $this->model->find($id);
        if (!$pendonor) {
            session()->setFlashdata('error', 'Data pendonor tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $modelRiwayat = new \App\Features\Role\RiwayatTanggalDonor\RiwayatTanggalDonorModel();
        if ($modelRiwayat->punyaRiwayat($id)) {
            session()->setFlashdata(
                'error',
                'Data pendonor tidak dapat dihapus karena sudah memiliki riwayat donor darah. Riwayat donor wajib dipertahankan untuk keperluan traceability, sesuai prosedur UTD.',
            );
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idOrang = $pendonor['id_orang'] ?? null;

        if (!empty($idOrang)) {
            $modelOrang = new \App\Features\Person\Orang\OrangModel();
            $orangRow   = $modelOrang->find($idOrang);
            $idAlamat   = $orangRow['id_alamat'] ?? null;
        }

        $this->model->db->transStart();

        try {
            // Bersihkan baris riwayat kosong (tanggal_donor NULL) yang otomatis dibuat saat
            // registrasi, karena punyaRiwayat() di atas sudah memastikan tidak ada riwayat donor asli.
            $modelRiwayat->builder()->where('id_pendonor', $id)->delete();

            $this->model->delete($id);

            if (!empty($idOrang)) {
                $modelOrang = new \App\Features\Person\Orang\OrangModel();
                $modelOrang->delete($idOrang);
            }

            if (!empty($idAlamat)) {
                $modelAlamat = new \App\Features\Lokasi\Alamat\AlamatModel();
                $modelAlamat->delete($idAlamat);
            }

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \CodeIgniter\Database\Exceptions\DatabaseException('violates foreign key constraint');
            }

            session()->setFlashdata('success', 'Data pendonor berhasil dihapus.');
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $this->friendly_db_error($e));
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $e->getMessage());
        }

        return $this->home();
    }

    /**
     * Menampilkan Halaman Detail Pendonor
     */
    public function detail(int|string $id): string
    {
        if ($id == 0)
            return $this->index();

        $dataPendonor = $this->model->find($id);

        $dataOrang  = [];
        $dataAlamat = [];

        if (!empty($dataPendonor['id_orang'])) {
            $modelOrang = new \App\Features\Person\Orang\OrangModel();
            $dataOrang  = $modelOrang->find($dataPendonor['id_orang']) ?? [];

            if (!empty($dataOrang['id_alamat'])) {
                $modelAlamat = new \App\Features\Lokasi\Alamat\AlamatModel();
                $dataAlamat  = $modelAlamat->get_detail_wilayah($dataOrang['id_alamat']) ?? [];
            }

            if (!empty($dataOrang['tempat_lahir_kota'])) {
                $modelKotaLahir = new \App\Features\Lokasi\Kota\KotaModel();
                $kotaLahir      = $modelKotaLahir->find($dataOrang['tempat_lahir_kota']);
                if ($kotaLahir) {
                    $dataAlamat['nama_kota_lahir'] = $kotaLahir['nama_kota'] ?? '';
                }
            }
        }

        $baris = array_merge($dataAlamat, $dataOrang, $dataPendonor);

        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        $konfigOrang     = $controllerOrang->get_fields_with_options(false, true);
        $konfigPendonor  = $this->get_fields_with_options(false, true);
        $konfigGabungan  = array_merge($konfigOrang, $konfigPendonor);

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

        return view('/admin/role/detail_pendonor', [
            'judul'       => 'Detail ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'baris'       => $baris,
        ]);
    }

    /**
     * Menampilkan data modal pendonor
     */
    public function list(): ResponseInterface
    {
        $data = $this->model->get_data_tabel();

        return $this->response->setJSON([
            'data' => $data,
        ]);
    }
}
