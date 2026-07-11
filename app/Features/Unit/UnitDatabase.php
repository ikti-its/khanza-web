<?php
declare(strict_types=1);

namespace App\Features\Unit;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class UnitDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'unit',
            'unit',
            [
                'id_unit'               => T::ID(1_000),
                'kode_unit'             => T::CODE(10),
                'nama_unit'             => T::NAME(100),
                'biaya_registrasi_baru' => T::MONEY(),
                'biaya_registrasi_lama' => T::MONEY(),
            ],
            'id_unit',
            [
                'kode_unit',
                'nama_unit',
            ],
            [],
            false,
            'unit.csv',
        );
    }
}
