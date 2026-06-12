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
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,   'id_skrining',      'ID Skrining'],
                [SHOW, REQUIRED, I::TEXT,    'no_rm',            'No. Rekam Medis'],
                [SHOW, REQUIRED, I::DATE,    'tgl_skrining',     'Tanggal Skrining'],
                [SHOW, REQUIRED, I::TIME,    'jam_skrining',     'Jam Skrining'],
                [SHOW, REQUIRED, I::SELECT,  'id_kesadaran',     'Kesadaran'],
                [SHOW, REQUIRED, I::SELECT,  'id_pernafasan',    'Pernafasan'],
                [SHOW, REQUIRED, I::SELECT,  'id_skala_nyeri',   'Skala Nyeri'],
                [SHOW, REQUIRED, I::SELECT,  'id_nyeri_dada',    'Nyeri Dada'],
                [SHOW, REQUIRED, I::SELECT,  'id_batuk',         'Batuk'],
                [SHOW, REQUIRED, I::BOOL,    'is_geriatri',      'Geriatri'],
                [SHOW, REQUIRED, I::BOOL,    'is_risiko_jatuh',  'Risiko Jatuh'],
                [SHOW, REQUIRED, I::SELECT,  'id_petugas',       'Petugas'],
                [SHOW, REQUIRED, I::SELECT,  'id_keputusan',     'Keputusan'],
            ],
        );
    }
    #[\Override]
    final public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $mockBaris = [
            'id_skrining'      => '',
            'no_rm'            => $this->request->getGet('no_rm') ?? '',
            'tgl_skrining'     => date('Y-m-d'),
            'jam_skrining'     => date('H:i:s'),
            'id_kesadaran'     => '',
            'id_pernafasan'    => '',
            'id_skala_nyeri'   => '',
            'id_nyeri_dada'    => '',
            'id_batuk'         => '',
            'is_geriatri'      => '',
            'is_risiko_jatuh'  => '',
            'id_keputusan'     => '',
            'id_petugas'       => '',
        ];

        $konfig = array_values(array_filter(
            $this->get_fields_with_options(false, true),
            fn($f) => $f[2] !== 'id_skrining' && $f[2] !== 'no_rm'
        ));

        return view('admin/skrining_rawat_jalan/tambah_skrining_rj', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $konfig,
            'baris'       => $mockBaris,
            'form_action' => '/submittambah',
        ]);
    }
    #[\Override]
    final public function update_page(int|string $id): string
    {
        $breadcrumbs = [
            ['title' => 'Ubah', 'icon' => 'ubah']
        ];

        $baris = $this->model->find($id);

        $dataPasien = null;
        if (!empty($baris['no_rm'])) {
            $dataPasien = $this->model->db
                ->table('role.pasien p')
                ->select('p.nomor_rm, o.nama, o.nik, o.tanggal_lahir, jk.nama_jenis_kelamin as jenis_kelamin')
                ->join('person.orang o', 'o.id_orang = p.id_orang')
                ->join('person.jenis_kelamin jk', 'jk.id_jenis_kelamin = o.id_jenis_kelamin', 'left')
                ->where('p.nomor_rm', $baris['no_rm'])
                ->get()
                ->getRowArray();
        }

        $konfig = array_values(array_filter(
            $this->get_fields_with_options(true, true),
            fn($f) => $f[2] !== 'id_skrining' && $f[2] !== 'no_rm'
        ));

        return view('admin/skrining_rawat_jalan/tambah_skrining_rj', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $konfig,
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
            'data_pasien' => $dataPasien,
        ]);
    }
}
