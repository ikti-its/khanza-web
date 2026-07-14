<?php
declare(strict_types=1);

namespace App\Features\Auth\RefRole;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RefRoleController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefRoleModel(),
            [
                ['Auth',           'auth'],
                ['Referensi Role', 'ref_role'],
            ],
            'Referensi Role',
            [
                // Daftar role terikat pada enum Role & matriks akses di
                // *Routes.php, jadi perubahan lewat UI tidak akan berpengaruh.
                A::READ,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id_role',   'ID Role'],
                [SHOW, REQUIRED, I::TEXT,  'nama_role', 'Nama Role'],
            ],
        );
    }
}
