<?php
declare(strict_types=1);

namespace App\Features\Operasi\PenyerahanPasienPeralatan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PenyerahanPasienPeralatanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenyerahanPasienPeralatanDatabase(),
            [
                'id'         => V::DEFAULT(),
                'keterangan' => V::DEFAULT(),
            ],
            [
                'id_penyerahan' => [],
                'id_peralatan'  => ['nama_peralatan'],
            ],
        );
    }
}
