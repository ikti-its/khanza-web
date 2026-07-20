<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPreOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class ChecklistPreOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ChecklistPreOperasiModel(),
            [
                ['Operasi',               'operasi'],
                ['Checklist Pre Operasi', 'checklist_pre_operasi'],
            ],
            'Checklist Pre Operasi',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,      OPTIONAL, I::INDEX,  'id_checklist',           'ID Checklist'],
                [SHOW,      REQUIRED, I::INDEX,  'id_jadwal',              'Jadwal Operasi'],
                [HIDE,      REQUIRED, I::INDEX,  'id_tindakan',            'Tindakan'],
                [HIDE,      REQUIRED, I::INDEX,  'id_sn_cn',               'SN/CN'],
                [SHOW,      REQUIRED, I::INDEX,  'id_dokter_bedah',        'Dokter Bedah'],
                [SHOW,      REQUIRED, I::INDEX,  'id_dokter_anestesi',     'Dokter Anestesi'],
                [HIDE,      REQUIRED, I::INDEX,  'id_petugas_ruangan',     'Petugas Ruangan'],
                [HIDE,      REQUIRED, I::INDEX,  'id_petugas_ok',          'Petugas OK'],
                [SHOW,      REQUIRED, I::DTIME,  'waktu_checklist',        'Waktu Checklist'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_identitas_sesuai',    'Identitas Sesuai'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_keadaan_umum',        'Keadaan Umum'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_penandaan_area',      'Penandaan Area'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_ijin_bedah',          'Ijin Bedah'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_ijin_anestesi',       'Ijin Anestesi'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_ijin_transfusi',      'Ijin Transfusi'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_persiapan_darah',     'Persiapan Darah'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'ket_persiapan_darah',    'Keterangan Persiapan Darah'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_perlengkapan_khusus', 'Perlengkapan Khusus'],
            ],
        );
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    #[\Override]
    protected function home(): RedirectResponse
    {
        $idJadwal = (int) ($this->request->getPost('id_jadwal') ?? 0);
        if ($idJadwal > 0) {
            return redirect()->to('/operasi/lembar-operasi/data?id_jadwal=' . $idJadwal);
        }
        return redirect()->to($this->get_uri_path() . '/data');
    }

    private function fetchJadwal(int $idJadwal): array
    {
        return $this->model
            ->db
            ->table('operasi.jadwal_operasi j')
            ->select([
                'j.id_jadwal',
                'j.id_dokter_bedah',
                'j.id_dokter_anestesi',
                'po.nomor_reg',
                'po.id_tindakan',
                'ti.nama_tindakan',
                'op.nama AS nama_pasien',
                'ob.nama AS nama_dokter_bedah',
                'oa.nama AS nama_dokter_anestesi',
            ])
            ->join('operasi.permintaan_operasi po', 'po.id_permintaan = j.id_permintaan', 'left')
            ->join('registrasi.registrasi r', 'r.nomor_reg      = po.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien      = r.id_pasien', 'left')
            ->join('person.orang op', 'op.id_orang      = p.id_orang', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan   = po.id_tindakan', 'left')
            ->join('role.dokter db', 'db.id_dokter     = j.id_dokter_bedah', 'left')
            ->join('person.orang ob', 'ob.id_orang      = db.id_orang', 'left')
            ->join('role.dokter da', 'da.id_dokter     = j.id_dokter_anestesi', 'left')
            ->join('person.orang oa', 'oa.id_orang      = da.id_orang', 'left')
            ->where('j.id_jadwal', $idJadwal)
            ->get()
            ->getRowArray() ?? [];
    }

    private function fetchOptions(): array
    {
        $db = $this->model->db;
        return [
            'keadaan_umum'    => $db
                ->table('operasi.ref_keadaan_umum')
                ->select('id_keadaan_umum, nama_keadaan')
                ->get()
                ->getResultArray(),
            'ketersediaan'    => $db
                ->table('operasi.ref_ketersediaan_status')
                ->select('id_ketersediaan_status, nama_ketersediaan')
                ->get()
                ->getResultArray(),
            'jenis_penunjang' => $db
                ->table('operasi.ref_jenis_penunjang')
                ->select('id_jenis_penunjang, nama_jenis')
                ->get()
                ->getResultArray(),
        ];
    }

    private function fetchNamaRole(string $tabel, string $idKolom, int $idValue): string
    {
        $row = $this->model
            ->db
            ->table("role.{$tabel} t")
            ->select('o.nama')
            ->join('person.orang o', 'o.id_orang = t.id_orang', 'left')
            ->where("t.{$idKolom}", $idValue)
            ->get()
            ->getRowArray();
        return $row['nama'] ?? '';
    }

    private function fetchTindakanName(int $idTindakan): string
    {
        $row = $this->model
            ->db
            ->table('operasi.ref_tindakan_operasi')
            ->select('nama_tindakan')
            ->where('id_tindakan', $idTindakan)
            ->get()
            ->getRowArray();
        return $row['nama_tindakan'] ?? '';
    }

    private function fetchPenunjang(int $idChecklist): array
    {
        return $this->model
            ->db
            ->table('operasi.checklist_pre_operasi_penunjang p')
            ->select(
                'p.id_penunjang, p.id_jenis_penunjang, p.id_ketersediaan, j.nama_jenis, k.nama_ketersediaan, p.keterangan',
            )
            ->join('operasi.ref_jenis_penunjang j', 'j.id_jenis_penunjang     = p.id_jenis_penunjang', 'left')
            ->join('operasi.ref_ketersediaan_status k', 'k.id_ketersediaan_status = p.id_ketersediaan', 'left')
            ->where('p.id_checklist', $idChecklist)
            ->get()
            ->getResultArray();
    }

    // Data Mapper for header
    private function buildHeaderData(array $rawPost): array
    {
        return [
            'id_jadwal'              => (int) ($rawPost['id_jadwal'] ?? 0) ?: null,
            'id_tindakan'            => (int) ($rawPost['id_tindakan'] ?? 0) ?: null,
            'id_sn_cn'               => (int) ($rawPost['id_sn_cn'] ?? 0) ?: null,
            'id_dokter_bedah'        => (int) ($rawPost['id_dokter_bedah'] ?? 0) ?: null,
            'id_dokter_anestesi'     => (int) ($rawPost['id_dokter_anestesi'] ?? 0) ?: null,
            'id_petugas_ruangan'     => (int) ($rawPost['id_petugas_ruangan'] ?? 0) ?: null,
            'id_petugas_ok'          => (int) ($rawPost['id_petugas_ok'] ?? 0) ?: null,
            'waktu_checklist'        => $rawPost['waktu_checklist'] ?? null,
            'is_identitas_sesuai'    => $rawPost['is_identitas_sesuai'] ?? null,
            'id_keadaan_umum'        => (int) ($rawPost['id_keadaan_umum'] ?? 0) ?: null,
            'id_penandaan_area'      => (int) ($rawPost['id_penandaan_area'] ?? 0) ?: null,
            'is_ijin_bedah'          => $rawPost['is_ijin_bedah'] ?? null,
            'is_ijin_anestesi'       => $rawPost['is_ijin_anestesi'] ?? null,
            'id_ijin_transfusi'      => (int) ($rawPost['id_ijin_transfusi'] ?? 0) ?: null,
            'id_persiapan_darah'     => (int) ($rawPost['id_persiapan_darah'] ?? 0) ?: null,
            'ket_persiapan_darah'    => $rawPost['ket_persiapan_darah'] ?? null,
            'id_perlengkapan_khusus' => (int) ($rawPost['id_perlengkapan_khusus'] ?? 0) ?: null,
        ];
    }

    // Batch Insert
    private function insertPenunjangList(int $idChecklist, array $penunjangList): void
    {
        $batchPenunjang = [];
        foreach ($penunjangList as $row) {
            $idJenis = (int) ($row['id_jenis_penunjang'] ?? 0);
            if ($idJenis === 0) {
                continue;
            }

            $batchPenunjang[] = [
                'id_checklist'       => $idChecklist,
                'id_jenis_penunjang' => $idJenis,
                'id_ketersediaan'    => (int) ($row['id_ketersediaan'] ?? 0) ?: null,
                'keterangan'         => $row['keterangan'] ?? null,
            ];
        }

        if (!empty($batchPenunjang)) {
            (new \App\Features\Operasi\ChecklistPreOperasiPenunjang\ChecklistPreOperasiPenunjangModel())->insertBatch(
                $batchPenunjang,
            );
        }
    }

    private function buildViewData(
        array $jadwal,
        array $record,
        string $formAction,
        null|int $idChecklist = null,
    ): array {
        return [
            'judul'        => ($formAction === '/submittambah/' ? 'Tambah ' : 'Ubah ') . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [[
                'title' => $formAction === '/submittambah/' ? 'Tambah' : 'Ubah',
                'icon'  => '',
            ]]),
            'modul_path'   => $this->get_uri_path(),
            'form_action'  => $formAction,
            'baris'        => $record,
            'jadwal'       => $jadwal,
            'options'      => $this->fetchOptions(),
            'id_checklist' => $idChecklist,
            'penunjang'    => $idChecklist !== null ? $this->fetchPenunjang($idChecklist) : [],
        ];
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    #[\Override]
    public function create_page(): string
    {
        $idJadwal = (int) ($this->request->getGet('id_jadwal') ?? 0);
        $jadwal   = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];

        return view('admin/operasi/tambah_checklist_pre_operasi', $this->buildViewData(
            $jadwal,
            [
                'id_jadwal'            => $idJadwal,
                'id_tindakan'          => $jadwal['id_tindakan'] ?? '',
                'nama_tindakan'        => $jadwal['nama_tindakan'] ?? '',
                'id_dokter_bedah'      => $jadwal['id_dokter_bedah'] ?? '',
                'nama_dokter_bedah'    => $jadwal['nama_dokter_bedah'] ?? '',
                'id_dokter_anestesi'   => $jadwal['id_dokter_anestesi'] ?? '',
                'nama_dokter_anestesi' => $jadwal['nama_dokter_anestesi'] ?? '',
            ],
            '/submittambah/',
        ));
    }

    #[\Override]
    public function update_page(int|string $id): string
    {
        $record   = $this->model->find_one($id);
        $idJadwal = (int) ($record['id_jadwal'] ?? 0);
        $jadwal   = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];

        if (($idDb = (int) ($record['id_dokter_bedah'] ?? 0)) > 0) {
            $record['nama_dokter_bedah'] = $this->fetchNamaRole('dokter', 'id_dokter', $idDb);
        }
        if (($idDa = (int) ($record['id_dokter_anestesi'] ?? 0)) > 0) {
            $record['nama_dokter_anestesi'] = $this->fetchNamaRole('dokter', 'id_dokter', $idDa);
        }
        if (($idT = (int) ($record['id_tindakan'] ?? 0)) > 0) {
            $record['nama_tindakan'] = $this->fetchTindakanName($idT);
        }
        if (($idSn = (int) ($record['id_sn_cn'] ?? 0)) > 0) {
            $record['nama_sn_cn'] = $this->fetchNamaRole('petugas', 'id_petugas', $idSn);
        }
        if (($idPr = (int) ($record['id_petugas_ruangan'] ?? 0)) > 0) {
            $record['nama_petugas_ruangan'] = $this->fetchNamaRole('petugas', 'id_petugas', $idPr);
        }
        if (($idPo = (int) ($record['id_petugas_ok'] ?? 0)) > 0) {
            $record['nama_petugas_ok'] = $this->fetchNamaRole('petugas', 'id_petugas', $idPo);
        }

        return view('admin/operasi/tambah_checklist_pre_operasi', $this->buildViewData(
            $jadwal,
            $record,
            '/submitedit/' . $id,
            (int) $id,
        ));
    }

    // -------------------------------------------------------------------------
    // Create / Update (overridden to save inline penunjang rows in same transaction)
    // -------------------------------------------------------------------------

    #[\Override]
    public function create(): string|RedirectResponse
    {
        $rawPost       = $this->request->getPost();
        $dataHeader    = $this->buildHeaderData($rawPost);
        $penunjangList = $rawPost['penunjang'] ?? [];

        $this->model->db->transStart();

        try {
            $this->model->insert($dataHeader);
            $idChecklist = $this->model->getInsertID();

            $this->insertPenunjangList((int) $idChecklist, $penunjangList);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan checklist pre operasi.');
            }

            return $this->home();
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
        if ($id == 0) {
            return $this->home();
        }

        $rawPost       = $this->request->getPost();
        $dataHeader    = $this->buildHeaderData($rawPost);
        $penunjangList = $rawPost['penunjang'] ?? [];

        $this->model->db->transStart();

        try {
            $this->model->update($id, $dataHeader);

            (new \App\Features\Operasi\ChecklistPreOperasiPenunjang\ChecklistPreOperasiPenunjangModel())
                ->where('id_checklist', $id)
                ->delete();

            $this->insertPenunjangList((int) $id, $penunjangList);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui checklist pre operasi.');
            }

            return $this->home();
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
        if ($id == 0) {
            return $this->home();
        }

        $this->model->db->transStart();

        try {
            (new \App\Features\Operasi\ChecklistPreOperasiPenunjang\ChecklistPreOperasiPenunjangModel())
                ->where('id_checklist', $id)
                ->delete();

            $this->model->delete($id);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus checklist pre operasi.');
            }

            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil dihapus.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
        }

        return $this->home();
    }
}
