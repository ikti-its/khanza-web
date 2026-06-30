<?php
declare(strict_types=1);

namespace App\Features\Operasi\PermintaanOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PermintaanOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanOperasiModel(),
            [
                ['Operasi',            'operasi'],
                ['Permintaan Operasi', 'permintaan_operasi'],
            ],
            'Permintaan Operasi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_permintaan', 'ID Permintaan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'nomor_reg',     'No. Registrasi'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'id_dokter',     'Dokter Peminta'],
                [SHOW,       REQUIRED, I::SELECT, 'id_tindakan',   'Tindakan Operasi'],
                [SHOW,       REQUIRED, I::DATE,   'tanggal_minta', 'Tanggal Minta'],
                [SHOW,       OPTIONAL, I::BOOL,   'is_cito',       'CITO'],
            ],
        );
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    private function filterKonfig(): array
    {
        return array_values(array_filter(
            $this->get_fields_with_options(false, true),
            fn($f) => !in_array($f[2], [
                'id_permintaan',
                'nomor_reg',
                'id_dokter',
                'tanggal_minta',
                'is_cito',
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
                'o.nama AS nama_pasien',
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

    private function fetchNamaRole(string $tabel, string $idKolom, int $idValue): array
    {
        $row = $this->model->db
            ->table("role.{$tabel} t")
            ->select(['t.id_dokter', 't.kode_dokter', 'o.nama AS nama_dokter'])
            ->join('person.orang o', 'o.id_orang = t.id_orang', 'left')
            ->where("t.{$idKolom}", $idValue)
            ->get()->getRowArray() ?? [];
        return $row;
    }

    private function fetchTindakan(int $idTindakan): array
    {
        return $this->model->db
            ->table('operasi.ref_tindakan_operasi')
            ->select(['id_tindakan', 'nama_tindakan'])
            ->where('id_tindakan', $idTindakan)
            ->get()
            ->getRowArray() ?? [];
    }

    private function buildHeaderData(array $rawPost, bool $isCreate = false): array
    {
        return [
            'nomor_reg'     => $rawPost['nomor_reg']     ?? '',
            'id_dokter'     => $rawPost['id_dokter']     ?? '',
            'id_tindakan'   => $rawPost['id_tindakan']   ?? '',
            'tanggal_minta' => $rawPost['tanggal_minta'] ?? ($isCreate ? date('Y-m-d H:i:s') : ''),
            'is_cito'       => $rawPost['is_cito']       ?? 0,
        ];
    }

    // -------------------------------------------------------------------------
    // Hooks
    // -------------------------------------------------------------------------

    #[\Override]
    protected function before_read(): void
    {
        $this->model
            ->set_order('is_cito', 'DESC');

    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    #[\Override]
    final public function create_page(): string
    {
        return view('admin/operasi/tambah_permintaan_operasi', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $this->filterKonfig(),
            'baris'       => [],
            'form_action' => '/submittambah',
        ]);
    }

    #[\Override]
    final public function update_page(int|string $id): string
    {
        $baris = $this->model->find($id) ?? [];

        if (!empty($baris['nomor_reg'])) {
            $baris = array_merge($baris, $this->fetchRegistrasi($baris['nomor_reg']));
        }

        if (!empty($baris['id_dokter'])) {
            $baris = array_merge($baris, $this->fetchNamaRole('dokter', 'id_dokter', (int) $baris['id_dokter']));
        }

        if (!empty($baris['id_tindakan'])) {
            $baris = array_merge($baris, $this->fetchTindakan((int) $baris['id_tindakan']));
        }

        return view('admin/operasi/tambah_permintaan_operasi', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $this->filterKonfig(),
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    // -------------------------------------------------------------------------
    // CRUD
    // -------------------------------------------------------------------------

    #[\Override]
    public function create(): string|RedirectResponse
    {
        $data = $this->buildHeaderData($this->request->getPost(), true);

        $this->model->db->transStart();

        try {
            $this->model->insert($data);
            $idPermintaan = $this->model->getInsertID();

            $jadwalModel = new \App\Features\Operasi\JadwalOperasi\JadwalOperasiModel();
            $jadwalModel->insert([
                'id_permintaan' => $idPermintaan,
                'id_status'     => 1,
            ]);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan permintaan operasi.');
            }

            session()->setFlashdata('success', 'Permintaan operasi berhasil disimpan.');
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

        $data = $this->buildHeaderData($this->request->getPost(), false);

        try {
            $this->model->update($id, $data);

            session()->setFlashdata('success', 'Permintaan operasi berhasil diperbarui.');
            return redirect()->to($this->get_uri_path() . '/data');

        } catch (\Exception $e) {
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }

    #[\Override]
    final public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();

        $this->model->db->transStart();

        try {
            $jadwalModel = new \App\Features\Operasi\JadwalOperasi\JadwalOperasiModel();
            $jadwalModel->where('id_permintaan', $id)->delete();

            $this->model->delete($id);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus permintaan operasi.');
            }

            session()->setFlashdata('success', 'Permintaan operasi berhasil dihapus.');

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
        }

        return $this->home();
    }

}
