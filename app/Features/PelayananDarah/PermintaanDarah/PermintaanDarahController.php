<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PermintaanDarah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PermintaanDarahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanDarahModel(),
            [
                ['Pelayanan Darah',  'pelayanan_darah'],
                ['Permintaan Darah', 'permintaan_darah'],
            ],
            'Permintaan Darah',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_permintaan',        'ID Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'no_permintaan',        'Nomor Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'id_registrasi',        'ID Registrasi'],
                [SHOW, REQUIRED, I::TEXT,   'id_dokter_pengirim',   'Dokter Pengirim'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_permintaan',   'Tanggal Permintaan'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_permintaan', 'Status Permintaan'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Permintaan Darah
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $konfigPermintaan = $this->get_fields_with_options(false, true);

        $controllerRawatInap  = new \App\Features\RawatInap\Registrasi\RegistrasiController();
        $controllerRegistrasi = new \App\Features\Registrasi\Registrasi\RegistrasiController();
        $controllerPasien     = new \App\Features\Role\Pasien\PasienController();
        $controllerOrang      = new \App\Features\Person\Orang\OrangController();

        $konfigRawatInap  = $controllerRawatInap->fields;
        $konfigRegistrasi = $controllerRegistrasi->fields;
        $konfigPasien     = $controllerPasien->fields;
        $konfigOrang      = $controllerOrang->fields;

        $modelKomponen = new \App\Features\InventoriDarah\KomponenDarah\KomponenDarahModel();
        $modelGolDarah = new \App\Features\Darah\GolonganDarah\GolonganDarahModel();
        $modelRhesus   = new \App\Features\Darah\Rhesus\RhesusModel();

        $masterKomponen = $modelKomponen->findAll();
        $masterGolDarah = $modelGolDarah->findAll();
        $masterRhesus   = $modelRhesus->findAll();

        $mockBaris      = [];
        $konfigGabungan = [];

        $tahunSekarang = date('Y');
        $bulanSekarang = date('m');

        $jumlahPermintaanBulanIni = $this->model->db->table('pelayanan_darah.permintaan_darah')
            ->where('EXTRACT(YEAR FROM tanggal_permintaan)', $tahunSekarang)
            ->where('EXTRACT(MONTH FROM tanggal_permintaan)', $bulanSekarang)
            ->countAllResults();

        $nextUrutan = $jumlahPermintaanBulanIni + 1;
        
        $stringUrutan = str_pad((string)$nextUrutan, 5, '0', STR_PAD_LEFT);
        
        $nomorPermintaanOtomatis = "{$tahunSekarang}/{$bulanSekarang}/REQ{$stringUrutan}";

        foreach ($konfigPermintaan as $fieldPermintaan) {
            $columnPermintaan = $fieldPermintaan[2];

            if ($columnPermintaan === 'id_permintaan') {
                continue;
            }

            $isTanggal = ($fieldPermintaan[3] === 'tanggal' || str_contains($columnPermintaan, 'tanggal') || $fieldPermintaan[3] === 'dtime');
            $mockBaris[$columnPermintaan] = $isTanggal ? date('Y-m-d\TH:i') : '';

            if ($columnPermintaan === 'no_permintaan') {
                $mockBaris[$columnPermintaan] = $nomorPermintaanOtomatis;
                $fieldPermintaan[3] = 'indeks';
            }

            if ($columnPermintaan === 'id_registrasi') {
                foreach ($konfigRegistrasi as $fieldRegistrasi) {
                    if ($fieldRegistrasi[2] === 'nomor_rawat') {
                        $mockBaris['nomor_rawat'] = '';
                        $konfigGabungan[] = $fieldRegistrasi;
                        break;
                    }
                }

                foreach ($konfigPasien as $fieldPasien) {
                    if ($fieldPasien[2] === 'nomor_rm') {
                        $mockBaris['nomor_rm'] = '';
                        $konfigGabungan[] = $fieldPasien;
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

                foreach ($konfigRawatInap as $fieldRanap) {
                    if ($fieldRanap[2] === 'kamar') {
                        $mockBaris['kamar'] = '';
                        $konfigGabungan[] = $fieldRanap;
                        break;
                    }
                }
                continue;
            }

            $konfigGabungan[] = $fieldPermintaan;
        }

        return view('admin/pelayanandarah/tambah_permintaandarah', [
            'judul'           => 'Tambah ' . $this->title,
            'breadcrumbs'     => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'      => $this->get_uri_path(),
            'kolom_id'        => $this->model->primaryKey,
            'konfig'          => $konfigGabungan,
            'baris'           => $mockBaris,
            'master_komponen' => $masterKomponen,
            'master_gol_darah'=> $masterGolDarah,
            'master_rhesus'   => $masterRhesus,
            'form_action'     => '/submittambah',
        ]);
    }

    /**
     * OVERRIDE: Memproses simpan data permintaan darah
     */
    #[\Override]
    public function create(): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $listKomponen = $this->request->getPost('id_komponen');
        $listGolDarah = $this->request->getPost('id_golongan_darah');
        $listRhesus   = $this->request->getPost('id_rhesus');
        $listJumlah   = $this->request->getPost('jumlah');

        $dataPermintaan = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataPermintaan[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        $dataPermintaan['id_status_permintaan'] = 1;

        $this->model->db->transStart();

        try {
            $this->model->insert($dataPermintaan);
            $idPermintaan = $this->model->getInsertID();

            if (!empty($listKomponen) && is_array($listKomponen)) {
                $modelDetail = new \App\Features\PelayananDarah\PermintaanDarahDetail\PermintaanDarahDetailModel();

                foreach ($listKomponen as $index => $idKomponen) {
                    if (empty($idKomponen)) continue;

                    $modelDetail->insert([
                        'id_permintaan'     => $idPermintaan,
                        'id_komponen'       => $idKomponen,
                        'id_golongan_darah' => $listGolDarah[$index],
                        'id_rhesus'         => $listRhesus[$index],
                        'jumlah'            => (int)$listJumlah[$index],
                    ]);
                }
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException("Gagal menyimpan data permintaan darah.");
            }

            session()->setFlashdata('success', 'Data permintaan darah berhasil disimpan.');

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = ($e instanceof \CodeIgniter\Database\Exceptions\DatabaseException) 
                ? $this->friendly_db_error($e) 
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Permintaan Darah
     */
    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $dataPermintaan = $this->model->find($id);
        if (!$dataPermintaan) {
            $dataPermintaan = [];
        }

        $dataRawatInap  = [];
        $dataRegistrasi = [];
        $dataPasien     = [];
        $dataOrang      = [];
        $dataDokter     = [];

        if (!empty($dataPermintaan['id_registrasi'])) {
            $modelRegistrasi = new \App\Features\Registrasi\Registrasi\RegistrasiModel();
            $dataRegistrasi  = $modelRegistrasi->find($dataPermintaan['id_registrasi']) ?? [];

            if (!empty($dataRegistrasi['id_pasien'])) {
                $modelPasien = new \App\Features\Role\Pasien\PasienModel();
                $dataPasien  = $modelPasien->find($dataRegistrasi['id_pasien']) ?? [];

                if (!empty($dataPasien['id_orang'])) {
                    $modelOrang = new \App\Features\Person\Orang\OrangModel();
                    $dataOrang  = $modelOrang->find($dataPasien['id_orang']) ?? [];
                }
            }
        }

        $modelRawatInap = new \App\Features\RawatInap\Registrasi\RegistrasiModel();
        $ranapResult    = $modelRawatInap->where('id_registrasi', $dataPermintaan['id_registrasi'])->first();
        if ($ranapResult && !empty($ranapResult['kamar'])) {
            $dataRawatInap['kamar'] = $ranapResult['kamar'];
        }
        
        if (!empty($dataPermintaan['id_dokter_pengirim'])) {
            $modelDokterUser = new \App\Features\Role\Dokter\DokterModel(); // Sesuai nama model role dokter kelompokmu
            $dataDokterRole  = $modelDokterUser->find($dataPermintaan['id_dokter_pengirim']) ?? [];
            
            if (!empty($dataDokterRole['id_orang'])) {
                $modelOrangDokter = new \App\Features\Person\Orang\OrangModel();
                $dataOrangDokter  = $modelOrangDokter->find($dataDokterRole['id_orang']) ?? [];
                $dataDokter['nama_dokter'] = $dataOrangDokter['nama'] ?? '';
            }
        }

        $baris = array_merge($dataOrang, $dataPasien, $dataRegistrasi, $dataRawatInap, $dataDokter, $dataPermintaan);

        $controllerRawatInap  = new \App\Features\RawatInap\Registrasi\RegistrasiController();
        $controllerRegistrasi = new \App\Features\Registrasi\Registrasi\RegistrasiController();
        $controllerPasien     = new \App\Features\Role\Pasien\PasienController();

        $konfigRawatInap  = $controllerRawatInap->fields;
        $konfigRegistrasi = $controllerRegistrasi->fields;
        $konfigPasien     = $controllerPasien->fields;
        $konfigPermintaan = $this->get_fields_with_options(false, true);

        $konfigGabungan = [];

        foreach ($konfigPermintaan as $fieldPermintaan) {
            $columnPermintaan = $fieldPermintaan[2];

            if ($columnPermintaan === 'id_registrasi') {
                foreach ($konfigRegistrasi as $fieldRegistrasi) {
                    if ($fieldRegistrasi[2] === 'nomor_rawat') { $konfigGabungan[] = $fieldRegistrasi; break; }
                }
                foreach ($konfigPasien as $fieldPasien) {
                    if ($fieldPasien[2] === 'nomor_rm') { $konfigGabungan[] = $fieldPasien; break; }
                }
                foreach ($konfigRawatInap as $fieldRanap) {
                    if ($fieldRanap[2] === 'kamar') { $konfigGabungan[] = $fieldRanap; break; }
                }
                continue;
            }
            $konfigGabungan[] = $fieldPermintaan;
        }

        $modelKomponen = new \App\Features\InventoriDarah\KomponenDarah\KomponenDarahModel();
        $modelGolDarah = new \App\Features\Darah\GolonganDarah\GolonganDarahModel();
        $modelRhesus   = new \App\Features\Darah\Rhesus\RhesusModel();

        $masterKomponen = $modelKomponen->findAll();
        $masterGolDarah = $modelGolDarah->findAll();
        $masterRhesus   = $modelRhesus->findAll();

        $modelDetail = new \App\Features\PelayananDarah\PermintaanDarahDetail\PermintaanDarahDetailModel();
        $dataDetailTersimpan = $modelDetail->where('id_permintaan', $id)->findAll();

        $breadcrumbs = [
            ['title' => 'Ubah', 'icon' => 'ubah']
        ];

        return view('admin/pelayanandarah/tambah_permintaandarah', [
            'judul'           => 'Ubah ' . $this->title,
            'breadcrumbs'     => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'      => $this->get_uri_path(),
            'kolom_id'        => $this->model->primaryKey,
            'konfig'          => $konfigGabungan,
            'baris'           => $baris,
            'master_komponen' => $masterKomponen,
            'master_gol_darah'=> $masterGolDarah,
            'master_rhesus'   => $masterRhesus,
            'detail_tersimpan'=> $dataDetailTersimpan,
            'form_action'     => '/submitedit/' . $id,
        ]);
    }

    /**
     * OVERRIDE: Mengeksekusi Simpan Perubahan Data Permintaan Darah
     */
    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->index();

        $rawPost = $this->request->getPost();

        $listKomponen = $this->request->getPost('id_komponen');
        $listGolDarah = $this->request->getPost('id_golongan_darah');
        $listRhesus   = $this->request->getPost('id_rhesus');
        $listJumlah   = $this->request->getPost('jumlah');

        $dataPermintaan = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataPermintaan[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        $this->model->db->transStart();

        try {
            $dataLama = $this->model->find($id);
            if ($dataLama) {
                $dataPermintaan['id_status_permintaan'] = $dataLama['id_status_permintaan'];
            }
            
            $this->model->update($id, $dataPermintaan);

            $modelDetail = new \App\Features\PelayananDarah\PermintaanDarahDetail\PermintaanDarahDetailModel();

            $modelDetail->where('id_permintaan', $id)->delete();

            if (!empty($listKomponen) && is_array($listKomponen)) {
                foreach ($listKomponen as $index => $idKomponen) {
                    if (empty($idKomponen)) continue;

                    $modelDetail->insert([
                        'id_permintaan'     => $id,
                        'id_komponen'       => $idKomponen,
                        'id_golongan_darah' => $listGolDarah[$index],
                        'id_rhesus'         => $listRhesus[$index],
                        'jumlah'            => (int)$listJumlah[$index],
                    ]);
                }
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException("Gagal memperbarui data permintaan darah.");
            }

            session()->setFlashdata('success', 'Data permintaan darah berhasil diperbarui.');

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = ($e instanceof \CodeIgniter\Database\Exceptions\DatabaseException) 
                ? $this->friendly_db_error($e) 
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * OVERRIDE: Menghapus data permintaan darah
     */
    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();
        
        $dataPermintaan = $this->model->find($id);
        if (!$dataPermintaan) {
            session()->setFlashdata('error', 'Gagal menghapus. Data permintaan darah tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $this->model->db->transStart();

        try {
            $modelDetail = new \App\Features\PelayananDarah\PermintaanDarahDetail\PermintaanDarahDetailModel();

            $modelDetail->where('id_permintaan', $id)->delete();

            $this->model->delete($id);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus data permintaan darah.');
            }

            session()->setFlashdata('success', 'Data permintaan darah berhasil dihapus.');

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
     * Menampilkan data modal permintaan darah
     */
    public function list()
    {
        $tabel = $this->model->table;

        $data = $this->model->builder()
            ->select("
                {$tabel}.id_permintaan,
                {$tabel}.no_permintaan,
                pelayanan_darah.status_permintaan.nama_status_permintaan AS status,
                CONCAT(role.pasien.nomor_rm, ' / ', registrasi.registrasi.nomor_rawat) AS identitas_rawat,
                person.orang.nama
            ")
            ->join('registrasi.registrasi', "registrasi.registrasi.id_registrasi = {$tabel}.id_registrasi", 'inner')
            ->join('role.pasien', 'role.pasien.id_pasien = registrasi.registrasi.id_pasien', 'inner')
            ->join('person.orang', 'person.orang.id_orang = role.pasien.id_orang', 'inner')
            ->join('pelayanan_darah.status_permintaan', "pelayanan_darah.status_permintaan.id_status_permintaan = {$tabel}.id_status_permintaan", 'inner')
            ->where("{$tabel}.id_status_permintaan !=", 3)
            ->orderBy("{$tabel}.tanggal_permintaan", 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
}
