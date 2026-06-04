<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\PemisahanKomponen;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PemisahanKomponenModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PemisahanKomponenDatabase(),
            [
                'id_pemisahan'      => V::DEFAULT(),
                'tanggal_pemisahan' => V::DEFAULT(),
            ],
            [
                'id_pengambilan_darah' => ['nomor_pengambilan'],
                'id_shift'             => ['nama_shift'],
                'id_petugas'           => [
                    'id_orang' => ['nama']
                ],
            ],
        );
    }
}
