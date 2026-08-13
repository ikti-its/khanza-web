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

    /**
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function generateNomorPermintaan(): string
    {
        helper('autonomor');

        $builder = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_header')
            ->select('no_permintaan')
            ->like('no_permintaan', 'MB' . date('Ymd'), 'after')
            ->orderBy('no_permintaan', 'DESC')
            ->limit(1);

        $lastNo = $this->model->guarded_get($builder, 'generateNomorPermintaan')->getRowArray();

        return generateNextNoPermintaanMb(
            is_string($lastNo['no_permintaan'] ?? null) ? $lastNo['no_permintaan'] : null,
        );
    }

    /** @return list<array<int|string, mixed>> */
    private function filterKonfig(): array
    {
        /** @var list<array<int|string, mixed>> */
        return array_values(array_filter(
            (new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderController())->get_fields_with_options(
                false,
                true,
            ),
            fn(array $f) => !in_array(
                $f[2] ?? null,
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

    /**
     * @return array<string, mixed>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchRegistrasi(string $nomorReg): array
    {
        $builder = $this->model
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
            ->where('r.nomor_reg', $nomorReg);

        /** @var array<string, mixed> */
        return $this->model->guarded_get($builder, 'fetchRegistrasi')->getRowArray() ?? [];
    }

    /**
     * @return array<string, mixed>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchDokter(string|int $idDokter): array
    {
        $builder = $this->model
            ->db
            ->table('role.dokter d')
            ->select(['d.kode_dokter', 'o.nama AS nama_dokter'])
            ->join('person.orang o', 'o.id_orang = d.id_orang')
            ->where('d.id_dokter', (int) $idDokter);

        /** @var array<string, mixed> */
        return $this->model->guarded_get($builder, 'fetchDokter')->getRowArray() ?? [];
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchItemTerpilih(int $idPermintaanLab): array
    {
        $builderItems = $this->model
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
            ->orderBy('mbi.id_permintaan_mb_item', 'ASC');

        /** @var list<array<string, mixed>> $items */
        $items = $this->model->guarded_get($builderItems, 'fetchItemTerpilih')->getResultArray();

        if ($items === []) {
            return [];
        }

        // Mengambil semua parameter sekaligus menggunakan whereIn
        $itemIds = array_column($items, 'id_permintaan_mb_item');

        $builderParams = $this->model
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
            ->orderBy('mbp.id_parameter', 'ASC');

        /** @var list<array<string, mixed>> $parameters */
        $parameters = $this->model->guarded_get($builderParams, 'fetchItemTerpilih')->getResultArray();

        // Kelompokkan parameter berdasarkan id_permintaan_mb_item
        $groupedParams = [];
        foreach ($parameters as $param) {
            $idItem                   = (string) ($param['id_permintaan_mb_item'] ?? '');
            $groupedParams[$idItem][] = $param;
        }

        foreach ($items as &$item) {
            $item['parameter'] = $groupedParams[(string) ($item['id_permintaan_mb_item'] ?? '')] ?? [];
        }
        unset($item);

        return $items;
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function isEditable(int $idPermintaanLab): bool
    {
        $builder = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_header')
            ->select('id_status_permintaan')
            ->where('id_permintaan', $idPermintaanLab);

        $row = $this->model->guarded_get($builder, 'isEditable')->getRowArray();

        return in_array((int) ($row['id_status_permintaan'] ?? 0), [1, 2], true);
    }

    /**
     * @param list<mixed>             $idItems
     * @param array<array-key, mixed> $idParameters
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \ReflectionException
     */
    private function insertItemsAndParameters(
        int $idPermintaanLab,
        array $idItems,
        array $idParameters,
        \App\Features\Laboratorium\PermintaanLabMbItem\PermintaanLabMbItemModel $modelItem,
        \App\Features\Laboratorium\PermintaanLabMbParameter\PermintaanLabMbParameterModel $modelParameter,
    ): void {
        /** @mago-expect analysis:mixed-assignment */
        foreach ($idItems as $idItem) {
            $modelItem->insert([
                'id_permintaan_lab'   => $idPermintaanLab,
                'id_item_pemeriksaan' => (int) $idItem,
            ]);
            $idMbItem = $modelItem->getInsertID();

            /** @var list<mixed> $paramsForItem */
            $paramsForItem = is_array($idParameters[(string) $idItem] ?? null) ? $idParameters[(string) $idItem] : [];

            /** @mago-expect analysis:mixed-assignment */
            foreach ($paramsForItem as $idParam) {
                $modelParameter->insert([
                    'id_permintaan_mb_item' => $idMbItem,
                    'id_parameter'          => (int) $idParam,
                ]);
            }
        }
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function deleteItemsAndParameters(
        int $idPermintaanLab,
        \App\Features\Laboratorium\PermintaanLabMbItem\PermintaanLabMbItemModel $modelItem,
        \App\Features\Laboratorium\PermintaanLabMbParameter\PermintaanLabMbParameterModel $modelParameter,
    ): void {
        $builder = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_mb_item')
            ->select('id_permintaan_mb_item')
            ->where('id_permintaan_lab', $idPermintaanLab);

        /** @var list<array<string, mixed>> $oldItems */
        $oldItems   = $this->model->guarded_get($builder, 'deleteItemsAndParameters')->getResultArray();
        $oldItemIds = array_column($oldItems, 'id_permintaan_mb_item');

        if ($oldItemIds !== []) {
            $modelParameter->whereIn('id_permintaan_mb_item', $oldItemIds)->delete();
        }

        $modelItem->where('id_permintaan_lab', $idPermintaanLab)->delete();
    }

    /**
     * @param array<string, mixed> $rawPost
     * @return array<string, mixed>
     */
    private function buildHeaderData(array $rawPost, bool $withStatus = false): array
    {
        return array_merge(
            [
                'no_permintaan'      => $rawPost['no_permintaan'] ?? '',
                'nomor_reg'          => $rawPost['nomor_reg'] ?? '',
                'id_kategori_lab'    => ID_KATEGORI_MB,
                'id_dokter_perujuk'  => trim((string) ($rawPost['id_dokter_perujuk'] ?? '')),
                'tgl_permintaan'     => $rawPost['tgl_permintaan'] ?? date('Y-m-d H:i:s'),
                'indikasi_klinis'    => $rawPost['indikasi_klinis'] ?? '',
                'informasi_tambahan' => $rawPost['informasi_tambahan'] ?? '',
            ],
            $withStatus ? ['id_status_permintaan' => 1] : [],
        );
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
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

        /** @var list<array<string, mixed>> */
        return $this->model
            ->guarded_get($builder->orderBy('h.tgl_permintaan', 'DESC'), 'fetchPermintaanLabHeaders')
            ->getResultArray();
    }

    /**
     * @return array<string, string>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchStatusFilters(): array
    {
        $builderCount = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_header')
            ->select('id_status_permintaan, COUNT(*) AS jumlah')
            ->where('id_kategori_lab', ID_KATEGORI_MB)
            ->groupBy('id_status_permintaan');

        /** @var list<array<string, mixed>> $countRows */
        $countRows = $this->model->guarded_get($builderCount, 'fetchStatusFilters')->getResultArray();

        /** @var array<int, mixed> $countMap */
        $countMap = array_column($countRows, 'jumlah', 'id_status_permintaan');

        $builderStatus = $this->model
            ->db
            ->table('laboratorium.ref_status_permintaan')
            ->select('id_status, nama_status')
            ->orderBy('id_status');

        /** @var list<array<string, mixed>> $statusRows */
        $statusRows = $this->model->guarded_get($builderStatus, 'fetchStatusFilters')->getResultArray();

        $filters = [];
        foreach ($statusRows as $row) {
            $idStatus                    = (int) ($row['id_status'] ?? 0);
            $filters[(string) $idStatus] = (string) ($row['nama_status'] ?? '')
            . ' ('
            . (string) ($countMap[$idStatus] ?? 0)
            . ')';
        }

        return $filters;
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    /**
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
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

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function update_page(int|string $id): string
    {
        $idPermintaanLab = (int) $id;

        $builder = $this->model
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
            ->where('h.id_permintaan', $idPermintaanLab);

        /** @var array<string, mixed> $baris */
        $baris = $this->model->guarded_get($builder, 'update_page')->getRowArray() ?? [];

        if (!empty($baris['nomor_reg'])) {
            $baris = array_merge($baris, $this->fetchRegistrasi((string) $baris['nomor_reg']));
        }

        if (!empty($baris['id_dokter_perujuk'])) {
            $baris = array_merge($baris, $this->fetchDokter((string) $baris['id_dokter_perujuk']));
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

    /**
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \ReflectionException
     */
    #[\Override]
    public function create(): string|RedirectResponse
    {
        /** @var array<string, mixed> $rawPost */
        $rawPost = $this->request->getPost();

        /** @mago-expect analysis:mixed-assignment */
        $idItemsRaw = $this->request->getPost('id_item');
        $idItems    = is_array($idItemsRaw) ? array_values($idItemsRaw) : [];

        /** @mago-expect analysis:mixed-assignment */
        $idParametersRaw = $this->request->getPost('id_parameter');
        $idParameters    = is_array($idParametersRaw) ? $idParametersRaw : [];

        if ($idItems === []) {
            session()->setFlashdata('error', 'Pilih minimal satu item pemeriksaan.');
            return redirect()->back()->withInput();
        }

        $noPermintaan = !empty($rawPost['no_permintaan'])
            ? (string) $rawPost['no_permintaan']
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
                (int) $modelHeader->getInsertID(),
                $idItems,
                $idParameters,
                $modelItem,
                $modelParameter,
            );

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
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

    /**
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \ReflectionException
     */
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

        /** @mago-expect analysis:mixed-assignment */
        $idItemsRaw = $this->request->getPost('id_item');
        $idItems    = is_array($idItemsRaw) ? array_values($idItemsRaw) : [];

        /** @mago-expect analysis:mixed-assignment */
        $idParametersRaw = $this->request->getPost('id_parameter');
        $idParameters    = is_array($idParametersRaw) ? $idParametersRaw : [];

        if ($idItems === []) {
            session()->setFlashdata('error', 'Pilih minimal satu item pemeriksaan.');
            return redirect()->back()->withInput();
        }

        /** @var array<string, mixed> $postData */
        $postData = $this->request->getPost();

        $modelHeader    = new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel();
        $modelItem      = new \App\Features\Laboratorium\PermintaanLabMbItem\PermintaanLabMbItemModel();
        $modelParameter = new \App\Features\Laboratorium\PermintaanLabMbParameter\PermintaanLabMbParameterModel();

        $this->model->db->transStart();

        try {
            $modelHeader->update($idPermintaanLab, $this->buildHeaderData($postData));

            $this->deleteItemsAndParameters($idPermintaanLab, $modelItem, $modelParameter);
            $this->insertItemsAndParameters($idPermintaanLab, $idItems, $idParameters, $modelItem, $modelParameter);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
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

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
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

            if (!$this->model->db->transStatus()) {
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

    /** @throws \ReflectionException */
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
            $tglJamSampel = (string) ($this->request->getPost('tgl_jam_sampel') ?? '');

            (new \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderModel())->update($idPermintaanLab, [
                'tgl_jam_sampel'       => $tglJamSampel !== '' && $tglJamSampel !== '0'
                    ? $tglJamSampel
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

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function index(): string
    {
        $filter       = (string) ($this->request->getGet('filter') ?? '');
        $activeFilter = $filter !== '' && $filter !== '0' ? $filter : null;
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

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function print(int|string $id): string
    {
        $idPermintaanLab = (int) $id;

        $builder = $this->model
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
            ->where('plh.id_permintaan', $idPermintaanLab);

        $header = $this->model->guarded_get($builder, 'print')->getRowArray() ?? [];

        if ($header === []) {
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

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
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
