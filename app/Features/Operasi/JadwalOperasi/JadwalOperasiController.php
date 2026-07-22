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
                // A::AUDIT,
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
                [SHOW,       OPTIONAL, I::DATE,  'tanggal_selesai',    'Tanggal Selesai'],
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

        $this->active_filter = $this->request->getGet('filter') ? $this->request->getGet('filter') : null;

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
        $this->model->set_order('is_cito', 'DESC')->set_order('tanggal', 'ASC')->set_order('waktu_mulai', 'ASC');
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    private function fetchPermintaan(int $idPermintaan): array
    {
        return $this->model
            ->db
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
            ->join('registrasi.registrasi r', 'r.nomor_reg    = po.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien    = r.id_pasien', 'left')
            ->join('person.orang op', 'op.id_orang    = p.id_orang', 'left')
            ->join('role.dokter d', 'd.id_dokter    = po.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang    = d.id_orang', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan = po.id_tindakan', 'left')
            ->where('po.id_permintaan', $idPermintaan)
            ->get()
            ->getRowArray() ?? [];
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

    private function fetchNamaRuangan(int $idRuangan): string
    {
        $row = $this->model
            ->db
            ->table('ruangan.ruangan')
            ->select('nama_ruangan')
            ->where('id_ruangan', $idRuangan)
            ->get()
            ->getRowArray();
        return $row['nama_ruangan'] ?? '';
    }

    /** Bentrok dibandingkan sebagai timestamp tanggal+waktu penuh supaya operasi lewat tengah malam terdeteksi benar. */
    private function checkConflict(int $excludeId, array $data): null|string
    {
        $tanggal        = $data['tanggal'];
        $tanggalSelesai = $data['tanggal_selesai'] ?? $tanggal;
        $waktuMulai     = $data['waktu_mulai'];
        $waktuSelesai   = $data['waktu_selesai'] ?? '23:59:59';

        $mulaiTs       = $this->model->db->escape("{$tanggal} {$waktuMulai}");
        $selesaiTs     = $this->model->db->escape("{$tanggalSelesai} {$waktuSelesai}");
        $overlapClause = "(j.tanggal + j.waktu_mulai, COALESCE(j.tanggal_selesai, j.tanggal) + COALESCE(j.waktu_selesai, '23:59:59'::time))"
        . " OVERLAPS ({$mulaiTs}::timestamp, {$selesaiTs}::timestamp)";

        $kolomWaktu = 'j.tanggal, j.waktu_mulai, j.tanggal_selesai, j.waktu_selesai';
        $checks     = [
            [
                'col'    => 'id_dokter_bedah',
                'join'   => [
                    ['role.dokter d',  'd.id_dokter = j.id_dokter_bedah'],
                    ['person.orang o', 'o.id_orang = d.id_orang'],
                ],
                'label'  => static fn(array $r) => "Dokter Bedah {$r['nama']}",
                'select' => "{$kolomWaktu}, o.nama",
            ],
            [
                'col'    => 'id_dokter_anestesi',
                'join'   => [
                    ['role.dokter d',  'd.id_dokter = j.id_dokter_anestesi'],
                    ['person.orang o', 'o.id_orang = d.id_orang'],
                ],
                'label'  => static fn(array $r) => "Dokter Anestesi {$r['nama']}",
                'select' => "{$kolomWaktu}, o.nama",
            ],
            [
                'col'    => 'id_ruangan',
                'join'   => [['ruangan.ruangan r', 'r.id_ruangan = j.id_ruangan']],
                'label'  => static fn(array $r) => "Ruangan {$r['nama_ruangan']}",
                'select' => "{$kolomWaktu}, r.nama_ruangan",
            ],
        ];

        foreach ($checks as $check) {
            if (empty($data[$check['col']])) {
                continue;
            }

            $builder = $this->model
                ->db
                ->table('operasi.jadwal_operasi j')
                ->select($check['select'])
                ->where('j.id_status !=', 5)
                ->where('j.id_jadwal !=', $excludeId)
                ->where("j.{$check['col']}", $data[$check['col']])
                ->where('j.tanggal <=', $tanggalSelesai)
                ->where('COALESCE(j.tanggal_selesai, j.tanggal) >=', $tanggal)
                ->where($overlapClause);

            foreach ($check['join'] as [$table, $cond]) {
                $builder->join($table, $cond, 'left');
            }

            $hit = $builder->get()->getRowArray();
            if ($hit) {
                $label = $check['label']($hit);
                return "{$label} sudah terjadwal operasi di waktu yang sama ({$this->formatRentangWaktu($hit)}).";
            }
        }

        return null;
    }

    private function formatRentangWaktu(array $jadwal): string
    {
        $tanggalSelesai = $jadwal['tanggal_selesai'] ?? $jadwal['tanggal'];
        if ($jadwal['tanggal'] === $tanggalSelesai) {
            return "{$jadwal['waktu_mulai']}–{$jadwal['waktu_selesai']}";
        }
        return "{$jadwal['tanggal']} {$jadwal['waktu_mulai']} – {$tanggalSelesai} {$jadwal['waktu_selesai']}";
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    private function fetchTimTerpilih(int $idJadwal): array
    {
        return $this->model
            ->db
            ->table('operasi.jadwal_operasi_tim jt')
            ->select('jt.id_dokter, jt.id_petugas, jt.id_peran, COALESCE(od.nama, op.nama) AS nama', false)
            ->join('role.dokter d', 'd.id_dokter   = jt.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang   = d.id_orang', 'left')
            ->join('role.petugas pt', 'pt.id_petugas = jt.id_petugas', 'left')
            ->join('person.orang op', 'op.id_orang   = pt.id_orang', 'left')
            ->where('jt.id_jadwal', $idJadwal)
            ->get()
            ->getResultArray();
    }

    private function fetchPeranTim(): array
    {
        $rows = $this->model
            ->db
            ->table('operasi.ref_peran_tim_medis')
            ->select(['id_peran', 'nama_peran'])
            ->orderBy('id_peran', 'ASC')
            ->get()
            ->getResultArray();

        $peran = [];
        foreach ($rows as $row) {
            $peran[(int) $row['id_peran']] = $row['nama_peran'];
        }
        return $peran;
    }

    private function fetchPeranJenis(): array
    {
        $rows = $this->model
            ->db
            ->table('operasi.ref_peran_tim_medis')
            ->select(['id_peran', 'jenis'])
            ->get()
            ->getResultArray();

        $jenis = [];
        foreach ($rows as $row) {
            $jenis[(int) $row['id_peran']] = $row['jenis'];
        }
        return $jenis;
    }

    /** Cegah petugas ditempatkan di peran khusus dokter (atau sebaliknya) sebelum data disimpan. */
    private function validateTim(array $tim): null|string
    {
        $peranValid = $this->fetchPeranTim();
        $peranJenis = $this->fetchPeranJenis();

        foreach ($tim as $anggota) {
            $idPeran = (int) ($anggota['id_peran'] ?? 0);
            if (!array_key_exists($idPeran, $peranValid)) {
                continue;
            }

            $idDokter  = (int) ($anggota['id_dokter'] ?? 0);
            $idPetugas = (int) ($anggota['id_petugas'] ?? 0);
            $jenis     = $peranJenis[$idPeran] ?? null;

            if ($jenis === 'dokter' && $idPetugas > 0) {
                return "Peran \"{$peranValid[$idPeran]}\" hanya boleh diisi dokter, bukan petugas.";
            }
            if ($jenis === 'petugas' && $idDokter > 0) {
                return "Peran \"{$peranValid[$idPeran]}\" hanya boleh diisi petugas, bukan dokter.";
            }
        }

        return null;
    }

    private function saveTim(int $idJadwal, array $tim): void
    {
        $this->model->db->table('operasi.jadwal_operasi_tim')->where('id_jadwal', $idJadwal)->delete();

        $peranValid    = $this->fetchPeranTim();
        $peranTerpakai = [];
        $rows          = [];

        foreach ($tim as $anggota) {
            $idDokter  = (int) ($anggota['id_dokter'] ?? 0);
            $idPetugas = (int) ($anggota['id_petugas'] ?? 0);
            if ($idDokter === 0 && $idPetugas === 0) {
                continue;
            }

            $idPeran = (int) ($anggota['id_peran'] ?? 0);
            if (!array_key_exists($idPeran, $peranValid) || array_key_exists($idPeran, $peranTerpakai)) {
                $idPeran = 0;
            } else {
                $peranTerpakai[$idPeran] = true;
            }

            $rows[] = [
                'id_jadwal'  => $idJadwal,
                'id_dokter'  => $idDokter ? $idDokter : null,
                'id_petugas' => $idPetugas ? $idPetugas : null,
                'id_peran'   => $idPeran ? $idPeran : null,
            ];
        }

        if (empty($rows)) {
            return;
        }
        $this->model->db->table('operasi.jadwal_operasi_tim')->insertBatch($rows);
    }

    #[\Override]
    public function update_page(int|string $id): string
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
            'judul'        => 'Jadwalkan Operasi',
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Jadwalkan', 'icon' => 'ubah']]),
            'modul_path'   => $this->get_uri_path(),
            'kolom_id'     => $this->model->primaryKey,
            'baris'        => $baris,
            'form_action'  => '/submitedit/' . $id,
            'tim_terpilih' => $this->fetchTimTerpilih((int) $id),
            'peran_tim'    => $this->fetchPeranTim(),
        ]);
    }

    // -------------------------------------------------------------------------
    // CRUD
    // -------------------------------------------------------------------------

    private function generateNomorOperasi(string $tanggal): string
    {
        helper('autonomor');

        $lastNo = $this->model
            ->db
            ->table('operasi.jadwal_operasi')
            ->select('nomor_operasi')
            ->like('nomor_operasi', 'OP' . date('Ymd', strtotime($tanggal)), 'after')
            ->orderBy('nomor_operasi', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return generateNextNoOperasi($lastNo['nomor_operasi'] ?? null, $tanggal);
    }

    /**
     * Kalau user tidak mengisi tanggal_selesai, tebak dari waktu: kalau waktu_selesai
     * lebih awal dari waktu_mulai berarti operasi lewat tengah malam (+1 hari).
     */
    private function resolveTanggalSelesai(
        null|string $tanggal,
        null|string $waktuMulai,
        null|string $waktuSelesai,
        null|string $tanggalSelesai,
    ): null|string {
        if ($tanggalSelesai !== null || $tanggal === null) {
            return $tanggalSelesai;
        }

        $lewatTengahMalam = $waktuMulai !== null && $waktuSelesai !== null && $waktuSelesai < $waktuMulai;
        return $lewatTengahMalam ? date('Y-m-d', strtotime("{$tanggal} +1 day")) : $tanggal;
    }

    /** Dibandingkan sebagai timestamp penuh, bukan cuma tanggal, supaya rentang terbalik (tanggal sama tapi waktu_selesai < waktu_mulai) ikut tertangkap. */
    private function rentangSelesaiTidakValid(
        null|string $tanggal,
        null|string $waktuMulai,
        null|string $tanggalSelesai,
        null|string $waktuSelesai,
    ): bool {
        if ($tanggal === null || $tanggalSelesai === null) {
            return false;
        }
        if ($waktuMulai === null || $waktuSelesai === null) {
            return $tanggalSelesai < $tanggal;
        }
        return strtotime("{$tanggalSelesai} {$waktuSelesai}") <= strtotime("{$tanggal} {$waktuMulai}");
    }

    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        $existing = $this->model->find($id);
        if (!$existing) {
            return $this->home();
        }

        /** Jadwal yang sudah diisi (Dijadwalkan/Proses/Selesai/Dibatalkan) tidak boleh dihapus—kalau ada
         * kesalahan data, seharusnya diubah lewat menu edit, bukan dihapus lalu dibuat ulang, karena baris
         * jadwal ini satu-satu dengan permintaan operasinya dan tidak ada alur untuk membuatnya kembali. */
        if ((int) ($existing['id_status'] ?? 0) !== 1) {
            session()->setFlashdata(
                'error',
                'Jadwal operasi yang sudah dijadwalkan tidak dapat dihapus. Jika ada kesalahan data, silakan ubah melalui menu edit.',
            );
            return $this->home();
        }

        try {
            $this->model->delete($id);
            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil dihapus.');
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            session()->setFlashdata('error', $this->friendly_db_error($e));
        }

        return $this->home();
    }

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        $rawPost             = $this->request->getPost();
        $tanggal             = $rawPost['tanggal'] ?? null;
        $waktuMulai          = $rawPost['waktu_mulai'] ?? null;
        $waktuSelesaiInput   = $rawPost['waktu_selesai'] ?? null;
        $waktuSelesai        = $waktuSelesaiInput ? $waktuSelesaiInput : null;
        $tanggalSelesaiInput = $rawPost['tanggal_selesai'] ?? null;
        $tanggalSelesai      = $this->resolveTanggalSelesai(
            $tanggal,
            $waktuMulai,
            $waktuSelesai,
            $tanggalSelesaiInput ? $tanggalSelesaiInput : null,
        );
        $tim = $this->request->getPost('tim') ?? [];

        if ($this->rentangSelesaiTidakValid($tanggal, $waktuMulai, $tanggalSelesai, $waktuSelesai)) {
            session()->setFlashdata('error', 'Tanggal/waktu selesai harus setelah tanggal/waktu mulai.');
            return redirect()->back()->withInput();
        }

        $timError = $this->validateTim($tim);
        if ($timError !== null) {
            session()->setFlashdata('error', $timError);
            return redirect()->back()->withInput();
        }

        $existing     = $this->model->find($id);
        $nomorOperasi = $existing['nomor_operasi'] ?? null;
        if ($nomorOperasi === null && $tanggal !== null) {
            $nomorOperasi = $this->generateNomorOperasi($tanggal);
        }

        $data = [
            'nomor_operasi'      => $nomorOperasi,
            'id_ruangan'         => $rawPost['id_ruangan'] ?? null,
            'id_dokter_bedah'    => $rawPost['id_dokter_bedah'] ?? null,
            'id_dokter_anestesi' => $rawPost['id_dokter_anestesi'] ?? null,
            'tanggal'            => $tanggal,
            'waktu_mulai'        => $waktuMulai,
            'waktu_selesai'      => $waktuSelesai,
            'tanggal_selesai'    => $tanggalSelesai,
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
            $this->saveTim((int) $id, $tim);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan jadwal operasi.');
            }

            session()->setFlashdata('success', 'Jadwal operasi berhasil disimpan.');
            return redirect()->to('/operasi/jadwal-operasi/data');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }
}
