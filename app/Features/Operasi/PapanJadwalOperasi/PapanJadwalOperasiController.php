<?php
declare(strict_types=1);

namespace App\Features\Operasi\PapanJadwalOperasi;

use App\Core\Controller\ControllerTemplate;
use App\Features\Operasi\JadwalOperasi\JadwalOperasiModel;
use CodeIgniter\HTTP\RedirectResponse;

final class PapanJadwalOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JadwalOperasiModel(),
            [
                ['Operasi',              'operasi'],
                ['Papan Jadwal Operasi', 'papan_jadwal_operasi'],
            ],
            'Papan Jadwal Operasi',
            [],
            [],
        );
    }

    #[\Override]
    final public function index(): string|RedirectResponse
    {
        $tanggal  = $this->request->getGet('tanggal') ?: date('Y-m-d');
        $slots    = $this->fetchSlots();
        $ruangans = $this->fetchRuangans();
        $jadwals  = $this->fetchJadwals($tanggal);

        $grid  = $this->buildGrid($jadwals, $slots);
        $spans = $this->buildSpans($ruangans, $slots, $grid);

        return view('admin/operasi/papan_jadwal_operasi', [
            'judul'      => 'Papan Jadwal Operasi',
            'breadcrumbs'=> array_merge($this->breadcrumbs, [['title' => 'Papan Jadwal', 'icon' => 'detail']]),
            'tanggal'    => $tanggal,
            'slots'      => $slots,
            'ruangans'   => $ruangans,
            'grid'       => $grid,
            'spans'      => $spans,
        ]);
    }

    private function fetchSlots(): array
    {
        return $this->model->db
            ->table('operasi.ref_slot_operasi')
            ->orderBy('id_slot', 'ASC')
            ->get()->getResultArray();
    }

    private function fetchRuangans(): array
    {
        return $this->model->db
            ->table('ruangan.ruangan')
            ->select(['id_ruangan', 'nama_ruangan', 'kode_ruangan'])
            ->like('kode_ruangan', 'OK', 'after')
            ->orderBy('kode_ruangan', 'ASC')
            ->get()->getResultArray();
    }

    /** 
     * Ambil jadwal yang masih berlangsung di $tanggal (bukan cuma yang mulai di situ), 
     * lalu tandai is_start_day/is_end_day untuk buildGrid() dan badge di view. 
     **/
    private function fetchJadwals(string $tanggal): array
    {
        $jadwals = $this->model->db
            ->table('operasi.jadwal_operasi j')
            ->select([
                'j.id_jadwal',
                'j.id_ruangan',
                'j.tanggal',
                'j.waktu_mulai',
                'j.tanggal_selesai',
                'j.waktu_selesai',
                'j.id_status',
                'j.nomor_operasi',
                'po.is_cito',
                'op.nama AS nama_pasien',
                'ti.nama_tindakan',
                'ob.nama AS nama_dokter_bedah',
                'oa.nama AS nama_dokter_anestesi',
            ])
            ->join('operasi.permintaan_operasi po',      'po.id_permintaan = j.id_permintaan',   'left')
            ->join('registrasi.registrasi r',            'r.nomor_reg      = po.nomor_reg',       'left')
            ->join('role.pasien p',                      'p.id_pasien      = r.id_pasien',        'left')
            ->join('person.orang op',                    'op.id_orang      = p.id_orang',         'left')
            ->join('operasi.ref_tindakan_operasi ti',    'ti.id_tindakan   = po.id_tindakan',     'left')
            ->join('role.dokter db',                     'db.id_dokter     = j.id_dokter_bedah',  'left')
            ->join('person.orang ob',                    'ob.id_orang      = db.id_orang',        'left')
            ->join('role.dokter da',                     'da.id_dokter     = j.id_dokter_anestesi', 'left')
            ->join('person.orang oa',                    'oa.id_orang      = da.id_orang',        'left')
            ->where('j.tanggal <=', $tanggal)
            ->where('COALESCE(j.tanggal_selesai, j.tanggal) >=', $tanggal)
            ->where('j.id_status !=', 5)
            ->get()->getResultArray();

        return array_map(static function (array $j) use ($tanggal): array {
            $j['is_start_day'] = $j['tanggal'] === $tanggal;
            $j['is_end_day']   = ($j['tanggal_selesai'] ?? $j['tanggal']) === $tanggal;
            return $j;
        }, $jadwals);
    }

    /** 
     * grid[id_slot][id_ruangan] = jadwal. Slot dihitung langsung dari waktu 
     * per hari (bukan tabel lookup) karena satu jadwal bisa lewat tengah malam. 
     **/
    private function buildGrid(array $jadwals, array $slots): array
    {
        $grid = [];
        foreach ($jadwals as $j) {
            $idRuangan  = (int) $j['id_ruangan'];
            $startBound = $j['is_start_day'] ? $j['waktu_mulai'] : '00:00:00';
            $endBound   = $j['is_end_day'] ? ($j['waktu_selesai'] ?? '23:59:59') : null;

            foreach ($slots as $slot) {
                $waktuSlot = $slot['waktu_slot'];
                if ($waktuSlot < $startBound) continue;
                if ($endBound !== null && $waktuSlot >= $endBound) continue;
                $grid[(int) $slot['id_slot']][$idRuangan] = $j;
            }
        }
        return $grid;
    }

    /** 
     * Slot berurutan milik jadwal yang sama di ruangan yang sama digabung jadi satu rowspan. 
     **/
    private function buildSpans(array $ruangans, array $slots, array $grid): array
    {
        $spans = [];
        foreach ($ruangans as $ruangan) {
            $idRuangan  = (int) $ruangan['id_ruangan'];
            $prevJadwal = null;
            $firstSlot  = null;

            foreach ($slots as $slot) {
                $idSlot   = (int) $slot['id_slot'];
                $jadwal   = $grid[$idSlot][$idRuangan] ?? null;
                $idJadwal = $jadwal !== null ? (int) $jadwal['id_jadwal'] : null;

                if ($idJadwal !== null && $idJadwal === $prevJadwal) {
                    $spans[$firstSlot][$idRuangan]++;
                    $spans[$idSlot][$idRuangan] = 0;
                } else {
                    $spans[$idSlot][$idRuangan] = 1;
                    $firstSlot  = $idSlot;
                    $prevJadwal = $idJadwal;
                }
            }
        }
        return $spans;
    }
}