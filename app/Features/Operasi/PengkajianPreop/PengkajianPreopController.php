<?php
declare(strict_types=1);

namespace App\Features\Operasi\PengkajianPreop;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PengkajianPreopController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengkajianPreopModel(),
            [
                ['Operasi',                'operasi'],
                ['Pengkajian Pre-Operasi', 'pengkajian_pre_op'],
            ],
            'Pengkajian Pre Operasi',
            [
                A::READ,
                // A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, REQUIRED, I::INDEX, 'id_pengkajian_pre',      'ID Pengkajian Pre'],
                [HIDE, REQUIRED, I::INDEX, 'id_jadwal',              'Jadwal Operasi'],
                [SHOW, REQUIRED, I::INDEX, 'id_dokter_bedah',        'Dokter Bedah'],
                [SHOW, REQUIRED, I::TIME,  'waktu_pengkajian',       'Waktu Pengkajian'],
                [SHOW, REQUIRED, I::TEXT,  'ringkasan_klinik',       'Ringkasan Klinik'],
                [SHOW, REQUIRED, I::TEXT,  'pemeriksaan_fisik',      'Pemeriksaan Fisik'],
                [SHOW, REQUIRED, I::TEXT,  'pemeriksaan_diagnostik', 'Pemeriksaan Diagnostik'],
                [SHOW, REQUIRED, I::TEXT,  'diagnosa_pre_operasi',   'Diagnosa Pre-Operasi'],
                [SHOW, REQUIRED, I::TEXT,  'rencana_tindakan',       'Rencana Tindakan'],
                [SHOW, REQUIRED, I::TEXT,  'persiapan_khusus',       'Persiapan Khusus'],
                [SHOW, REQUIRED, I::TEXT,  'terapi_pre_operasi',     'Terapi Pre-Operasi'],
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
                'j.id_dokter_bedah',
                'po.nomor_reg',
                'ti.nama_tindakan',
                'op.nama AS nama_pasien',
                'ob.nama AS nama_dokter_bedah',
            ])
            ->join('operasi.permintaan_operasi po',   'po.id_permintaan = j.id_permintaan', 'left')
            ->join('rekam_medis.registrasi r',        'r.nomor_reg      = po.nomor_reg',    'left')
            ->join('role.pasien p',                   'p.id_pasien      = r.id_pasien',     'left')
            ->join('person.orang op',                 'op.id_orang      = p.id_orang',      'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan   = po.id_tindakan',  'left')
            ->join('role.dokter db',                  'db.id_dokter     = j.id_dokter_bedah', 'left')
            ->join('person.orang ob',                 'ob.id_orang      = db.id_orang',     'left')
            ->where('j.id_jadwal', $idJadwal)
            ->get()->getRowArray() ?? [];
    }

    private function buildViewData(array $jadwal, array $record, string $formAction): array
    {
        $isCreate = $formAction === '/submittambah/';
        return [
            'judul'       => ($isCreate ? 'Tambah ' : 'Ubah ') . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => $isCreate ? 'Tambah' : 'Ubah', 'icon' => '']]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => $formAction,
            'baris'       => $record,
            'jadwal'      => $jadwal,
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

        return view('admin/operasi/tambah_pengkajian_preop',
            $this->buildViewData($jadwal, [
                'id_jadwal'         => $idJadwal,
                'id_dokter_bedah'   => $jadwal['id_dokter_bedah']   ?? '',
                'nama_dokter_bedah' => $jadwal['nama_dokter_bedah'] ?? '',
                'rencana_tindakan'  => $jadwal['nama_tindakan']     ?? '',
            ], '/submittambah/'));
    }

    #[\Override]
    public function update_page(int|string $id): string
    {
        $record   = $this->model->find_one($id);
        $idJadwal = (int) ($record['id_jadwal'] ?? 0);
        $jadwal   = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];

        $record['nama_dokter_bedah'] = $jadwal['nama_dokter_bedah'] ?? '';

        return view('admin/operasi/tambah_pengkajian_preop',
            $this->buildViewData($jadwal, $record, '/submitedit/' . $id));
    }
}
