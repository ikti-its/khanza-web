<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Kecamatan;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

/*  Dalam 1 kota/kabupaten terdapat 1 atau lebih kecamatan
 *  Di Indonesia, terdapat sekitar 7.000 kecamatan
 *
 *  Kode kota/kabupaten menggunakan 6 digit berdasarkan peraturan Kemendagri
 *  https://peraturan.bpk.go.id/Details/196233/permendagri-no-58-tahun-2021
 *  Menurut pasal 4 ayat 3c, 2 digit pertama menunjukkan pulau asal provinsi,
 *  2 digit kedua menunjukkan nomor kota/kabupaten di provinsi tersebut, dan
 *  2 digit ketiga menunjukkan nomor kecamatan di kota/kabupaten tersebut
 *
 *  Kode kecamatan menggunakan angka 01 sampai 99
 *  Kecamatan = 99 < 128 = ID8()
 *
 *  Contoh Kecamatan Sukolilo, Kota Surabaya, Provinsi Jawa Timur
 *  3        = Pulau Jawa
 *  35       = Provinsi Jawa Timur
 *  35.78    = Kota Surabaya
 *  35.78.09 = Kecamatan Sukolilo
 */

final class KecamatanDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'lokasi',
            'kecamatan',
            [
                'id_kecamatan'   => T::ID(8000),
                'id_provinsi'    => T::FK_AUTO(),
                'id_kota_lokal'  => T::FK_AUTO(),
                'id_kec_lokal'   => T::INT(11, 99),
                'nama_kecamatan' => T::NAME(30),
            ],
            'id_kecamatan',
            [
                ['id_provinsi', 'id_kota_lokal', 'id_kec_lokal'],
            ],
            [
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
            'kecamatan.csv',
        );
    }
}
