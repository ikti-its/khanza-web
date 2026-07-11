<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\JenisBarang;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class JenisBarangDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'jenis_barang',
            [
                'id_jenis_barang'   => T::ID(50),
                'kode_jenis_barang' => T::CODE(10),
                'nama_jenis_barang' => T::NAME(50),
            ],
            'id_jenis_barang',
            ['kode_jenis_barang'],
            [],
            true,
            'jenis_barang.csv',
        );
    }
}
