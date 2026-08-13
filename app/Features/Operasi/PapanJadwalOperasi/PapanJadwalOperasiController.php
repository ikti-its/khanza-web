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

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function index(): string|RedirectResponse
    {
        $tanggalGet = (string) ($this->request->getGet('tanggal') ?? '');
        $tanggal    = $tanggalGet !== '' ? $tanggalGet : date('Y-m-d');
        $slots      = $this->fetchSlots();
        $ruangans   = $this->fetchRuangans();
        $jadwals    = $this->fetchJadwals($tanggal);

        $grid  = $this->buildGrid($jadwals, $slots);
        $spans = $this->buildSpans($ruangans, $slots, $grid);

        return view('admin/operasi/papan_jadwal_operasi', [
            'judul'       => 'Papan Jadwal Operasi',
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Papan Jadwal', 'icon' => 'detail']]),
            'tanggal'     => $tanggal,
            'slots'       => $slots,
            'ruangans'    => $ruangans,
            'grid'        => $grid,
            'spans'       => $spans,
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchSlots(): array
    {
        $builder = $this->model
            ->db
            ->table('operasi.ref_slot_operasi')
            ->orderBy('id_slot', 'ASC');

        /** @var list<array<string, mixed>> */
        return $this->model->guarded_get($builder, 'fetchSlots')->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchRuangans(): array
    {
        $builder = $this->model
            ->db
            ->table('ruangan.ruangan')
            ->select(['id_ruangan', 'nama_ruangan', 'kode_ruangan'])
            ->like('kode_ruangan', 'OK', 'after')
            ->orderBy('kode_ruangan', 'ASC');

        /** @var list<array<string, mixed>> */
        return $this->model->guarded_get($builder, 'fetchRuangans')->getResultArray();
    }

    /**
     * Ambil jadwal yang masih berlangsung di $tanggal (bukan cuma yang mulai di situ),
     * lalu tandai is_start_day/is_end_day untuk buildGrid() dan badge di view.
     *
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     **/
    private function fetchJadwals(string $tanggal): array
    {
        $builder = $this->model
            ->db
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
            ->join('operasi.permintaan_operasi po', 'po.id_permintaan = j.id_permintaan', 'left')
            ->join('registrasi.registrasi r', 'r.nomor_reg      = po.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien      = r.id_pasien', 'left')
            ->join('person.orang op', 'op.id_orang      = p.id_orang', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan   = po.id_tindakan', 'left')
            ->join('role.dokter db', 'db.id_dokter     = j.id_dokter_bedah', 'left')
            ->join('person.orang ob', 'ob.id_orang      = db.id_orang', 'left')
            ->join('role.dokter da', 'da.id_dokter     = j.id_dokter_anestesi', 'left')
            ->join('person.orang oa', 'oa.id_orang      = da.id_orang', 'left')
            ->where('j.tanggal <=', $tanggal)
            ->where('COALESCE(j.tanggal_selesai, j.tanggal) >=', $tanggal)
            ->where('j.id_status !=', 5);

        /** @var list<array<string, mixed>> $jadwals */
        $jadwals = $this->model->guarded_get($builder, 'fetchJadwals')->getResultArray();

        return array_map(static function (array $j) use ($tanggal): array {
            $j['is_start_day'] = ($j['tanggal'] ?? null) === $tanggal;
            $j['is_end_day']   = ($j['tanggal_selesai'] ?? $j['tanggal'] ?? null) === $tanggal;
            return $j;
        }, $jadwals);
    }

    /**
     * grid[id_slot][id_ruangan] = jadwal. Slot dihitung langsung dari waktu
     * per hari (bukan tabel lookup) karena satu jadwal bisa lewat tengah malam.
     *
     * @param list<array<string, mixed>> $jadwals
     * @param list<array<string, mixed>> $slots
     * @return array<int, array<int, array<string, mixed>>>
     **/
    private function buildGrid(array $jadwals, array $slots): array
    {
        $grid = [];
        foreach ($jadwals as $j) {
            $idRuangan  = (int) ($j['id_ruangan'] ?? 0);
            $startBound = (string) (($j['is_start_day'] ?? false) ? ($j['waktu_mulai'] ?? '00:00:00') : '00:00:00');
            $endBound   = ($j['is_end_day'] ?? false) ? (string) ($j['waktu_selesai'] ?? '23:59:59') : null;

            foreach ($slots as $slot) {
                $waktuSlot = (string) ($slot['waktu_slot'] ?? '');
                if ($waktuSlot < $startBound) {
                    continue;
                }
                if ($endBound !== null && $waktuSlot >= $endBound) {
                    continue;
                }
                $grid[(int) ($slot['id_slot'] ?? 0)][$idRuangan] = $j;
            }
        }
        return $grid;
    }

    /**
     * Slot berurutan milik jadwal yang sama di ruangan yang sama digabung jadi satu rowspan.
     *
     * @param list<array<string, mixed>> $ruangans
     * @param list<array<string, mixed>> $slots
     * @param array<int, array<int, array<string, mixed>>> $grid
     * @return array<int, array<int, int>>
     **/
    private function buildSpans(array $ruangans, array $slots, array $grid): array
    {
        $spans = [];
        foreach ($ruangans as $ruangan) {
            $idRuangan  = (int) ($ruangan['id_ruangan'] ?? 0);
            $prevJadwal = null;
            $firstSlot  = null;

            foreach ($slots as $slot) {
                $idSlot   = (int) ($slot['id_slot'] ?? 0);
                $jadwal   = $grid[$idSlot][$idRuangan] ?? null;
                $idJadwal = $jadwal !== null ? (int) ($jadwal['id_jadwal'] ?? 0) : null;

                if ($idJadwal !== null && $idJadwal === $prevJadwal && $firstSlot !== null) {
                    $spans[$firstSlot][$idRuangan]++;
                    $spans[$idSlot][$idRuangan] = 0;
                    continue;
                }
                $spans[$idSlot][$idRuangan] = 1;
                $firstSlot                  = $idSlot;
                $prevJadwal                 = $idJadwal;
            }
        }
        return $spans;
    }
}
