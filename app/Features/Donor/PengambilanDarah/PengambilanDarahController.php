<?php
declare(strict_types=1);

namespace App\Features\Donor\PengambilanDarah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PengambilanDarahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengambilanDarahModel(),
            [
                ['Donor',             'donor'],
                ['Pengambilan Darah', 'pengambilan_darah'],
            ],
            'Pengambilan Darah',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::DETAIL,
                A::SEPARATE,
                A::TEST,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,   'id_pengambilan_darah',  'ID Pengambilan Darah'],
                [SHOW, REQUIRED, I::TEXT,    'nomor_pengambilan',     'Nomor Pengambilan'],
                [SHOW, REQUIRED, I::INDEX,   'id_kunjungan',          'ID Kunjungan'],
                [SHOW, REQUIRED, I::DATE,    'tanggal_pengambilan',   'Tanggal Pengambilan'],
                [SHOW, REQUIRED, I::SELECT,  'id_shift',              'Shift'],
                [SHOW, REQUIRED, I::TEXT,    'no_bag',                'Nomor Bag'],
                [SHOW, REQUIRED, I::SELECT,  'id_jenis_bag',          'Jenis Bag'],
                [SHOW, REQUIRED, I::SELECT,  'id_jenis_donor',        'Jenis Donor'],
                [SHOW, REQUIRED, I::SELECT,  'id_lokasi_pengambilan', 'Lokasi Pengambilan'],
                [SHOW, REQUIRED, I::INDEX,   'id_petugas',            'ID Petugas'],
                [SHOW, OPTIONAL, I::SELECT,  'id_status_pengambilan', 'Status Pengambilan'],
            ],
        );
    }

    /**
     * Menginjeksikan status alur kerja (workflow state) eksternal ke data tabel
     */
    #[\Override]
    protected function after_read(array &$data_tabel): void
    {
        foreach ($data_tabel as $index => $baris) {
            $idPengambilan = $baris['id_pengambilan_darah'];

            $data_tabel[$index]['sudah_dipisahkan'] = $this->model->apakahSudahDipisahkan($idPengambilan);
            
            $data_tabel[$index]['sudah_diuji'] = $this->model->apakahSudahDiuji($idPengambilan);
        }
    }

    /**
     * OVERRIDE: Menampilkan Form Pengambilan Darah & Penggunaan BHP
     */
    #[\Override]
    final public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $konfigPengambilan = $this->get_fields_with_options(false, true);

        $controllerKunjungan = new \App\Features\Donor\Kunjungan\KunjunganController();
        $controllerPendonor  = new \App\Features\Role\Pendonor\PendonorController();
        $controllerOrang     = new \App\Features\Person\Orang\OrangController();

        $konfigKunjungan = $controllerKunjungan->get_fields_with_options(false, true);
        $konfigPendonor  = $controllerPendonor->get_fields_with_options(false, true);
        $konfigOrang     = $controllerOrang->get_fields_with_options(false, true);

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
                    'kode_barang' => $row['kode_barang'],
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
                    'kode_barang' => $row['kode_barang'],
                    'nama_barang' => $row['nama_barang'],
                    'harga'       => $row['harga'],
                    'stok'        => $sisaStokNon
                ];
            }
        }

        $mockBaris = [];
        $konfigGabungan = [];

        foreach ($konfigPengambilan as $fieldPengambilan) {
            $columnPengambilan = $fieldPengambilan[2];

            if ($columnPengambilan === 'id_pengambilan_darah') {
                continue;
            }

            $isTanggal = ($fieldPengambilan[3] === 'tanggal' || str_contains($columnPengambilan, 'tanggal'));
            $mockBaris[$columnPengambilan] = $isTanggal ? date('Y-m-d') : '';

            if ($columnPengambilan === 'id_kunjungan') {
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

            $konfigGabungan[] = $fieldPengambilan;
        }

        return view('/admin/donor/tambah_pengambilandarah', [
            'judul'             => 'Tambah ' . $this->title,
            'breadcrumbs'       => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'        => $this->get_uri_path(),
            'kolom_id'          => $this->model->primaryKey,
            'konfig'            => $konfigGabungan,
            'baris'             => $mockBaris,
            'bhp_medis_options' => $masterBhpMedis,
            'bhp_non_options'   => $masterBhpNonMedis,
            'form_action'       => '/submittambah',
        ]);
    }
    
    /**
     * OVERRIDE: Memproses simpan data pengambilan darah & penggunaan BHP
     */
    #[\Override]
    final public function create(): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $bhpMedis      = $this->request->getPost('id_medis_donor');
        $hargaMedis    = $this->request->getPost('harga_medis');
        $bhpNonMedis   = $this->request->getPost('id_penunjang_donor');
        $hargaNonMedis = $this->request->getPost('harga_penunjang');

        $dataPengambilan = [];
        
        foreach ($this->fields as $field) {
            $namaKolom = $field[2]; 

            if (array_key_exists($namaKolom, $rawPost)) {
                $dataPengambilan[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        if (empty($this->request->getPost('id_status_pengambilan'))) {
            $dataPengambilan['id_status_pengambilan'] = null;
        }

        $this->model->db->transStart();

        try {
            if (!empty($dataPengambilan['tanggal_pengambilan'])) {
                $this->model->validasiTanggalOperasional($dataPengambilan['tanggal_pengambilan']);
            }

            $this->model->insert($dataPengambilan);
            $idPengambilan = $this->model->getInsertID();

            if (!empty($bhpMedis) && is_array($bhpMedis)) {
                $modelMedisDonor = new \App\Features\LogistikUTD\MedisDonor\MedisDonorModel();

                foreach ($bhpMedis as $idBarang => $jumlah) {
                    if ((int)$jumlah <= 0) continue;

                    $modelMedisDonor->insert([
                        'id_pengambilan_darah' => $idPengambilan, 
                        'id_barang'            => $idBarang,
                        'jumlah'               => (int)$jumlah,
                        'harga'                => (float)($hargaMedis[$idBarang] ?? 0),
                    ]);
                }
            }

            if (!empty($bhpNonMedis) && is_array($bhpNonMedis)) {
                $modelPenunjangDonor = new \App\Features\LogistikUTD\PenunjangDonor\PenunjangDonorModel();

                foreach ($bhpNonMedis as $idBarang => $jumlah) {
                    if ((int)$jumlah <= 0) continue;

                    $modelPenunjangDonor->insert([
                        'id_pengambilan_darah' => $idPengambilan,
                        'id_barang'            => $idBarang,
                        'jumlah'               => (int)$jumlah,
                        'harga'                => (float)($hargaNonMedis[$idBarang] ?? 0),
                    ]);
                }
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException("Gagal menyimpan data pengambilan darah dan BHP.");
            }

            session()->setFlashdata('success', 'Data pengambilan darah dan BHP berhasil disimpan.');
            return redirect()->to($this->get_uri_path() . '/data');

        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $this->friendly_db_error($e));
            return redirect()->to($this->get_uri_path() . '/data');

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->to($this->get_uri_path() . '/data');
        }
    }
    
    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Pengambilan Darah & Penggunaan BHP
     */
    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $dataPengambilan = $this->model->find($id);
        if (!$dataPengambilan) {
            $dataPengambilan = [];
        }

        $dataKunjungan = [];
        $dataPendonor  = [];
        $dataOrang     = [];
        $dataPetugasMedis = [];

        if (!empty($dataPengambilan['id_kunjungan'])) {
            $modelKunjungan = new \App\Features\Donor\Kunjungan\KunjunganModel();
            $dataKunjungan  = $modelKunjungan->find($dataPengambilan['id_kunjungan']) ?? [];

            if (!empty($dataKunjungan['id_pendonor'])) {
                $modelPendonor = new \App\Features\Role\Pendonor\PendonorModel();
                $dataPendonor  = $modelPendonor->find($dataKunjungan['id_pendonor']) ?? [];

                if (!empty($dataPendonor['id_orang'])) {
                    $modelOrang = new \App\Features\Person\Orang\OrangModel();
                    $dataOrang  = $modelOrang->find($dataPendonor['id_orang']) ?? [];
                }
            }
        }

        if (!empty($dataPengambilan['id_petugas'])) {
            $modelPetugas = new \App\Features\Role\Petugas\PetugasModel();
            $petugasRow   = $modelPetugas->find($dataPengambilan['id_petugas']) ?? [];

            if (!empty($petugasRow['id_orang'])) {
                $modelOrangPetugas = new \App\Features\Person\Orang\OrangModel();
                $orangPetugasRow   = $modelOrangPetugas->find($petugasRow['id_orang']) ?? [];
                
                if (isset($orangPetugasRow['nama'])) {
                    $dataPetugasMedis['nama_petugas'] = $orangPetugasRow['nama'];
                }
            }
        }

        $modelMedisDonor    = new \App\Features\LogistikUTD\MedisDonor\MedisDonorModel();
        $modelPenunjangDonor = new \App\Features\LogistikUTD\PenunjangDonor\PenunjangDonorModel();

        $savedBhpMedis = $modelMedisDonor->findAll();
        $savedBhpNonMedis = $modelPenunjangDonor->findAll();

        $modelMasterMedis = new \App\Features\InventoriMedis\DataBarang\DataBarangModel();
        foreach ($savedBhpMedis as $k => $v) {
            $masterItem = $modelMasterMedis->find($v['id_barang']);
            $savedBhpMedis[$k]['nama_barang'] = $masterItem['nama'] ?? 'Barang Medis';
        }

        $modelMasterPenunjang = new \App\Features\InventoriNonMedis\Barang\BarangModel();
        foreach ($savedBhpNonMedis as $k => $v) {
            $masterItem = $modelMasterPenunjang->find($v['id_barang']);
            $savedBhpNonMedis[$k]['nama_barang'] = $masterItem['nama_barang'] ?? 'Barang Penunjang';
        }

        $baris = array_merge($dataOrang, $dataPendonor, $dataKunjungan, $dataPetugasMedis, $dataPengambilan);

        $controllerKunjungan = new \App\Features\Donor\Kunjungan\KunjunganController();
        $controllerPendonor  = new \App\Features\Role\Pendonor\PendonorController();
        $controllerOrang     = new \App\Features\Person\Orang\OrangController();

        $konfigPengambilan = $this->get_fields_with_options(false, true);
        $konfigKunjungan   = $controllerKunjungan->get_fields_with_options(false, true);
        $konfigPendonor    = $controllerPendonor->get_fields_with_options(false, true);
        $konfigOrang       = $controllerOrang->get_fields_with_options(false, true);

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
                    'kode_barang' => $row['kode_barang'],
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
                    'kode_barang' => $row['kode_barang'],
                    'nama_barang' => $row['nama_barang'],
                    'harga'       => $row['harga'],
                    'stok'        => $sisaStokNon
                ];
            }
        }

        $konfigGabungan = [];

        foreach ($konfigPengambilan as $fieldPengambilan) {
            $columnPengambilan = $fieldPengambilan[2];

            if ($columnPengambilan === 'id_pengambilan_darah') {
                continue;
            }

            if ($columnPengambilan === 'id_kunjungan') {
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

            $konfigGabungan[] = $fieldPengambilan;
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

        return view('/admin/donor/tambah_pengambilandarah', [
            'judul'             => 'Ubah ' . $this->title,
            'breadcrumbs'       => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'        => $this->get_uri_path(),
            'kolom_id'          => $this->model->primaryKey,
            'konfig'            => $konfigGabungan,
            'baris'             => $baris,
            'bhp_medis_options' => $masterBhpMedis,
            'bhp_non_options'   => $masterBhpNonMedis,
            'saved_medis'       => $savedBhpMedis,
            'saved_non_medis'   => $savedBhpNonMedis,
            'form_action'       => '/submitedit/' . $id,
        ]);
    }

    /**
     * OVERRIDE: Mengeksekusi Simpan Perubahan Data Pengambilan Darah & Penggunaan BHP
     */
    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->index();

        $rawPost = $this->request->getPost();

        $bhpMedisUpdate      = $this->request->getPost('id_medis_donor');
        $hargaMedis          = $this->request->getPost('harga_medis');
        $bhpPenunjangUpdate  = $this->request->getPost('id_penunjang_donor');
        $hargaPenunjang      = $this->request->getPost('harga_penunjang');

        $dataPengambilan = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataPengambilan[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        if (empty($this->request->getPost('id_status_pengambilan'))) {
            $dataPengambilan['id_status_pengambilan'] = null;
        }

        $this->model->db->transStart();

        try {
            if (!empty($dataPengambilan['tanggal_pengambilan'])) {
                $this->model->validasiTanggalOperasional($dataPengambilan['tanggal_pengambilan']);
            }

            $this->model->update($id, $dataPengambilan);

            $modelMedisDonor = new \App\Features\LogistikUTD\MedisDonor\MedisDonorModel();

            $modelMedisDonor->where('id_pengambilan_darah', $id)->delete();

            if (!empty($bhpMedisUpdate) && is_array($bhpMedisUpdate)) {
                foreach ($bhpMedisUpdate as $idBarang => $jumlah) {
                    if ((int)$jumlah <= 0) continue;

                    $modelMedisDonor->insert([
                        'id_pengambilan_darah' => $id,
                        'id_barang'            => $idBarang,
                        'jumlah'               => (int)$jumlah,
                        'harga'                => (float)($hargaMedis[$idBarang] ?? 0),
                    ]);
                }
            }

            $modelPenunjangDonor = new \App\Features\LogistikUTD\PenunjangDonor\PenunjangDonorModel();

            $modelPenunjangDonor->where('id_pengambilan_darah', $id)->delete();

            if (!empty($bhpPenunjangUpdate) && is_array($bhpPenunjangUpdate)) {
                foreach ($bhpPenunjangUpdate as $idBarang => $jumlah) {
                    if ((int)$jumlah <= 0) continue;

                    $modelPenunjangDonor->insert([
                        'id_pengambilan_darah' => $id,
                        'id_barang'            => $idBarang,
                        'jumlah'               => (int)$jumlah,
                        'harga'                => (float)($hargaPenunjang[$idBarang] ?? 0),
                    ]);
                }
            }

            $idStatus = (int)($dataPengambilan['id_status_pengambilan'] ?? 0);
            $this->model->syncTanggalDonorTerakhir($idStatus, $dataPengambilan, $id);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException("Gagal memperbarui data pengambilan darah dan logistik BHP.");
            }

            session()->setFlashdata('success', 'Data pengambilan darah dan penggunaan BHP berhasil diperbarui.');

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
     * OVERRIDE: Menghapus data pengambilan darah & penggunaan BHP
     */
    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();
        
        $dataPengambilan = $this->model->find($id);
        if (!$dataPengambilan) {
            session()->setFlashdata('error', 'Gagal menghapus. Data pengambilan darah tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $this->model->db->transStart();

        try {
            $modelMedisDonor    = new \App\Features\LogistikUTD\MedisDonor\MedisDonorModel();
            $modelPenunjangDonor = new \App\Features\LogistikUTD\PenunjangDonor\PenunjangDonorModel();

            $modelMedisDonor->where('id_pengambilan_darah', $id)->delete();
            $modelPenunjangDonor->where('id_pengambilan_darah', $id)->delete();

            $this->model->syncTanggalDonorTerakhir(0, $dataPengambilan, $id);

            $this->model->delete($id);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \CodeIgniter\Database\Exceptions\DatabaseException('violates foreign key constraint');
            }

            session()->setFlashdata('success', 'Data pengambilan darah dan penggunaan BHP berhasil dihapus.');

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
     * Menampilkan Halaman Detail Pengambilan Darah & Penggunaan BHP
     */
    public function detail(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $dataPengambilan = $this->model->find($id);

        $dataKunjungan = [];
        $dataPendonor  = [];
        $dataOrang     = [];
        $dataPetugasMedis = [];

        if (!empty($dataPengambilan['id_kunjungan'])) {
            $modelKunjungan = new \App\Features\Donor\Kunjungan\KunjunganModel();
            $dataKunjungan  = $modelKunjungan->find($dataPengambilan['id_kunjungan']) ?? [];

            if (!empty($dataKunjungan['id_pendonor'])) {
                $modelPendonor = new \App\Features\Role\Pendonor\PendonorModel();
                $dataPendonor  = $modelPendonor->find($dataKunjungan['id_pendonor']) ?? [];

                if (!empty($dataPendonor['id_orang'])) {
                    $modelOrang = new \App\Features\Person\Orang\OrangModel();
                    $dataOrang  = $modelOrang->find($dataPendonor['id_orang']) ?? [];
                }
            }
        }

        if (!empty($dataPengambilan['id_petugas'])) {
            $modelPetugas = new \App\Features\Role\Petugas\PetugasModel();
            $petugasRow   = $modelPetugas->find($dataPengambilan['id_petugas']) ?? [];

            if (!empty($petugasRow['id_orang'])) {
                $modelOrangPetugas = new \App\Features\Person\Orang\OrangModel();
                $orangPetugasRow   = $modelOrangPetugas->find($petugasRow['id_orang']) ?? [];
                
                if (isset($orangPetugasRow['nama'])) {
                    $dataPetugasMedis['nama_petugas'] = $orangPetugasRow['nama'];
                }
            }
        }

        $bhpMedis = $this->model->getBhpMedisDetail($id);
        $bhpPenunjang = $this->model->getBhpPenunjangDetail($id);

        $baris = array_merge($dataOrang, $dataPendonor, $dataKunjungan, $dataPetugasMedis, $dataPengambilan);

        $optionsFields = $this->get_fields_with_options(false, true);
        foreach ($optionsFields as $field) {
            $colName = $field[2];
            $options = $field[5] ?? [];
            
            if (!empty($options) && isset($baris[$colName])) {
                $idMentah = $baris[$colName];
                
                foreach ($options as $opt) {
                    if ((string)$opt[1] === (string)$idMentah) {
                        $baris[$colName] = $opt[0];
                        break;
                    }
                }
            }
        }

        $breadcrumbs = [
            ['title' => 'Detail', 'icon' => 'detail']
        ];

        return view('/admin/donor/detail_pengambilandarah', [
            'judul'          => 'Detail ' . $this->title,
            'breadcrumbs'    => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'     => $this->get_uri_path(),
            'baris'          => $baris,
            'bhp_medis'      => $bhpMedis,
            'bhp_penunjang'  => $bhpPenunjang,
        ]);
    }
}
