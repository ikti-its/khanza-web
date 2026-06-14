<?php
declare(strict_types=1);

namespace App\Features\Ruangan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RuanganController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RuanganModel(),
            [
                ['Ruangan', 'ruangan'],
            ],
            'Ruangan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_ruangan',      'ID Ruangan'],
                [SHOW, REQUIRED, I::TEXT,  'kode_ruangan',    'Kode Ruangan'],
                [SHOW, REQUIRED, I::TEXT,  'nama_ruangan',    'Nama Ruangan'],
                [SHOW, REQUIRED, I::TEXT,  'jenis_instalasi', 'Jenis Instalasi'],
            ],
        );
    }
}