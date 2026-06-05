<?php
declare(strict_types=1);

namespace App\Features\Pendidikan\JenisPendidikan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class JenisPendidikanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JenisPendidikanModel(),
            [
                ['Pendidikan',  'pendidikan'],
                ['Jenis Pendidikan', 'jenis-pendidikan'],
            ],
            'Jenis Pendidikan',
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
                [SHOW, REQUIRED, I::TEXT,  'nama_jenis',   'Jenis'],
            ],
        );
    }
}
