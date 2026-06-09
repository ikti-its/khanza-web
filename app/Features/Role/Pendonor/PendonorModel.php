<?php
declare(strict_types=1);

namespace App\Features\Role\Pendonor;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PendonorModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PendonorDatabase(),
            [
                'id_pendonor'            => V::DEFAULT(),
                'nomor_pendonor'         => V::DEFAULT(),
                'nomor_telepon'          => V::DEFAULT(),
                'tanggal_donor_terakhir' => V::DEFAULT(),
            ],
            [
                'id_orang'  => [
                    'nama',
                    'nik', 
                    'id_jenis_kelamin'  => ['nama_jenis_kelamin'],
                    'tempat_lahir_kota' => ['nama_kota'],
                    'tanggal_lahir',
                    'id_alamat'         => ['alamat_lengkap'],
                    'id_golongan_darah' => ['nama_golongan_darah'],
                ],
                'id_rhesus' => ['kode_rhesus'],
            ],
        );
    }
}
