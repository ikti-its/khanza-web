<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabMbItem;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanLabMbItemController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabMbItemModel(),
            [
                ['Laboratorium',          'laboratorium'],
                ['Item Permintaan Lab MB', 'permintaan_lab_mb_item'],
            ],
            'Item Permintaan Lab MB',
            [
                A::READ,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_permintaan_mb_item', 'ID Permintaan MB Item'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_lab',     'ID Permintaan Lab'],
                [SHOW, REQUIRED, I::INDEX, 'id_item_pemeriksaan',   'ID Item Pemeriksaan'],
            ],
        );
    }
}