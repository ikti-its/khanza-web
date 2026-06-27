<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\StokDarah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StokDarahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StokDarahModel(),
            [
                ['Inventori Darah',  'inventori_darah'],
                ['Stok Darah',       'stok_darah'],
            ],
            'Stok Darah',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_stok_darah',       'ID Stok Darah'],
                [SHOW, REQUIRED, I::TEXT,   'no_kantong',          'Nomor Kantong'],
                [SHOW, REQUIRED, I::SELECT, 'id_komponen',         'Komponen'],
                [SHOW, REQUIRED, I::SELECT, 'id_golongan_darah',   'Golongan Darah'],
                [SHOW, REQUIRED, I::SELECT, 'id_rhesus',           'Rhesus'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_pengambilan', 'Tanggal Pengambilan'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_kadaluarsa',  'Tanggal Kadaluarsa'],
                [SHOW, REQUIRED, I::SELECT, 'id_sumber_darah',     'Sumber Darah'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_stok',      'Status Stok'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Halaman Utama Data Stok Darah
     */
    #[\Override]
    public function index(): string
    {
        $hariIni = date('Y-m-d');
        $this->model->updateStatusKadaluarsa($hariIni);

        return parent::index();
    }

    /**
     * OVERRIDE: Menampilkan Form Stok Darah
     */
    #[\Override]
    final public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $konfigFields = $this->get_fields_with_options(false, true);

        $mockBaris = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if ($namaKolom === 'id_stok_darah') continue;

            $mockBaris[$namaKolom] = '';
        }

        return view('/admin/inventoridarah/tambah_stokdarah', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $konfigFields,
            'baris'       => $mockBaris,
            'form_action' => '/submittambah',
        ]);
    }

    /**
     * Menampilkan data modal stok darah
     */
    public function list()
    {
        $hariIni = date('Y-m-d');
        $data = $this->model->get_stok_siap_pakai($hariIni);

        foreach ($data as &$row) {
            $row['tanggal_kadaluarsa'] = date('d-m-Y', strtotime($row['tanggal_kadaluarsa']));
        }

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
}
