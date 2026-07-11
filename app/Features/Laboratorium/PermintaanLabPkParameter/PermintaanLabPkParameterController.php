<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabPkParameter;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanLabPkParameterController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabPkParameterModel(),
            [
                ['Laboratorium',                'laboratorium'],
                ['Parameter Permintaan Lab PK', 'permintaan_lab_pk_parameter'],
            ],
            'Parameter Permintaan Lab PK',
            [
                A::READ,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_pk_parameter',       'ID PK Parameter'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_pk_item', 'ID Permintaan PK Item'],
                [SHOW, REQUIRED, I::INDEX, 'id_parameter',          'ID Parameter'],
            ],
        );
    }
}
