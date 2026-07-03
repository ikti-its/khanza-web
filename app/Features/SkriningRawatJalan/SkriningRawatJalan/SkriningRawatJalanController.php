<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\SkriningRawatJalan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class SkriningRawatJalanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SkriningRawatJalanModel(),
            [
                ['Skrining Rawat Jalan', 'skrining_rawat_jalan'],
            ],
            'Skrining Rawat Jalan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::REGISTRASI,
                A::PRINT,
            ],
            [
                [HIDE,      OPTIONAL, I::INDEX,   'id_skrining',      'ID Skrining'],
                [SHOW,      REQUIRED, I::TEXT,    'no_rm',            'No. Rekam Medis'],
                [SHOW,      REQUIRED, I::DATE,    'tgl_skrining',     'Tanggal Skrining'],
                [SHOW,      REQUIRED, I::TIME,    'jam_skrining',     'Jam Skrining'],
                [FORM_ONLY, REQUIRED, I::SELECT,  'id_kesadaran',     'Kesadaran'],
                [FORM_ONLY, REQUIRED, I::SELECT,  'id_pernafasan',    'Pernafasan'],
                [FORM_ONLY, REQUIRED, I::SELECT,  'id_skala_nyeri',   'Skala Nyeri'],
                [FORM_ONLY, REQUIRED, I::SELECT,  'id_nyeri_dada',    'Nyeri Dada'],
                [FORM_ONLY, REQUIRED, I::SELECT,  'id_batuk',         'Batuk'],
                [FORM_ONLY, REQUIRED, I::BOOL,    'is_geriatri',      'Geriatri'],
                [FORM_ONLY, REQUIRED, I::BOOL,    'is_risiko_jatuh',  'Risiko Jatuh'],
                [SHOW,      OPTIONAL, I::SELECT,  'id_unit',          'Unit Tujuan'],
                [SHOW,      REQUIRED, I::SELECT,  'id_petugas',       'Petugas'],
                [SHOW,      REQUIRED, I::SELECT,  'id_keputusan',     'Keputusan'],
            ],
        );

        $this->row_alert = ['value' => 'alert_igd', 'threshold' => 'alert_max'];
    }

    #[\Override]
    protected function after_read(array &$data_tabel): void
    {
        foreach ($data_tabel as &$row) {
            $row['alert_igd'] = ((int) ($row['id_keputusan'] ?? 1)) === 2 ? 0 : 1;
            $row['alert_max'] = 1;
        }
    }

    // ──────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────

    private function getKonfig(bool $isUpdate = false): array
    {
        return array_values(array_filter(
            $this->get_fields_with_options($isUpdate, true),
            fn($f) => !in_array($f[2], ['id_skrining', 'no_rm', 'id_unit', 'id_petugas'], true)
        ));
    }

    private function fetchDataPasienByRm(string $noRm): array
    {
        return $this->model->db
            ->table('role.pasien p')
            ->select('p.nomor_rm, o.nama, o.nik, o.tanggal_lahir, jk.nama_jenis_kelamin as jenis_kelamin')
            ->join('person.orang o', 'o.id_orang = p.id_orang')
            ->join('person.jenis_kelamin jk', 'jk.id_jenis_kelamin = o.id_jenis_kelamin', 'left')
            ->where('p.nomor_rm', $noRm)
            ->get()
            ->getRowArray() ?? [];
    }

    // ──────────────────────────────────────────────────────────
    // PRINT
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function print(int|string $id): string
    {
        $baris = $this->model->find_one($id);
        if (empty($baris)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        $dataPasien = !empty($baris['no_rm']) ? $this->fetchDataPasienByRm($baris['no_rm']) : [];

        return view('components/cetak/cetak_skrining_rawat_jalan', [
            'judul'      => 'Cetak Skrining Rawat Jalan',
            'baris'      => $baris,
            'dataPasien' => $dataPasien,
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // PAGES
    // ──────────────────────────────────────────────────────────

    #[\Override]
    final public function create_page(): string
    {
        $mockBaris = [
            'id_skrining' => '', 'no_rm' => $this->request->getGet('no_rm') ?? '',
            'tgl_skrining' => date('Y-m-d'), 'jam_skrining' => date('H:i:s'),
            'id_kesadaran' => '', 'id_pernafasan' => '', 'id_skala_nyeri' => '',
            'id_nyeri_dada' => '', 'id_batuk' => '', 'is_geriatri' => '',
            'is_risiko_jatuh' => '', 'id_unit' => '', 'nama_unit' => '',
            'id_keputusan' => '', 'id_petugas' => '',
        ];

        return view('admin/skrining_rawat_jalan/tambah_skrining_rj', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $this->getKonfig(),
            'baris'       => $mockBaris,
            'form_action' => '/submittambah',
        ]);
    }

    #[\Override]
    final public function update_page(int|string $id): string
    {
        $baris = $this->model->find_one($id);
        
        if (empty($baris)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        $dataPasien = !empty($baris['no_rm']) ? $this->fetchDataPasienByRm($baris['no_rm']) : null;

        return view('admin/skrining_rawat_jalan/tambah_skrining_rj', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $this->getKonfig(true),
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
            'data_pasien' => $dataPasien,
        ]);
    }
}
