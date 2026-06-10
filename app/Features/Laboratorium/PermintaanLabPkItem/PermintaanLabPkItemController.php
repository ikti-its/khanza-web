<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabPkItem;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanLabPkItemController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabPkItemModel(),
            [
                ['Laboratorium',          'laboratorium'],
                ['Item Permintaan Lab PK', 'permintaan_lab_pk_item'],
            ],
            'Item Permintaan Lab PK',
            [
                A::READ,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_permintaan_pk_item', 'ID Permintaan PK Item'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_lab',     'ID Permintaan Lab'],
                [SHOW, REQUIRED, I::INDEX, 'id_item_pemeriksaan',   'ID Item Pemeriksaan'],
            ],
        );
    }
}