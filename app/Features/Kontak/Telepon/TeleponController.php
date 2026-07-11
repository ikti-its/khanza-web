<?php
declare(strict_types=1);

namespace App\Features\Kontak\Telepon;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class TeleponController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TeleponModel(),
            [
                ['Kontak',  'kontak'],
                ['Telepon', 'telepon'],
            ],
            'Telepon',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id_telepon',    'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nomor_telepon', 'Nomor'],
                [SHOW, REQUIRED, I::TEXT,  'nama_jenis',    'Jenis Telepon'],
                [SHOW, REQUIRED, I::TEXT,  'nama_provider', 'Provider'],
                [SHOW, REQUIRED, I::TEXT,  'nik',           'NIK'],
                [SHOW, REQUIRED, I::TEXT,  'nama',          'Nama Lengkap'],
            ],
        );
    }
}
