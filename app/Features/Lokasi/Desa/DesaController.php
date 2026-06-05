<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Desa;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class DesaController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DesaModel(),
            [
                ['Lokasi', 'lokasi'],
                ['Desa', 'desa'],
            ],
            'Desa',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id_desa',   'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_provinsi',  'Provinsi'],
                [SHOW, REQUIRED, I::TEXT,  'nama_kota',      'Kota'],
                [SHOW, REQUIRED, I::TEXT,  'nama_kecamatan', 'Kecamatan'],
                [SHOW, REQUIRED, I::TEXT,  'id_desa_lokal',  'Kode Lokal'],
                [SHOW, REQUIRED, I::TEXT,  'nama_desa',      'Desa'],
            ],
        );
    }
}
