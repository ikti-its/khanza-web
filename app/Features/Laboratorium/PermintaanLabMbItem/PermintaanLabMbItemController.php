<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabMbItem;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

final class PermintaanLabMbItemController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabMbItemModel(),
            [
                ['Laboratorium',      'laboratorium'],
                ['Permintaan Lab MB', 'permintaan_lab_mb'],
            ],
            'Permintaan Lab MB',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::SAMPEL,
                A::PRINT,
                A::FILTER,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_permintaan_mb_item',    'ID Permintaan MB Item'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_lab',        'ID Permintaan Lab'],
                [SHOW, REQUIRED, I::INDEX, 'id_item_pemeriksaan',      'ID Item Pemeriksaan'],
                [SHOW, REQUIRED, I::INDEX, 'id_parameter_pemeriksaan', 'ID Parameter Pemeriksaan'],
            ],
        );
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    private function generateNomorPermintaan(): string
    {
        helper('autonomor');

        $lastNo = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_header')
            ->select('no_permintaan')
            ->like('no_permintaan', 'MB' . date('Ymd'), 'after')
            ->orderBy('no_permintaan', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return generateNextNoPermintaanMb($lastNo['no_permintaan'] ?? null);
    }

    private function filterKonfig(): array
    {
        return array_values(array_filter(
            (new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderController())->get_fields_with_options(
                false,
                true,
            ),
            static fn($f) => !in_array(
                $f[2],
                [
                    'id_permintaan',
                    'no_permintaan',
                    'nomor_reg',
                    'id_kategori_lab',
                    'id_dokter_perujuk',
                    'id_status_permintaan',
                    'tgl_jam_sampel',
                ],
                true,
            ),
        ));
    }

    private function fetchRegistrasi(string $nomorReg): array
    {
        return $this->model
            ->db
            ->table('registrasi.registrasi r')
            ->select([
                'r.nomor_reg',
                'p.nomor_rm',
                'o.nama',
                'd.id_dokter',
                'd.kode_dokter',
                'od.nama AS nama_dokter',
            ])
            ->join('role.pasien p', 'p.id_pasien = r.id_pasien')
            ->join('person.orang o', 'o.id_orang  = p.id_orang')
            ->join('role.dokter d', 'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang', 'left')
            ->where('r.nomor_reg', $nomorReg)
            ->get()
            ->getRowArray() ?? [];
    }

    private function fetchDokter(string|int $idDokter): array
    {
        return $this->model
            ->db
            ->table('role.dokter d')
            ->select(['d.kode_dokter', 'o.nama AS nama_dokter'])
            ->join('person.orang o', 'o.id_orang = d.id_orang')
            ->where('d.id_dokter', (int) $idDokter)
            ->get()
            ->getRowArray() ?? [];
    }

    private function fetchItemTerpilih(int $idPermintaanLab): array
    {
        $items = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_mb_item mbi')
            ->select([
                'mbi.id_permintaan_mb_item',
                'mbi.id_item_pemeriksaan',
                'i.kode_periksa',
                'i.nama_item',
                'i.tarif',
            ])
            ->join('laboratorium.ref_item_pemeriksaan_lab i', 'i.id_item_lab = mbi.id_item_pemeriksaan')
            ->where('mbi.id_permintaan_lab', $idPermintaanLab)
            ->orderBy('mbi.id_permintaan_mb_item', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($items)) {
            return [];
        }

        // Mengambil semua parameter sekaligus menggunakan whereIn
        $itemIds = array_column($items, 'id_permintaan_mb_item');

        $parameters = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_mb_parameter mbp')
            ->select([
                'mbp.id_permintaan_mb_item',
                'mbp.id_mb_parameter',
                'mbp.id_parameter',
                'p.nama_parameter',
                'p.satuan',
                'p.nilai_rujukan',
                'p.biaya_item',
            ])
            ->join('laboratorium.ref_parameter_pemeriksaan_lab p', 'p.id_parameter = mbp.id_parameter')
            ->whereIn('mbp.id_permintaan_mb_item', $itemIds)
            ->orderBy('mbp.id_parameter', 'ASC')
            ->get()
            ->getResultArray();

        // Kelompokkan parameter berdasarkan id_permintaan_mb_item
        $groupedParams = [];
        foreach ($parameters as $param) {
            $groupedParams[$param['id_permintaan_mb_item']][] = $param;
        }

        foreach ($items as &$item) {
            $item['parameter'] = $groupedParams[$item['id_permintaan_mb_item']] ?? [];
        }

        return $items;
    }

    private function isEditable(int $idPermintaanLab): bool
    {
        $row = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_header')
            ->select('id_status_permintaan')
            ->where('id_permintaan', $idPermintaanLab)
            ->get()
            ->getRowArray();

        return in_array((int) ($row['id_status_permintaan'] ?? 0), [1, 2], true);
    }

    private function insertItemsAndParameters(
        int $idPermintaanLab,
        array $idItems,
        array $idParameters,
        \App\Features\Laboratorium\PermintaanLabMbItem\PermintaanLabMbItemModel $modelItem,
        \App\Features\Laboratorium\PermintaanLabMbParameter\PermintaanLabMbParameterModel $modelParameter,
    ): void {
        foreach ($idItems as $idItem) {
            $modelItem->insert([
                'id_permintaan_lab'   => $idPermintaanLab,
                'id_item_pemeriksaan' => (int) $idItem,
            ]);
            $idMbItem = $modelItem->getInsertID();

            foreach ($idParameters[(string) $idItem] ?? [] as $idParam) {
                $modelParameter->insert([
                    'id_permintaan_mb_item' => $idMbItem,
                    'id_parameter'          => (int) $idParam,
                ]);
            }
        }
    }

    private function deleteItemsAndParameters(
        int $idPermintaanLab,
        \App\Features\Laboratorium\PermintaanLabMbItem\PermintaanLabMbItemModel $modelItem,
        \App\Features\Laboratorium\PermintaanLabMbParameter\PermintaanLabMbParameterModel $modelParameter,
    ): void {
        $oldItems = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_mb_item')
            ->select('id_permintaan_mb_item')
            ->where('id_permintaan_lab', $idPermintaanLab)
            ->get()
            ->getResultArray();

        $oldItemIds = array_column($oldItems, 'id_permintaan_mb_item');

        if (!empty($oldItemIds)) {
            $modelParameter->whereIn('id_permintaan_mb_item', $oldItemIds)->delete();
        }

        $modelItem->where('id_permintaan_lab', $idPermintaanLab)->delete();
    }

    private function buildHeaderData(array $rawPost, bool $withStatus = false): array
    {
        return array_merge(
            [
                'no_permintaan'      => $rawPost['no_permintaan'] ?? '',
                'nomor_reg'          => $rawPost['nomor_reg'] ?? '',
                'id_kategori_lab'    => ID_KATEGORI_MB,
                'id_dokter_perujuk'  => trim($rawPost['id_dokter_perujuk'] ?? ''),
                'tgl_permintaan'     => $rawPost['tgl_permintaan'] ?? date('Y-m-d H:i:s'),
                'indikasi_klinis'    => $rawPost['indikasi_klinis'] ?? '',
                'informasi_tambahan' => $rawPost['informasi_tambahan'] ?? '',
            ],
            $withStatus ? ['id_status_permintaan' => 1] : [],
        );
    }

    // Centralize query for index() and list()
    private function fetchPermintaanLabHeaders(null|int $idStatus = null): array
    {
        $builder = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_header h')
            ->select([
                'h.id_permintaan',
                'h.no_permintaan',
                'h.nomor_reg',
                'h.tgl_permintaan',
                'h.id_status_permintaan',
                'p.nomor_rm',
                'o.nama',
                'o.tanggal_lahir',
                'd.kode_dokter',
                'od.nama AS nama_dokter',
                's.nama_status',
            ])
            ->join('registrasi.registrasi r', 'r.nomor_reg  = h.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien  = r.id_pasien', 'left')
            ->join('person.orang o', 'o.id_orang   = p.id_orang', 'left')
            ->join('role.dokter d', 'd.id_dokter  = h.id_dokter_perujuk', 'left')
            ->join('person.orang od', 'od.id_orang  = d.id_orang', 'left')
            ->join('laboratorium.ref_status_permintaan s', 's.id_status  = h.id_status_permintaan', 'left')
            ->where('h.id_kategori_lab', ID_KATEGORI_MB);

        if ($idStatus !== null) {
            $builder->where('h.id_status_permintaan', $idStatus);
        }

        return $builder->orderBy('h.tgl_permintaan', 'DESC')->get()->getResultArray();
    }

    // Jumlah data per status (untuk filter)
    private function fetchStatusFilters(): array
    {
        $countMap = array_column(
            $this->model
                ->db
                ->table('laboratorium.permintaan_lab_header')
                ->select('id_status_permintaan, COUNT(*) AS jumlah')
                ->where('id_kategori_lab', ID_KATEGORI_MB)
                ->groupBy('id_status_permintaan')
                ->get()
                ->getResultArray(),
            'jumlah',
            'id_status_permintaan',
        );

        $filters = [];
        foreach ($this->model
            ->db
            ->table('laboratorium.ref_status_permintaan')
            ->select('id_status, nama_status')
            ->orderBy('id_status')
            ->get()
            ->getResultArray() as $row) {
            $filters[(string) $row['id_status']] =
                $row['nama_status'] . ' (' . ($countMap[$row['id_status']] ?? 0) . ')';
        }

        return $filters;
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    #[\Override]
    public function create_page(): string
    {
        return view('admin/laboratorium/tambah_permintaan_mb', [
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
    public function update_page(int|string $id): string
    {
        $idPermintaanLab = (int) $id;

        $baris = $this->model
            ->db
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

        return view('admin/laboratorium/tambah_permintaan_mb', [
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
        $rawPost      = $this->request->getPost();
        $idItems      = $this->request->getPost('id_item') ?? [];
        $idParameters = $this->request->getPost('id_parameter') ?? [];

        if (empty($idItems)) {
            session()->setFlashdata('error', 'Pilih minimal satu item pemeriksaan.');
            return redirect()->back()->withInput();
        }

        $noPermintaan = !empty($rawPost['no_permintaan'])
            ? $rawPost['no_permintaan']
            : $this->generateNomorPermintaan();

        $header = array_merge($this->buildHeaderData($rawPost, true), [
            'no_permintaan' => $noPermintaan,
        ]);

        $modelHeader    = new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel();
        $modelItem      = new \App\Features\Laboratorium\PermintaanLabMbItem\PermintaanLabMbItemModel();
        $modelParameter = new \App\Features\Laboratorium\PermintaanLabMbParameter\PermintaanLabMbParameterModel();

        $this->model->db->transStart();

        try {
            $modelHeader->insert($header);
            $this->insertItemsAndParameters(
                $modelHeader->getInsertID(),
                $idItems,
                $idParameters,
                $modelItem,
                $modelParameter,
            );

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal menyimpan permintaan lab MB.');
            }

            session()->setFlashdata('success', 'Permintaan lab MB berhasil disimpan.');
            return redirect()->to($this->get_uri_path() . '/data');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }
        $idPermintaanLab = (int) $id;

        if (!$idPermintaanLab) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return redirect()->back();
        }

        if (!$this->isEditable($idPermintaanLab)) {
            session()->setFlashdata('error', 'Permintaan tidak dapat diubah karena status sudah Selesai atau Batal.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idItems      = $this->request->getPost('id_item') ?? [];
        $idParameters = $this->request->getPost('id_parameter') ?? [];

        if (empty($idItems)) {
            session()->setFlashdata('error', 'Pilih minimal satu item pemeriksaan.');
            return redirect()->back()->withInput();
        }

        $modelHeader    = new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel();
        $modelItem      = new \App\Features\Laboratorium\PermintaanLabMbItem\PermintaanLabMbItemModel();
        $modelParameter = new \App\Features\Laboratorium\PermintaanLabMbParameter\PermintaanLabMbParameterModel();

        $this->model->db->transStart();

        try {
            $modelHeader->update($idPermintaanLab, $this->buildHeaderData($this->request->getPost()));

            $this->deleteItemsAndParameters($idPermintaanLab, $modelItem, $modelParameter);
            $this->insertItemsAndParameters($idPermintaanLab, $idItems, $idParameters, $modelItem, $modelParameter);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal memperbarui permintaan lab MB.');
            }

            session()->setFlashdata('success', 'Permintaan lab MB berhasil diperbarui.');
            return redirect()->to($this->get_uri_path() . '/data');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }

    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        $idPermintaanLab = (int) $id;
        if (!$idPermintaanLab) {
            return $this->home();
        }

        $modelHeader    = new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel();
        $modelItem      = new \App\Features\Laboratorium\PermintaanLabMbItem\PermintaanLabMbItemModel();
        $modelParameter = new \App\Features\Laboratorium\PermintaanLabMbParameter\PermintaanLabMbParameterModel();

        $this->model->db->transStart();

        try {
            $this->deleteItemsAndParameters($idPermintaanLab, $modelItem, $modelParameter);

            $modelHeader->delete($idPermintaanLab);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal menghapus permintaan lab MB.');
            }

            session()->setFlashdata('success', 'Permintaan lab MB berhasil dihapus.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
        }

        return $this->home();
    }

    // -------------------------------------------------------------------------
    // Sampel
    // -------------------------------------------------------------------------

    public function sampel(int|string $id): RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        $idPermintaanLab = (int) $id;
        if (!$idPermintaanLab) {
            return $this->home();
        }

        try {
            (new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel())->update($idPermintaanLab, [
                'tgl_jam_sampel'       => $this->request->getPost('tgl_jam_sampel')
                    ? $this->request->getPost('tgl_jam_sampel')
                    : date('Y-m-d H:i:s'),
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
    public function index(): string
    {
        $activeFilter = $this->request->getGet('filter') ? $this->request->getGet('filter') : null;
        $rows         = $this->fetchPermintaanLabHeaders($activeFilter !== null ? (int) $activeFilter : null);

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
            'judul'         => $this->title,
            'breadcrumbs'   => $this->breadcrumbs,
            'meta_data'     => ['page' => 1, 'size' => count($rows), 'total' => 1],
            'modul_path'    => $this->get_uri_path(),
            'kolom_id'      => 'id_permintaan',
            'konfig'        => $konfig,
            'aksi'          => $this->actions,
            'tabel'         => $rows,
            'row_alert'     => [],
            'child_link'    => null,
            'query_string'  => '',
            'filters'       => $this->fetchStatusFilters(),
            'active_filter' => $activeFilter,
        ]);
    }

    // -------------------------------------------------------------------------
    // Cetak
    // -------------------------------------------------------------------------

    #[\Override]
    public function print(int|string $id): string
    {
        $idPermintaanLab = (int) $id;

        $header = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_header plh')
            ->select([
                'plh.id_permintaan',
                'plh.no_permintaan',
                'plh.nomor_reg',
                'plh.tgl_permintaan',
                'plh.indikasi_klinis',
                'plh.informasi_tambahan',
                'p.nomor_rm',
                'o.nama AS nama_pasien',
                'd.kode_dokter',
                'od.nama AS nama_dokter',
            ])
            ->join('registrasi.registrasi r', 'r.nomor_reg       = plh.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien       = r.id_pasien', 'left')
            ->join('person.orang o', 'o.id_orang        = p.id_orang', 'left')
            ->join('role.dokter d', 'd.id_dokter       = plh.id_dokter_perujuk', 'left')
            ->join('person.orang od', 'od.id_orang       = d.id_orang', 'left')
            ->where('plh.id_permintaan', $idPermintaanLab)
            ->get()
            ->getRowArray() ?? [];

        if (empty($header)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        return view('Views/components/cetak/cetak_permintaan_lab_mb', [
            'header' => $header,
            'items'  => $this->fetchItemTerpilih($idPermintaanLab),
        ]);
    }

    // -------------------------------------------------------------------------
    // List
    // -------------------------------------------------------------------------

    public function list(): ResponseInterface
    {
        $idPermintaan = (int) ($this->request->getGet('id_permintaan') ?? 0);

        if ($idPermintaan > 0) {
            return $this->response->setJSON(['data' => $this->fetchItemTerpilih($idPermintaan)]);
        }

        $rows = $this->fetchPermintaanLabHeaders();

        return $this->response->setJSON(['data' => $rows]);
    }
}
