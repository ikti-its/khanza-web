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
                ['Inventaris Darah',   'inventaris_darah'],
                ['Pemisahan Komponen', 'pemisahan_komponen'],
            ],
            'Pemisahan Komponen',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                // A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_pemisahan',         'ID Pemisahan'],
                [SHOW, REQUIRED, I::INDEX, 'id_pengambilan_darah', 'ID Pengambilan Darah'],
                [SHOW, REQUIRED, I::DATE,  'tanggal_pemisahan',    'Tanggal Pemisahan'],
                [SHOW, REQUIRED, I::SELECT,'id_shift',             'Shift'],
                [SHOW, REQUIRED, I::INDEX, 'id_petugas',           'Petugas'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Pemisahan Komponen & Penggunaan BHP
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $fieldsWithOptions = $this->get_fields_with_options(false, true);

        $controllerPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahController();
        $konfigPengambilan     = $controllerPengambilan->get_fields_with_options(false, true);

        $modelKomponen = new \App\Features\Darah\KomponenDarah\KomponenDarahModel(); 
        $masterKomponen = $modelKomponen->findAll();

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
                    'nama_barang' => $row['nama_barang'],
                    'harga'       => $row['harga'],
                    'stok'        => $sisaStokNon
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

        $komponenTerpilih  = $this->request->getPost('id_komponen');
        $nomorPengambilan  = $this->request->getPost('nomor_pengambilan');
        
        $bhpMedis          = $this->request->getPost('id_medis_donor');
        $hargaMedis        = $this->request->getPost('harga_medis');
        $bhpNonMedis       = $this->request->getPost('id_penunjang_donor');
        $hargaNonMedis     = $this->request->getPost('harga_penunjang');

        $dataPemisahan = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataPemisahan[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        $this->model->db->transStart();

        try {
            $this->model->insert($dataPemisahan);
            $idPemisahan = $this->model->getInsertID();

            if (!empty($komponenTerpilih) && is_array($komponenTerpilih)) {
                $modelKompDetail   = new \App\Features\InventoriDarah\PemisahanKomponenDetail\PemisahanKomponenDetailModel(); 
                $modelMasterKomp   = new \App\Features\Darah\KomponenDarah\KomponenDarahModel();

                $idPengambilan      = $dataPemisahan['id_pengambilan_darah'];
                $modelPengambilan   = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
                $dataPengambilan    = $modelPengambilan->find($idPengambilan);

                $tanggalPengambilan = $dataPengambilan['tanggal_pengambilan'] ?? date('Y-m-d');

                foreach ($komponenTerpilih as $idKomponen) {
                    $masterKomp = $modelMasterKomp->find($idKomponen);
                    if (!$masterKomp) continue;
                    
                    $lamaHari   = (int)($masterKomp['masa_berlaku_hari']);
                    $tanggalKadaluarsa = date('Y-m-d', strtotime($tanggalPengambilan . ' + ' . $lamaHari . ' days'));

                    $modelKompDetail->insert([
                        'id_pemisahan'       => $idPemisahan,
                        'no_kantong'         => $masterKomp['kode_komponen'] . $nomorPengambilan,
                        'id_komponen'        => $idKomponen,
                        'tanggal_kadaluarsa' => $tanggalKadaluarsa,
                    ]);
                }
            }

            if (!empty($bhpMedis) && is_array($bhpMedis)) {
                $modelMedisPemisahan = new \App\Features\LogistikUTD\MedisPemisahan\MedisPemisahanModel();

                foreach ($bhpMedis as $idBarang => $jumlah) {
                    if ((int)$jumlah <= 0) continue;

                    $modelMedisPemisahan->insert([
                        'id_pemisahan' => $idPemisahan,
                        'id_barang'    => $idBarang,
                        'jumlah'       => (int)$jumlah,
                        'harga'        => (float)($hargaMedis[$idBarang] ?? 0),
                    ]);
                }
            }

            if (!empty($bhpNonMedis) && is_array($bhpNonMedis)) {
                $modelPenunjangPemisahan = new \App\Features\LogistikUTD\PenunjangPemisahan\PenunjangPemisahanModel();

                foreach ($bhpNonMedis as $idBarang => $jumlah) {
                    if ((int)$jumlah <= 0) continue;

                    $modelPenunjangPemisahan->insert([
                        'id_pemisahan' => $idPemisahan,
                        'id_barang'    => $idBarang,
                        'jumlah'       => (int)$jumlah,
                        'harga'        => (float)($hargaNonMedis[$idBarang] ?? 0),
                    ]);
                }
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException("Gagal menyimpan data pemisahan komponen dan penggunaan BHP.");
            }

            session()->setFlashdata('success', 'Data pemisahan komponen dan penggunaan BHP berhasil disimpan.');

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = ($e instanceof \CodeIgniter\Database\Exceptions\DatabaseException) 
                ? $this->friendly_db_error($e) 
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }
}
