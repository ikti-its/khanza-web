<?php
declare(strict_types=1);

namespace App\Features\UGD\Registrasi;

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
                'tanggal_kunjungan' => V::DEFAULT(),
                'alamat_pj'         => V::DEFAULT(),
                'hubungan_pj'       => V::DEFAULT(),
                'biaya_registrasi'  => V::DEFAULT(),
                'jenis_bayar'       => V::DEFAULT(),
                'status_rawat'      => V::DEFAULT(),
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
                'status_bayar'  => ['nama_status_bayar'],
            ],
        );
    }
}
