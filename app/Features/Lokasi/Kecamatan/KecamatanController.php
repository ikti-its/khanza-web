<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Kecamatan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class KecamatanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KecamatanModel(),
            [
                ['Lokasi',    'lokasi'],
                ['Kecamatan', 'kecamatan'],
            ],
            'Kecamatan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id_kecamatan',   'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_provinsi',  'Provinsi'],
                [SHOW, REQUIRED, I::TEXT,  'nama_kota',      'Kota'],
                [SHOW, REQUIRED, I::TEXT,  'id_kec_lokal',   'Kode Lokal'],
                [SHOW, REQUIRED, I::TEXT,  'nama_kecamatan', 'Kecamatan'],
            ],
        );
    }

    /**
     * Menampilkan data modal kecamatan
     */
    public function list()
    {
        return $this->response->setJSON([
            'data' => $this->model->get_data_tabel(),
        ]);
    }
}
