<?php
declare(strict_types=1);

namespace App\Features\RawatInap\Registrasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RegistrasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RegistrasiDatabase(),
            [
                'id_rawat_inap'  => V::DEFAULT(),
                'kamar'          => V::DEFAULT(),
                'tarif_kamar'    => V::DEFAULT(),
                'diagnosa_awal'  => V::DEFAULT(),
                'diagnosa_akhir' => V::DEFAULT(),
                'tanggal_masuk'  => V::DEFAULT(),
                'tanggal_keluar' => V::DEFAULT(),
                'jam_keluar'     => V::DEFAULT(),
                'lama_ranap'     => V::DEFAULT(),
                'total_biaya'    => V::DEFAULT(),
            ],
            [
                'id_registrasi' => ['nomor_rawat'],
                'jenis_bayar'   => ['nama_jenis_bayar'],
                'status_pulang' => ['nama_status_pulang'],
                'dokter_pj'     => [
                    'id_orang' => ['nama'],
                ],
                'status_bayar'  => ['nama_status_bayar'],
            ],
        );
    }
}
