<?php
declare(strict_types=1);

namespace App\Features\Pendidikan\JenjangPendidikan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class JenjangPendidikanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JenjangPendidikanModel(),
            [
                ['Pendidikan',  'pendidikan'],
                ['Jenjang Pendidikan', 'jenjang-pendidikan'],
            ],
            'Jenjang Pendidikan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_jenis',     'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_jenjang', 'Jenjang'],
            ],
        );
    }
}
