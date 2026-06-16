<?php
declare(strict_types=1);

namespace App\Features\RekamMedis\Registrasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RegistrasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RegistrasiDatabase(),
            [
                'id_registrasi'     => V::DEFAULT(),
                'nomor_reg'         => V::DEFAULT(),
                'nomor_rawat'       => V::DEFAULT(),
                'datetime'          => V::DEFAULT(),
                'hubungan_pj'       => V::DEFAULT(),
                'no_telepon'        => V::DEFAULT(),
                'biaya_registrasi'  => V::DEFAULT(),
                'jenis_bayar'       => V::DEFAULT(),
                'status_registrasi' => V::DEFAULT(),
                'status_rawat'      => V::DEFAULT(),
                'status_poli'       => V::DEFAULT(),
                'status_bayar'      => V::DEFAULT(),
            ],
            [
                'id_dokter'     => [
                    'kode_dokter',
                    'id_orang'  => ['nama'],
                    'spesialis'
                ],
                'id_pasien'     => [
                    'id_orang'  => ['nama'],
                    'nomor_rm'
                ],
                'id_pj_pasien'  => ['nama'],
                'id_alamat_pj'  => ['alamat_lengkap'],
                'id_poliklinik' => ['kode_poliklinik', 'nama_poliklinik'],
            ],
        );
    }
}