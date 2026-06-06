<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\StokOpname;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class StokOpnameModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StokOpnameDatabase(),
            [
                'id_opname'             => V::DEFAULT(),
                'tanggal'               => V::DEFAULT(),
                'catatan'               => V::DEFAULT(),
                'catatan_atasan'        => V::DEFAULT(),
            ],
            [
                'id_status_stok_opname' => ['nama_status_stok_opname'],
            ],
        );
    }
}
