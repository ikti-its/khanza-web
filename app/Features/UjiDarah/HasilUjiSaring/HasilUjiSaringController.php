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
        $nomorPengambilan   = '';

        if ($idPengambilanDarah) {
            $modelPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
            $dataPengambilan  = $modelPengambilan->find($idPengambilanDarah);
            if ($dataPengambilan) {
                $nomorPengambilan = $dataPengambilan['nomor_pengambilan'] ?? '';
            }
        }

        $this->model->db->transStart();

        try {
            $this->model->insert($dataUjiSaring);
            $idUjiSaring = $this->model->getInsertID();

            $isHbsagReaktif  = (isset($rawPost['hbsag']) && (string)$rawPost['hbsag'] === '1');
            $isHcvReaktif    = (isset($rawPost['hcv']) && (string)$rawPost['hcv'] === '1');
            $isHivReaktif    = (isset($rawPost['hiv']) && (string)$rawPost['hiv'] === '1');
            $isSifilisReaktif = (isset($rawPost['sifilis']) && (string)$rawPost['sifilis'] === '1');
            $isMalariaReaktif = (isset($rawPost['malaria']) && (string)$rawPost['malaria'] === '1');

            $modelStokDarah = new \App\Features\InventoriDarah\StokDarah\StokDarahModel();

            if ($isHbsagReaktif || $isHcvReaktif || $isHivReaktif || $isSifilisReaktif || $isMalariaReaktif) {
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

            session()->setFlashdata('success', 'Data hasil uji saring berhasil disimpan.');

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
}
