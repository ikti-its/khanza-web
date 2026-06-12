<?php
declare(strict_types=1);

namespace App\Features\RawatInap\JenisBayar;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class JenisBayarController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JenisBayarModel(),
            [
                ['Rawat Inap',   'rawat_inap'],
                ['Jenis Bayar',  'jenis_bayar'],
            ],
            'Jenis Bayar',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_jenis_bayar',   'ID Jenis Bayar'],
                [SHOW, REQUIRED, I::TEXT,  'nama_jenis_bayar', 'Nama Jenis Bayar'],
            ],
        );
    }
}
