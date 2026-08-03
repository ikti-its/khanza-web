<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PenyerahanDarah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PenyerahanDarahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenyerahanDarahModel(),
            [
                ['Pelayanan Darah',  'pelayanan_darah'],
                ['Penyerahan Darah', 'penyerahan_darah'],
            ],
            'Penyerahan Darah',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                A::DELETE,
                A::DETAIL,
                A::PAY,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_penyerahan',        'ID Penyerahan'],
                [SHOW, REQUIRED, I::TEXT,   'no_penyerahan',        'Nomor Penyerahan'],
                [SHOW, REQUIRED, I::INDEX,  'id_permintaan',        'ID Permintaan'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_penyerahan',   'Tanggal Penyerahan'],
                [SHOW, REQUIRED, I::SELECT, 'id_shift',             'Shift'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas_cross',     'ID Petugas Crossmatch'],
                [SHOW, REQUIRED, I::TEXT,   'keterangan',           'Keterangan'],
                [SHOW, REQUIRED, I::SELECT, 'id_rekening',          'Rekening'],
                [SHOW, REQUIRED, I::NAME,   'pengambil_darah',      'Pengambil Darah'],
                [SHOW, REQUIRED, I::TEXT,   'alamat_pengambil',     'Alamat Pengambil'],
                [SHOW, REQUIRED, I::INDEX,  'id_penanggung_jawab',  'ID Penanggung Jawab'],
                [SHOW, REQUIRED, I::FLOAT,  'besar_ppn',            'PPN (%)'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_pembayaran', 'Status Pembayaran'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Halaman Utama Penyerahan Darah
     */
    #[\Override]
    public function index(): string
    {
        $currentPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage     = 10;
        $offset      = ($currentPage - 1) * $perPage;

        $totalRows  = $this->model->count_filtered();
        $data_tabel = $this->model->get_data_tabel($perPage, $offset);

        $konfig = [
            [1, 'No. Penyerahan',     'no_penyerahan',          'teks',        0],
            [1, 'No. Permintaan',     'no_permintaan',          'teks',        0],
            [1, 'Tanggal Penyerahan', 'tanggal_penyerahan',     'tanggal_jam', 0],
            [1, 'Pengambil Darah',    'pengambil_darah',        'teks',        0],
            [1, 'Status Pembayaran',  'nama_status_pembayaran', 'status',      0],
        ];

        return view('/layouts/data', [
            'judul'         => $this->title,
            'breadcrumbs'   => $this->breadcrumbs,
            'meta_data'     => [
                'page'  => $currentPage,
                'size'  => count($data_tabel),
                'total' => ceil($totalRows / $perPage),
            ],
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
     * OVERRIDE: Menampilkan Form Penyerahan Darah
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah'],
        ];

        $konfigPenyerahan = $this->get_fields_with_options(false, true);

        $controllerPermintaan = new \App\Features\PelayananDarah\PermintaanDarah\PermintaanDarahController();
        $konfigPermintaan     = $controllerPermintaan->fields;

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

        $tahunSekarang = date('Y');
        $bulanSekarang = date('m');

        $prefiksPenyerahan = "{$tahunSekarang}-{$bulanSekarang}-PD";

        $nomorTerakhir = $this->model
            ->db
            ->table('pelayanan_darah.penyerahan_darah')
            ->select('no_penyerahan')
            ->like('no_penyerahan', $prefiksPenyerahan, 'after')
            ->orderBy('no_penyerahan', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        $nextUrutan = $nomorTerakhir
            ? ((int) substr($nomorTerakhir['no_penyerahan'], strlen($prefiksPenyerahan)) + 1)
            : 1;

        $stringUrutan = str_pad((string) $nextUrutan, 5, '0', STR_PAD_LEFT);

        $nomorPenyerahanOtomatis = "{$prefiksPenyerahan}{$stringUrutan}";

        foreach ($konfigPenyerahan as $fieldPenyerahan) {
            $columnPenyerahan = $fieldPenyerahan[2];

            if ($columnPenyerahan === 'id_penyerahan') {
                continue;
            }

            $isTanggal =
                $fieldPenyerahan[3] === 'tanggal'
                || str_contains($columnPenyerahan, 'tanggal')
                || $fieldPenyerahan[3] === 'dtime';
            $mockBaris[$columnPenyerahan] = $isTanggal ? date('Y-m-d\TH:i') : '';

            if ($columnPenyerahan === 'besar_ppn') {
                $mockBaris[$columnPenyerahan] = '11.00';
            }

            if ($columnPenyerahan === 'no_penyerahan') {
                $mockBaris[$columnPenyerahan] = $nomorPenyerahanOtomatis;
                $fieldPenyerahan[3]           = 'indeks';
            }

            if ($columnPenyerahan === 'id_permintaan') {
                foreach ($konfigPermintaan as $fieldPermintaan) {
                    if ($fieldPermintaan[2] === 'no_permintaan') {
                        $mockBaris['no_permintaan'] = '';
                        $konfigGabungan[]           = $fieldPermintaan;
                        break;
                    }
                }
                continue;
            }

            $konfigGabungan[] = $fieldPenyerahan;
        }

        return view('admin/pelayanandarah/tambah_penyerahandarah', [
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
     * OVERRIDE: Memproses simpan data penyerahan darah & penggunaan BHP
     */
    #[\Override]
    final public function create(): string|RedirectResponse
    {
        $rawPost           = $this->request->getPost();
        $idPermintaan      = $rawPost['id_permintaan'] ?? null;
        $stokDarahTerpilih = $this->request->getPost('id_stok_darah');

        $bhpMedis      = $this->request->getPost('id_medis_donor');
        $hargaMedis    = $this->request->getPost('harga_medis');
        $bhpNonMedis   = $this->request->getPost('id_penunjang_donor');
        $hargaNonMedis = $this->request->getPost('harga_penunjang');

        $dataPenyerahan = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataPenyerahan[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        $this->model->db->transStart();

        try {
            if (empty($idPermintaan)) {
                throw new \RuntimeException('Gagal menyimpan! Data permintaan darah tidak terdeteksi.');
            }

            $this->model->validasiDanHitungKuota((int) $idPermintaan, $stokDarahTerpilih);

            $this->model->insert($dataPenyerahan);
            $idPenyerahan = $this->model->getInsertID();

            if (!empty($stokDarahTerpilih) && is_array($stokDarahTerpilih)) {
                $modelDetail    = new \App\Features\PelayananDarah\PenyerahanDarahDetail\PenyerahanDarahDetailModel();
                $modelStokDarah = new \App\Features\InventoriDarah\StokDarah\StokDarahModel();
                $modelKomponen  = new \App\Features\InventoriDarah\KomponenDarah\KomponenDarahModel();

                foreach ($stokDarahTerpilih as $idStokDarah) {
                    if (empty($idStokDarah))
                        continue;

                    $stok = $modelStokDarah->find($idStokDarah);
                    if (!$stok)
                        continue;

                    $masterKomp = $modelKomponen->find($stok['id_komponen']);

                    $jasaSarana = (float) ($masterKomp['jasa_sarana'] ?? 0);
                    $paketBhp   = (float) ($masterKomp['paket_bhp'] ?? 0);
                    $kso        = (float) ($masterKomp['kso'] ?? 0);
                    $manajemen  = (float) ($masterKomp['manajemen'] ?? 0);

                    $modelDetail->insert([
                        'id_penyerahan' => $idPenyerahan,
                        'id_stok_darah' => $idStokDarah,
                        'jasa_sarana'   => $jasaSarana,
                        'paket_bhp'     => $paketBhp,
                        'kso'           => $kso,
                        'manajemen'     => $manajemen,
                    ]);

                    $modelStokDarah->update($idStokDarah, [
                        'id_status_stok' => 4,
                    ]);
                }
            }

            if (!empty($bhpMedis) && is_array($bhpMedis)) {
                $modelMedisPenyerahan = new \App\Features\LogistikUTD\MedisPenyerahan\MedisPenyerahanModel(); // Sesuai penamaan skema timmu

                foreach ($bhpMedis as $idBarang => $jumlah) {
                    if ((int) $jumlah <= 0)
                        continue;

                    $modelMedisPenyerahan->insert([
                        'id_penyerahan' => $idPenyerahan,
                        'id_barang'     => $idBarang,
                        'jumlah'        => (int) $jumlah,
                        'harga'         => (float) ($hargaMedis[$idBarang] ?? 0),
                    ]);
                }
            }

            if (!empty($bhpNonMedis) && is_array($bhpNonMedis)) {
                $modelPenunjangPenyerahan = new \App\Features\LogistikUTD\PenunjangPenyerahan\PenunjangPenyerahanModel(); // Sesuai penamaan skema timmu

                foreach ($bhpNonMedis as $idBarang => $jumlah) {
                    if ((int) $jumlah <= 0)
                        continue;

                    $modelPenunjangPenyerahan->insert([
                        'id_penyerahan' => $idPenyerahan,
                        'id_barang'     => $idBarang,
                        'jumlah'        => (int) $jumlah,
                        'harga'         => (float) ($hargaNonMedis[$idBarang] ?? 0),
                    ]);
                }
            }

            $this->model->sinkronisasiStatusPermintaan((int) $idPermintaan);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal menyimpan data penyerahan darah dan penggunaan BHP.');
            }

            session()->setFlashdata('success', 'Data penyerahan darah dan penggunaan BHP berhasil disimpan.');
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
     * OVERRIDE: Menghapus data penyerahan darah & penggunaan BHP
     */
    #[\Override]
    final public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->home();

        $dataPenyerahan = $this->model->find($id);
        if (!$dataPenyerahan) {
            session()->setFlashdata('error', 'Gagal menghapus. Data penyerahan darah tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $this->model->db->transStart();

        try {
            $modelDetail              = new \App\Features\PelayananDarah\PenyerahanDarahDetail\PenyerahanDarahDetailModel();
            $modelStokDarah           = new \App\Features\InventoriDarah\StokDarah\StokDarahModel();
            $modelMedisPenyerahan     = new \App\Features\LogistikUTD\MedisPenyerahan\MedisPenyerahanModel();
            $modelPenunjangPenyerahan = new \App\Features\LogistikUTD\PenunjangPenyerahan\PenunjangPenyerahanModel();

            $daftarDetailTersimpan = $modelDetail
                ->db
                ->table($modelDetail->table)
                ->where('id_penyerahan', $id)
                ->get()
                ->getResultArray();

            if (!empty($daftarDetailTersimpan) && is_array($daftarDetailTersimpan)) {
                foreach ($daftarDetailTersimpan as $detail) {
                    $modelStokDarah
                        ->builder()
                        ->where($modelStokDarah->primaryKey, $detail['id_stok_darah'])
                        ->update(['id_status_stok' => 2]);
                }
            }

            $modelMedisPenyerahan->where('id_penyerahan', $id)->delete();
            $modelPenunjangPenyerahan->where('id_penyerahan', $id)->delete();

            $modelDetail->where('id_penyerahan', $id)->delete();

            $idPermintaanAsal = (int) $dataPenyerahan['id_permintaan'];

            $this->model->delete($id);

            $this->model->sinkronisasiStatusPermintaan($idPermintaanAsal);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal menghapus data penyerahan darah dan penggunaan BHP.');
            }

            session()->setFlashdata('success', 'Data penyerahan darah dan penggunaan BHP berhasil dihapus.');
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
     * Menampilkan Halaman Detail Penyerahan Darah & Penggunaan BHP
     */
    public function detail(int|string $id): string
    {
        if ($id == 0)
            return $this->index();

        $dataPenyerahan = $this->model->find($id);
        if (!$dataPenyerahan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Data Penyerahan Darah tidak ditemukan.',
            );
        }

        $baris            = $dataPenyerahan;
        $dataPermintaan   = [];
        $dataPetugasCross = [];
        $dataPj           = [];

        if (!empty($baris['id_permintaan'])) {
            $modelPermintaan = new \App\Features\PelayananDarah\PermintaanDarah\PermintaanDarahModel();
            $permintaanRow   = $modelPermintaan->find($baris['id_permintaan']);
            if ($permintaanRow) {
                $dataPermintaan['no_permintaan'] = $permintaanRow['no_permintaan'] ?? '-';
            }
        }

        if (!empty($baris['id_petugas_cross'])) {
            $modelPetugasCross = new \App\Features\Role\Petugas\PetugasModel();
            $rowCross          = $modelPetugasCross->find($baris['id_petugas_cross']);
            if ($rowCross && !empty($rowCross['id_orang'])) {
                $modelOrangCross                        = new \App\Features\Person\Orang\OrangModel();
                $orangCross                             = $modelOrangCross->find($rowCross['id_orang']);
                $dataPetugasCross['nama_petugas_cross'] = $orangCross['nama'] ?? '';
            }
        }

        if (!empty($baris['id_penanggung_jawab'])) {
            $modelPj = new \App\Features\Role\Petugas\PetugasModel();
            $rowPj   = $modelPj->find($baris['id_penanggung_jawab']);
            if ($rowPj && !empty($rowPj['id_orang'])) {
                $modelOrangPj      = new \App\Features\Person\Orang\OrangModel();
                $orangPj           = $modelOrangPj->find($rowPj['id_orang']);
                $dataPj['nama_pj'] = $orangPj['nama'] ?? '';
            }
        }

        $baris = array_merge($dataPermintaan, $dataPetugasCross, $dataPj, $baris);

        $konfigFields = $this->get_fields_with_options(false, true);
        foreach ($konfigFields as $field) {
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

        $detailDarah = $this->model
            ->db
            ->table('pelayanan_darah.penyerahan_darah_detail pdd')
            ->select(
                'sk.no_kantong, kd.nama_komponen, gd.nama_golongan_darah, r.kode_rhesus, pdd.jasa_sarana, pdd.paket_bhp, pdd.kso, pdd.manajemen',
            )
            ->join('inventori_darah.stok_darah sk', 'sk.id_stok_darah = pdd.id_stok_darah', 'inner')
            ->join('inventori_darah.komponen_darah kd', 'kd.id_komponen = sk.id_komponen', 'inner')
            ->join('darah.golongan_darah gd', 'gd.id_golongan_darah = sk.id_golongan_darah', 'left')
            ->join('darah.rhesus r', 'r.id_rhesus = sk.id_rhesus', 'left')
            ->where('pdd.id_penyerahan', $id)
            ->get()
            ->getResultArray();

        $bhpMedis     = $this->model->getBhpMedisDetail($id);
        $bhpPenunjang = $this->model->getBhpPenunjangDetail($id);

        foreach ($baris as $key => $value) {
            if ($value === null) {
                $baris[$key] = '';
            }
        }

        $breadcrumbs = [
            ['title' => 'Detail', 'icon' => 'detail'],
        ];

        return view('admin/pelayanandarah/detail_penyerahandarah', [
            'judul'         => 'Detail ' . $this->title,
            'breadcrumbs'   => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'    => $this->get_uri_path(),
            'baris'         => $baris,
            'detail_darah'  => $detailDarah,
            'bhp_medis'     => $bhpMedis,
            'bhp_penunjang' => $bhpPenunjang,
        ]);
    }

    /**
     * Endpoint POST: Memproses perubahan status pembayaran dari tombol aksi bayar
     */
    public function bayar(int|string $id)
    {
        $idStatusSudahBayar = 2;

        $this->model->update($id, [
            'id_status_pembayaran' => $idStatusSudahBayar,
        ]);

        session()->setFlashdata('success', 'Pembayaran berhasil dikonfirmasi lunas.');
        return redirect()->to($this->get_uri_path() . '/data');
    }
}
