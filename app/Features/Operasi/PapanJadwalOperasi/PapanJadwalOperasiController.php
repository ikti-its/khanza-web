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
        $tanggal = $this->request->getGet('tanggal') ?: date('Y-m-d');

        $slots = $this->model->db
            ->table('operasi.ref_slot_operasi')
            ->orderBy('id_slot', 'ASC')
            ->get()->getResultArray();

        $ruangans = $this->model->db
            ->table('ruangan.ruangan')
            ->select(['id_ruangan', 'nama_ruangan', 'kode_ruangan'])
            ->like('kode_ruangan', 'OK', 'after')
            ->orderBy('kode_ruangan', 'ASC')
            ->get()->getResultArray();

        $jadwals = $this->model->db
            ->table('operasi.jadwal_operasi j')
            ->select([
                'j.id_jadwal',
                'j.id_ruangan',
                'j.waktu_mulai',
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
            ->where('j.tanggal', $tanggal)
            ->where('j.id_status !=', 5)
            ->get()->getResultArray();

        $jadwalSlots = [];
        if (!empty($jadwals)) {
            $idJadwals = array_column($jadwals, 'id_jadwal');
            $slotRows  = $this->model->db
                ->table('operasi.jadwal_operasi_slot')
                ->select(['id_jadwal', 'id_slot'])
                ->whereIn('id_jadwal', $idJadwals)
                ->get()->getResultArray();

            foreach ($slotRows as $sr) {
                $jadwalSlots[(int) $sr['id_jadwal']][] = (int) $sr['id_slot'];
            }
        }

        // grid[id_slot][id_ruangan] = jadwal row
        $grid = [];
        foreach ($jadwals as $j) {
            $idJadwal  = (int) $j['id_jadwal'];
            $idRuangan = (int) $j['id_ruangan'];
            $usedSlots = $jadwalSlots[$idJadwal] ?? [];

            foreach ($usedSlots as $idSlot) {
                $grid[$idSlot][$idRuangan] = $j;
            }
        }

        // Calculate rowspan: consecutive slots of the same jadwal in the same room merge into one cell
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
}