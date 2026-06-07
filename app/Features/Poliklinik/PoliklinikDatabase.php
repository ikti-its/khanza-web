<?php
declare(strict_types=1);

namespace App\Features\Poliklinik;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PoliklinikDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'poliklinik',
            'poliklinik',
            [
                'id_poliklinik'         => T::ID(1_000),
                'kode_poliklinik'       => T::CODE(10),
                'nama_poliklinik'       => T::NAME(100),
                'biaya_registrasi_baru' => T::MONEY(),
                'biaya_registrasi_lama' => T::MONEY(),

            ],
            'id_poliklinik',
            [
                'kode_poliklinik',
                'nama_poliklinik',
            ],
            [],
            false,
            '/poliklinik.csv'
        );
    }
}