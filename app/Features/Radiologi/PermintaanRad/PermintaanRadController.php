<?php
declare(strict_types=1);

namespace App\Features\Radiologi\PermintaanRad;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

final class PermintaanRadController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanRadModel(),
            [
                ['Radiologi',            'radiologi'],
                ['Permintaan Radiologi', 'permintaan_rad'],
            ],
            'Permintaan Radiologi',
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
                [HIDE,       OPTIONAL, I::INDEX,  'id_permintaan',        'ID Permintaan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'no_permintaan',        'No. Permintaan'],
                [SHOW,       REQUIRED, I::TEXT,   'nomor_reg',            'Nomor Registrasi'],
                [SHOW,       REQUIRED, I::DTIME,  'tgl_jam_permintaan',   'Tanggal Permintaan'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'informasi_tambahan',   'Informasi Tambahan'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'indikasi_klinis',      'Indikasi Klinis'],
                [TABLE_ONLY, OPTIONAL, I::SELECT, 'id_status_permintaan', 'Status Permintaan'],
                [TABLE_ONLY, OPTIONAL, I::DTIME,  'tgl_jam_sampel',       'Waktu Sampel'],
            ],
        );
    }

    // ──────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────

    /**
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function generateNomorPermintaan(): string
    {
        helper('autonomor');

        $builder = $this->model
            ->db
            ->table('radiologi.permintaan_rad')
            ->select('no_permintaan')
            ->like('no_permintaan', 'RAD' . date('Ymd'), 'after')
            ->orderBy('no_permintaan', 'DESC')
            ->limit(1);

        $lastNo = $this->model->guarded_get($builder, 'generateNomorPermintaan')->getRowArray();

        return generateNextNoPermintaanRad(
            is_string($lastNo['no_permintaan'] ?? null) ? $lastNo['no_permintaan'] : null,
        );
    }

    /** @return list<array<int|string, mixed>> */
    private function getKonfig(): array
    {
        /** @var list<array<int|string, mixed>> */
        return array_values(array_filter(
            $this->get_fields_with_options(false, true),
            static fn(array $f) => ($f[2] ?? null) !== 'nomor_reg',
        ));
    }

    /**
     * @return array<string, mixed>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchDetailRegistrasi(string $nomorReg): array
    {
        $builder = $this->model
            ->db
            ->table('registrasi.registrasi r')
            ->select([
                'r.nomor_reg',
                'p.nomor_rm',
                'o.nama',
                'd.kode_dokter AS kode_dokter_perujuk',
                'od.nama AS nama_dokter',
            ])
            ->join('role.pasien p', 'p.id_pasien = r.id_pasien', 'left')
            ->join('person.orang o', 'o.id_orang  = p.id_orang', 'left')
            ->join('role.dokter d', 'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang', 'left')
            ->where('r.nomor_reg', $nomorReg);

        /** @var array<string, mixed> */
        return $this->model->guarded_get($builder, 'fetchDetailRegistrasi')->getRowArray() ?? [];
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchItemTerpilih(int $idPermintaan): array
    {
        $builder = $this->model
            ->db
            ->table('radiologi.permintaan_rad_item pri')
            ->select([
                'pri.id_item',
                'pri.is_baca_saja',
                'r.kode_periksa',
                'r.nama_pemeriksaan',
                'r.tarif_dasar',
                'r.tarif_baca',
            ])
            ->join('radiologi.ref_item_rad r', 'r.id_item = pri.id_item')
            ->where('pri.id_permintaan', $idPermintaan);

        /** @var list<array<string, mixed>> */
        return $this->model->guarded_get($builder, 'fetchItemTerpilih')->getResultArray();
    }

    /**
     * @param array<string, mixed> $rawPost
     * @return array<string, mixed>
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function buildHeaderDataForCreate(array $rawPost): array
    {
        $noPermintaan = (string) ($rawPost['no_permintaan'] ?? '');
        if ($noPermintaan === '') {
            $noPermintaan = $this->generateNomorPermintaan();
        }

        return $this->buildHeaderData($rawPost, $noPermintaan) + ['id_status_permintaan' => 1];
    }

    /**
     * @param array<string, mixed> $rawPost
     * @return array<string, mixed>
     */
    private function buildHeaderDataForUpdate(array $rawPost): array
    {
        return $this->buildHeaderData($rawPost, (string) ($rawPost['no_permintaan'] ?? ''));
    }

    /**
     * @param array<string, mixed> $rawPost
     * @return array<string, mixed>
     */
    private function buildHeaderData(array $rawPost, string $noPermintaan): array
    {
        $tglPermintaan = (string) ($rawPost['tgl_jam_permintaan'] ?? '');
        if ($tglPermintaan === '') {
            $tglPermintaan = date('Y-m-d H:i:s');
        }

        return [
            'no_permintaan'      => $noPermintaan,
            'nomor_reg'          => $rawPost['nomor_reg'] ?? '',
            'tgl_jam_permintaan' => $tglPermintaan,
            'informasi_tambahan' => $rawPost['informasi_tambahan'] ?? '',
            'indikasi_klinis'    => $rawPost['indikasi_klinis'] ?? '',
        ];
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function hasHasil(int $idPermintaan): bool
    {
        return (
            $this->model->db->table('radiologi.hasil_rad')->where('id_permintaan_rad', $idPermintaan)->countAllResults()
            > 0
        );
    }

    /**
     * @param list<mixed> $idItems
     * @param array<array-key, mixed> $bacaSajaMap
     * @throws \ReflectionException
     */
    private function insertItems(int $idPermintaan, array $idItems, array $bacaSajaMap): void
    {
        if ($idItems === []) {
            return;
        }

        $data = array_values(array_map(static fn(mixed $idItem) => [
            'id_permintaan' => $idPermintaan,
            'id_item'       => (int) $idItem,
            'is_baca_saja'  => ($bacaSajaMap[(string) $idItem] ?? '0') === '1',
        ], $idItems));

        (new \App\Features\Radiologi\PermintaanRadItem\PermintaanRadItemModel())->insertBatch($data);
    }

    // ──────────────────────────────────────────────────────────
    // INDEX — filter status + jumlah data
    // ──────────────────────────────────────────────────────────

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function index(): string|RedirectResponse
    {
        $builder = $this->model
            ->db
            ->table('radiologi.ref_status_permintaan_rad s')
            ->select('s.id_status, s.nama_status, COUNT(pr.id_permintaan) AS jumlah')
            ->join('radiologi.permintaan_rad pr', 'pr.id_status_permintaan = s.id_status', 'left')
            ->groupBy('s.id_status, s.nama_status')
            ->orderBy('s.id_status');

        /** @var list<array<string, mixed>> $statuses */
        $statuses = $this->model->guarded_get($builder, 'index')->getResultArray();

        $this->filters = [];
        foreach ($statuses as $row) {
            $this->filters[(string) ($row['id_status'] ?? '')] =
                (string) ($row['nama_status'] ?? '') . ' (' . (string) ($row['jumlah'] ?? 0) . ')';
        }

        $filter              = (string) ($this->request->getGet('filter') ?? '');
        $this->active_filter = $filter !== '' ? $filter : null;
        if ($this->active_filter !== null) {
            $this->model->set_filter('id_status_permintaan', (int) $this->active_filter);
        }

        return parent::index();
    }

    // ──────────────────────────────────────────────────────────
    // HALAMAN TAMBAH
    // ──────────────────────────────────────────────────────────

    /**
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    #[\Override]
    public function create_page(): string
    {
        return view('admin/radiologi/tambah_permintaan_rad', [
            'judul'         => 'Tambah ' . $this->title,
            'breadcrumbs'   => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'    => $this->get_uri_path(),
            'kolom_id'      => $this->model->primaryKey,
            'konfig'        => $this->getKonfig(),
            'baris'         => [],
            'form_action'   => '/submittambah',
            'no_permintaan' => $this->generateNomorPermintaan(),
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // PROSES SIMPAN
    // ──────────────────────────────────────────────────────────

    /**
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    #[\Override]
    public function create(): string|RedirectResponse
    {
        /** @mago-expect analysis:mixed-assignment */
        $idItemsRaw = $this->request->getPost('id_item');
        $idItems    = is_array($idItemsRaw) ? array_values($idItemsRaw) : [];

        /** @mago-expect analysis:mixed-assignment */
        $bacaSajaMapRaw = $this->request->getPost('is_baca_saja');
        $bacaSajaMap    = is_array($bacaSajaMapRaw) ? $bacaSajaMapRaw : [];

        if ($idItems === []) {
            session()->setFlashdata('error', 'Pilih minimal satu item radiologi.');
            return redirect()->back()->withInput();
        }

        /** @var array<string, mixed> $postData */
        $postData = $this->request->getPost();
        $data     = $this->buildHeaderDataForCreate($postData);

        $this->model->db->transStart();

        try {
            $this->model->insert($data);
            $this->insertItems((int) $this->model->getInsertID(), $idItems, $bacaSajaMap);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal menyimpan permintaan radiologi.');
            }

            session()->setFlashdata('success', 'Permintaan radiologi berhasil disimpan.');
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

    // ──────────────────────────────────────────────────────────
    // HALAMAN UBAH
    // ──────────────────────────────────────────────────────────

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function update_page(int|string $id): string|RedirectResponse
    {
        $baris = $this->model->find($id);

        if (!is_array($baris)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        $nomorReg = (string) ($baris['nomor_reg'] ?? '');
        if ($nomorReg !== '') {
            $baris = array_merge($baris, $this->fetchDetailRegistrasi($nomorReg));
        }

        return view('admin/radiologi/tambah_permintaan_rad', [
            'judul'         => 'Ubah ' . $this->title,
            'breadcrumbs'   => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'    => $this->get_uri_path(),
            'kolom_id'      => $this->model->primaryKey,
            'konfig'        => $this->getKonfig(),
            'baris'         => $baris,
            'form_action'   => '/submitedit/' . $id,
            'no_permintaan' => $baris['no_permintaan'] ?? '',
            'item_terpilih' => $this->fetchItemTerpilih((int) $id),
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // PROSES UPDATE
    // ──────────────────────────────────────────────────────────

    /**
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     */
    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        if ($this->hasHasil((int) $id)) {
            session()->setFlashdata('error', 'Permintaan tidak dapat diubah karena hasil radiologi sudah dicatat.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        /** @mago-expect analysis:mixed-assignment */
        $idItemsRaw = $this->request->getPost('id_item');
        $idItems    = is_array($idItemsRaw) ? array_values($idItemsRaw) : [];

        /** @mago-expect analysis:mixed-assignment */
        $bacaSajaMapRaw = $this->request->getPost('is_baca_saja');
        $bacaSajaMap    = is_array($bacaSajaMapRaw) ? $bacaSajaMapRaw : [];

        if ($idItems === []) {
            session()->setFlashdata('error', 'Pilih minimal satu item radiologi.');
            return redirect()->back()->withInput();
        }

        /** @var array<string, mixed> $postData */
        $postData = $this->request->getPost();
        $data     = $this->buildHeaderDataForUpdate($postData);

        $this->model->db->transStart();

        try {
            $this->model->update($id, $data);

            $modelItem = new \App\Features\Radiologi\PermintaanRadItem\PermintaanRadItemModel();
            $modelItem->where('id_permintaan', $id)->delete();
            $this->insertItems((int) $id, $idItems, $bacaSajaMap);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal memperbarui permintaan radiologi.');
            }

            session()->setFlashdata('success', 'Permintaan radiologi berhasil diperbarui.');
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

    // ──────────────────────────────────────────────────────────
    // DELETE — cascade hapus item lalu header
    // ──────────────────────────────────────────────────────────

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        if ($this->hasHasil((int) $id)) {
            session()->setFlashdata('error', 'Permintaan tidak dapat dihapus karena hasil radiologi sudah dicatat.');
            return $this->home();
        }

        $this->model->db->transStart();

        try {
            (new \App\Features\Radiologi\PermintaanRadItem\PermintaanRadItemModel())
                ->where('id_permintaan', $id)
                ->delete();
            $this->model->delete($id);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal menghapus permintaan radiologi.');
            }

            session()->setFlashdata('success', 'Permintaan radiologi berhasil dihapus.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
        }

        return $this->home();
    }

    // ──────────────────────────────────────────────────────────
    // SAMPEL
    // ──────────────────────────────────────────────────────────

    /** @throws \ReflectionException */
    public function sampel(int|string $id): RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        try {
            $tglJamSampel = (string) ($this->request->getPost('tgl_jam_sampel') ?? '');

            $this->model->update($id, [
                'tgl_jam_sampel'       => $tglJamSampel !== '' ? $tglJamSampel : date('Y-m-d H:i:s'),
                'id_status_permintaan' => 2,
            ]);

            session()->setFlashdata('success', 'Waktu pengambilan sampel berhasil dicatat.');
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            session()->setFlashdata('error', $this->friendly_db_error($e));
        }

        return $this->home();
    }

    // ──────────────────────────────────────────────────────────
    // CETAK
    // ──────────────────────────────────────────────────────────

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function print(int|string $id): string|RedirectResponse
    {
        $permintaan = $this->model->find($id);

        if (!is_array($permintaan)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        $nomorReg         = (string) ($permintaan['nomor_reg'] ?? '');
        $detailRegistrasi = $nomorReg !== '' ? $this->fetchDetailRegistrasi($nomorReg) : [];

        $itemList = $this->fetchItemTerpilih((int) $id);

        return view('Views/components/cetak/cetak_permintaan_rad', [
            'permintaan'       => $permintaan,
            'detailRegistrasi' => $detailRegistrasi,
            'itemList'         => $itemList,
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // LIST
    // ──────────────────────────────────────────────────────────

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    public function list(): ResponseInterface
    {
        $builder = $this->model
            ->db
            ->table('radiologi.permintaan_rad pr')
            ->select([
                'pr.id_permintaan',
                'pr.no_permintaan',
                'pr.nomor_reg',
                'pr.tgl_jam_permintaan',
                'p.nomor_rm',
                'o.nama',
                's.nama_status',
                'r.id_dokter AS id_dokter_perujuk',
                'od.nama AS nama_dokter_perujuk',
            ])
            ->join('registrasi.registrasi r', 'r.nomor_reg = pr.nomor_reg')
            ->join('role.pasien p', 'p.id_pasien = r.id_pasien')
            ->join('person.orang o', 'o.id_orang  = p.id_orang')
            ->join('radiologi.ref_status_permintaan_rad s', 's.id_status = pr.id_status_permintaan', 'left')
            ->join('role.dokter d', 'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang', 'left')
            ->orderBy('pr.tgl_jam_permintaan', 'DESC');

        $status = (string) ($this->request->getGet('status') ?? '');
        if ($status !== '') {
            $builder->where('pr.id_status_permintaan', (int) $status);
        }

        $data = $this->model->guarded_get($builder, 'list')->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }
}
