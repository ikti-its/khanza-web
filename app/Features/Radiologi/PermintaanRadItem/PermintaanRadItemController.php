<?php
declare(strict_types=1);

namespace App\Features\Radiologi\PermintaanRadItem;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanRadItemController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanRadItemModel(),
            [
                ['Radiologi',             'radiologi'],
                ['Permintaan Rad Item',   'permintaan_rad_item'],
            ],
            'Permintaan Rad Item',
            [
                A::READ,
                // A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_permintaan_item', 'ID'],
                [SHOW, REQUIRED, I::SELECT, 'id_permintaan',      'No. Permintaan'],
                [SHOW, REQUIRED, I::SELECT, 'id_item',            'Item Radiologi'],
            ],
        );
    }
}