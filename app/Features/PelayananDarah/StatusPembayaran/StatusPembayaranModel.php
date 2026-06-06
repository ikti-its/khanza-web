<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\StatusPembayaran;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class StatusPembayaranModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusPembayaranDatabase(),
            [
                'id_status_pembayaran'   => V::DEFAULT(),
                'nama_status_pembayaran' => V::DEFAULT(),
            ],
            [],
        );
    }
}
