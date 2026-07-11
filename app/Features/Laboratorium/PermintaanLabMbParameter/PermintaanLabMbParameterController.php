<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabMbParameter;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanLabMbParameterController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabMbParameterModel(),
            [
                ['Laboratorium',                'laboratorium'],
                ['Parameter Permintaan Lab MB', 'permintaan_lab_mb_parameter'],
            ],
            'Parameter Permintaan Lab MB',
            [
                A::READ,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_mb_parameter',       'ID MB Parameter'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_mb_item', 'ID Permintaan MB Item'],
                [SHOW, REQUIRED, I::INDEX, 'id_parameter',          'ID Parameter'],
            ],
        );
    }
}
