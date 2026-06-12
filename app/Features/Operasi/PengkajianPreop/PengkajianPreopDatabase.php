<?php
declare(strict_types=1);

namespace App\Features\Operasi\PengkajianPreop;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PengkajianPreopDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'pengkajian_preop',
            [
                'id_pengkajian_pre'      => T::ID(300_000_000),
                'id_jadwal'              => T::FK_AUTO(),
                'id_dokter_bedah'        => T::FK_AUTO(),
                'waktu_pengkajian'       => T::DTIME(),
                'ringkasan_klinik'       => T::TEXT(),
                'pemeriksaan_fisik'      => T::TEXT(),
                'pemeriksaan_diagnostik' => T::TEXT(),
                'diagnosa_pre_operasi'   => T::TEXT(),
                'rencana_tindakan'       => T::TEXT(),
                'persiapan_khusus'       => T::TEXT(),
                'terapi_pre_operasi'     => T::TEXT(),
            ],
            'id_pengkajian_pre',
            [],
            [
                [
                    ['id_jadwal'],
                    \App\Features\Operasi\JadwalOperasi\JadwalOperasiDatabase::class,
                    ['id_jadwal'],
                ],
                [
                    ['id_dokter_bedah'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
            ],
        );
    }
}
