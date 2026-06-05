<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\Suplier;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class SuplierDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'suplier',
            [
                'id_suplier'   => T::ID(200),
                'kode_suplier' => T::CODE(10),
                'nama_suplier' => T::NAME(100),
                'no_telp'      => T::TEXT()->nullable(),
                'id_kota'      => T::FK_AUTO()->nullable(),
                'alamat'       => T::TEXT()->nullable(),
                'id_rekening'  => T::FK_AUTO()->nullable(),
            ],
            'id_suplier',
            ['kode_suplier'],
            [
                [
                    'id_kota',
                    \App\Features\Lokasi\Kota\KotaDatabase::class,
                    'id_kota',
                ],
                [
                    'id_rekening',
                    \App\Features\Finansial\Rekening\RekeningDatabase::class,
                    'id_rekening',
                ],
            ],
            true,
            'suplier.csv',
        );
    }
}
