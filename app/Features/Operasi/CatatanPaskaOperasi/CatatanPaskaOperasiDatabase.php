<?php
declare(strict_types=1);

namespace App\Features\Operasi\CatatanPaskaOperasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class CatatanPaskaOperasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'catatan_paska_operasi',
            [
                'id_catatan_paska'        => T::ID(300_000_000),
                'nomor_reg'               => T::FK_AUTO(),
                'kode_dokter_bedah'       => T::FK_AUTO(),
                'waktu_penilaian'         => T::DTIME(),
                'instruksi_rawat'         => T::TEXT(),
                'instruksi_cairan'        => T::TEXT(),
                'instruksi_antibiotik'    => T::TEXT(),
                'instruksi_analgetik'     => T::TEXT(),
                'instruksi_medikamentosa' => T::TEXT(),
                'instruksi_diet'          => T::TEXT(),
                'instruksi_penunjang'     => T::TEXT(),
                'instruksi_transfusi'     => T::TEXT(),
                'instruksi_lainnya'       => T::TEXT(),
            ],
            'id_catatan_paska',
            [],
            [
                [
                    ['nomor_reg'],
                    \App\Features\RekamMedis\Registrasi\RegistrasiDatabase::class,
                    ['id_registrasi'],
                ],
                [
                    ['kode_dokter_bedah'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
            ],
        );
    }
}
