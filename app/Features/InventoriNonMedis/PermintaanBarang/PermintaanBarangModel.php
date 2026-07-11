<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PermintaanBarang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanBarangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanBarangDatabase(),
            [
                'id_permintaan' => V::DEFAULT(),
                'no_permintaan' => V::DEFAULT(),
                'tanggal'       => V::DEFAULT(),
            ],
            [
                'petugas'                     => ['id_orang' => ['nama']],
                'petugas_gudang'              => ['id_orang' => ['nama']],
                'master_ruangan'              => ['nama_ruangan'],
                'id_status_permintaan_barang' => ['nama_status_permintaan_barang'],
            ],
        );
    }

    // Batasi pilihan status hanya Draf (1) dan Proses Permintaan (4)
    public function get_all_options(): array
    {
        $options = parent::get_all_options();

        if (isset($options['id_status_permintaan_barang'])) {
            $options['id_status_permintaan_barang'] = array_values(array_filter($options['id_status_permintaan_barang'], fn(array $opt) => in_array(
                $opt[1],
                ['1', '4'],
                true,
            )));
        }

        return $options;
    }
}
