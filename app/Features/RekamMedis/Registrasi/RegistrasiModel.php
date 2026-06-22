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
                'id_registrasi'   => V::DEFAULT(),
                'nomor_reg'       => V::DEFAULT(),
                'nomor_rawat'     => V::DEFAULT(),
                'tanggal_reg'     => V::DEFAULT(),
                'no_telepon'      => V::DEFAULT(),
                'biaya_registrasi'=> V::DEFAULT(),
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
                'id_pj_pasien'      => ['nama'],
                'id_alamat_pj'      => ['alamat_lengkap'],
                'hubungan_pj'       => ['nama_hubungan_pj'],
                'jenis_bayar'       => ['nama_jenis_bayar'],
                'status_bayar'      => ['nama_status_bayar'],
                'unit'              => ['kode_unit', 'nama_unit'],
                'status_registrasi' => ['nama_status_registrasi'],
                'status_rawat'      => ['nama_status_rawat'],
                'status_poli'       => ['nama_status_poli'],
            ],
        );
    }
}