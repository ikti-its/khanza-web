<?php
declare(strict_types=1);

namespace App\Features\RekamMedis\HubunganPj;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;
    
final class HubunganPjDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'rekam_medis',
            'hubungan_pj',
            [
                'id_hubungan_pj'   => T::ID(20),
                'nama_hubungan_pj' => T::NAME(20),
            ],
            'id_hubungan_pj',
            [],
            [],
            true,
            'hubungan_pj.csv',
        );
    }
}
