<?php
declare(strict_types=1);

namespace App\Features\TriaseUGD\DataTriase;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class DataTriaseController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DataTriaseModel(),
            [
                ['Triase UGD',  'triase_ugd'],
                ['Data Triase', 'data_triase'],
            ],
            'Data Triase',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_triase',             'ID Triase'],
                [SHOW, REQUIRED, I::INDEX,  'id_registrasi',         'ID Registrasi'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_kunjungan',     'Tanggal Kunjungan'],
                [SHOW, REQUIRED, I::SELECT, 'id_cara_masuk',         'Cara Masuk'],
                [SHOW, REQUIRED, I::SELECT, 'id_alat_transportasi',  'Alat Transportasi'],
                [SHOW, REQUIRED, I::SELECT, 'id_alasan_kedatangan',  'Alasan Kedatangan'],
                [SHOW, REQUIRED, I::TEXT,   'keterangan_kedatangan', 'Keterangan'],
                [SHOW, REQUIRED, I::INDEX,  'id_macam_kasus',        'Macam Kasus'],
                [HIDE, REQUIRED, I::NUMBER, 'sistolik',              'Tekanan Sistolik'],
                [HIDE, REQUIRED, I::NUMBER, 'diastolik',             'Tekanan Diastolik'],
                [HIDE, REQUIRED, I::NUMBER, 'nadi',                  'Nadi (x/menit)'],
                [HIDE, REQUIRED, I::NUMBER, 'pernapasan',            'Pernapasan (x/menit)'],
                [HIDE, REQUIRED, I::TEMP,   'suhu',                  'Suhu'],
                [HIDE, REQUIRED, I::NUMBER, 'saturasi_o2',           'Saturasi O2 (%)'],
                [HIDE, REQUIRED, I::NUMBER, 'nyeri',                 'Nyeri (0-10)'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Triase UGD
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah'],
        ];

        $konfigTriase = $this->get_fields_with_options(false, true);

        $controllerPrimer     = new \App\Features\TriaseUGD\DataTriasePrimer\DataTriasePrimerController();
        $controllerSekunder   = new \App\Features\TriaseUGD\DataTriaseSekunder\DataTriaseSekunderController();
        $konfigTriasePrimer   = $controllerPrimer->get_fields_with_options(false, true);
        $konfigTriaseSekunder = $controllerSekunder->get_fields_with_options(false, true);

        $dbPemeriksaan     = new \App\Features\TriaseUGD\TriasePemeriksaan\TriasePemeriksaanModel();
        $masterPemeriksaan = $dbPemeriksaan->findAll();

        $dbSkala     = new \App\Features\TriaseUGD\TriaseSkala\TriaseSkalaModel();
        $masterSkala = $dbSkala->findAll();

        $mockBaris      = [];
        $konfigGabungan = [];

        foreach ($konfigTriase as $field) {
            $namaKolom = $field[2];

            if ($namaKolom === 'id_triase') {
                continue;
            }

            $isTanggal             = $field[3] === 'tanggal'
            || str_contains($namaKolom, 'tanggal')
            || $field[3] === 'dtime';
            $mockBaris[$namaKolom] = $isTanggal ? date('Y-m-d\TH:i') : '';

            $konfigGabungan[] = $field;
        }

        foreach ($konfigTriasePrimer as $fPrimer) {
            $namaKolomPrimer = $fPrimer[2];

            if ($namaKolomPrimer === 'id_triase_primer' || $namaKolomPrimer === 'id_triase') {
                continue;
            }

            $isTanggalPrimer             = $fPrimer[3] === 'tanggal'
            || str_contains($namaKolomPrimer, 'tanggal')
            || $fPrimer[3] === 'dtime';
            $mockBaris[$namaKolomPrimer] = $isTanggalPrimer ? date('Y-m-d\TH:i') : '';

            $konfigGabungan[] = $fPrimer;
        }

        foreach ($konfigTriaseSekunder as $fSekunder) {
            $namaKolomSekunder = $fSekunder[2];

            if ($namaKolomSekunder === 'id_triase_sekunder' || $namaKolomSekunder === 'id_triase') {
                continue;
            }

            if (array_key_exists($namaKolomSekunder, $mockBaris)) {
                continue;
            }

            $isTanggalSekunder             = $fSekunder[3] === 'tanggal'
            || str_contains($namaKolomSekunder, 'tanggal')
            || $fSekunder[3] === 'dtime';
            $mockBaris[$namaKolomSekunder] = $isTanggalSekunder ? date('Y-m-d\TH:i') : '';

            $konfigGabungan[] = $fSekunder;
        }

        return view('admin/triaseugd/tambah_datatriase', [
            'judul'              => 'Tambah ' . $this->title,
            'breadcrumbs'        => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'         => $this->get_uri_path(),
            'kolom_id'           => $this->model->primaryKey,
            'konfig'             => $konfigGabungan,
            'baris'              => $mockBaris,
            'master_pemeriksaan' => $masterPemeriksaan,
            'master_skala'       => $masterSkala,
            'form_action'        => '/submittambah',
        ]);
    }

    /**
     * OVERRIDE: Memproses Simpan Data Triase UGD
     */
    #[\Override]
    public function create(): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $dataTriase = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataTriase[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        $listSkalaTerpilih = $rawPost['id_skala'] ?? null;
        $keluhanUtama      = $rawPost['keluhan_utama'] ?? null;
        $idKebutuhan       = $rawPost['id_kebutuhan_khusus'] ?? null;
        $anamnesaSingkat   = $rawPost['anamnesa_singkat'] ?? null;

        $this->model->db->transStart();

        try {
            $dataTriaseLama = $this->model->where('id_registrasi', $dataTriase['id_registrasi'])->first();

            if ($dataTriaseLama) {
                throw new \RuntimeException(
                    'Gagal menyimpan! Nomor rawat ini sudah memiliki dokumen keputusan triase sebelumnya. Silakan gunakan tombol ubah pada kolom aksi jika terjadi perubahan kondisi klinis pada pasien.',
                );
            }

            $this->model->insert($dataTriase);
            $idTriaseBaru = $this->model->getInsertID();

            $tabAktif = $rawPost['triase_tab_aktif'] ?? 'primer';

            $modelMasterSkala = new \App\Features\TriaseUGD\TriaseSkala\TriaseSkalaModel();

            if ($tabAktif === 'primer') {
                $modelPrimer = new \App\Features\TriaseUGD\DataTriasePrimer\DataTriasePrimerModel();

                $modelPrimer->insert([
                    'id_triase'           => $idTriaseBaru,
                    'keluhan_utama'       => $keluhanUtama,
                    'id_kebutuhan_khusus' => $idKebutuhan,
                    'tanggal_triase'      => $rawPost['tanggal_triase'],
                    'catatan'             => $rawPost['catatan'],
                    'id_plan_primer'      => $rawPost['id_plan_primer'],
                    'id_petugas'          => $rawPost['id_petugas'],
                ]);
            } else if ($tabAktif === 'sekunder') {
                $modelSekunder = new \App\Features\TriaseUGD\DataTriaseSekunder\DataTriaseSekunderModel();

                $modelSekunder->insert([
                    'id_triase'        => $idTriaseBaru,
                    'anamnesa_singkat' => $anamnesaSingkat,
                    'tanggal_triase'   => $rawPost['tanggal_triase'],
                    'catatan'          => $rawPost['catatan'],
                    'id_plan_sekunder' => $rawPost['id_plan_sekunder'],
                    'id_petugas'       => $rawPost['id_petugas'],
                ]);
            }

            if (!empty($listSkalaTerpilih) && is_array($listSkalaTerpilih)) {
                $modelDetail = new \App\Features\TriaseUGD\DataTriaseDetail\DataTriaseDetailModel();

                $listSkalaTerpilih = array_unique($listSkalaTerpilih);

                foreach ($listSkalaTerpilih as $idSkala) {
                    if (empty($idSkala))
                        continue;

                    $infoSkala = $modelMasterSkala->find($idSkala);
                    if (!$infoSkala)
                        continue;

                    $tingkatSkala = (int) ($infoSkala['id_tingkat_skala'] ?? 0);

                    if ($tabAktif === 'primer' && !in_array($tingkatSkala, [1, 2], true)) {
                        continue;
                    }
                    if ($tabAktif === 'sekunder' && !in_array($tingkatSkala, [3, 4, 5], true)) {
                        continue;
                    }

                    $modelDetail->insert([
                        'id_triase' => $idTriaseBaru,
                        'id_skala'  => $idSkala,
                    ]);
                }
            }

            $modelRegistrasi = new \App\Features\UGD\Registrasi\RegistrasiModel();
            $modelRegistrasi->updateStatusTriase($dataTriase['id_registrasi'], 2);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan data pemeriksaan triase pasien.');
            }

            session()->setFlashdata('success', 'Data pemeriksaan triase pasien berhasil disimpan.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data / Retriase UGD
     */
    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0)
            return $this->index();

        $triaseInduk = $this->model->find($id);
        if (!$triaseInduk) {
            $triaseInduk = [];
        }

        $dataRegistrasi = [];
        $dataPasien     = [];
        $dataOrang      = [];
        $dataKasus      = [];
        $dataPetugas    = [];
        $dataPrimer     = [];
        $dataSekunder   = [];

        $modelPrimer = new \App\Features\TriaseUGD\DataTriasePrimer\DataTriasePrimerModel();
        if (!empty($triaseInduk)) {
            $dataPrimer = $modelPrimer->where('id_triase', $id)->first() ?? [];
        }

        $modelSekunder = new \App\Features\TriaseUGD\DataTriaseSekunder\DataTriaseSekunderModel();
        if (!empty($triaseInduk)) {
            $dataSekunder = $modelSekunder->where('id_triase', $id)->first() ?? [];
        }

        $tabAktif = !empty($dataPrimer) ? 'primer' : 'sekunder';

        if (!empty($triaseInduk['id_registrasi'])) {
            $modelReg = new \App\Features\UGD\Registrasi\RegistrasiModel();
            $regRow   = $modelReg->find($triaseInduk['id_registrasi']) ?? [];

            if (!empty($regRow)) {
                $dataRegistrasi['id_registrasi'] = $regRow['id_registrasi'] ?? '';
                $dataRegistrasi['nomor_rawat']   = $regRow['nomor_rawat'] ?? '';
                // $dataRegistrasi['tanggal_kunjungan'] = $regRow['tanggal_reg'] ?? '';

                if (!empty($regRow['id_pasien'])) {
                    $modelPasien = new \App\Features\Role\Pasien\PasienModel();
                    $pasienRow   = $modelPasien->find($regRow['id_pasien']) ?? [];

                    if (!empty($pasienRow)) {
                        $dataPasien['nomor_rm'] = $pasienRow['nomor_rm'] ?? '';

                        if (!empty($pasienRow['id_orang'])) {
                            $modelOrang = new \App\Features\Person\Orang\OrangModel();
                            $orangRow   = $modelOrang->find($pasienRow['id_orang']) ?? [];

                            if (!empty($orangRow)) {
                                $dataOrang['nama_pasien']   = $orangRow['nama'] ?? '';
                                $dataOrang['tanggal_lahir'] = $orangRow['tanggal_lahir'] ?? '';
                            }
                        }
                    }
                }
            }
        }

        if (!empty($triaseInduk['id_macam_kasus'])) {
            $modelKasus = new \App\Features\TriaseUGD\TriaseMacamKasus\TriaseMacamKasusModel();
            $kasusRow   = $modelKasus->find($triaseInduk['id_macam_kasus']) ?? [];
            if (!empty($kasusRow)) {
                $dataKasus['nama_macam_kasus'] = $kasusRow['nama_macam_kasus'] ?? '';
            }
        }

        $idPetugasTerpilih = !empty($dataPrimer)
            ? $dataPrimer['id_petugas'] ?? null
            : $dataSekunder['id_petugas'] ?? null;
        if (!empty($idPetugasTerpilih)) {
            $modelPetugas = new \App\Features\Role\Petugas\PetugasModel();
            $petugasRow   = $modelPetugas->find($idPetugasTerpilih) ?? [];

            if (!empty($petugasRow['id_orang'])) {
                $modelOrang = new \App\Features\Person\Orang\OrangModel();
                $orangRow   = $modelOrang->find($petugasRow['id_orang']) ?? [];

                if (!empty($orangRow['nama'])) {
                    $dataPetugas['nama_petugas'] = $orangRow['nama'];
                }
            }
        }

        $barisRaw = array_merge(
            $dataOrang,
            $dataPasien,
            $dataRegistrasi,
            $dataKasus,
            $dataPetugas,
            $dataPrimer,
            $dataSekunder,
            $triaseInduk,
        );

        $baris = [];
        foreach ($barisRaw as $key => $val) {
            if (str_contains($key, 'tanggal')) {
                $baris[$key] = $val ? date('Y-m-d\TH:i', strtotime((string) $val)) : '';
            } else {
                $baris[$key] = $val;
            }
        }

        $konfigTriase = $this->get_fields_with_options(false, true);

        $controllerPrimer     = new \App\Features\TriaseUGD\DataTriasePrimer\DataTriasePrimerController();
        $controllerSekunder   = new \App\Features\TriaseUGD\DataTriaseSekunder\DataTriaseSekunderController();
        $konfigTriasePrimer   = $controllerPrimer->get_fields_with_options(false, true);
        $konfigTriaseSekunder = $controllerSekunder->get_fields_with_options(false, true);

        $dbPemeriksaan     = new \App\Features\TriaseUGD\TriasePemeriksaan\TriasePemeriksaanModel();
        $masterPemeriksaan = $dbPemeriksaan->findAll();

        $dbSkala     = new \App\Features\TriaseUGD\TriaseSkala\TriaseSkalaModel();
        $masterSkala = $dbSkala->findAll();

        $konfigGabungan = [];
        foreach ($konfigTriase as $field) {
            if ($field[2] === 'id_triase')
                continue;
            $konfigGabungan[] = $field;
        }

        foreach ($konfigTriasePrimer as $fPrimer) {
            if ($fPrimer[2] === 'id_triase_primer' || $fPrimer[2] === 'id_triase')
                continue;
            $konfigGabungan[] = $fPrimer;
        }

        foreach ($konfigTriaseSekunder as $fSekunder) {
            if ($fSekunder[2] === 'id_triase_sekunder' || $fSekunder[2] === 'id_triase')
                continue;

            $exists = false;
            foreach ($konfigGabungan as $konfig) {
                if ($konfig[2] === $fSekunder[2]) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $konfigGabungan[] = $fSekunder;
            }
        }

        $modelDetail         = new \App\Features\TriaseUGD\DataTriaseDetail\DataTriaseDetailModel();
        $listDetailTersimpan = [];
        if (!empty($triaseInduk)) {
            $listDetailTersimpan = $modelDetail->where('id_triase', $id)->findAll();
        }

        $breadcrumbs = [
            ['title' => 'Ubah', 'icon' => 'ubah'],
        ];

        return view('admin/triaseugd/tambah_datatriase', [
            'judul'              => 'Ubah ' . $this->title,
            'breadcrumbs'        => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'         => $this->get_uri_path(),
            'kolom_id'           => $this->model->primaryKey,
            'konfig'             => $konfigGabungan,
            'baris'              => $baris,
            'master_pemeriksaan' => $masterPemeriksaan,
            'master_skala'       => $masterSkala,
            'tab_aktif_lama'     => $tabAktif,
            'detail_triase_lama' => $listDetailTersimpan,
            'form_action'        => '/submitedit/' . $id,
        ]);
    }

    /**
     * OVERRIDE: Mengeksekusi Simpan Perubahan Data Triase & Retriase UGD
     */
    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->index();

        $rawPost = $this->request->getPost();

        $dataTriase = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataTriase[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        $listSkalaTerpilih = $rawPost['id_skala'] ?? null;
        $keluhanUtama      = $rawPost['keluhan_utama'] ?? null;
        $idKebutuhan       = $rawPost['id_kebutuhan_khusus'] ?? null;
        $anamnesaSingkat   = $rawPost['anamnesa_singkat'] ?? null;

        $this->model->db->transStart();

        try {
            $this->model->update($id, $dataTriase);

            $tabAktif = $rawPost['triase_tab_aktif'] ?? 'primer';

            $modelPrimer   = new \App\Features\TriaseUGD\DataTriasePrimer\DataTriasePrimerModel();
            $modelSekunder = new \App\Features\TriaseUGD\DataTriaseSekunder\DataTriaseSekunderModel();

            $modelMasterSkala = new \App\Features\TriaseUGD\TriaseSkala\TriaseSkalaModel();

            if ($tabAktif === 'primer') {
                $modelSekunder->where('id_triase', $id)->delete();

                $existingPrimer = $modelPrimer->where('id_triase', $id)->first();
                $payloadPrimer  = [
                    'id_triase'           => $id,
                    'keluhan_utama'       => $keluhanUtama,
                    'id_kebutuhan_khusus' => $idKebutuhan,
                    'tanggal_triase'      => $rawPost['tanggal_triase'],
                    'catatan'             => $rawPost['catatan'],
                    'id_plan_primer'      => $rawPost['id_plan_primer'],
                    'id_petugas'          => $rawPost['id_petugas'],
                ];

                if ($existingPrimer) {
                    $modelPrimer->where('id_triase', $id)->set($payloadPrimer)->update();
                } else {
                    $modelPrimer->insert($payloadPrimer);
                }
            } else if ($tabAktif === 'sekunder') {
                $modelPrimer->where('id_triase', $id)->delete();

                $existingSekunder = $modelSekunder->where('id_triase', $id)->first();
                $payloadSekunder  = [
                    'id_triase'        => $id,
                    'anamnesa_singkat' => $anamnesaSingkat,
                    'tanggal_triase'   => $rawPost['tanggal_triase'],
                    'catatan'          => $rawPost['catatan'],
                    'id_plan_sekunder' => $rawPost['id_plan_sekunder'],
                    'id_petugas'       => $rawPost['id_petugas'],
                ];

                if ($existingSekunder) {
                    $modelSekunder->where('id_triase', $id)->set($payloadSekunder)->update();
                } else {
                    $modelSekunder->insert($payloadSekunder);
                }
            }

            $modelDetail = new \App\Features\TriaseUGD\DataTriaseDetail\DataTriaseDetailModel();

            $modelDetail->where('id_triase', $id)->delete();

            if (!empty($listSkalaTerpilih) && is_array($listSkalaTerpilih)) {
                $listSkalaTerpilih = array_unique($listSkalaTerpilih);

                foreach ($listSkalaTerpilih as $idSkala) {
                    if (empty($idSkala))
                        continue;

                    $infoSkala = $modelMasterSkala->find($idSkala);
                    if (!$infoSkala)
                        continue;

                    $tingkatSkala = (int) ($infoSkala['id_tingkat_skala'] ?? 0);

                    if ($tabAktif === 'primer' && !in_array($tingkatSkala, [1, 2], true)) {
                        continue;
                    }
                    if ($tabAktif === 'sekunder' && !in_array($tingkatSkala, [3, 4, 5], true)) {
                        continue;
                    }

                    $modelDetail->insert([
                        'id_triase' => $id,
                        'id_skala'  => $idSkala,
                    ]);
                }
            }

            if (!empty($dataTriase['id_registrasi'])) {
                $modelRegistrasi = new \App\Features\UGD\Registrasi\RegistrasiModel();
                $modelRegistrasi->updateStatusTriase($dataTriase['id_registrasi'], 2);
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui data pemeriksaan triase pasien.');
            }

            session()->setFlashdata('success', 'Data pemeriksaan triase pasien berhasil diperbarui.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * OVERRIDE: Menghapus data triase UGD
     */
    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->home();

        $dataTriase = $this->model->find($id);
        if (!$dataTriase) {
            session()->setFlashdata('error', 'Gagal menghapus. Data pemeriksaan triase tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $this->model->db->transStart();

        try {
            $modelDetail   = new \App\Features\TriaseUGD\DataTriaseDetail\DataTriaseDetailModel();
            $modelPrimer   = new \App\Features\TriaseUGD\DataTriasePrimer\DataTriasePrimerModel();
            $modelSekunder = new \App\Features\TriaseUGD\DataTriaseSekunder\DataTriaseSekunderModel();

            $modelDetail->where('id_triase', $id)->delete();
            $modelPrimer->where('id_triase', $id)->delete();
            $modelSekunder->where('id_triase', $id)->delete();

            $this->model->delete($id);

            $modelRegistrasi = new \App\Features\UGD\Registrasi\RegistrasiModel();
            $modelRegistrasi->updateStatusTriase($dataTriase['id_registrasi'], 1);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus data pemeriksaan triase pasien.');
            }

            session()->setFlashdata('success', 'Data pemeriksaan triase pasien berhasil dihapus.');
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $this->friendly_db_error($e));
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $e->getMessage());
        }

        return $this->home();
    }
}
