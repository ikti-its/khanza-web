<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabPa;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class HasilLabPaModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilLabPaDatabase(),
            [
                'id_hasil_pa'         => V::DEFAULT(),
                'tgl_jam_hasil'       => V::DEFAULT(),
                'diagnosa_klinis'     => V::DEFAULT(),
                'makroskopik'         => V::DEFAULT(),
                'mikroskopik'         => V::DEFAULT(),
                'kesimpulan'          => V::DEFAULT(),
                'kesan'               => V::DEFAULT(),
            ],
            [
                'id_permintaan_lab'   => ['nomor_reg'],
                'id_dokter_pj'        => [
                    'id_orang' => ['nama']
                ],
                'id_petugas_lab'      => [
                    'id_orang' => ['nama']
                ],
                'id_item_pemeriksaan' => ['kode_periksa', 'nama_item', 'tarif'],
            ],
        );
    }
}
