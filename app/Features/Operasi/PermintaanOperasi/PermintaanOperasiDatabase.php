<?php
declare(strict_types=1);

namespace App\Features\Operasi\PermintaanOperasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PermintaanOperasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'permintaan_operasi',
            [
                'id_permintaan' => T::ID(300_000_000),
                'nomor_reg'     => T::FK_AUTO(),
                'id_dokter'     => T::FK_AUTO(),
                'id_tindakan'   => T::FK_AUTO(),
                'tanggal_minta' => T::DTIME(),
                'is_cito'       => T::BOOL(),
            ],
            'id_permintaan',
            [],
            [
                [
                    ['nomor_reg'],
                    \App\Features\RekamMedis\Registrasi\RegistrasiDatabase::class,
                    ['nomor_reg'],
                ],
                [
                    ['id_dokter'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_tindakan'],
                    \App\Features\Operasi\RefTindakanOperasi\RefTindakanOperasiDatabase::class,
                    ['id_tindakan'],
                ],
            ],
        );
    }
}
