<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabPa;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PermintaanLabPaController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabPaModel(),
            [
                ['Laboratorium',      'laboratorium'],
                ['Permintaan Lab PA', 'permintaan_lab_pa'],
            ],
            'Permintaan Lab PA',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::SAMPEL,
                A::PRINT,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_permintaan_pa',            'ID Permintaan PA'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_lab',           'ID Permintaan Lab'],
                [SHOW, REQUIRED, I::DATE,  'tgl_pengambilan_bahan',       'Tanggal Pengambilan Bahan'],
                [SHOW, REQUIRED, I::TEXT,  'metode_diperoleh',            'Metode Diperoleh'],
                [SHOW, REQUIRED, I::TEXT,  'lokasi_jaringan',             'Lokasi Jaringan'],
                [SHOW, REQUIRED, I::TEXT,  'bahan_pengawet',              'Bahan Pengawet'],
                [SHOW, REQUIRED, I::TEXT,  'riwayat_lokasi_lab',          'Riwayat Lokasi Lab'],
                [SHOW, REQUIRED, I::DATE,  'riwayat_tgl_sebelumnya',      'Riwayat Tanggal Sebelumnya'],
                [SHOW, REQUIRED, I::TEXT,  'riwayat_no_pa_sebelumnya',    'Riwayat No. PA Sebelumnya'],
                [SHOW, REQUIRED, I::TEXT,  'riwayat_diagnosa_sebelumnya', 'Riwayat Diagnosa Sebelumnya'],
            ],
        );
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------
 
    private function generateNomorPermintaan(): string
    {
        helper('autonomor');
 
        $lastNo = $this->model->db
            ->table('laboratorium.permintaan_lab_header')
            ->select('no_permintaan')
            ->like('no_permintaan', 'PA' . date('Ymd'), 'after')
            ->orderBy('no_permintaan', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
 
        return generateNextNoPermintaanPa($lastNo['no_permintaan'] ?? null);
    }
 
    private function filterKonfig(): array
    {
        return array_values(array_filter(
            (new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderController())->get_fields_with_options(false, true),
            fn($f) => !in_array($f[2], [
                'id_permintaan',
                'no_permintaan',
                'nomor_reg',
                'id_kategori_lab',
                'id_dokter_perujuk',
                'id_status_permintaan',
                'tgl_jam_sampel',
            ], true)
        ));
    }
 
    private function fetchRegistrasi(string $nomorReg): array
    {
        return $this->model->db
            ->table('registrasi.registrasi r')
            ->select([
                'r.nomor_reg',
                'p.nomor_rm',
                'o.nama',
                'd.id_dokter',
                'd.kode_dokter',
                'od.nama AS nama_dokter',
            ])
            ->join('role.pasien p',   'p.id_pasien = r.id_pasien')
            ->join('person.orang o',  'o.id_orang  = p.id_orang')
            ->join('role.dokter d',   'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang',  'left')
            ->where('r.nomor_reg', $nomorReg)
            ->get()
            ->getRowArray() ?? [];
    }
 
    private function fetchDokter(string|int $idDokter): array
    {
        return $this->model->db
            ->table('role.dokter d')
            ->select(['d.kode_dokter', 'o.nama AS nama_dokter'])
            ->join('person.orang o', 'o.id_orang = d.id_orang')
            ->where('d.id_dokter', (int) $idDokter)
            ->get()
            ->getRowArray() ?? [];
    }
 
    private function fetchItemTerpilih(int $idPermintaanLab): array
    {
        return $this->model->db
            ->table('laboratorium.permintaan_lab_pa_item pai')
            ->select([
                'pai.id_permintaan_pa_item',
                'pai.id_item_pemeriksaan',
                'i.kode_periksa',
                'i.nama_item',
                'i.tarif',
            ])
            ->join('laboratorium.ref_item_pemeriksaan_lab i', 'i.id_item_lab = pai.id_item_pemeriksaan')
            ->where('pai.id_permintaan_lab', $idPermintaanLab)
            ->get()
            ->getResultArray();
    }
 
    private function buildHeaderData(array $rawPost, bool $withStatus = false): array
    {
        return array_merge([
            'no_permintaan'      => $rawPost['no_permintaan']      ?? '',
            'nomor_reg'          => $rawPost['nomor_reg']          ?? '',
            'id_kategori_lab'    => ID_KATEGORI_PA,
            'id_dokter_perujuk'  => trim($rawPost['id_dokter_perujuk'] ?? ''),
            'tgl_permintaan'     => $rawPost['tgl_permintaan']     ?? date('Y-m-d H:i:s'),
            'indikasi_klinis'    => $rawPost['indikasi_klinis']    ?? '',
            'informasi_tambahan' => $rawPost['informasi_tambahan'] ?? '',
        ], $withStatus ? ['id_status_permintaan' => 1] : []);
    }
 
    private function buildSpesimenData(array $rawPost): array
    {
        return [
            'tgl_pengambilan_bahan'       => $rawPost['tgl_pengambilan_bahan']       ?? null,
            'metode_diperoleh'            => $rawPost['metode_diperoleh']            ?? '',
            'lokasi_jaringan'             => $rawPost['lokasi_jaringan']             ?? '',
            'bahan_pengawet'              => $rawPost['bahan_pengawet']              ?? '',
            'riwayat_lokasi_lab'          => $rawPost['riwayat_lokasi_lab']          ?? '',
            'riwayat_tgl_sebelumnya'      => $rawPost['riwayat_tgl_sebelumnya']      ?? null,
            'riwayat_no_pa_sebelumnya'    => $rawPost['riwayat_no_pa_sebelumnya']    ?? '',
            'riwayat_diagnosa_sebelumnya' => $rawPost['riwayat_diagnosa_sebelumnya'] ?? '',
        ];
    }
 
    private function insertItems(
        int $idPermintaanLab,
        array $idItems,
        \App\Features\Laboratorium\PermintaanLabPaItem\PermintaanLabPaItemModel $modelItem,
    ): void {
        if (empty($idItems)) return;

        // Menggunakan Batch Insert untuk efisiensi eksekusi database
        $data = array_map(fn($idItem) => [
            'id_permintaan_lab'   => $idPermintaanLab,
            'id_item_pemeriksaan' => (int) $idItem,
        ], $idItems);

        $modelItem->insertBatch($data);
    }

    // // Centralize query for index() and list()
    private function fetchPermintaanLabHeaders(): array
    {
        return $this->model->db
            ->table('laboratorium.permintaan_lab_header h')
            ->select([
                'h.id_permintaan', 'h.no_permintaan', 'h.nomor_reg', 'h.tgl_permintaan',
                'h.id_status_permintaan',
                'p.nomor_rm', 'o.nama', 'o.tanggal_lahir', 'd.kode_dokter', 'od.nama AS nama_dokter', 's.nama_status',
            ])
            ->join('registrasi.registrasi r',             'r.nomor_reg  = h.nomor_reg',            'left')
            ->join('role.pasien p',                        'p.id_pasien  = r.id_pasien',            'left')
            ->join('person.orang o',                       'o.id_orang   = p.id_orang',             'left')
            ->join('role.dokter d',                        'd.id_dokter  = h.id_dokter_perujuk',    'left')
            ->join('person.orang od',                      'od.id_orang  = d.id_orang',             'left')
            ->join('laboratorium.ref_status_permintaan s', 's.id_status  = h.id_status_permintaan', 'left')
            ->where('h.id_kategori_lab', ID_KATEGORI_PA)
            ->orderBy('h.tgl_permintaan', 'DESC')
            ->get()
            ->getResultArray();
    }
 
    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------
 
    #[\Override]
    final public function create_page(): string
    {
        return view('admin/laboratorium/tambah_permintaan_pa', [
            'judul'         => 'Tambah ' . $this->title,
            'breadcrumbs'   => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'    => $this->get_uri_path(),
            'kolom_id'      => $this->model->primaryKey,
            'konfig'        => $this->filterKonfig(),
            'baris'         => [],
            'form_action'   => '/submittambah',
            'no_permintaan' => $this->generateNomorPermintaan(),
            'item_terpilih' => [],
        ]);
    }
 
    #[\Override]
    final public function update_page(int|string $id): string
    {
        $idPermintaanLab = (int) $id;
 
        $paRow = $this->model->where('id_permintaan_lab', $idPermintaanLab)->first();
 
        $baris = $this->model->db
            ->table('laboratorium.permintaan_lab_header h')
            ->select([
                'h.id_permintaan AS id_permintaan_lab',
                'h.no_permintaan',
                'h.nomor_reg',
                'h.id_dokter_perujuk',
                'h.tgl_permintaan',
                'h.indikasi_klinis',
                'h.informasi_tambahan',
                'h.id_status_permintaan',
            ])
            ->where('h.id_permintaan', $idPermintaanLab)
            ->get()
            ->getRowArray() ?? [];

        // Penggabungan $baris dengan format data PA yang lebih bersih
        if (!empty($paRow)) {
            $baris = array_merge($baris, [
                'tgl_pengambilan_bahan'       => $paRow['tgl_pengambilan_bahan']
                    ? date('Y-m-d\TH:i', strtotime($paRow['tgl_pengambilan_bahan']))
                    : '',
                'metode_diperoleh'            => $paRow['metode_diperoleh']            ?? '',
                'lokasi_jaringan'             => $paRow['lokasi_jaringan']             ?? '',
                'bahan_pengawet'              => $paRow['bahan_pengawet']              ?? '',
                'riwayat_lokasi_lab'          => $paRow['riwayat_lokasi_lab']          ?? '',
                'riwayat_tgl_sebelumnya'      => $paRow['riwayat_tgl_sebelumnya']      ?? '',
                'riwayat_no_pa_sebelumnya'    => $paRow['riwayat_no_pa_sebelumnya']    ?? '',
                'riwayat_diagnosa_sebelumnya' => $paRow['riwayat_diagnosa_sebelumnya'] ?? '',
            ]);
        }
 
        if (!empty($baris['nomor_reg'])) {
            $baris = array_merge($baris, $this->fetchRegistrasi($baris['nomor_reg']));
        }
 
        if (!empty($baris['id_dokter_perujuk'])) {
            $baris = array_merge($baris, $this->fetchDokter($baris['id_dokter_perujuk']));
        }
 
        return view('admin/laboratorium/tambah_permintaan_pa', [
            'judul'         => 'Ubah ' . $this->title,
            'breadcrumbs'   => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'    => $this->get_uri_path(),
            'kolom_id'      => $this->model->primaryKey,
            'konfig'        => $this->filterKonfig(),
            'baris'         => $baris,
            'form_action'   => '/submitedit/' . $id,
            'no_permintaan' => $baris['no_permintaan'] ?? '',
            'item_terpilih' => $this->fetchItemTerpilih($idPermintaanLab),
        ]);
    }
 
    // -------------------------------------------------------------------------
    // CRUD
    // -------------------------------------------------------------------------
 
    #[\Override]
    public function create(): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();
        $idItems = $this->request->getPost('id_item');
 
        if (empty($idItems)) {
            session()->setFlashdata('error', 'Pilih minimal satu item pemeriksaan.');
            return redirect()->back()->withInput();
        }
 
        $noPermintaan = !empty($rawPost['no_permintaan']) ? $rawPost['no_permintaan'] : $this->generateNomorPermintaan();
 
        $header   = array_merge($this->buildHeaderData($rawPost, true), ['no_permintaan' => $noPermintaan]);
        $spesimen = $this->buildSpesimenData($rawPost);
        $modelHeader = new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel();
        $modelItem   = new \App\Features\Laboratorium\PermintaanLabPaItem\PermintaanLabPaItemModel();
 
        $this->model->db->transStart();
 
        try {
            $modelHeader->insert($header);
            $idPermintaanLab = $modelHeader->getInsertID();
 
            $this->model->insert(array_merge($spesimen, ['id_permintaan_lab' => $idPermintaanLab]));
            $this->insertItems($idPermintaanLab, $idItems, $modelItem);
 
            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan permintaan lab PA.');
            }
 
            session()->setFlashdata('success', 'Permintaan lab PA berhasil disimpan.');
            return redirect()->to($this->get_uri_path() . '/data');
 
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }
 
    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();
 
        $idPermintaanLab = (int) $id;
 
        if (!$idPermintaanLab) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return redirect()->back();
        }
 
        $rawPost = $this->request->getPost();
        $idItems = $this->request->getPost('id_item') ?? [];
 
        if (empty($idItems)) {
            session()->setFlashdata('error', 'Pilih minimal satu item pemeriksaan.');
            return redirect()->back()->withInput();
        }
 
        $spesimen    = $this->buildSpesimenData($rawPost);
        $modelHeader = new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel();
        $modelItem   = new \App\Features\Laboratorium\PermintaanLabPaItem\PermintaanLabPaItemModel();
 
        $this->model->db->transStart();
 
        try {
            $modelHeader->update($idPermintaanLab, $this->buildHeaderData($rawPost));
 
            $this->model->where('id_permintaan_lab', $idPermintaanLab)->set($spesimen)->update();
 
            $modelItem->where('id_permintaan_lab', $idPermintaanLab)->delete();
            $this->insertItems($idPermintaanLab, $idItems, $modelItem);
 
            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui permintaan lab PA.');
            }
 
            session()->setFlashdata('success', 'Permintaan lab PA berhasil diperbarui.');
            return redirect()->to($this->get_uri_path() . '/data');
 
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }
 
    #[\Override]
    final public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();
 
        $idPermintaanLab = (int) $id;
        if (!$idPermintaanLab) return $this->home();
 
        $modelHeader = new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel();
        $modelItem   = new \App\Features\Laboratorium\PermintaanLabPaItem\PermintaanLabPaItemModel();
 
        $this->model->db->transStart();
 
        try {
            $modelItem->where('id_permintaan_lab', $idPermintaanLab)->delete();
            $this->model->where('id_permintaan_lab', $idPermintaanLab)->delete();
 
            $modelHeader->delete($idPermintaanLab);
 
            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus permintaan lab PA.');
            }
 
            session()->setFlashdata('success', 'Permintaan lab PA berhasil dihapus.');
 
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
        }
 
        return $this->home();
    }
 
    // -------------------------------------------------------------------------
    // Sampel
    // -------------------------------------------------------------------------
 
    public function sampel(int|string $id): RedirectResponse
    {
        if ($id == 0) return $this->home();
 
        $idPermintaanLab = (int) $id;
        if (!$idPermintaanLab) return $this->home();
 
        try {
            (new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel())->update($idPermintaanLab,
            [
                'tgl_jam_sampel'       => $this->request->getPost('tgl_jam_sampel') ?: date('Y-m-d H:i:s'),
                'id_status_permintaan' => 2,
            ]);
            session()->setFlashdata('success', 'Waktu pengambilan sampel berhasil dicatat.');
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            session()->setFlashdata('error', $this->friendly_db_error($e));
        }
 
        return $this->home();
    }
 
    // -------------------------------------------------------------------------
    // Index
    // -------------------------------------------------------------------------
 
    #[\Override]
    final public function index(): string
    {
        $rows = $this->fetchPermintaanLabHeaders();
 
        $konfig = [
            [1, 'No. Permintaan', 'no_permintaan',  'teks',    0],
            [1, 'No. Registrasi', 'nomor_reg',      'teks',    0],
            [1, 'No. RM',         'nomor_rm',       'teks',    0],
            [1, 'Nama Pasien',    'nama',           'teks',    0],
            [1, 'Dokter Perujuk', 'nama_dokter',    'teks',    0],
            [1, 'Tgl Permintaan', 'tgl_permintaan', 'tanggal', 0],
            [1, 'Status',         'nama_status',    'status',  0],
        ];
 
        return view('/layouts/data', [
            'judul'        => $this->title,
            'breadcrumbs'  => $this->breadcrumbs,
            'meta_data'    => ['page' => 1, 'size' => count($rows), 'total' => 1],
            'modul_path'   => $this->get_uri_path(),
            'kolom_id'     => 'id_permintaan',
            'konfig'       => $konfig,
            'aksi'         => $this->actions,
            'tabel'        => $rows,
            'row_alert'    => [],
            'child_link'   => null,
            'query_string' => '',
        ]);
    }
 
    // -------------------------------------------------------------------------
    // Cetak
    // -------------------------------------------------------------------------

    #[\Override]
    public function print(int|string $id): string
    {
        $idPermintaanLab = (int) $id;

        $header = $this->model->db
            ->table('laboratorium.permintaan_lab_header plh')
            ->select([
                'plh.id_permintaan', 'plh.no_permintaan', 'plh.nomor_reg',
                'plh.tgl_permintaan', 'plh.indikasi_klinis', 'plh.informasi_tambahan',
                'p.nomor_rm', 'o.nama AS nama_pasien',
                'd.kode_dokter', 'od.nama AS nama_dokter',
            ])
            ->join('registrasi.registrasi r',  'r.nomor_reg       = plh.nomor_reg',         'left')
            ->join('role.pasien p',             'p.id_pasien       = r.id_pasien',           'left')
            ->join('person.orang o',            'o.id_orang        = p.id_orang',            'left')
            ->join('role.dokter d',             'd.id_dokter       = plh.id_dokter_perujuk', 'left')
            ->join('person.orang od',           'od.id_orang       = d.id_orang',            'left')
            ->where('plh.id_permintaan', $idPermintaanLab)
            ->get()->getRowArray() ?? [];

        if (empty($header)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        $spesimen = $this->model->where('id_permintaan_lab', $idPermintaanLab)->first() ?? [];

        return view('Views/components/cetak/cetak_permintaan_lab_pa', [
            'header'   => $header,
            'spesimen' => $spesimen,
            'items'    => $this->fetchItemTerpilih($idPermintaanLab),
        ]);
    }

    // -------------------------------------------------------------------------
    // List
    // -------------------------------------------------------------------------

    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $idPermintaan = (int) ($this->request->getGet('id_permintaan') ?? 0);

        if ($idPermintaan > 0) {
            return $this->response->setJSON(['data' => $this->fetchItemTerpilih($idPermintaan)]);
        }

        $rows = $this->fetchPermintaanLabHeaders();

        return $this->response->setJSON(['data' => $rows]);
    }
}
