<?php
declare(strict_types=1);

namespace App\Features\UjiDarah\HasilUjiSaring;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class HasilUjiSaringController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilUjiSaringModel(),
            [
                ['Uji Darah',        'uji_darah'],
                ['Hasil Uji Saring', 'hasil_uji_saring'],
            ],
            'Hasil Uji Saring',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_uji_saring',        'ID Uji Saring'],
                [SHOW, REQUIRED, I::INDEX, 'id_pengambilan_darah', 'ID Pengambilan Darah'],
                [SHOW, REQUIRED, I::DATE,  'tanggal_uji',          'Tanggal Uji'],
                [SHOW, REQUIRED, I::SELECT,'id_metode_uji',        'Metode Uji'],
                [SHOW, REQUIRED, I::INDEX, 'id_petugas',           'ID Petugas'],
                [SHOW, REQUIRED, I::BOOL,  'hbsag',                'HBsAg'],
                [SHOW, REQUIRED, I::BOOL,  'hcv',                  'HCV'],
                [SHOW, REQUIRED, I::BOOL,  'hiv',                  'HIV'],
                [SHOW, REQUIRED, I::BOOL,  'sifilis',              'Sifilis'],
                [SHOW, REQUIRED, I::BOOL,  'malaria',              'Malaria'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Hasil Uji Saring
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $konfigUjiSaring = $this->get_fields_with_options(false, true);

        $controllerPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahController();
        $konfigPengambilan     = $controllerPengambilan->get_fields_with_options(false, true);

        $mockBaris      = [];
        $konfigGabungan = [];

        foreach ($konfigUjiSaring as $field) {
            $namaKolom = $field[2];

            if ($namaKolom === 'id_uji_saring') {
                continue;
            }
            
            $isTanggal = ($field[3] === 'tanggal' || str_contains($namaKolom, 'tanggal'));
            $mockBaris[$namaKolom] = $isTanggal ? date('Y-m-d') : '';

            if ($namaKolom === 'id_pengambilan_darah') {
                foreach ($konfigPengambilan as $fPengambilan) {
                    if ($fPengambilan[2] === 'nomor_pengambilan') {
                        $mockBaris['nomor_pengambilan'] = '';
                        $konfigGabungan[] = $fPengambilan; 
                        break;
                    }
                }
                continue;
            }

            $konfigGabungan[] = $field;
        }

        $id_pengambilan = $this->request->getGet('pengambilan');

        if ($id_pengambilan !== null && is_numeric($id_pengambilan)) {
            $modelPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
            $dataPengambilan  = $modelPengambilan->find($id_pengambilan);

            if ($dataPengambilan) {
                $mockBaris['id_pengambilan_darah'] = $dataPengambilan['id_pengambilan_darah'];
                $mockBaris['nomor_pengambilan']    = $dataPengambilan['nomor_pengambilan'];
            }
        }

        return view('admin/ujidarah/tambah_ujisaring', [
            'judul'          => 'Tambah ' . $this->title,
            'breadcrumbs'    => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'     => $this->get_uri_path(),
            'kolom_id'       => $this->model->primaryKey,
            'konfig'         => $konfigGabungan,
            'baris'          => $mockBaris,
            'form_action'    => '/submittambah',
        ]);
    }
}
