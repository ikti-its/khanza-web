<?php
declare(strict_types=1);

namespace App\Features\UGD\HubunganPj;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class HubunganPjController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HubunganPjModel(),
            [
                ['UGD',          'ugd'],
                ['Hubungan Penanggung Jawab', 'hubungan_pj'],
            ],
            'Hubungan Penanggung Jawab',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_hubungan_pj',   'ID Hubungan PJ'],
                [SHOW, REQUIRED, I::TEXT,  'nama_hubungan_pj', 'Hubungan Penanggung Jawab'],
            ],
        );
    }
}
