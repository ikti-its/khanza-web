<?php
declare(strict_types=1);

namespace App\Features\Operasi\PenyerahanPasien;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PenyerahanPasienController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenyerahanPasienModel(),
            [
                ['Operasi',           'operasi'],
                ['Penyerahan Pasien', 'penyerahan_pasien'],
            ],
            'Penyerahan Pasien',
            [
                A::READ,
                // A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,      OPTIONAL, I::INDEX,  'id_penyerahan',            'ID Penyerahan'],
                [SHOW,      REQUIRED, I::INDEX,  'id_jadwal',                'Jadwal Operasi'],
                [FORM_ONLY, REQUIRED, I::DTIME,  'waktu_masuk_asal',         'Waktu Masuk Asal'],
                [FORM_ONLY, REQUIRED, I::DTIME,  'waktu_pindah',             'Waktu Pindah'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_indikasi',              'Indikasi'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'ket_indikasi',             'Keterangan Indikasi'],
                [SHOW,      REQUIRED, I::INDEX,  'id_ruang_asal',            'Ruang Asal'],
                [SHOW,      REQUIRED, I::INDEX,  'id_ruang_selanjutnya',     'Ruang Selanjutnya'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_metode',                'Metode'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'diagnosa_utama',           'Diagnosa Utama'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'diagnosa_sekunder',        'Diagnosa Sekunder'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'prosedur_dilakukan',       'Prosedur Dilakukan'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'obat_diberikan',           'Obat Diberikan'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'pemeriksaan_penunjang',    'Pemeriksaan Penunjang'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_disetujui',             'Disetujui'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'nama_pemberi_persetujuan', 'Nama Pemberi Persetujuan'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_hubungan',              'Hubungan'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'asal_id_keadaan',          'Keadaan Asal'],
                [FORM_ONLY, REQUIRED, I::NUMBER, 'asal_sistolik',            'Sistolik Asal'],
                [FORM_ONLY, REQUIRED, I::NUMBER, 'asal_diastolik',           'Diastolik Asal'],
                [FORM_ONLY, REQUIRED, I::NUMBER, 'asal_nadi',                'Nadi Asal'],
                [FORM_ONLY, REQUIRED, I::NUMBER, 'asal_respiratory_rate',    'RR Asal'],
                [FORM_ONLY, REQUIRED, I::TEMP,   'asal_suhu',                'Suhu Asal'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'asal_keluhan',             'Keluhan Asal'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'tiba_id_keadaan',          'Keadaan Tiba'],
                [FORM_ONLY, REQUIRED, I::NUMBER, 'tiba_sistolik',            'Sistolik Tiba'],
                [FORM_ONLY, REQUIRED, I::NUMBER, 'tiba_diastolik',           'Diastolik Tiba'],
                [FORM_ONLY, REQUIRED, I::NUMBER, 'tiba_nadi',                'Nadi Tiba'],
                [FORM_ONLY, REQUIRED, I::NUMBER, 'tiba_respiratory_rate',    'RR Tiba'],
                [FORM_ONLY, REQUIRED, I::TEMP,   'tiba_suhu',                'Suhu Tiba'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'tiba_keluhan',             'Keluhan Tiba'],
                [SHOW,      REQUIRED, I::INDEX,  'id_perawat_menyerahkan',   'Perawat Menyerahkan'],
                [SHOW,      REQUIRED, I::INDEX,  'id_perawat_menerima',      'Perawat Menerima'],
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
        return $this->model->db
            ->table('operasi.jadwal_operasi j')
            ->select([
                'j.id_jadwal',
                'po.nomor_reg',
                'ti.nama_tindakan',
                'op.nama AS nama_pasien',
            ])
            ->join('operasi.permintaan_operasi po',   'po.id_permintaan = j.id_permintaan', 'left')
            ->join('rekam_medis.registrasi r',        'r.nomor_reg      = po.nomor_reg',    'left')
            ->join('role.pasien p',                   'p.id_pasien      = r.id_pasien',     'left')
            ->join('person.orang op',                 'op.id_orang      = p.id_orang',      'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan   = po.id_tindakan',  'left')
            ->where('j.id_jadwal', $idJadwal)
            ->get()->getRowArray() ?? [];
    }

    private function fetchOptions(): array
    {
        $db = $this->model->db;
        return [
            'indikasi'     => $db->table('operasi.ref_indikasi_pindah')
                ->select('id_indikasi, nama_indikasi')->get()->getResultArray(),
            'metode'       => $db->table('operasi.ref_metode_transfer')
                ->select('id_metode, nama_metode')->get()->getResultArray(),
            'peralatan'    => $db->table('operasi.ref_peralatan_transfer')
                ->select('id_peralatan, nama_peralatan')->get()->getResultArray(),
            'keadaan_umum' => $db->table('operasi.ref_keadaan_umum_transfer')
                ->select('id_keadaan_umum, nama_keadaan')->get()->getResultArray(),
            'hubungan'     => $db->table('operasi.ref_hubungan_keluarga')
                ->select('id_hubungan_keluarga, nama_hubungan')->get()->getResultArray(),
        ];
    }

    private function fetchPeralatan(int $idPenyerahan): array
    {
        $rows = $this->model->db
            ->table('operasi.penyerahan_pasien_peralatan')
            ->select('id_peralatan, keterangan')
            ->where('id_penyerahan', $idPenyerahan)
            ->get()->getResultArray();
            
        return array_column($rows, null, 'id_peralatan');
    }

    private function fetchRuanganName(int $idRuangan): string
    {
        $row = $this->model->db
            ->table('ruangan.ruangan')
            ->select('nama_ruangan')
            ->where('id_ruangan', $idRuangan)
            ->get()->getRowArray();
        return $row['nama_ruangan'] ?? '';
    }

    private function fetchNamaRole(string $tabel, string $idKolom, int $idValue): string
    {
        $row = $this->model->db
            ->table("role.{$tabel} t")
            ->select('o.nama')
            ->join('person.orang o', 'o.id_orang = t.id_orang', 'left')
            ->where("t.{$idKolom}", $idValue)
            ->get()->getRowArray();
        return $row['nama'] ?? '';
    }

    private function buildViewData(
        array $jadwal,
        array $record,
        string $formAction,
        array $peralatanMap = [],
    ): array {
        $isCreate = $formAction === '/submittambah/';
        return [
            'judul'         => ($isCreate ? 'Tambah ' : 'Ubah ') . $this->title,
            'breadcrumbs'   => array_merge($this->breadcrumbs, [['title' => $isCreate ? 'Tambah' : 'Ubah', 'icon' => '']]),
            'modul_path'    => $this->get_uri_path(),
            'form_action'   => $formAction,
            'baris'         => $record,
            'jadwal'        => $jadwal,
            'options'       => $this->fetchOptions(),
            'peralatan_map' => $peralatanMap,
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

        return view('admin/operasi/tambah_penyerahan_pasien',
            $this->buildViewData($jadwal, [
                'id_jadwal'     => $idJadwal,
                'nama_tindakan' => $jadwal['nama_tindakan'] ?? '',
            ], '/submittambah/'));
    }

    #[\Override]
    public function update_page(int|string $id): string
    {
        $record       = $this->model->find_one($id);
        $idJadwal     = (int) ($record['id_jadwal'] ?? 0);
        $jadwal       = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];
        $idPenyerahan = (int) $id;

        if (($idRa = (int) ($record['id_ruang_asal']          ?? 0)) > 0) {
            $record['nama_ruang_asal']          = $this->fetchRuanganName($idRa);
        }
        if (($idRs = (int) ($record['id_ruang_selanjutnya']   ?? 0)) > 0) {
            $record['nama_ruang_selanjutnya']   = $this->fetchRuanganName($idRs);
        }
        if (($idPm = (int) ($record['id_perawat_menyerahkan'] ?? 0)) > 0) {
            $record['nama_perawat_menyerahkan'] = $this->fetchNamaRole('petugas', 'id_petugas', $idPm);
        }
        if (($idPr = (int) ($record['id_perawat_menerima']    ?? 0)) > 0) {
            $record['nama_perawat_menerima']    = $this->fetchNamaRole('petugas', 'id_petugas', $idPr);
        }

        if (isset($record['is_disetujui'])) {
            $isTrue = ($record['is_disetujui'] === true || $record['is_disetujui'] == 1 || $record['is_disetujui'] === 't');
            $record['is_disetujui'] = $isTrue ? '1' : '0';
        }

        return view('admin/operasi/tambah_penyerahan_pasien',
            $this->buildViewData(
                $jadwal,
                $record,
                '/submitedit/' . $id,
                $this->fetchPeralatan($idPenyerahan),
            ));
    }

    // -------------------------------------------------------------------------
    // Create / Update
    // -------------------------------------------------------------------------

    // Data Mapper for header
    private function buildDataHeader(array $rawPost): array
    {
        return [
            'id_jadwal'                => (int) ($rawPost['id_jadwal']                ?? 0) ?: null,
            'waktu_masuk_asal'         => $rawPost['waktu_masuk_asal']                ?? null,
            'waktu_pindah'             => $rawPost['waktu_pindah']                    ?? null,
            'id_indikasi'              => (int) ($rawPost['id_indikasi']              ?? 0) ?: null,
            'ket_indikasi'             => $rawPost['ket_indikasi']                    ?? null,
            'id_ruang_asal'            => (int) ($rawPost['id_ruang_asal']            ?? 0) ?: null,
            'id_ruang_selanjutnya'     => (int) ($rawPost['id_ruang_selanjutnya']     ?? 0) ?: null,
            'id_metode'                => (int) ($rawPost['id_metode']                ?? 0) ?: null,
            'diagnosa_utama'           => $rawPost['diagnosa_utama']                  ?? null,
            'diagnosa_sekunder'        => $rawPost['diagnosa_sekunder']               ?? null,
            'prosedur_dilakukan'       => $rawPost['prosedur_dilakukan']              ?? null,
            'obat_diberikan'           => $rawPost['obat_diberikan']                  ?? null,
            'pemeriksaan_penunjang'    => $rawPost['pemeriksaan_penunjang']           ?? null,
            'is_disetujui'             => $rawPost['is_disetujui']                    ?? '0',
            'nama_pemberi_persetujuan' => $rawPost['nama_pemberi_persetujuan']        ?? null,
            'id_hubungan'              => (int) ($rawPost['id_hubungan']              ?? 0) ?: null,
            'asal_id_keadaan'          => (int) ($rawPost['asal_id_keadaan']          ?? 0) ?: null,
            'asal_sistolik'            => $rawPost['asal_sistolik']                   ?? null,
            'asal_diastolik'           => $rawPost['asal_diastolik']                  ?? null,
            'asal_nadi'                => $rawPost['asal_nadi']                       ?? null,
            'asal_respiratory_rate'    => $rawPost['asal_respiratory_rate']           ?? null,
            'asal_suhu'                => $rawPost['asal_suhu']                       ?? null,
            'asal_keluhan'             => $rawPost['asal_keluhan']                    ?? null,
            'tiba_id_keadaan'          => (int) ($rawPost['tiba_id_keadaan']          ?? 0) ?: null,
            'tiba_sistolik'            => $rawPost['tiba_sistolik']                   ?? null,
            'tiba_diastolik'           => $rawPost['tiba_diastolik']                  ?? null,
            'tiba_nadi'                => $rawPost['tiba_nadi']                       ?? null,
            'tiba_respiratory_rate'    => $rawPost['tiba_respiratory_rate']           ?? null,
            'tiba_suhu'                => $rawPost['tiba_suhu']                       ?? null,
            'tiba_keluhan'             => $rawPost['tiba_keluhan']                    ?? null,
            'id_perawat_menyerahkan'   => (int) ($rawPost['id_perawat_menyerahkan']   ?? 0) ?: null,
            'id_perawat_menerima'      => (int) ($rawPost['id_perawat_menerima']      ?? 0) ?: null,
        ];
    }

    private function savePeralatan(
        \App\Features\Operasi\PenyerahanPasienPeralatan\PenyerahanPasienPeralatanModel $model,
        int|string $idPenyerahan,
        array $peralatanList,
    ): void {
        $batchData = [];
        foreach ($peralatanList as $row) {
            if (($row['selected'] ?? '0') !== '1') continue;
            
            $idPeralatan = (int) ($row['id_peralatan'] ?? 0);
            if ($idPeralatan === 0) continue;
            
            $batchData[] = [
                'id_penyerahan' => $idPenyerahan,
                'id_peralatan'  => $idPeralatan,
                'keterangan'    => $row['keterangan'] ?? '',
            ];
        }

        if (!empty($batchData)) {
            $model->insertBatch($batchData);
        }
    }

    #[\Override]
    public function create(): string|RedirectResponse
    {
        $rawPost       = $this->request->getPost();
        $peralatanList = $rawPost['peralatan'] ?? [];

        $this->model->db->transStart();

        try {
            $this->model->insert($this->buildDataHeader($rawPost));
            $idPenyerahan = $this->model->getInsertID();

            $modelPeralatan = new \App\Features\Operasi\PenyerahanPasienPeralatan\PenyerahanPasienPeralatanModel();
            $this->savePeralatan($modelPeralatan, $idPenyerahan, $peralatanList);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan penyerahan pasien.');
            }

            return $this->home();

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

        $rawPost       = $this->request->getPost();
        $peralatanList = $rawPost['peralatan'] ?? [];

        $this->model->db->transStart();

        try {
            $this->model->update($id, $this->buildDataHeader($rawPost));

            $modelPeralatan = new \App\Features\Operasi\PenyerahanPasienPeralatan\PenyerahanPasienPeralatanModel();
            $modelPeralatan->where('id_penyerahan', $id)->delete();
            
            $this->savePeralatan($modelPeralatan, $id, $peralatanList);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui penyerahan pasien.');
            }

            return $this->home();

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }
}
