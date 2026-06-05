<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Provinsi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class ProvinsiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ProvinsiModel(),
            [
                ['Lokasi', 'lokasi'],
                ['Provinsi', 'provinsi'],
            ],
            'Provinsi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id_provinsi',   'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_pulau',    'Pulau'],
                [SHOW, REQUIRED, I::TEXT,  'kode_provinsi', 'Kode'],
                [SHOW, REQUIRED, I::TEXT,  'nama_provinsi', 'Provinsi'],
            ],
        );
    }
}
