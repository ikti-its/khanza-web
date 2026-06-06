<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PenyerahanDarahDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PenyerahanDarahDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenyerahanDarahDetailDatabase(),
            [
                'id_penyerahan_detail' => V::DEFAULT(),
                'jasa_sarana'          => V::DEFAULT(),
                'paket_bhp'            => V::DEFAULT(),
                'kso'                  => V::DEFAULT(),
                'manajemen'            => V::DEFAULT(),
            ],
            [
                'id_penyerahan' => [
                    'no_penyerahan',
                    'tanggal_penyerahan',
                    'keterangan',
                    'pengambil_darah',
                    'alamat_pengambil',
                ],
                'id_stok_darah' => ['no_kantong', 'tanggal_pengambilan', 'tanggal_kadaluarsa'],
            ],
        );
    }
}
