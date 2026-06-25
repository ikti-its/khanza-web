<?php
declare(strict_types=1);

namespace App\Features\Operasi\PaketTindakanOperasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PaketTindakanOperasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'paket_tindakan_operasi',
            [
                'id_paket'    => T::ID(50),
                'id_tindakan' => T::FK_AUTO(),
                'id_komponen' => T::FK_AUTO(),
                'tarif_kelas_3' => T::MONEY(),
                'tarif_kelas_2' => T::MONEY(),
                'tarif_kelas_1' => T::MONEY(),
                'tarif_vip'     => T::MONEY(),
                'tarif_vvip'    => T::MONEY(),
            ],
            'id_paket',
            [],
            [
                [
                    ['id_tindakan'],
                    \App\Features\Operasi\RefTindakanOperasi\RefTindakanOperasiDatabase::class,
                    ['id_tindakan'],
                ],
                [
                    ['id_komponen'],
                    \App\Features\Operasi\RefKomponenJasa\RefKomponenJasaDatabase::class,
                    ['id_komponen'],
                ],
            ],
            false,
            'paket_tindakan_operasi.csv',
        );
    }
}