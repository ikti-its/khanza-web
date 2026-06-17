<?php
declare(strict_types=1);

namespace App\Features\Operasi\PengkajianPreAnestesi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PengkajianPreAnestesiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengkajianPreAnestesiModel(),
            [
                ['Operasi',                 'operasi'],
                ['Pengkajian Pre Anestesi', 'pengkajian_pre_anestesi'],
            ],
            'Pengkajian Pre Anestesi',
            [
                A::READ,
                // A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_pre_anestesi',      'ID Pre Anestesi'],
                [HIDE, REQUIRED, I::INDEX,  'id_jadwal',            'Jadwal Operasi'],
                [HIDE, REQUIRED, I::INDEX,  'id_dokter_anestesi',   'Dokter Anestesi'],
                [SHOW, REQUIRED, I::TIME,   'waktu_pengkajian',     'Waktu Pengkajian'],
                [SHOW, REQUIRED, I::TEXT,   'diagnosa',             'Diagnosa'],
                [SHOW, REQUIRED, I::TEXT,   'rencana_tindakan',     'Rencana Tindakan'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_operasi',      'Tanggal Operasi'],
                [SHOW, REQUIRED, I::NUMBER, 'tinggi_badan',         'Tinggi Badan'],
                [SHOW, REQUIRED, I::NUMBER, 'berat_badan',          'Berat Badan'],
                [SHOW, REQUIRED, I::NUMBER, 'sistolik',             'Sistolik'],
                [SHOW, REQUIRED, I::NUMBER, 'diastolik',            'Diastolik'],
                [SHOW, REQUIRED, I::NUMBER, 'saturasi_o2',          'Saturasi O2'],
                [SHOW, REQUIRED, I::NUMBER, 'nadi',                 'Nadi'],
                [SHOW, REQUIRED, I::TEMP,   'suhu',                 'Suhu'],
                [SHOW, REQUIRED, I::NUMBER, 'pernapasan',           'Pernapasan'],
                [SHOW, REQUIRED, I::TEXT,   'fisik_cardiovascular', 'Fisik Cardiovascular'],
                [SHOW, REQUIRED, I::TEXT,   'fisik_paru',           'Fisik Paru'],
                [SHOW, REQUIRED, I::TEXT,   'fisik_abdomen',        'Fisik Abdomen'],
                [SHOW, REQUIRED, I::TEXT,   'fisik_extrimitas',     'Fisik Ekstrimitas'],
                [SHOW, REQUIRED, I::TEXT,   'fisik_endokrin',       'Fisik Endokrin'],
                [SHOW, REQUIRED, I::TEXT,   'fisik_ginjal',         'Fisik Ginjal'],
                [SHOW, REQUIRED, I::TEXT,   'fisik_obat_obatan',    'Fisik Obat-obatan'],
                [SHOW, REQUIRED, I::TEXT,   'fisik_laboratorium',   'Fisik Laboratorium'],
                [SHOW, REQUIRED, I::TEXT,   'fisik_penunjang',      'Fisik Penunjang'],
                [SHOW, REQUIRED, I::TEXT,   'alergi_obat',          'Alergi Obat'],
                [SHOW, REQUIRED, I::TEXT,   'alergi_lainnya',       'Alergi Lainnya'],
                [SHOW, REQUIRED, I::TEXT,   'riwayat_terapi',       'Riwayat Terapi'],
                [SHOW, REQUIRED, I::SELECT, 'is_merokok',           'Merokok'],
                [SHOW, OPTIONAL, I::NUMBER, 'jumlah_rokok',         'Jumlah Rokok'],
                [SHOW, REQUIRED, I::SELECT, 'is_alkohol',           'Alkohol'],
                [SHOW, OPTIONAL, I::NUMBER, 'jumlah_alkohol',       'Jumlah Alkohol'],
                [SHOW, REQUIRED, I::INDEX,  'id_obat_bebas',        'Obat Bebas'],
                [SHOW, REQUIRED, I::TEXT,   'ket_obat',             'Keterangan Obat'],
                [SHOW, REQUIRED, I::TEXT,   'rw_cardiovascular',    'Riwayat Cardiovascular'],
                [SHOW, REQUIRED, I::TEXT,   'rw_respiratory',       'Riwayat Respiratory'],
                [SHOW, REQUIRED, I::TEXT,   'rw_endocrine',         'Riwayat Endocrine'],
                [SHOW, REQUIRED, I::TEXT,   'rw_lainnya',           'Riwayat Lainnya'],
                [SHOW, REQUIRED, I::INDEX,  'id_rencana_anestesi',  'Rencana Anestesi'],
                [SHOW, REQUIRED, I::INDEX,  'id_asa',               'ASA'],
                [SHOW, REQUIRED, I::TIME,   'waktu_puasa',          'Waktu Puasa'],
                [SHOW, REQUIRED, I::TEXT,   'rencana_perawatan',    'Rencana Perawatan'],
                [SHOW, REQUIRED, I::TEXT,   'catatan_khusus',       'Catatan Khusus'],
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
                'j.tanggal',
                'j.id_dokter_anestesi',
                'po.nomor_reg',
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
            'rencana_anestesi' => $db->table('operasi.ref_rencana_anestesi')
                ->select('id_rencana_anestesi, nama_rencana')->get()->getResultArray(),
            'asa'              => $db->table('operasi.ref_angka_asa')
                ->select('id_asa, nama_asa')->get()->getResultArray(),
            'obat_bebas'       => $db->table('operasi.ref_obat_bebas')
                ->select('id_obat_bebas, nama_kategori')->get()->getResultArray(),
        ];
    }

    // Data Mapper for header
    private function buildHeaderData(array $rawPost): array
    {
        return [
            'id_jadwal'            => (int) ($rawPost['id_jadwal']            ?? 0) ?: null,
            'id_dokter_anestesi'   => (int) ($rawPost['id_dokter_anestesi']   ?? 0) ?: null,
            'waktu_pengkajian'     => $rawPost['waktu_pengkajian']            ?? null,
            'diagnosa'             => $rawPost['diagnosa']                    ?? null,
            'rencana_tindakan'     => $rawPost['rencana_tindakan']            ?? null,
            'tanggal_operasi'      => $rawPost['tanggal_operasi']             ?? null,
            'tinggi_badan'         => $rawPost['tinggi_badan']                ?? null,
            'berat_badan'          => $rawPost['berat_badan']                 ?? null,
            'sistolik'             => $rawPost['sistolik']                    ?? null,
            'diastolik'            => $rawPost['diastolik']                   ?? null,
            'saturasi_o2'          => $rawPost['saturasi_o2']                 ?? null,
            'nadi'                 => $rawPost['nadi']                        ?? null,
            'suhu'                 => $rawPost['suhu']                        ?? null,
            'pernapasan'           => $rawPost['pernapasan']                  ?? null,
            'fisik_cardiovascular' => $rawPost['fisik_cardiovascular']        ?? null,
            'fisik_paru'           => $rawPost['fisik_paru']                  ?? null,
            'fisik_abdomen'        => $rawPost['fisik_abdomen']               ?? null,
            'fisik_extrimitas'     => $rawPost['fisik_extrimitas']            ?? null,
            'fisik_endokrin'       => $rawPost['fisik_endokrin']              ?? null,
            'fisik_ginjal'         => $rawPost['fisik_ginjal']                ?? null,
            'fisik_obat_obatan'    => $rawPost['fisik_obat_obatan']           ?? null,
            'fisik_laboratorium'   => $rawPost['fisik_laboratorium']          ?? null,
            'fisik_penunjang'      => $rawPost['fisik_penunjang']             ?? null,
            'alergi_obat'          => $rawPost['alergi_obat']                 ?? null,
            'alergi_lainnya'       => $rawPost['alergi_lainnya']              ?? null,
            'riwayat_terapi'       => $rawPost['riwayat_terapi']              ?? null,
            'is_merokok'           => $rawPost['is_merokok']                  ?? null,
            'jumlah_rokok'         => $rawPost['jumlah_rokok']                ?? null,
            'is_alkohol'           => $rawPost['is_alkohol']                  ?? null,
            'jumlah_alkohol'       => $rawPost['jumlah_alkohol']              ?? null,
            'id_obat_bebas'        => (int) ($rawPost['id_obat_bebas']        ?? 0) ?: null,
            'ket_obat'             => $rawPost['ket_obat']                    ?? null,
            'rw_cardiovascular'    => $rawPost['rw_cardiovascular']           ?? null,
            'rw_respiratory'       => $rawPost['rw_respiratory']              ?? null,
            'rw_endocrine'         => $rawPost['rw_endocrine']                ?? null,
            'rw_lainnya'           => $rawPost['rw_lainnya']                  ?? null,
            'id_rencana_anestesi'  => (int) ($rawPost['id_rencana_anestesi']  ?? 0) ?: null,
            'id_asa'               => (int) ($rawPost['id_asa']               ?? 0) ?: null,
            'waktu_puasa'          => $rawPost['waktu_puasa']                 ?? null,
            'rencana_perawatan'    => $rawPost['rencana_perawatan']           ?? null,
            'catatan_khusus'       => $rawPost['catatan_khusus']              ?? null,
        ];
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

        return view('admin/operasi/tambah_pengkajian_pre_anestesi',
            $this->buildViewData($jadwal, [
                'id_jadwal'            => $idJadwal,
                'id_dokter_anestesi'   => $jadwal['id_dokter_anestesi']   ?? '',
                'nama_dokter_anestesi' => $jadwal['nama_dokter_anestesi'] ?? '',
                'tanggal_operasi'      => $jadwal['tanggal']              ?? '',
                'rencana_tindakan'     => $jadwal['nama_tindakan']        ?? '',
            ], '/submittambah/'));
    }

    #[\Override]
    public function update_page(int|string $id): string
    {
        $record   = $this->model->find_one($id);
        $idJadwal = (int) ($record['id_jadwal'] ?? 0);
        $jadwal   = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];

        $record['nama_dokter_anestesi'] = $jadwal['nama_dokter_anestesi'] ?? '';

        return view('admin/operasi/tambah_pengkajian_pre_anestesi',
            $this->buildViewData($jadwal, $record, '/submitedit/' . $id));
    }
}
