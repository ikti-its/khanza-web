<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabPaItem;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanLabPaItemController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabPaItemModel(),
            [
                ['Laboratorium',          'laboratorium'],
                ['Item Permintaan Lab PA', 'permintaan_lab_pa_item'],
            ],
            'Item Permintaan Lab PA',
            [
                A::READ,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_permintaan_pa_item', 'ID Permintaan PA Item'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_lab',     'ID Permintaan Lab'],
                [SHOW, REQUIRED, I::INDEX, 'id_item_pemeriksaan',   'ID Item Pemeriksaan'],
            ],
        );
    }
}