<?php
declare(strict_types=1);

namespace App\Features\Operasi\JadwalOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class JadwalOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JadwalOperasiModel(),
            [
                ['Operasi',        'operasi'],
                ['Jadwal Operasi', 'jadwal_operasi'],
            ],
            'Jadwal Operasi',
            [
                A::READ,
                // A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::LEMBAR_OPERASI,
                A::FILTER,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX, 'id_jadwal',          'ID Jadwal'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,  'nomor_operasi',      'No. Operasi'],
                [TABLE_ONLY, OPTIONAL, I::INDEX, 'id_permintaan',      'Nama Pasien'],
                [TABLE_ONLY, OPTIONAL, I::INDEX, 'id_ruangan',         'Ruangan'],
                [HIDE,       OPTIONAL, I::INDEX, 'id_dokter_bedah',    'Dokter Bedah'],
                [HIDE,       OPTIONAL, I::INDEX, 'id_dokter_anestesi', 'Dokter Anestesi'],
                [SHOW,       REQUIRED, I::DATE,  'tanggal',            'Tanggal'],
                [SHOW,       REQUIRED, I::TIME,  'waktu_mulai',        'Waktu Mulai'],
                [SHOW,       OPTIONAL, I::TIME,  'waktu_selesai',      'Waktu Selesai'],
                [TABLE_ONLY, OPTIONAL, I::INDEX, 'id_status',          'Status'],
            ],
        );
    }

    // -------------------------------------------------------------------------
    // Index
    // -------------------------------------------------------------------------

    #[\Override]
    public function index(): string|RedirectResponse
    {
        $this->filters = [
            'menunggu'    => 'Menunggu Dijadwalkan',
            'dijadwalkan' => 'Dijadwalkan',
            'proses'      => 'Proses Operasi',
            'selesai'     => 'Selesai',
            'dibatalkan'  => 'Dibatalkan',
        ];

        $this->active_filter = $this->request->getGet('filter') ?: null;

        match ($this->active_filter) {
            'menunggu'    => $this->model->set_filter('id_status', 1),
            'dijadwalkan' => $this->model->set_filter('id_status', 2),
            'proses'      => $this->model->set_filter('id_status', 3),
            'selesai'     => $this->model->set_filter('id_status', 4),
            'dibatalkan'  => $this->model->set_filter('id_status', 5),
            default       => null,
        };

        return parent::index();
    }

    // -------------------------------------------------------------------------
    // Hooks
    // -------------------------------------------------------------------------

    #[\Override]
    protected function before_read(): void
    {
        $this->model
            ->set_order('is_cito', 'DESC')
            ->set_order('tanggal', 'ASC')
            ->set_order('waktu_mulai', 'ASC');
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    private function fetchPermintaan(int $idPermintaan): array
    {
        return $this->model->db
            ->table('operasi.permintaan_operasi po')
            ->select([
                'po.id_permintaan',
                'po.nomor_reg',
                'po.tanggal_minta',
                'po.is_cito',
                'op.nama AS nama_pasien',
                'od.nama AS nama_dokter_peminta',
                'ti.nama_tindakan',
            ])
            ->join('registrasi.registrasi r',        'r.nomor_reg    = po.nomor_reg',   'left')
            ->join('role.pasien p',                   'p.id_pasien    = r.id_pasien',    'left')
            ->join('person.orang op',                 'op.id_orang    = p.id_orang',     'left')
            ->join('role.dokter d',                   'd.id_dokter    = po.id_dokter',   'left')
            ->join('person.orang od',                 'od.id_orang    = d.id_orang',     'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan = po.id_tindakan', 'left')
            ->where('po.id_permintaan', $idPermintaan)
            ->get()
            ->getRowArray() ?? [];
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

    private function fetchNamaRuangan(int $idRuangan): string
    {
        $row = $this->model->db
            ->table('ruangan.ruangan')
            ->select('nama_ruangan')
            ->where('id_ruangan', $idRuangan)
            ->get()->getRowArray();
        return $row['nama_ruangan'] ?? '';
    }

    private function checkConflict(int $excludeId, array $data): ?string
    {
        $tanggal      = $data['tanggal'];
        $waktuMulai   = $data['waktu_mulai'];
        $waktuSelesai = $data['waktu_selesai'];

        $checks = [
            [
                'col'    => 'id_dokter_bedah',
                'join'   => [['role.dokter d', 'd.id_dokter = j.id_dokter_bedah'], ['person.orang o', 'o.id_orang = d.id_orang']],
                'label'  => fn(array $r) => "Dokter Bedah {$r['nama']}",
                'select' => 'j.waktu_mulai, j.waktu_selesai, o.nama',
            ],
            [
                'col'    => 'id_dokter_anestesi',
                'join'   => [['role.dokter d', 'd.id_dokter = j.id_dokter_anestesi'], ['person.orang o', 'o.id_orang = d.id_orang']],
                'label'  => fn(array $r) => "Dokter Anestesi {$r['nama']}",
                'select' => 'j.waktu_mulai, j.waktu_selesai, o.nama',
            ],
            [
                'col'    => 'id_ruangan',
                'join'   => [['ruangan.ruangan r', 'r.id_ruangan = j.id_ruangan']],
                'label'  => fn(array $r) => "Ruangan {$r['nama_ruangan']}",
                'select' => 'j.waktu_mulai, j.waktu_selesai, r.nama_ruangan',
            ],
        ];

        foreach ($checks as $check) {
            if (empty($data[$check['col']])) continue;

            $builder = $this->model->db
                ->table('operasi.jadwal_operasi j')
                ->select($check['select'])
                ->where('j.tanggal', $tanggal)
                ->where('j.id_status !=', 5)
                ->where('j.id_jadwal !=', $excludeId)
                ->where("j.{$check['col']}", $data[$check['col']])
                ->where("(j.waktu_mulai, j.waktu_selesai) OVERLAPS ('{$waktuMulai}'::time, '{$waktuSelesai}'::time)");

            foreach ($check['join'] as [$table, $cond]) {
                $builder->join($table, $cond, 'left');
            }

            $hit = $builder->get()->getRowArray();
            if ($hit) {
                $label = ($check['label'])($hit);
                return "{$label} sudah terjadwal operasi di waktu yang sama ({$hit['waktu_mulai']}–{$hit['waktu_selesai']}).";
            }
        }

        return null;
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    private function fetchTimTerpilih(int $idJadwal): array
    {
        return $this->model->db
            ->table('operasi.jadwal_operasi_tim jt')
            ->select('jt.id_dokter, jt.id_petugas, jt.id_peran, COALESCE(od.nama, op.nama) AS nama', false)
            ->join('role.dokter d',   'd.id_dokter   = jt.id_dokter',  'left')
            ->join('person.orang od', 'od.id_orang   = d.id_orang',    'left')
            ->join('role.petugas pt', 'pt.id_petugas = jt.id_petugas', 'left')
            ->join('person.orang op', 'op.id_orang   = pt.id_orang',   'left')
            ->where('jt.id_jadwal', $idJadwal)
            ->get()->getResultArray();
    }

    private function fetchPeranTim(): array
    {
        $rows = $this->model->db
            ->table('operasi.ref_peran_tim_medis')
            ->select(['id_peran', 'nama_peran'])
            ->orderBy('id_peran', 'ASC')
            ->get()->getResultArray();

        $peran = [];
        foreach ($rows as $row) {
            $peran[(int) $row['id_peran']] = $row['nama_peran'];
        }
        return $peran;
    }

    private function saveSlots(int $idJadwal, string $waktuMulai, string $waktuSelesai): void
    {
        $this->model->db->table('operasi.jadwal_operasi_slot')->where('id_jadwal', $idJadwal)->delete();

        $builder = $this->model->db
            ->table('operasi.ref_slot_operasi')
            ->select('id_slot')
            ->where('waktu_slot >=', $waktuMulai);

        if ($waktuSelesai !== '00:00:00') {
            $builder->where('waktu_slot <', $waktuSelesai);
        }

        $rows = $builder->get()->getResultArray();
        if (empty($rows)) return;

        $this->model->db->table('operasi.jadwal_operasi_slot')->insertBatch(
            array_map(fn($s) => ['id_jadwal' => $idJadwal, 'id_slot' => (int) $s['id_slot']], $rows)
        );
    }

    private function saveTim(int $idJadwal, array $tim): void
    {
        $this->model->db->table('operasi.jadwal_operasi_tim')->where('id_jadwal', $idJadwal)->delete();

        $peranValid = $this->fetchPeranTim();
        $peranTerpakai = [];
        $rows = [];

        foreach ($tim as $anggota) {
            $idDokter  = (int) ($anggota['id_dokter']  ?? 0);
            $idPetugas = (int) ($anggota['id_petugas'] ?? 0);
            if ($idDokter === 0 && $idPetugas === 0) continue;

            $idPeran = (int) ($anggota['id_peran'] ?? 0);
            if (!isset($peranValid[$idPeran]) || isset($peranTerpakai[$idPeran])) {
                $idPeran = 0;
            } else {
                $peranTerpakai[$idPeran] = true;
            }

            $rows[] = [
                'id_jadwal'  => $idJadwal,
                'id_dokter'  => $idDokter  ?: null,
                'id_petugas' => $idPetugas ?: null,
                'id_peran'   => $idPeran   ?: null,
            ];
        }

        if (empty($rows)) return;
        $this->model->db->table('operasi.jadwal_operasi_tim')->insertBatch($rows);
    }

    #[\Override]
    final public function update_page(int|string $id): string
    {
        $baris = $this->model->find($id) ?? [];

        if (!empty($baris['id_permintaan'])) {
            $baris = array_merge($baris, $this->fetchPermintaan((int) $baris['id_permintaan']));
        }

        if (($idDb = (int) ($baris['id_dokter_bedah'] ?? 0)) > 0) {
            $baris['nama_dokter_bedah'] = $this->fetchNamaRole('dokter', 'id_dokter', $idDb);
        }

        if (($idDa = (int) ($baris['id_dokter_anestesi'] ?? 0)) > 0) {
            $baris['nama_dokter_anestesi'] = $this->fetchNamaRole('dokter', 'id_dokter', $idDa);
        }

        if (($idRuangan = (int) ($baris['id_ruangan'] ?? 0)) > 0) {
            $baris['nama_ruangan'] = $this->fetchNamaRuangan($idRuangan);
        }

        return view('admin/operasi/jadwalkan_operasi', [
            'judul'         => 'Jadwalkan Operasi',
            'breadcrumbs'   => array_merge($this->breadcrumbs, [['title' => 'Jadwalkan', 'icon' => 'ubah']]),
            'modul_path'    => $this->get_uri_path(),
            'kolom_id'      => $this->model->primaryKey,
            'baris'         => $baris,
            'form_action'   => '/submitedit/' . $id,
            'tim_terpilih'  => $this->fetchTimTerpilih((int) $id),
            'peran_tim'     => $this->fetchPeranTim(),
        ]);
    }

    // -------------------------------------------------------------------------
    // CRUD
    // -------------------------------------------------------------------------

    private function generateNomorOperasi(string $tanggal): string
    {
        helper('autonomor');

        $lastNo = $this->model->db
            ->table('operasi.jadwal_operasi')
            ->select('nomor_operasi')
            ->like('nomor_operasi', 'OP' . date('Ymd', strtotime($tanggal)), 'after')
            ->orderBy('nomor_operasi', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return generateNextNoOperasi($lastNo['nomor_operasi'] ?? null, $tanggal);
    }

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();

        $rawPost      = $this->request->getPost();
        $tanggal      = $rawPost['tanggal']       ?? null;
        $waktuMulai   = $rawPost['waktu_mulai']   ?? null;
        $waktuSelesai = $rawPost['waktu_selesai'] ?? null;
        $tim          = $this->request->getPost('tim') ?? [];

        $existing     = $this->model->find($id);
        $nomorOperasi = $existing['nomor_operasi'] ?? null;
        if ($nomorOperasi === null && $tanggal !== null) {
            $nomorOperasi = $this->generateNomorOperasi($tanggal);
        }

        $data = [
            'nomor_operasi'      => $nomorOperasi,
            'id_ruangan'         => $rawPost['id_ruangan']         ?? null,
            'id_dokter_bedah'    => $rawPost['id_dokter_bedah']    ?? null,
            'id_dokter_anestesi' => $rawPost['id_dokter_anestesi'] ?? null,
            'tanggal'            => $tanggal,
            'waktu_mulai'        => $waktuMulai,
            'waktu_selesai'      => $waktuSelesai,
            'id_status'          => 2,
        ];

        try {
            $conflict = $this->checkConflict((int) $id, $data);
            if ($conflict !== null) {
                session()->setFlashdata('error', $conflict);
                return redirect()->back()->withInput();
            }

            $this->model->db->transStart();

            $this->model->update($id, $data);
            if ($waktuMulai !== null && $waktuSelesai !== null) {
                $this->saveSlots((int) $id, $waktuMulai, $waktuSelesai);
            }
            $this->saveTim((int) $id, $tim);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan jadwal operasi.');
            }

            session()->setFlashdata('success', 'Jadwal operasi berhasil disimpan.');
            return redirect()->to('/operasi/jadwal-operasi/data');

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }

}
