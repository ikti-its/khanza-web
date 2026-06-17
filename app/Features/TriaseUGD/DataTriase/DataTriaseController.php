<?php
declare(strict_types=1);

namespace App\Features\TriaseUGD\DataTriase;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class DataTriaseController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DataTriaseModel(),
            [
                ['Triase UGD',  'triase_ugd'],
                ['Data Triase', 'data_triase'],
            ],
            'Data Triase',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_triase',             'ID Triase'],
                [SHOW, REQUIRED, I::INDEX,  'id_registrasi',         'ID Registrasi'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_kunjungan',     'Tanggal Kunjungan'],
                [SHOW, REQUIRED, I::SELECT, 'id_cara_masuk',         'Cara Masuk'],
                [SHOW, REQUIRED, I::SELECT, 'id_alat_transportasi',  'Alat Transportasi'],
                [SHOW, REQUIRED, I::SELECT, 'id_alasan_kedatangan',  'Alasan Kedatangan'],
                [SHOW, REQUIRED, I::TEXT,   'keterangan_kedatangan', 'Keterangan'],
                [SHOW, REQUIRED, I::INDEX,  'id_macam_kasus',        'ID Macam Kasus'],
                [SHOW, REQUIRED, I::NUMBER, 'sistolik',              'Tekanan Sistolik'],
                [SHOW, REQUIRED, I::NUMBER, 'diastolik',             'Tekanan Diastolik'],
                [SHOW, REQUIRED, I::NUMBER, 'nadi',                  'Nadi (x/menit)'],
                [SHOW, REQUIRED, I::NUMBER, 'pernapasan',            'Pernapasan (x/menit)'],
                [SHOW, REQUIRED, I::TEMP,   'suhu',                  'Suhu'],
                [SHOW, REQUIRED, I::NUMBER, 'saturasi_o2',           'Saturasi O2 (%)'],
                [SHOW, REQUIRED, I::NUMBER, 'nyeri',                 'Nyeri (0-10)'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Triase UGD
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $konfigTriase = $this->get_fields_with_options(false, true);

        $controllerPrimer   = new \App\Features\TriaseUGD\DataTriasePrimer\DataTriasePrimerController();
        $controllerSekunder = new \App\Features\TriaseUGD\DataTriaseSekunder\DataTriaseSekunderController();
        $konfigTriasePrimer = $controllerPrimer->get_fields_with_options(false, true);
        $konfigTriaseSekunder = $controllerSekunder->get_fields_with_options(false, true);

        $dbPemeriksaan = new \App\Features\TriaseUGD\TriasePemeriksaan\TriasePemeriksaanModel();
        $masterPemeriksaan = $dbPemeriksaan->findAll();

        $dbSkala = new \App\Features\TriaseUGD\TriaseSkala\TriaseSkalaModel();
        $masterSkala = $dbSkala->findAll();

        $mockBaris      = [];
        $konfigGabungan = [];

        foreach ($konfigTriase as $field) {
            $namaKolom = $field[2];

            if ($namaKolom === 'id_triase') {
                continue;
            }
            
            $isTanggal = ($field[3] === 'tanggal' || str_contains($namaKolom, 'tanggal') || $field[3] === 'dtime');
            $mockBaris[$namaKolom] = $isTanggal ? date('Y-m-d\TH:i') : '';

            $konfigGabungan[] = $field;
        }

        foreach ($konfigTriasePrimer as $fPrimer) {
            $namaKolomPrimer = $fPrimer[2];

            if ($namaKolomPrimer === 'id_triase_primer' || $namaKolomPrimer === 'id_triase') {
                continue;
            }

            $isTanggalPrimer = ($fPrimer[3] === 'tanggal' || str_contains($namaKolomPrimer, 'tanggal') || $fPrimer[3] === 'dtime');
            $mockBaris[$namaKolomPrimer] = $isTanggalPrimer ? date('Y-m-d\TH:i') : '';

            $konfigGabungan[] = $fPrimer;
        }

        foreach ($konfigTriaseSekunder as $fSekunder) {
            $namaKolomSekunder = $fSekunder[2];
            
            if ($namaKolomSekunder === 'id_triase_sekunder' || $namaKolomSekunder === 'id_triase') {
                continue;
            }

            if (array_key_exists($namaKolomSekunder, $mockBaris)) {
                continue; 
            }

            $isTanggalSekunder = ($fSekunder[3] === 'tanggal' || str_contains($namaKolomSekunder, 'tanggal') || $fSekunder[3] === 'dtime');
            $mockBaris[$namaKolomSekunder] = $isTanggalSekunder ? date('Y-m-d\TH:i') : '';

            $konfigGabungan[] = $fSekunder;
        }

        return view('admin/triaseugd/tambah_datatriase', [
            'judul'                 => 'Tambah ' . $this->title,
            'breadcrumbs'           => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'            => $this->get_uri_path(),
            'kolom_id'              => $this->model->primaryKey,
            'konfig'                => $konfigGabungan,
            'baris'                 => $mockBaris,
            'master_pemeriksaan'    => $masterPemeriksaan,
            'master_skala'          => $masterSkala,
            'form_action'           => '/submittambah',
        ]);
    }
}
