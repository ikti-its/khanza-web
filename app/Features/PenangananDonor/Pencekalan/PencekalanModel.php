<?php
declare(strict_types=1);

namespace App\Features\PenangananDonor\Pencekalan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PencekalanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PencekalanDatabase(),
            [
                'id_pencekalan'   => V::DEFAULT(),
                'tanggal_mulai'   => V::DEFAULT(),
                'tanggal_selesai' => V::DEFAULT(),
                'keterangan'      => V::DEFAULT(),
            ],
            [
                'id_kunjungan'        => ['nomor_kunjungan'],
                'id_jenis_pencekalan' => ['nama_jenis_pencekalan'],
                'id_shift'            => ['nama_shift'],
                'id_petugas'          => [
                    'id_orang' => ['nama']
                ],
            ],
        );
    }
}
