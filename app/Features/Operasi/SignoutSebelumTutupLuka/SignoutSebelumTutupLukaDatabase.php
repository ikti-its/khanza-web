<?php
declare(strict_types=1);

namespace App\Features\Operasi\SignoutSebelumTutupLuka;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class SignoutSebelumTutupLukaDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'signout_sebelum_tutupluka',
            [
                'id_signout'              => T::ID(300_000_000),
                'nomor_reg'               => T::FK_AUTO(),
                'waktu_signout'           => T::DTIME(),
                'sn_cn'                   => T::TEXT(),
                'kode_dokter_bedah'       => T::FK_AUTO(),
                'kode_dokter_anestesi'    => T::FK_AUTO(),
                'tindakan'                => T::TEXT(),
                'is_nama_tindakan_sesuai' => T::BOOL(),
                'is_kasa_lengkap'         => T::BOOL(),
                'is_instrumen_lengkap'    => T::BOOL(),
                'is_alat_tajam_lengkap'   => T::BOOL(),
                'id_label_spesimen'       => T::FK_AUTO(),
                'id_formulir_spesimen'    => T::FK_AUTO(),
                'is_konfirmasi_bedah'     => T::BOOL(),
                'is_konfirmasi_anestesi'  => T::BOOL(),
                'is_konfirmasi_perawat'   => T::BOOL(),
                'catatan_pemulihan'       => T::NOTE(),
                'id_perawat_ok'           => T::FK_AUTO(),
            ],
            'id_signout',
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
                [
                    ['kode_dokter_anestesi'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_label_spesimen'],
                    \App\Features\Operasi\RefStatusSpesimen\RefStatusSpesimenDatabase::class,
                    ['id_status_spesimen'],
                ],
                [
                    ['id_formulir_spesimen'],
                    \App\Features\Operasi\RefStatusSpesimen\RefStatusSpesimenDatabase::class,
                    ['id_status_spesimen'],
                ],
                [
                    ['id_perawat_ok'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
            ],
        );
    }
}
