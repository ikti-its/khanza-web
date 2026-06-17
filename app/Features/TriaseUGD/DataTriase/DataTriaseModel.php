<?php
declare(strict_types=1);

namespace App\Features\TriaseUGD\DataTriase;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class DataTriaseModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DataTriaseDatabase(),
            [
                'id_triase'             => V::DEFAULT(),
                'tanggal_kunjungan'     => V::DEFAULT(),
                'keterangan_kedatangan' => V::DEFAULT(),
                'sistolik'              => V::DEFAULT(),
                'diastolik'             => V::DEFAULT(),
                'nadi'                  => V::DEFAULT(),
                'pernapasan'            => V::DEFAULT(),
                'suhu'                  => V::DEFAULT(),
                'saturasi_o2'           => V::DEFAULT(),
                'nyeri'                 => V::DEFAULT(),
            ],
            [
                'id_registrasi'        => [
                    'nomor_rawat',
                    'id_pasien'    => [
                        'nomor_rm',
                        'id_orang' => ['nama']
                    ],
                ],
                'id_cara_masuk'        => ['nama_cara'],
                'id_alat_transportasi' => ['nama_transportasi'],
                'id_alasan_kedatangan' => ['nama_alasan'],
                'id_macam_kasus'       => ['nama_macam_kasus'],
            ],
        );
    }
}
