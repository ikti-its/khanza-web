<?php
declare(strict_types=1);

namespace App\Features\Operasi\LembarOperasi;

use App\Core\Controller\ControllerTemplate;
use App\Features\Operasi\JadwalOperasi\JadwalOperasiModel;
use CodeIgniter\HTTP\RedirectResponse;

final class LembarOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JadwalOperasiModel(),
            [
                ['Operasi',         'operasi'],
                ['Lembar Operasi',  'lembar_operasi'],
            ],
            'Lembar Operasi',
            [],
            [],
        );
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    private function fetchJadwal(int $idJadwal): array
    {
        return $this->model->db
            ->table('operasi.jadwal_operasi j')
            ->select([
                'j.id_jadwal',
                'j.tanggal',
                'j.waktu_mulai',
                'j.waktu_selesai',
                'j.id_status',
                'rs.nama_status',
                'po.id_permintaan',
                'po.nomor_reg',
                'po.tanggal_minta',
                'po.is_cito',
                'ti.nama_tindakan',
                'op.nama AS nama_pasien',
                'od.nama AS nama_dokter_peminta',
                'ru.nama_ruangan',
                'ob.nama AS nama_dokter_bedah',
                'oa.nama AS nama_dokter_anestesi',
            ])
            ->join('operasi.permintaan_operasi po',      'po.id_permintaan = j.id_permintaan',   'left')
            ->join('registrasi.registrasi r',           'r.nomor_reg      = po.nomor_reg',       'left')
            ->join('role.pasien p',                      'p.id_pasien      = r.id_pasien',        'left')
            ->join('person.orang op',                    'op.id_orang      = p.id_orang',         'left')
            ->join('role.dokter d',                      'd.id_dokter      = po.id_dokter',       'left')
            ->join('person.orang od',                    'od.id_orang      = d.id_orang',         'left')
            ->join('operasi.ref_tindakan_operasi ti',    'ti.id_tindakan   = po.id_tindakan',     'left')
            ->join('ruangan.ruangan ru',                 'ru.id_ruangan    = j.id_ruangan',       'left')
            ->join('role.dokter db',                     'db.id_dokter     = j.id_dokter_bedah',  'left')
            ->join('person.orang ob',                    'ob.id_orang      = db.id_orang',        'left')
            ->join('role.dokter da',                     'da.id_dokter     = j.id_dokter_anestesi', 'left')
            ->join('person.orang oa',                    'oa.id_orang      = da.id_orang',        'left')
            ->join('operasi.ref_status_operasi rs',      'rs.id_status     = j.id_status',        'left')
            ->where('j.id_jadwal', $idJadwal)
            ->get()
            ->getRowArray() ?? [];
    }

    public function fetchTagihanId(int $idJadwal): ?int
    {
        $row = $this->model->db
            ->table('operasi.tagihan_operasi')
            ->select('id_tagihan')
            ->where('id_jadwal', $idJadwal)
            ->get()->getRowArray();

        return $row ? (int) $row['id_tagihan'] : null;
    }

    private function buildForms(int $idJadwal): array
    {
        $sql = "SELECT
            (SELECT id_pengkajian_pre   FROM operasi.pengkajian_preop          WHERE id_jadwal = ?) AS pengkajian_preop,
            (SELECT id_pre_anestesi     FROM operasi.pengkajian_pre_anestesi   WHERE id_jadwal = ?) AS pengkajian_pre_anestesi,
            (SELECT id_checklist        FROM operasi.checklist_pre_operasi     WHERE id_jadwal = ?) AS checklist_pre_operasi,
            (SELECT id_signin           FROM operasi.signin_sebelum_anestesi   WHERE id_jadwal = ?) AS signin_sebelum_anestesi,
            (SELECT id_pengkajian       FROM operasi.pengkajian_pre_induksi    WHERE id_jadwal = ?) AS pengkajian_pre_induksi,
            (SELECT id_timeout          FROM operasi.time_out_sebelum_insisi   WHERE id_jadwal = ?) AS time_out_sebelum_insisi,
            (SELECT id_catatan_anestesi FROM operasi.catatan_anestesi_sedasi   WHERE id_jadwal = ?) AS catatan_anestesi_sedasi,
            (SELECT id_signout          FROM operasi.signout_sebelum_tutupluka WHERE id_jadwal = ?) AS signout_sebelum_tutupluka,
            (SELECT id_catatan_paska    FROM operasi.catatan_paska_operasi     WHERE id_jadwal = ?) AS catatan_paska_operasi,
            (SELECT id_checklist_post   FROM operasi.checklist_postop          WHERE id_jadwal = ?) AS checklist_postop,
            (SELECT id_skor_aldrette    FROM operasi.skor_aldrette             WHERE id_jadwal = ?) AS skor_aldrette,
            (SELECT id_skor_steward     FROM operasi.skor_steward              WHERE id_jadwal = ?) AS skor_steward,
            (SELECT id_skor_bromage     FROM operasi.skor_bromage              WHERE id_jadwal = ?) AS skor_bromage,
            (SELECT id_penyerahan       FROM operasi.penyerahan_pasien         WHERE id_jadwal = ?) AS penyerahan_pasien";

        $bindings = array_fill(0, 14, $idJadwal);
        $existingRecords = $this->model->db->query($sql, $bindings)->getRowArray() ?? [];

        // Helper mapper untuk array
        $entry = fn(string $label, string $slug, string $table): array => [
            'label'     => $label,
            'tambah'    => "/operasi/{$slug}/tambah",
            'ubah'      => "/operasi/{$slug}/edit",
            'record_id' => !empty($existingRecords[$table]) ? (int) $existingRecords[$table] : null,
        ];

        return [
            'Pra-Operasi' => [
                $entry('Pengkajian Pre-Operasi',  'pengkajian-pre-operasi',  'pengkajian_preop'),
                $entry('Pengkajian Pre-Anestesi', 'pengkajian-pre-anestesi', 'pengkajian_pre_anestesi'),
                $entry('Checklist Pre-Operasi',   'checklist-pre-operasi',   'checklist_pre_operasi'),
            ],
            'Intrabedah — Sebelum Induksi & Insisi' => [
                $entry('Sign-in Sebelum Anestesi',  'sign-in-sebelum-anestesi', 'signin_sebelum_anestesi'),
                $entry('Pengkajian Pre-Induksi',    'pengkajian-pre-induksi',   'pengkajian_pre_induksi'),
                $entry('Time Out Sebelum Insisi',   'time-out-sebelum-insisi',  'time_out_sebelum_insisi'),
                $entry('Catatan Anestesi & Sedasi', 'catatan-anestesi-sedasi',  'catatan_anestesi_sedasi'),
            ],
            'Intrabedah — Sebelum Tutup Luka' => [
                $entry('Sign-out Sebelum Tutup Luka', 'sign-out-sebelum-tutup-luka', 'signout_sebelum_tutupluka'),
            ],
            'Pasca Operasi' => [
                $entry('Catatan Pasca Operasi',  'catatan-paska-operasi',  'catatan_paska_operasi'),
                $entry('Checklist Post-Operasi', 'checklist-post-operasi', 'checklist_postop'),
                $entry('Skor Aldrette',          'skor-aldrette',          'skor_aldrette'),
                $entry('Skor Steward',           'skor-steward',           'skor_steward'),
                $entry('Skor Bromage',           'skor-bromage',           'skor_bromage'),
            ],
            'Transfer' => [
                $entry('Penyerahan Pasien', 'penyerahan-pasien', 'penyerahan_pasien'),
            ],
        ];
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    #[\Override]
    final public function index(): string|RedirectResponse
    {
        $idJadwal = (int) ($this->request->getGet('id_jadwal') ?? 0);

        if ($idJadwal === 0) {
            return redirect()->to('/operasi/jadwal-operasi/data');
        }

        $jadwal = $this->fetchJadwal($idJadwal);

        if (empty($jadwal)) {
            session()->setFlashdata('error', 'Jadwal operasi tidak ditemukan.');
            return redirect()->to('/operasi/jadwal-operasi/data');
        }

        return view('admin/operasi/lembar_operasi', [
            'judul'       => 'Lembar Operasi',
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Detail', 'icon' => 'detail']]),
            'jadwal'      => $jadwal,
            'forms'       => $this->buildForms($idJadwal),
            'id_jadwal'   => $idJadwal,
            'tagihan_id'  => $this->fetchTagihanId($idJadwal),
            'mulai_url'   => $this->get_uri_path() . '/submitedit/' . $idJadwal,
        ]);
    }

    // -------------------------------------------------------------------------
    // Actions
    // -------------------------------------------------------------------------

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        $newStatus = (int) ($this->request->getPost('id_status') ?? 0);
        
        if (!in_array($newStatus, [3, 4, 5], true)) {
            session()->setFlashdata('error', 'Status tidak valid.');
            return redirect()->to($this->get_uri_path() . '/data?id_jadwal=' . $id);
        }

        $messages = [
            3 => 'Status diperbarui: Proses Operasi.',
            4 => 'Operasi telah diselesaikan.',
            5 => 'Operasi dibatalkan.',
        ];

        try {
            $this->model->update($id, ['id_status' => $newStatus]);
            session()->setFlashdata('success', $messages[$newStatus]);
        } catch (\Exception $e) {
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException 
                ? $this->friendly_db_error($e) 
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
        }

        return redirect()->to($this->get_uri_path() . '/data?id_jadwal=' . $id);
    }
}
