<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabPkItem;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanLabPkItemController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabPkItemModel(),
            [
                ['Laboratorium',      'laboratorium'],
                ['Permintaan Lab PK', 'permintaan_lab_pk'],
            ],
            'Permintaan Lab PK',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::SAMPEL,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_permintaan_pk_item', 'ID Permintaan PK Item'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_lab',    'ID Permintaan Lab'],
                [SHOW, REQUIRED, I::INDEX, 'id_item_pemeriksaan',  'ID Item Pemeriksaan'],
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
            ->like('no_permintaan', 'PK' . date('Ymd'), 'after')
            ->orderBy('no_permintaan', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
 
        return generateNextNoPermintaanPk($lastNo['no_permintaan'] ?? null);
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
            ->table('rekam_medis.registrasi r')
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
        $items = $this->model->db
            ->table('laboratorium.permintaan_lab_pk_item pki')
            ->select([
                'pki.id_permintaan_pk_item',
                'pki.id_item_pemeriksaan',
                'i.kode_periksa',
                'i.nama_item',
                'i.tarif',
            ])
            ->join('laboratorium.ref_item_pemeriksaan_lab i', 'i.id_item_lab = pki.id_item_pemeriksaan')
            ->where('pki.id_permintaan_lab', $idPermintaanLab)
            ->get()
            ->getResultArray();
 
        foreach ($items as &$item) {
            $item['parameter'] = $this->model->db
                ->table('laboratorium.permintaan_lab_pk_parameter pkp')
                ->select([
                    'pkp.id_pk_parameter',
                    'pkp.id_parameter',
                    'p.nama_parameter',
                    'p.satuan',
                    'p.nilai_rujukan',
                    'p.biaya_item',
                ])
                ->join('laboratorium.ref_parameter_pemeriksaan_lab p', 'p.id_parameter = pkp.id_parameter')
                ->where('pkp.id_permintaan_pk_item', $item['id_permintaan_pk_item'])
                ->get()
                ->getResultArray();
        }
        unset($item);
 
        return $items;
    }
 
    private function isEditable(int $idPermintaanLab): bool
    {
        $row = $this->model->db
            ->table('laboratorium.permintaan_lab_header')
            ->select('id_status_permintaan')
            ->where('id_permintaan', $idPermintaanLab)
            ->get()
            ->getRowArray();
 
        return in_array((int) ($row['id_status_permintaan'] ?? 0), [1, 2], true);
    }
 
    private function buildHeaderData(array $rawPost, bool $withStatus = false): array
    {
        $data = [
            'no_permintaan'      => $rawPost['no_permintaan']      ?? '',
            'nomor_reg'          => $rawPost['nomor_reg']          ?? '',
            'id_kategori_lab'    => ID_KATEGORI_PK,
            'id_dokter_perujuk'  => trim($rawPost['id_dokter_perujuk'] ?? ''),
            'tgl_permintaan'     => $rawPost['tgl_permintaan']     ?? date('Y-m-d H:i:s'),
            'indikasi_klinis'    => $rawPost['indikasi_klinis']    ?? '',
            'informasi_tambahan' => $rawPost['informasi_tambahan'] ?? '',
        ];
 
        if ($withStatus) {
            $data['id_status_permintaan'] = 1;
        }
 
        return $data;
    }
 
    private function insertItemsAndParameters(
        int $idPermintaanLab,
        array $idItems,
        array $idParameters,
        \App\Features\Laboratorium\PermintaanLabPkItem\PermintaanLabPkItemModel $modelItem,
        \App\Features\Laboratorium\PermintaanLabPkParameter\PermintaanLabPkParameterModel $modelParameter,
    ): void {
        foreach ($idItems as $idItem) {
            $modelItem->insert([
                'id_permintaan_lab'   => $idPermintaanLab,
                'id_item_pemeriksaan' => (int) $idItem,
            ]);
            $idPkItem = $modelItem->getInsertID();
 
            foreach ($idParameters[(string) $idItem] ?? [] as $idParam) {
                $modelParameter->insert([
                    'id_permintaan_pk_item' => $idPkItem,
                    'id_parameter'          => (int) $idParam,
                ]);
            }
        }
    }
 
    private function deleteItemsAndParameters(
        int $idPermintaanLab,
        \App\Features\Laboratorium\PermintaanLabPkItem\PermintaanLabPkItemModel $modelItem,
        \App\Features\Laboratorium\PermintaanLabPkParameter\PermintaanLabPkParameterModel $modelParameter,
    ): void {
        $oldItems = $this->model->db
            ->table('laboratorium.permintaan_lab_pk_item')
            ->select('id_permintaan_pk_item')
            ->where('id_permintaan_lab', $idPermintaanLab)
            ->get()
            ->getResultArray();
 
        foreach ($oldItems as $oldItem) {
            $modelParameter->where('id_permintaan_pk_item', $oldItem['id_permintaan_pk_item'])->delete();
        }
 
        $modelItem->where('id_permintaan_lab', $idPermintaanLab)->delete();
    }
 
    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------
 
    #[\Override]
    final public function create_page(): string
    {
        return view('admin/laboratorium/tambah_permintaan_pk', [
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
 
        if (!empty($baris['nomor_reg'])) {
            $baris = array_merge($baris, $this->fetchRegistrasi($baris['nomor_reg']));
        }
 
        if (!empty($baris['id_dokter_perujuk'])) {
            $baris = array_merge($baris, $this->fetchDokter($baris['id_dokter_perujuk']));
        }
 
        return view('admin/laboratorium/tambah_permintaan_pk', [
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
    public function create(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $rawPost      = $this->request->getPost();
        $idItems      = $this->request->getPost('id_item')      ?? [];
        $idParameters = $this->request->getPost('id_parameter') ?? [];
 
        if (empty($idItems)) {
            session()->setFlashdata('error', 'Pilih minimal satu item pemeriksaan.');
            return redirect()->back()->withInput();
        }
 
        $noPermintaan = $rawPost['no_permintaan'] ?? '';
        if (empty($noPermintaan)) {
            $noPermintaan = $this->generateNomorPermintaan();
        }
 
        $header = array_merge($this->buildHeaderData($rawPost, true), [
            'no_permintaan' => $noPermintaan,
        ]);
 
        $modelHeader    = new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel();
        $modelItem      = new \App\Features\Laboratorium\PermintaanLabPkItem\PermintaanLabPkItemModel();
        $modelParameter = new \App\Features\Laboratorium\PermintaanLabPkParameter\PermintaanLabPkParameterModel();
 
        $this->model->db->transStart();
 
        try {
            $modelHeader->insert($header);
            $idPermintaanLab = (int) $modelHeader->getInsertID();
 
            if (!$idPermintaanLab) {
                $err = $this->model->db->error();
                throw new \RuntimeException('Header: ' . ($err['message'] ?? 'insert gagal, ID tidak didapat'));
            }
 
            $this->insertItemsAndParameters($idPermintaanLab, $idItems, $idParameters, $modelItem, $modelParameter);
 
            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan permintaan lab PK. ' . ($err['message'] ?? ''));
            }
 
            session()->setFlashdata('success', 'Permintaan lab PK berhasil disimpan.');
            return redirect()->to($this->get_uri_path() . '/data');
 
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $this->friendly_db_error($e));
            return redirect()->back()->withInput();
        } catch (\RuntimeException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
 
    #[\Override]
    public function update(int|string $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($id == 0) return $this->home();
 
        $idPermintaanLab = (int) $id;
 
        if (!$idPermintaanLab) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return redirect()->back();
        }
 
        if (!$this->isEditable($idPermintaanLab)) {
            session()->setFlashdata('error', 'Permintaan tidak dapat diubah karena status sudah Selesai atau Batal.');
            return redirect()->to($this->get_uri_path() . '/data');
        }
 
        $idItems      = $this->request->getPost('id_item')      ?? [];
        $idParameters = $this->request->getPost('id_parameter') ?? [];
 
        if (empty($idItems)) {
            session()->setFlashdata('error', 'Pilih minimal satu item pemeriksaan.');
            return redirect()->back()->withInput();
        }
 
        $modelHeader    = new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel();
        $modelItem      = new \App\Features\Laboratorium\PermintaanLabPkItem\PermintaanLabPkItemModel();
        $modelParameter = new \App\Features\Laboratorium\PermintaanLabPkParameter\PermintaanLabPkParameterModel();
 
        $this->model->db->transStart();
 
        try {
            $modelHeader->update($idPermintaanLab, $this->buildHeaderData($this->request->getPost()));
 
            $this->deleteItemsAndParameters($idPermintaanLab, $modelItem, $modelParameter);
            $this->insertItemsAndParameters($idPermintaanLab, $idItems, $idParameters, $modelItem, $modelParameter);
 
            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui permintaan lab PK.');
            }
 
            session()->setFlashdata('success', 'Permintaan lab PK berhasil diperbarui.');
            return redirect()->to($this->get_uri_path() . '/data');
 
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $this->friendly_db_error($e));
            return redirect()->back()->withInput();
        } catch (\RuntimeException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
 
    #[\Override]
    final public function delete(int|string $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($id == 0) return $this->home();
 
        $idPermintaanLab = (int) $id;
        if (!$idPermintaanLab) return $this->home();
 
        $modelHeader    = new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel();
        $modelItem      = new \App\Features\Laboratorium\PermintaanLabPkItem\PermintaanLabPkItemModel();
        $modelParameter = new \App\Features\Laboratorium\PermintaanLabPkParameter\PermintaanLabPkParameterModel();
 
        $this->model->db->transStart();
 
        try {
            $this->deleteItemsAndParameters($idPermintaanLab, $modelItem, $modelParameter);
 
            $modelHeader->delete($idPermintaanLab);
 
            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus permintaan lab PK.');
            }
 
            session()->setFlashdata('success', 'Permintaan lab PK berhasil dihapus.');
 
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $this->friendly_db_error($e));
        } catch (\RuntimeException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $e->getMessage());
        }
 
        return $this->home();
    }
 
    // -------------------------------------------------------------------------
    // Sampel
    // -------------------------------------------------------------------------
 
    public function sampel(int|string $id): \CodeIgniter\HTTP\RedirectResponse
    {
        if ($id == 0) return $this->home();
 
        $idPermintaanLab = (int) $id;
        if (!$idPermintaanLab) return $this->home();
 
        try {
            $modelHeader = new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel();
            $modelHeader->update($idPermintaanLab, [
                'tgl_jam_sampel'       => date('Y-m-d H:i:s'),
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
        $rows = $this->model->db
            ->table('laboratorium.permintaan_lab_header h')
            ->select([
                'h.id_permintaan',
                'h.no_permintaan',
                'h.nomor_reg',
                'h.tgl_permintaan',
                'p.nomor_rm',
                'o.nama',
                'd.kode_dokter',
                'od.nama AS nama_dokter',
                's.nama_status',
            ])
            ->join('rekam_medis.registrasi r',             'r.nomor_reg  = h.nomor_reg',            'left')
            ->join('role.pasien p',                        'p.id_pasien  = r.id_pasien',            'left')
            ->join('person.orang o',                       'o.id_orang   = p.id_orang',             'left')
            ->join('role.dokter d',                        'd.id_dokter  = h.id_dokter_perujuk',    'left')
            ->join('person.orang od',                      'od.id_orang  = d.id_orang',             'left')
            ->join('laboratorium.ref_status_permintaan s', 's.id_status  = h.id_status_permintaan', 'left')
            ->where('h.id_kategori_lab', ID_KATEGORI_PK)
            ->orderBy('h.tgl_permintaan', 'DESC')
            ->get()
            ->getResultArray();
 
        $konfig = [
            [1, 'No. Permintaan', 'no_permintaan', 'teks',    0],
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
    // List
    // -------------------------------------------------------------------------
 
    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $idPermintaan = (int) ($this->request->getGet('id_permintaan') ?? 0);
 
        if ($idPermintaan > 0) {
            return $this->response->setJSON(['data' => $this->fetchItemTerpilih($idPermintaan)]);
        }
 
        $rows = $this->model->db
            ->table('laboratorium.permintaan_lab_header h')
            ->select([
                'h.id_permintaan',
                'h.no_permintaan',
                'h.nomor_reg',
                'h.tgl_permintaan',
                'p.nomor_rm',
                'o.nama',
                's.nama_status',
            ])
            ->join('rekam_medis.registrasi r',             'r.nomor_reg = h.nomor_reg',             'left')
            ->join('role.pasien p',                        'p.id_pasien = r.id_pasien',             'left')
            ->join('person.orang o',                       'o.id_orang  = p.id_orang',              'left')
            ->join('laboratorium.ref_status_permintaan s', 's.id_status = h.id_status_permintaan',  'left')
            ->where('h.id_kategori_lab', ID_KATEGORI_PK)
            ->orderBy('h.tgl_permintaan', 'DESC')
            ->get()->getResultArray();
 
        return $this->response->setJSON(['data' => $rows]);
    }
}
