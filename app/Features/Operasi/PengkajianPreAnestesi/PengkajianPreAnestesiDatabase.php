<?php
declare(strict_types=1);

namespace App\Features\Operasi\PengkajianPreAnestesi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PengkajianPreAnestesiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'pengkajian_pre_anestesi',
            [
                'id_pre_anestesi'      => T::ID(300_000_000),
                'id_jadwal'            => T::FK_AUTO(),
                'id_dokter_anestesi'   => T::FK_AUTO(),
                'waktu_pengkajian'     => T::DTIME(),
                'diagnosa'             => T::TEXT(),
                'rencana_tindakan'     => T::TEXT(),
                'tanggal_operasi'      => T::DATE(),
                'tinggi_badan'         => T::BODY(),
                'berat_badan'          => T::BODY(),
                'sistolik'             => T::VITAL(0, 300),
                'diastolik'            => T::VITAL(0, 200),
                'saturasi_o2'          => T::VITAL(0, 100),
                'nadi'                 => T::VITAL(0, 300),
                'suhu'                 => T::TEMP(),
                'pernapasan'           => T::VITAL(0, 100),
                'fisik_cardiovascular' => T::NOTE(),
                'fisik_paru'           => T::NOTE(),
                'fisik_abdomen'        => T::NOTE(),
                'fisik_extrimitas'     => T::NOTE(),
                'fisik_endokrin'       => T::NOTE(),
                'fisik_ginjal'         => T::NOTE(),
                'fisik_obat_obatan'    => T::NOTE(),
                'fisik_laboratorium'   => T::NOTE(),
                'fisik_penunjang'      => T::NOTE(),
                'alergi_obat'          => T::NOTE(),
                'alergi_lainnya'       => T::NOTE(),
                'riwayat_terapi'       => T::NOTE(),
                'is_merokok'           => T::BOOL(),
                'jumlah_rokok'         => T::VITAL(0, 200),
                'is_alkohol'           => T::BOOL(),
                'jumlah_alkohol'       => T::VITAL(0, 1_000),
                'id_obat_bebas'        => T::FK_AUTO(),
                'ket_obat'             => T::NOTE(),
                'rw_cardiovascular'    => T::NOTE(),
                'rw_respiratory'       => T::NOTE(),
                'rw_endocrine'         => T::NOTE(),
                'rw_lainnya'           => T::NOTE(),
                'id_rencana_anestesi'  => T::FK_AUTO(),
                'id_asa'               => T::FK_AUTO(),
                'waktu_puasa'          => T::TIME(),
                'rencana_perawatan'    => T::NOTE(),
                'catatan_khusus'       => T::NOTE(),
            ],
            'id_pre_anestesi',
            [],
            [
                [
                    ['id_jadwal'],
                    \App\Features\Operasi\JadwalOperasi\JadwalOperasiDatabase::class,
                    ['id_jadwal'],
                ],
                [
                    ['id_dokter_anestesi'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_obat_bebas'],
                    \App\Features\Operasi\RefObatBebas\RefObatBebasDatabase::class,
                    ['id_obat_bebas'],
                ],
                [
                    ['id_rencana_anestesi'],
                    \App\Features\Operasi\RefRencanaAnestesi\RefRencanaAnestesiDatabase::class,
                    ['id_rencana_anestesi'],
                ],
                [
                    ['id_asa'],
                    \App\Features\Operasi\RefAngkaAsa\RefAngkaAsaDatabase::class,
                    ['id_asa'],
                ],
            ],
        );
    }
}
