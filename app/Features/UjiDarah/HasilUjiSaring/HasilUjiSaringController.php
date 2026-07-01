<?php
declare(strict_types=1);

namespace App\Features\UjiDarah\HasilUjiSaring;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class HasilUjiSaringController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilUjiSaringModel(),
            [
                ['Uji Darah',        'uji_darah'],
                ['Hasil Uji Saring', 'hasil_uji_saring'],
            ],
            'Hasil Uji Saring',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_uji_saring',        'ID Uji Saring'],
                [SHOW, REQUIRED, I::INDEX, 'id_pengambilan_darah', 'ID Pengambilan Darah'],
                [SHOW, REQUIRED, I::DATE,  'tanggal_uji',          'Tanggal Uji'],
                [SHOW, REQUIRED, I::SELECT,'id_metode_uji',        'Metode Uji'],
                [SHOW, REQUIRED, I::INDEX, 'id_petugas',           'ID Petugas'],
                [SHOW, REQUIRED, I::BOOL,  'hbsag',                'HBsAg'],
                [SHOW, REQUIRED, I::BOOL,  'hcv',                  'HCV'],
                [SHOW, REQUIRED, I::BOOL,  'hiv',                  'HIV'],
                [SHOW, REQUIRED, I::BOOL,  'sifilis',              'Sifilis'],
                [SHOW, REQUIRED, I::BOOL,  'malaria',              'Malaria'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Hasil Uji Saring
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $konfigUjiSaring = $this->get_fields_with_options(false, true);

        $controllerPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahController();
        $konfigPengambilan     = $controllerPengambilan->get_fields_with_options(false, true);

        $mockBaris      = [];
        $konfigGabungan = [];

        foreach ($konfigUjiSaring as $field) {
            $namaKolom = $field[2];

            if ($namaKolom === 'id_uji_saring') {
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

        return view('admin/ujidarah/tambah_ujisaring', [
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
     * OVERRIDE: Memproses simpan data hasil uji saring
     */
    #[\Override]
    public function create(): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $dataUjiSaring = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataUjiSaring[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        if (!isset($rawPost['malaria']) || $rawPost['malaria'] === '') {
            $dataUjiSaring['malaria'] = null;
        }

        $idPengambilanDarah = $dataUjiSaring['id_pengambilan_darah'] ?? null;
        $dataPengambilan    = [];
        $nomorPengambilan   = '';
        $daftarReaktif      = [];
        $pencekalanUrl      = null;

        if ($idPengambilanDarah) {
            $modelPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
            $dataPengambilan  = $modelPengambilan->find($idPengambilanDarah);
            if ($dataPengambilan) {
                $nomorPengambilan = $dataPengambilan['nomor_pengambilan'] ?? '';
            }
        }

        $this->model->db->transStart();

        try {
            $this->model->validasiTanggalUji($rawPost['id_pengambilan_darah'], $rawPost['tanggal_uji']);

            $this->model->insert($dataUjiSaring);
            $idUjiSaring = $this->model->getInsertID();

            $daftarReaktif = $this->getDaftarReaktif($rawPost);
            $modelStokDarah = new \App\Features\InventoriDarah\StokDarah\StokDarahModel();

            if (!empty($daftarReaktif)) {
                $modelKasusReaktif = new \App\Features\PenangananDonor\KasusReaktif\KasusReaktifModel();

                $tanggalDitetapkan = date('Y-m-d');
                $idStatusKasus     = 1;

                $modelKasusReaktif->insert([
                    'id_uji_saring'      => $idUjiSaring,
                    'tanggal_ditetapkan' => $tanggalDitetapkan,
                    'id_status_kasus'    => $idStatusKasus,
                ]);

                if (!empty($nomorPengambilan)) {
                    $modelStokDarah->like('no_kantong', $nomorPengambilan, 'before')
                                   ->set(['id_status_stok' => 3])
                                   ->update();
                }

                if (empty($dataPengambilan['id_kunjungan'])) {
                    throw new \RuntimeException('Data kunjungan tidak ditemukan, sehingga form pencekalan tidak dapat dibuka.');
                }

                $queryPencekalan = [
                    'kunjungan'  => $dataPengambilan['id_kunjungan'],
                    'petugas'    => $dataUjiSaring['id_petugas'] ?? '',
                    'reaktif'    => implode(',', $daftarReaktif),
                ];

                $pencekalanUrl = '/penanganan-donor/pencekalan/tambah?' . http_build_query($queryPencekalan);

            } else {
                if (!empty($nomorPengambilan)) {
                    $modelStokDarah->like('no_kantong', $nomorPengambilan, 'before')
                                   ->set(['id_status_stok' => 2])
                                   ->update();
                }
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException("Gagal menyimpan data hasil uji saring.");
            }

            if ($pencekalanUrl !== null) {
                session()->set('pencekalan_url', $pencekalanUrl);
                session()->set(
                    'pencekalan_message',
                    'Silakan lengkapi data pencekalan terlebih dahulu sebagai tindak lanjut hasil uji saring reaktif.'
                );
            } else {
                session()->setFlashdata('success', 'Data hasil uji saring berhasil disimpan.');
            }

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = ($e instanceof \CodeIgniter\Database\Exceptions\DatabaseException) 
                ? $this->friendly_db_error($e) 
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
            return redirect()->back()->withInput();
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Hasil Uji Saring
     */
    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $dataUjiSaring = $this->model->find($id);
        if (!$dataUjiSaring) {
            $dataUjiSaring = [];
        }

        $dataPengambilan  = [];
        $dataPetugasMedis = [];

        if (!empty($dataUjiSaring['id_pengambilan_darah'])) {
            $modelPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
            $pengambilanRow   = $modelPengambilan->find($dataUjiSaring['id_pengambilan_darah']);
            
            if ($pengambilanRow) {
                $dataPengambilan['nomor_pengambilan'] = $pengambilanRow['nomor_pengambilan'] ?? '';
            }
        }

        if (!empty($dataUjiSaring['id_petugas'])) {
            $modelPetugas = new \App\Features\Role\Petugas\PetugasModel();
            $petugasRow   = $modelPetugas->find($dataUjiSaring['id_petugas']) ?? [];

            if (!empty($petugasRow['id_orang'])) {
                $modelOrangPetugas = new \App\Features\Person\Orang\OrangModel();
                $orangPetugasRow   = $modelOrangPetugas->find($petugasRow['id_orang']) ?? [];
                
                if (isset($orangPetugasRow['nama'])) {
                    $dataPetugasMedis['nama_petugas'] = $orangPetugasRow['nama'];
                }
            }
        }

        $baris = array_merge($dataPengambilan, $dataPetugasMedis, $dataUjiSaring);

        $kolomUji = ['hbsag', 'hcv', 'hiv', 'sifilis', 'malaria'];
        foreach ($kolomUji as $kolom) {
            if (array_key_exists($kolom, $baris) && $baris[$kolom] !== null && $baris[$kolom] !== '') {
                $isTrue = ($baris[$kolom] === true || $baris[$kolom] == 1 || $baris[$kolom] === 't');
                $baris[$kolom] = $isTrue ? '1' : '0';
            }
        }

        $controllerPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahController();
        
        $konfigUjiSaring   = $this->get_fields_with_options(false, true);
        $konfigPengambilan = $controllerPengambilan->get_fields_with_options(false, true);

        $konfigGabungan = [];

        foreach ($konfigUjiSaring as $field) {
            $namaKolom = $field[2];

            if ($namaKolom === 'id_uji_saring') {
                continue;
            }

            if ($namaKolom === 'id_pengambilan_darah') {
                foreach ($konfigPengambilan as $fPengambilan) {
                    if ($fPengambilan[2] === 'nomor_pengambilan') {
                        $konfigGabungan[] = $fPengambilan;
                        break;
                    }
                }
                continue;
            }

            $konfigGabungan[] = $field;
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

        return view('admin/ujidarah/tambah_ujisaring', [
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
     * OVERRIDE: Mengeksekusi simpan perubahan data hasil uji saring
     */
    #[\Override]
    public function update(int|string $id): RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $dataUjiSaring = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataUjiSaring[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        if (!isset($dataUjiSaring['malaria']) || $dataUjiSaring['malaria'] === '') {
            $dataUjiSaring['malaria'] = null;
        }

        $idPengambilanDarah = $dataUjiSaring['id_pengambilan_darah'] ?? null;
        $dataPengambilan    = [];
        $nomorPengambilan   = '';

        if ($idPengambilanDarah) {
            $modelPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
            $dataPengambilan  = $modelPengambilan->find($idPengambilanDarah) ?? [];
            if (!empty($dataPengambilan)) {
                $nomorPengambilan = $dataPengambilan['nomor_pengambilan'] ?? '';
            }
        }

        if (empty($nomorPengambilan)) {
            session()->setFlashdata('error', 'Gagal memperbarui data. Nomor pengambilan darah tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idKunjungan    = $dataPengambilan['id_kunjungan'] ?? null;
        $daftarReaktif  = $this->getDaftarReaktif($rawPost);
        $pencekalanUrl  = null;
        $hapusSesiWajib = false;

        $this->model->db->transStart();

        try {
            $this->model->validasiTanggalUji($rawPost['id_pengambilan_darah'], $rawPost['tanggal_uji']);

            $this->model->update($id, $dataUjiSaring);

            $modelStokDarah    = new \App\Features\InventoriDarah\StokDarah\StokDarahModel();
            $modelKasusReaktif = new \App\Features\PenangananDonor\KasusReaktif\KasusReaktifModel();

            $kasusLama = $modelKasusReaktif->where('id_uji_saring', $id)->first();

            if (!empty($daftarReaktif)) {
                if (!$kasusLama) {
                    $modelKasusReaktif->insert([
                        'id_uji_saring'      => $id,
                        'tanggal_ditetapkan' => date('Y-m-d'),
                        'id_status_kasus'    => 1,
                    ]);
                }

                $modelStokDarah->like('no_kantong', $nomorPengambilan, 'before')
                               ->set(['id_status_stok' => 3])
                               ->update();

                if (empty($idKunjungan)) {
                    throw new \RuntimeException('Data kunjungan tidak ditemukan, sehingga form pencekalan tidak dapat dibuka.');
                }

                if ($this->hasPencekalan($idKunjungan)) {
                    $this->updateKeteranganPencekalan($idKunjungan, $daftarReaktif);
                    $hapusSesiWajib = true;
                } else {
                    $pencekalanUrl = $this->makePencekalanUrl($dataPengambilan, $dataUjiSaring, $daftarReaktif);
                }
            } else {
                if ($kasusLama) {
                    $modelKasusReaktif->where('id_uji_saring', $id)->delete();
                }

                $this->hapusPencekalan($idKunjungan);

                $modelStokDarah->like('no_kantong', $nomorPengambilan, 'before')
                               ->set(['id_status_stok' => 2])
                               ->update();

                $hapusSesiWajib = true;
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException("Gagal memperbarui data hasil uji saring.");
            }

            if ($hapusSesiWajib) {
                $this->clearPencekalanSession();
            }

            if ($pencekalanUrl !== null) {
                session()->set('pencekalan_url', $pencekalanUrl);
                session()->set(
                    'pencekalan_message',
                    'Silakan lengkapi data pencekalan terlebih dahulu sebagai tindak lanjut hasil uji saring reaktif.'
                );
            } else {
                session()->setFlashdata('success', 'Data hasil uji saring berhasil diperbarui.');
            }

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = ($e instanceof \CodeIgniter\Database\Exceptions\DatabaseException) 
                ? $this->friendly_db_error($e) 
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
            return redirect()->back()->withInput();
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * OVERRIDE: Menghapus data hasil uji saring
     */
    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();

        $dataUjiSaring = $this->model->find($id);
        if (!$dataUjiSaring) {
            session()->setFlashdata('error', 'Gagal menghapus. Data hasil uji saring tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idPengambilanDarah = $dataUjiSaring['id_pengambilan_darah'] ?? null;
        $dataPengambilan    = [];
        $nomorPengambilan   = '';
        $idKunjungan        = null;

        if ($idPengambilanDarah) {
            $modelPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
            $dataPengambilan  = $modelPengambilan->find($idPengambilanDarah) ?? [];

            if (!empty($dataPengambilan)) {
                $nomorPengambilan = $dataPengambilan['nomor_pengambilan'] ?? '';
                $idKunjungan      = $dataPengambilan['id_kunjungan'] ?? null;
            }
        }

        $this->model->db->transStart();

        try {
            $modelKasusReaktif = new \App\Features\PenangananDonor\KasusReaktif\KasusReaktifModel();
            $modelStokDarah    = new \App\Features\InventoriDarah\StokDarah\StokDarahModel();

            $modelKasusReaktif->where('id_uji_saring', $id)->delete();

            $this->hapusPencekalan($idKunjungan);

            if (!empty($nomorPengambilan)) {
                $modelStokDarah->like('no_kantong', $nomorPengambilan, 'before')
                               ->set(['id_status_stok' => 1])
                               ->update();
            }

            $this->model->delete($id);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus data hasil uji saring.');
            }

            $this->clearPencekalanSession();

            session()->setFlashdata('success', 'Data hasil uji saring berhasil dihapus.');

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
     * Mengambil daftar parameter IMLTD yang bernilai reaktif
     */
    private function getDaftarReaktif(array $rawPost): array
    {
        $daftarUji = [
            'hbsag'   => 'HBsAg',
            'hcv'     => 'HCV',
            'hiv'     => 'HIV',
            'sifilis' => 'Sifilis',
            'malaria' => 'Malaria',
        ];
    
        $daftarReaktif = [];
    
        foreach ($daftarUji as $kolom => $label) {
            if (isset($rawPost[$kolom]) && (string) $rawPost[$kolom] === '1') {
                $daftarReaktif[] = $label;
            }
        }
    
        return $daftarReaktif;
    }
    
    /**
     * Membuat keterangan pencekalan berdasarkan hasil uji saring reaktif
     */
    private function makeKeteranganReaktif(array $daftarReaktif): string
    {
        return 'Reaktif ' . implode(', ', $daftarReaktif) . ' pada uji saring IMLTD';
    }
    
    /**
     * Membuat URL form pencekalan dari hasil uji saring reaktif
     */
    private function makePencekalanUrl(array $dataPengambilan, array $dataUjiSaring, array $daftarReaktif): string
    {
        $queryPencekalan = [
            'kunjungan' => $dataPengambilan['id_kunjungan'] ?? '',
            'petugas'   => $dataUjiSaring['id_petugas'] ?? '',
            'reaktif'   => implode(',', $daftarReaktif),
        ];
    
        return '/penanganan-donor/pencekalan/tambah?' . http_build_query($queryPencekalan);
    }
    
    /**
     * Mengecek apakah pencekalan dari hasil uji saring IMLTD sudah ada
     */
    private function hasPencekalan(int|string $idKunjungan): bool
    {
        if (empty($idKunjungan)) {
            return false;
        }
    
        $modelPencekalan = new \App\Features\PenangananDonor\Pencekalan\PencekalanModel();
    
        return $modelPencekalan
            ->where('id_kunjungan', $idKunjungan)
            ->first() !== null;
    }
    
    /**
     * Menghapus pencekalan yang berasal dari hasil uji saring IMLTD
     */
    private function hapusPencekalan(int|string $idKunjungan): void
    {
        if (empty($idKunjungan)) {
            return;
        }
    
        $modelPencekalan = new \App\Features\PenangananDonor\Pencekalan\PencekalanModel();
    
        $modelPencekalan
            ->where('id_kunjungan', $idKunjungan)
            ->delete();
    }
    
    /**
     * Memperbarui keterangan pencekalan jika hasil reaktif berubah.
     */
    private function updateKeteranganPencekalan(int|string $idKunjungan, array $daftarReaktif): void
    {
        if (empty($idKunjungan)) {
            return;
        }
    
        $modelPencekalan = new \App\Features\PenangananDonor\Pencekalan\PencekalanModel();
    
        $modelPencekalan
            ->where('id_kunjungan', $idKunjungan)
            ->set([
                'keterangan' => $this->makeKeteranganReaktif($daftarReaktif),
            ])
            ->update();
    }
    
    /**
     * Menghapus session pengisian pencekalan
     */
    private function clearPencekalanSession(): void
    {
        session()->remove([
            'pencekalan_url',
            'pencekalan_message',
        ]);
    }
}
