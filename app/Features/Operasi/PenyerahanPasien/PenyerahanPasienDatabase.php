<?php
declare(strict_types=1);

namespace App\Features\Operasi\PenyerahanPasien;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PenyerahanPasienDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'penyerahan_pasien',
            [
                'id_penyerahan'            => T::ID(300_000_000),
                'id_jadwal'                => T::FK_AUTO(),
                'waktu_masuk_asal'         => T::DTIME(),
                'waktu_pindah'             => T::DTIME(),
                'id_indikasi'              => T::FK_AUTO(),
                'ket_indikasi'             => T::NOTE(),
                'id_ruang_asal'            => T::FK_AUTO(),
                'id_ruang_selanjutnya'     => T::FK_AUTO(),
                'id_metode'                => T::FK_AUTO(),
                'diagnosa_utama'           => T::TEXT(),
                'diagnosa_sekunder'        => T::TEXT(),
                'prosedur_dilakukan'       => T::NOTE(),
                'obat_diberikan'           => T::NOTE(),
                'pemeriksaan_penunjang'    => T::NOTE(),
                'is_disetujui'             => T::BOOL(),
                'nama_pemberi_persetujuan' => T::TEXT(),
                'id_hubungan'              => T::FK_AUTO(),
                'asal_id_keadaan'          => T::FK_AUTO(),
                'asal_sistolik'            => T::VITAL(0, 300),
                'asal_diastolik'           => T::VITAL(0, 200),
                'asal_nadi'                => T::VITAL(0, 300),
                'asal_respiratory_rate'    => T::VITAL(0, 100),
                'asal_suhu'                => T::TEMP(),
                'asal_keluhan'             => T::NOTE(),
                'tiba_id_keadaan'          => T::FK_AUTO(),
                'tiba_sistolik'            => T::VITAL(0, 300),
                'tiba_diastolik'           => T::VITAL(0, 200),
                'tiba_nadi'                => T::VITAL(0, 300),
                'tiba_respiratory_rate'    => T::VITAL(0, 100),
                'tiba_suhu'                => T::TEMP(),
                'tiba_keluhan'             => T::NOTE(),
                'id_perawat_menyerahkan'   => T::FK_AUTO(),
                'id_perawat_menerima'      => T::FK_AUTO(),
            ],
            'id_penyerahan',
            [],
            [
                [
                    ['id_jadwal'],
                    \App\Features\Operasi\JadwalOperasi\JadwalOperasiDatabase::class,
                    ['id_jadwal'],
                ],
                [
                    ['id_indikasi'],
                    \App\Features\Operasi\RefIndikasiPindah\RefIndikasiPindahDatabase::class,
                    ['id_indikasi'],
                ],
                [
                    ['id_ruang_asal'],
                    \App\Features\Ruangan\RuanganDatabase::class,
                    ['id_ruangan'],
                ],
                [
                    ['id_ruang_selanjutnya'],
                    \App\Features\Ruangan\RuanganDatabase::class,
                    ['id_ruangan'],
                ],
                [
                    ['id_metode'],
                    \App\Features\Operasi\RefMetodeTransfer\RefMetodeTransferDatabase::class,
                    ['id_metode'],
                ],
                [
                    ['id_hubungan'],
                    \App\Features\Operasi\RefHubunganKeluarga\RefHubunganKeluargaDatabase::class,
                    ['id_hubungan_keluarga'],
                ],
                [
                    ['asal_id_keadaan'],
                    \App\Features\Operasi\RefKeadaanUmumTransfer\RefKeadaanUmumTransferDatabase::class,
                    ['id_keadaan_umum'],
                ],
                [
                    ['tiba_id_keadaan'],
                    \App\Features\Operasi\RefKeadaanUmumTransfer\RefKeadaanUmumTransferDatabase::class,
                    ['id_keadaan_umum'],
                ],
                [
                    ['id_perawat_menyerahkan'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_perawat_menerima'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
            ],
        );
    }
}
