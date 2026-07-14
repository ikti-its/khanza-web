<?php

declare(strict_types=1);

namespace App\Features\Auth\AksesFitur;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class AksesFiturModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new AksesFiturDatabase(),
            [
                'id' => V::DEFAULT(),
                'boleh_baca' => V::BOOL(),
                'boleh_tulis' => V::BOOL(),
            ],
            [
                'id_role' => ['nama_role'],
                'feature_group' => ['nama_grup'],
            ],
        );
    }
}
