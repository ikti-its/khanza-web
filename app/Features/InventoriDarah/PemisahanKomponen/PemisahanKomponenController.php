<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\PemisahanKomponen;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PemisahanKomponenController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PemisahanKomponenModel(),
            [
                ['Inventori Darah',    'inventori_darah'],
                ['Pemisahan Komponen', 'pemisahan_komponen'],
            ],
            'Pemisahan Komponen',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                A::DELETE,
                A::DETAIL,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_pemisahan',         'ID Pemisahan'],
                [SHOW, REQUIRED, I::INDEX,  'id_pengambilan_darah', 'ID Pengambilan Darah'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_pemisahan',    'Tanggal Pemisahan'],
                [SHOW, REQUIRED, I::SELECT, 'id_shift',             'Shift'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas',           'Petugas'],
            ],
        );
    }

    /**
     * OVERRIDE: Halaman Utama Pemisahan Komponen
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
            [1, 'No. Pengambilan',   'nomor_pengambilan', 'teks',    0],
            [1, 'Nomor Bag',         'no_bag',            'teks',    0],
            [1, 'Tanggal Pemisahan', 'tanggal_pemisahan', 'tanggal', 0],
            [1, 'Shift',             'nama_shift',        'teks',    0],
            [1, 'Petugas',           'nama_petugas',      'teks',    0],
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
     * OVERRIDE: Menampilkan Form Pemisahan Komponen & Penggunaan BHP
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah'],
        ];

        $fieldsWithOptions = $this->get_fields_with_options(false, true);

        $controllerPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahController();
        $konfigPengambilan     = $controllerPengambilan->get_fields_with_options(false, true);

        $modelKomponen  = new \App\Features\InventoriDarah\KomponenDarah\KomponenDarahModel();
        $masterKomponen = $modelKomponen->findAll();

        $modelBhpMedis    = new \App\Features\LogistikUTD\PengambilanMedis\PengambilanMedisModel();
        $modelBhpNonMedis = new \App\Features\LogistikUTD\PengambilanPenunjang\PengambilanPenunjangModel();

        $rawMedis     = $modelBhpMedis->get_katalog_dan_stok_ruangan();
        $rawPenunjang = $modelBhpNonMedis->get_katalog_dan_stok_ruangan();

        $masterBhpMedis = [];
        foreach ($rawMedis as $row) {
            $sisaStok =
                (int) $row['total_masuk'] - (int) $row['total_terpakai_donor'] - (int) $row['total_terpakai_pemisahan']
                    - (int) $row['total_terpakai_penyerahan']
                - (int) $row['total_rusak'];

            if ((int) $row['total_masuk'] > 0) {
                $masterBhpMedis[] = [
                    'id_barang'   => $row['id_barang'],
                    'kode_barang' => $row['kode_barang'],
                    'nama_barang' => $row['nama_barang'],
                    'harga'       => $row['harga'],
                    'stok'        => $sisaStok,
                ];
            }
        }

        $masterBhpNonMedis = [];
        foreach ($rawPenunjang as $row) {
            $sisaStokNon =
                (int) $row['total_masuk'] - (int) $row['total_terpakai_donor'] - (int) $row['total_terpakai_pemisahan']
                    - (int) $row['total_terpakai_penyerahan']
                - (int) $row['total_rusak'];

            if ((int) $row['total_masuk'] > 0) {
                $masterBhpNonMedis[] = [
                    'id_barang'   => $row['id_barang'],
                    'kode_barang' => $row['kode_barang'],
                    'nama_barang' => $row['nama_barang'],
                    'harga'       => $row['harga'],
                    'stok'        => $sisaStokNon,
                ];
            }
        }

        $mockBaris      = [];
        $konfigGabungan = [];

        foreach ($fieldsWithOptions as $field) {
            $namaKolom = $field[2];

            if ($namaKolom === 'id_pemisahan') {
                continue;
            }

            $isTanggal             = $field[3] === 'tanggal' || str_contains($namaKolom, 'tanggal');
            $mockBaris[$namaKolom] = $isTanggal ? date('Y-m-d') : '';

            if ($namaKolom === 'id_pengambilan_darah') {
                foreach ($konfigPengambilan as $fPengambilan) {
                    if ($fPengambilan[2] === 'nomor_pengambilan') {
                        $mockBaris['nomor_pengambilan'] = '';
                        $konfigGabungan[]               = $fPengambilan;
                        break;
                    }
                }
                continue;
            }

            $konfigGabungan[] = $field;
        }

        $id_pengambilan = $this->request->getGet('pengambilan');
        $batasKomponen  = ['nama_jenis_bag' => '-', 'batas' => null];

        if ($id_pengambilan !== null && is_numeric($id_pengambilan)) {
            $modelPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
            $dataPengambilan  = $modelPengambilan->find($id_pengambilan);

            if ($dataPengambilan) {
                $mockBaris['id_pengambilan_darah'] = $dataPengambilan['id_pengambilan_darah'];
                $mockBaris['nomor_pengambilan']    = $dataPengambilan['nomor_pengambilan'];
                $batasKomponen                     = $this->model->getBatasKomponen($id_pengambilan);
            }
        }

        return view('admin/inventoridarah/tambah_pemisahankomponen', [
            'judul'             => 'Tambah ' . $this->title,
            'breadcrumbs'       => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'        => $this->get_uri_path(),
            'kolom_id'          => $this->model->primaryKey,
            'konfig'            => $konfigGabungan,
            'baris'             => $mockBaris,
            'master_komponen'   => $masterKomponen,
            'bhp_medis_options' => $masterBhpMedis,
            'bhp_non_options'   => $masterBhpNonMedis,
            'batas_komponen'    => $batasKomponen,
            'form_action'       => '/submittambah',
        ]);
    }

    /**
     * OVERRIDE: Memproses simpan data pemisahan komponen & penggunaan BHP
     */
    #[\Override]
    final public function create(): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $komponenTerpilih = $this->request->getPost('id_komponen');
        $nomorPengambilan = $this->request->getPost('nomor_pengambilan');

        $bhpMedis      = $this->request->getPost('id_medis_donor');
        $hargaMedis    = $this->request->getPost('harga_medis');
        $bhpNonMedis   = $this->request->getPost('id_penunjang_donor');
        $hargaNonMedis = $this->request->getPost('harga_penunjang');

        $dataPemisahan = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataPemisahan[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        $this->model->db->transStart();

        try {
            $this->model->validasiTanggalPemisahan($rawPost['id_pengambilan_darah'], $rawPost['tanggal_pemisahan']);
            $this->model->validasiJumlahKomponen(
                $rawPost['id_pengambilan_darah'],
                is_array($komponenTerpilih) ? $komponenTerpilih : [],
            );

            $this->model->insert($dataPemisahan);
            $idPemisahan = $this->model->getInsertID();

            if (!empty($komponenTerpilih) && is_array($komponenTerpilih)) {
                $modelKompDetail = new \App\Features\InventoriDarah\PemisahanKomponenDetail\PemisahanKomponenDetailModel();
                $modelMasterKomp = new \App\Features\InventoriDarah\KomponenDarah\KomponenDarahModel();

                $idPengambilan    = $dataPemisahan['id_pengambilan_darah'];
                $modelPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
                $dataPengambilan  = $modelPengambilan->find($idPengambilan);

                $tanggalPengambilan = $dataPengambilan['tanggal_pengambilan'] ?? date('Y-m-d');

                foreach ($komponenTerpilih as $idKomponen) {
                    $masterKomp = $modelMasterKomp->find($idKomponen);
                    if (!$masterKomp)
                        continue;

                    $lamaHari          = (int) $masterKomp['masa_berlaku_hari'];
                    $tanggalKadaluarsa = date('Y-m-d', strtotime($tanggalPengambilan . ' + ' . $lamaHari . ' days'));

                    $kodeKomponen = $masterKomp['kode_komponen'];
                    $noKantong    = $kodeKomponen . $nomorPengambilan;

                    $modelKompDetail->insert([
                        'id_pemisahan'       => $idPemisahan,
                        'no_kantong'         => $noKantong,
                        'id_komponen'        => $idKomponen,
                        'tanggal_kadaluarsa' => $tanggalKadaluarsa,
                    ]);

                    $this->stok_karantina(
                        $noKantong,
                        $idKomponen,
                        $tanggalPengambilan,
                        $tanggalKadaluarsa,
                        $dataPengambilan,
                    );
                }
            }

            if (!empty($bhpMedis) && is_array($bhpMedis)) {
                $modelMedisPemisahan = new \App\Features\LogistikUTD\MedisPemisahan\MedisPemisahanModel();

                foreach ($bhpMedis as $idBarang => $jumlah) {
                    if ((int) $jumlah <= 0)
                        continue;

                    $modelMedisPemisahan->insert([
                        'id_pemisahan' => $idPemisahan,
                        'id_barang'    => $idBarang,
                        'jumlah'       => (int) $jumlah,
                        'harga'        => (float) ($hargaMedis[$idBarang] ?? 0),
                    ]);
                }
            }

            if (!empty($bhpNonMedis) && is_array($bhpNonMedis)) {
                $modelPenunjangPemisahan = new \App\Features\LogistikUTD\PenunjangPemisahan\PenunjangPemisahanModel();

                foreach ($bhpNonMedis as $idBarang => $jumlah) {
                    if ((int) $jumlah <= 0)
                        continue;

                    $modelPenunjangPemisahan->insert([
                        'id_pemisahan' => $idPemisahan,
                        'id_barang'    => $idBarang,
                        'jumlah'       => (int) $jumlah,
                        'harga'        => (float) ($hargaNonMedis[$idBarang] ?? 0),
                    ]);
                }
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan data pemisahan komponen dan penggunaan BHP.');
            }

            session()->setFlashdata('success', 'Data pemisahan komponen dan penggunaan BHP berhasil disimpan.');
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
     * OVERRIDE: Menghapus data pemisahan komponen & penggunaan BHP
     */
    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->home();

        $dataPemisahan = $this->model->find($id);
        if (!$dataPemisahan) {
            session()->setFlashdata('error', 'Gagal menghapus. Data pemisahan komponen tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idPengambilanDarah = $dataPemisahan['id_pengambilan_darah'] ?? null;
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
            $modelMedisPemisahan     = new \App\Features\LogistikUTD\MedisPemisahan\MedisPemisahanModel();
            $modelPenunjangPemisahan = new \App\Features\LogistikUTD\PenunjangPemisahan\PenunjangPemisahanModel();
            $modelKompDetail         = new \App\Features\InventoriDarah\PemisahanKomponenDetail\PemisahanKomponenDetailModel();
            $modelStokDarah          = new \App\Features\InventoriDarah\StokDarah\StokDarahModel();

            $modelMedisPemisahan->where('id_pemisahan', $id)->delete();
            $modelPenunjangPemisahan->where('id_pemisahan', $id)->delete();

            $modelKompDetail->where('id_pemisahan', $id)->delete();

            if (!empty($nomorPengambilan)) {
                $modelStokDarah->like('no_kantong', $nomorPengambilan, 'before')->delete();
            }

            $this->model->delete($id);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus data pemisahan komponen dan penggunaan BHP.');
            }

            session()->setFlashdata('success', 'Data pemisahan komponen dan penggunaan BHP berhasil dihapus.');
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
     * HELPER: Penyimpanan stok darah dengan status karantina
     */
    private function stok_karantina(
        string $noKantong,
        $idKomponen,
        string $tanggalPengambilan,
        string $tanggalKadaluarsa,
        null|array $dataPengambilan,
    ): void {
        $modelStokDarah = new \App\Features\InventoriDarah\StokDarah\StokDarahModel();

        $idGolonganDarah = null;
        $idRhesus        = null;
        $idSumberDarah   = 1;
        $idStatusStok    = 1;

        if (!empty($dataPengambilan['id_kunjungan'])) {
            $modelKunjungan = new \App\Features\Donor\Kunjungan\KunjunganModel();
            $dataKunjungan  = $modelKunjungan->find($dataPengambilan['id_kunjungan']);

            if (!empty($dataKunjungan['id_pendonor'])) {
                $modelPendonor = new \App\Features\Role\Pendonor\PendonorModel();
                $dataPendonor  = $modelPendonor->find($dataKunjungan['id_pendonor']);

                if (!empty($dataPendonor)) {
                    $idRhesus = $dataPendonor['id_rhesus'] ?? null;

                    if (!empty($dataPendonor['id_orang'])) {
                        $modelOrang = new \App\Features\Person\Orang\OrangModel();
                        $dataOrang  = $modelOrang->find($dataPendonor['id_orang']);

                        if (!empty($dataOrang)) {
                            $idGolonganDarah = $dataOrang['id_golongan_darah'] ?? null;
                        }
                    }
                }
            }
        }

        $modelStokDarah->insert([
            'no_kantong'          => $noKantong,
            'id_komponen'         => $idKomponen,
            'id_golongan_darah'   => $idGolonganDarah,
            'id_rhesus'           => $idRhesus,
            'tanggal_pengambilan' => $tanggalPengambilan,
            'tanggal_kadaluarsa'  => $tanggalKadaluarsa,
            'id_sumber_darah'     => $idSumberDarah,
            'id_status_stok'      => $idStatusStok,
        ]);
    }

    /**
     * Menampilkan Halaman Detail Pemisahan Komponen
     */
    final public function detail(int|string $id): string
    {
        if ($id == 0)
            return $this->index();

        $dataPemisahan = $this->model->find($id);
        if (!$dataPemisahan) {
            $dataPemisahan = [];
        }

        $dataPengambilan = [];
        $dataPetugas     = [];

        if (!empty($dataPemisahan['id_pengambilan_darah'])) {
            $modelPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
            $rawPengambilan   = $modelPengambilan->find($dataPemisahan['id_pengambilan_darah']) ?? [];

            $dataPengambilan = [
                'nomor_pengambilan' => $rawPengambilan['nomor_pengambilan'] ?? '',
                'no_bag'            => $rawPengambilan['no_bag'] ?? '',
            ];
        }

        if (!empty($dataPemisahan['id_petugas'])) {
            $modelPetugas = new \App\Features\Role\Petugas\PetugasModel();
            $petugasRow   = $modelPetugas->find($dataPemisahan['id_petugas']) ?? [];

            if (!empty($petugasRow['id_orang'])) {
                $modelOrangPetugas = new \App\Features\Person\Orang\OrangModel();
                $orangPetugasRow   = $modelOrangPetugas->find($petugasRow['id_orang']) ?? [];

                if (isset($orangPetugasRow['nama'])) {
                    $dataPetugas['nama_petugas'] = $orangPetugasRow['nama'];
                }
            }
        }

        $komponenTerpilih = $this->model->getHasilPemisahan($id);

        $bhpMedis     = $this->model->getBhpMedisDetail($id);
        $bhpPenunjang = $this->model->getBhpPenunjangDetail($id);

        $baris = array_merge($dataPengambilan, $dataPemisahan, $dataPetugas);

        $konfigPemisahan = $this->get_fields_with_options(false, true);

        foreach ($konfigPemisahan as $field) {
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

        return view('/admin/inventoridarah/detail_pemisahankomponen', [
            'judul'             => 'Detail ' . $this->title,
            'breadcrumbs'       => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'        => $this->get_uri_path(),
            'baris'             => $baris,
            'komponen_terpilih' => $komponenTerpilih,
            'bhp_medis'         => $bhpMedis,
            'bhp_penunjang'     => $bhpPenunjang,
        ]);
    }
}
