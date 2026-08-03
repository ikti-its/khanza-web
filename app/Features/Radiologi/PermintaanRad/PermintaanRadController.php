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

    private function generateNomorPermintaan(): string
    {
        helper('autonomor');

        $lastNo = $this->model
            ->db
            ->table('radiologi.permintaan_rad')
            ->select('no_permintaan')
            ->like('no_permintaan', 'RAD' . date('Ymd'), 'after')
            ->orderBy('no_permintaan', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return generateNextNoPermintaanRad($lastNo['no_permintaan'] ?? null);
    }

    private function getKonfig(): array
    {
        return array_values(array_filter(
            $this->get_fields_with_options(false, true),
            static fn($f) => $f[2] !== 'nomor_reg',
        ));
    }

    private function fetchDetailRegistrasi(string $nomorReg): array
    {
        return $this->model
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
            ->where('r.nomor_reg', $nomorReg)
            ->get()
            ->getRowArray() ?? [];
    }

    private function fetchItemTerpilih(int $idPermintaan): array
    {
        return $this->model
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
            ->where('pri.id_permintaan', $idPermintaan)
            ->get()
            ->getResultArray();
    }

    private function buildHeaderData(array $rawPost, bool $isCreate = false): array
    {
        $noPermintaan = '';
        if (!empty($rawPost['no_permintaan'])) {
            $noPermintaan = $rawPost['no_permintaan'];
        }
        if (empty($rawPost['no_permintaan']) && $isCreate) {
            $noPermintaan = $this->generateNomorPermintaan();
        }
        $tglPermintaan = !empty($rawPost['tgl_jam_permintaan']) ? $rawPost['tgl_jam_permintaan'] : date('Y-m-d H:i:s');

        $data = [
            'no_permintaan'      => $noPermintaan,
            'nomor_reg'          => $rawPost['nomor_reg'] ?? '',
            'tgl_jam_permintaan' => $tglPermintaan,
            'informasi_tambahan' => $rawPost['informasi_tambahan'] ?? '',
            'indikasi_klinis'    => $rawPost['indikasi_klinis'] ?? '',
        ];

        if ($isCreate) {
            $data['id_status_permintaan'] = 1;
        }

        return $data;
    }

    private function hasHasil(int $idPermintaan): bool
    {
        return (
            $this->model->db->table('radiologi.hasil_rad')->where('id_permintaan_rad', $idPermintaan)->countAllResults()
            > 0
        );
    }

    private function insertItems(int $idPermintaan, array $idItems, array $bacaSajaMap): void
    {
        if (empty($idItems)) {
            return;
        }

        $data = array_map(static fn($idItem) => [
            'id_permintaan' => $idPermintaan,
            'id_item'       => (int) $idItem,
            'is_baca_saja'  => ($bacaSajaMap[(string) $idItem] ?? '0') === '1',
        ], $idItems);

        (new \App\Features\Radiologi\PermintaanRadItem\PermintaanRadItemModel())->insertBatch($data);
    }

    // ──────────────────────────────────────────────────────────
    // INDEX — filter status + jumlah data
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function index(): string|RedirectResponse
    {
        $statuses = $this->model
            ->db
            ->table('radiologi.ref_status_permintaan_rad s')
            ->select('s.id_status, s.nama_status, COUNT(pr.id_permintaan) AS jumlah')
            ->join('radiologi.permintaan_rad pr', 'pr.id_status_permintaan = s.id_status', 'left')
            ->groupBy('s.id_status, s.nama_status')
            ->orderBy('s.id_status')
            ->get()
            ->getResultArray();

        $this->filters = [];
        foreach ($statuses as $row) {
            $this->filters[(string) $row['id_status']] = $row['nama_status'] . ' (' . $row['jumlah'] . ')';
        }

        $this->active_filter = $this->request->getGet('filter') ? $this->request->getGet('filter') : null;
        if ($this->active_filter !== null) {
            $this->model->set_filter('id_status_permintaan', (int) $this->active_filter);
        }

        return parent::index();
    }

    // ──────────────────────────────────────────────────────────
    // HALAMAN TAMBAH
    // ──────────────────────────────────────────────────────────

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

    #[\Override]
    public function create(): string|RedirectResponse
    {
        $idItems     = $this->request->getPost('id_item') ?? [];
        $bacaSajaMap = $this->request->getPost('is_baca_saja') ?? [];
        if (empty($idItems)) {
            session()->setFlashdata('error', 'Pilih minimal satu item radiologi.');
            return redirect()->back()->withInput();
        }

        $data = $this->buildHeaderData($this->request->getPost(), true);

        $this->model->db->transStart();

        try {
            $this->model->insert($data);
            $this->insertItems((int) $this->model->getInsertID(), $idItems, $bacaSajaMap);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
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

    #[\Override]
    public function update_page(int|string $id): string
    {
        $baris = $this->model->find($id);

        if (empty($baris)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        if (!empty($baris['nomor_reg'])) {
            $baris = array_merge($baris, $this->fetchDetailRegistrasi($baris['nomor_reg']));
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

        $idItems     = $this->request->getPost('id_item') ?? [];
        $bacaSajaMap = $this->request->getPost('is_baca_saja') ?? [];
        if (empty($idItems)) {
            session()->setFlashdata('error', 'Pilih minimal satu item radiologi.');
            return redirect()->back()->withInput();
        }

        $data = $this->buildHeaderData($this->request->getPost(), false);

        $this->model->db->transStart();

        try {
            $this->model->update($id, $data);

            $modelItem = new \App\Features\Radiologi\PermintaanRadItem\PermintaanRadItemModel();
            $modelItem->where('id_permintaan', $id)->delete();
            $this->insertItems((int) $id, $idItems, $bacaSajaMap);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
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

            if (!$this->model->db->transStatus() ) {
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

    public function sampel(int|string $id): RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        try {
            $this->model->update($id, [
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

    // ──────────────────────────────────────────────────────────
    // CETAK
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function print(int|string $id): string
    {
        $permintaan = $this->model->find($id);

        if (empty($permintaan)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        $detailRegistrasi = !empty($permintaan['nomor_reg'])
            ? $this->fetchDetailRegistrasi($permintaan['nomor_reg'])
            : [];

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

        if (($status = $this->request->getGet('status')) !== null) {
            $builder->where('pr.id_status_permintaan', (int) $status);
        }

        return $this->response->setJSON(['data' => $builder->get()->getResultArray()]);
    }
}
