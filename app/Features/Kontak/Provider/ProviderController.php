<?php
declare(strict_types=1);

namespace App\Features\Kontak\Provider;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class ProviderController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ProviderModel(),
            [
                ['Kontak', 'kontak'],
                ['Provider', 'provider'],
            ],
            'Provider',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX,  'id_provider',   'ID'],
                [SHOW, REQUIRED, I::TEXT,   'nama_provider', 'Provider'],
            ],
        );
    }
}
