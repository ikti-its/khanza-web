<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabPa;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PermintaanLabPaDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'laboratorium',
            'permintaan_lab_pa',
            [
                'id_permintaan_pa'            => T::ID(100_000),
                'id_permintaan_lab'           => T::FK_AUTO(),
                'tgl_pengambilan_bahan'       => T::DTIME(),
                'metode_diperoleh'            => T::TEXT(),
                'lokasi_jaringan'             => T::TEXT(),
                'bahan_pengawet'              => T::TEXT(),
                'riwayat_lokasi_lab'          => T::TEXT(),
                'riwayat_tgl_sebelumnya'      => T::DATE(),
                'riwayat_no_pa_sebelumnya'    => T::TEXT(),
                'riwayat_diagnosa_sebelumnya' => T::TEXT(),
            ],
            'id_permintaan_pa',
            [],
            [
                [
                    ['id_permintaan_lab'],
                    \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderDatabase::class,
                    ['id_permintaan'],
                ],
            ],
        );
    }
}
