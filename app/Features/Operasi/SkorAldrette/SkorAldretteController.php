<?php
declare(strict_types=1);

namespace App\Features\Operasi\SkorAldrette;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class SkorAldretteController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SkorAldretteModel(),
            [
                ['Operasi',       'operasi'],
                ['Skor Aldrette', 'skor_aldrette'],
            ],
            'Skor Aldrette',
            [
                A::READ,
                // A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,      OPTIONAL, I::INDEX,  'id_skor_aldrette',   'ID Skor Aldrette'],
                [SHOW,      REQUIRED, I::INDEX,  'id_jadwal',          'Jadwal Operasi'],
                [SHOW,      REQUIRED, I::INDEX,  'id_petugas',         'Petugas'],
                [SHOW,      REQUIRED, I::INDEX,  'id_dokter_anestesi', 'Dokter Anestesi'],
                [SHOW,      REQUIRED, I::DTIME,  'waktu_penilaian',    'Waktu Penilaian'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'skor_aktivitas',     'Skor Aktivitas'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'skor_respirasi',     'Skor Respirasi'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'skor_tekanan_darah', 'Skor Tekanan Darah'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'skor_kesadaran',     'Skor Kesadaran'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'skor_warna_kulit',   'Skor Warna Kulit'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_boleh_pindah',    'Boleh Pindah'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'catatan_keluar',     'Catatan Keluar'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'instruksi_rr',       'Instruksi RR'],
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
                'j.id_dokter_anestesi',
                'po.nomor_reg',
                'po.id_tindakan',
                'ti.nama_tindakan',
                'op.nama AS nama_pasien',
                'oa.nama AS nama_dokter_anestesi',
            ])
            ->join('operasi.permintaan_operasi po',   'po.id_permintaan = j.id_permintaan',      'left')
            ->join('rekam_medis.registrasi r',        'r.nomor_reg      = po.nomor_reg',         'left')
            ->join('role.pasien p',                   'p.id_pasien      = r.id_pasien',          'left')
            ->join('person.orang op',                 'op.id_orang      = p.id_orang',           'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan   = po.id_tindakan',       'left')
            ->join('role.dokter da',                  'da.id_dokter     = j.id_dokter_anestesi', 'left')
            ->join('person.orang oa',                 'oa.id_orang      = da.id_orang',          'left')
            ->where('j.id_jadwal', $idJadwal)
            ->get()->getRowArray() ?? [];
    }

    private function fetchOptions(): array
    {
        $db = $this->model->db;
        return [
            'aktivitas'     => $db->table('operasi.ref_aldrette_aktivitas')
                ->select('id_aktivitas, nama_skala, nilai')->orderBy('nilai')->get()->getResultArray(),
            'respirasi'     => $db->table('operasi.ref_aldrette_respirasi')
                ->select('id_respirasi, nama_skala, nilai')->orderBy('nilai')->get()->getResultArray(),
            'tekanan_darah' => $db->table('operasi.ref_aldrette_tekanan_darah')
                ->select('id_td, nama_skala, nilai')->orderBy('nilai')->get()->getResultArray(),
            'kesadaran'     => $db->table('operasi.ref_aldrette_kesadaran')
                ->select('id_kesadaran, nama_skala, nilai')->orderBy('nilai')->get()->getResultArray(),
            'warna_kulit'   => $db->table('operasi.ref_aldrette_warna_kulit')
                ->select('id_warna, nama_skala, nilai')->orderBy('nilai')->get()->getResultArray(),
        ];
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

    private function fetchTindakanName(int $idTindakan): string
    {
        $row = $this->model->db
            ->table('operasi.ref_tindakan_operasi')
            ->select('nama_tindakan')
            ->where('id_tindakan', $idTindakan)
            ->get()->getRowArray();
        return $row['nama_tindakan'] ?? '';
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
            'options'     => $this->fetchOptions(),
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

        return view('admin/operasi/tambah_skor_aldrette',
            $this->buildViewData($jadwal, [
                'id_jadwal'            => $idJadwal,
                'id_tindakan'          => $jadwal['id_tindakan']          ?? '',
                'nama_tindakan'        => $jadwal['nama_tindakan']        ?? '',
                'id_dokter_anestesi'   => $jadwal['id_dokter_anestesi']   ?? '',
                'nama_dokter_anestesi' => $jadwal['nama_dokter_anestesi'] ?? '',
            ], '/submittambah/'));
    }

    #[\Override]
    public function update_page(int|string $id): string
    {
        $record   = $this->model->find_one($id);
        $idJadwal = (int) ($record['id_jadwal'] ?? 0);
        $jadwal   = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];

        if (($idT  = (int) ($record['id_tindakan']        ?? 0)) > 0) {
            $record['nama_tindakan']        = $this->fetchTindakanName($idT);
        }
        if (($idP  = (int) ($record['id_petugas']         ?? 0)) > 0) {
            $record['nama_petugas']         = $this->fetchNamaRole('petugas', 'id_petugas', $idP);
        }
        if (($idDa = (int) ($record['id_dokter_anestesi'] ?? 0)) > 0) {
            $record['nama_dokter_anestesi'] = $this->fetchNamaRole('dokter', 'id_dokter', $idDa);
        }

        return view('admin/operasi/tambah_skor_aldrette',
            $this->buildViewData($jadwal, $record, '/submitedit/' . $id));
    }
}
