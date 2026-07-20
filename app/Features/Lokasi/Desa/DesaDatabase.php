<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Desa;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

/*
 *  Dalam 1 kecamatan terdapat 1 atau lebih desa
 *  Di Indonesia, terdapat sekitar 83.000 desa
 *
 *  Kode desa menggunakan 4 digit berdasarkan peraturan Kemendagri
 *  https://peraturan.bpk.go.id/Details/196233/permendagri-no-58-tahun-2021
 *  Menurut pasal 8 ayat 2, digit pertama menunjukkan jenis Desa
 *      1 = Kelurahan
 *      2 = Desa
 *      3 = Desa Adat
 *
 *  Digit 2,3,4 menunjukkan nomor urut pembentukan desa dari 001 hingga 999
 *  Desa/kelurahan/desa adat = 3000 < 32768 = ID16()
 *
 *  Contoh Kelurahan Keputih, Kecamatan Sukolilo, Kota Surabaya, Jawa Timur
 *  memiliki kode 35.78.09.1001
 *  35              = Provinsi Jawa Timur
 *  35.78           = Kota Surabaya
 *  35.78.09        = Kecamatan Sukolilo
 *  35.78.09.1001   = Kelurahan Keputih
 */

final class DesaDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'lokasi',
            'desa',
            [
                'id_desa'       => T::ID(90000),
                'id_provinsi'   => T::FK_AUTO(),
                'id_kota_lokal' => T::FK_AUTO(),
                'id_kec_lokal'  => T::FK_AUTO(),
                'id_desa_lokal' => T::INT(1001, 2999),
                'nama_desa'     => T::NAME(50),
            ],
            'id_desa',
            [
                ['id_provinsi', 'id_kota_lokal', 'id_kec_lokal', 'id_desa_lokal'],
            ],
            [
                [
                    ['id_provinsi', 'id_kota_lokal', 'id_kec_lokal'],
                    \App\Features\Lokasi\Kecamatan\KecamatanDatabase::class,
                    ['id_provinsi', 'id_kota_lokal', 'id_kec_lokal'],
                ],
                [
                    ['id_provinsi', 'id_kota_lokal'],
                    \App\Features\Lokasi\Kota\KotaDatabase::class,
                    ['id_provinsi', 'id_kota_lokal'],
                ],
                [
                    'id_provinsi',
                    \App\Features\Lokasi\Provinsi\ProvinsiDatabase::class,
                    'id_provinsi',
                ],
            ],
            true,
            'desa.csv',
        );
    }
}
