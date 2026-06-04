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
                'nomor_reg'            => ['nomor_rawat'],
                'id_kategori_lab'      => ['nama_kategori'],
                'kode_dokter_perujuk'  => [],
                'id_status_permintaan' => ['nama_status'],
            ],
        );
    }
}
