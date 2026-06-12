<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabHeader;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanLabHeaderModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabHeaderDatabase(),
            [
                'id_permintaan'        => V::DEFAULT(),
                'no_permintaan'        => V::DEFAULT(),
                'tgl_permintaan'       => V::DEFAULT(),
                'indikasi_klinis'      => V::DEFAULT(),
                'informasi_tambahan'   => V::DEFAULT(),
            ],
            [
                'id_registrasi'        => ['nomor_reg'],
                'id_kategori_lab'      => ['nama_kategori'],
                'id_dokter_perujuk'    => [
                    'id_orang'  => ['nama']
                ],
                'id_status_permintaan' => ['nama_status'],
            ],
        );
    }
}
