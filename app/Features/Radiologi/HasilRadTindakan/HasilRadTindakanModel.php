<?php
declare(strict_types=1);

namespace App\Features\Radiologi\HasilRadTindakan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class HasilRadTindakanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilRadTindakanDatabase(),
            [
                'id_hasil_tindakan'       => V::DEFAULT(),
                'proyeksi'                => V::DEFAULT(),
                'kilovoltage_kv'          => V::DEFAULT(),
                'milliampere_second_mas'  => V::DEFAULT(),
                'focus_film_distance_ffd' => V::DEFAULT(),
                'back_scatter_factor_bsf' => V::DEFAULT(),
                'inaktivasi'              => V::DEFAULT(),
                'jumlah_penyinaran'       => V::DEFAULT(),
                'dosis_radiasi'           => V::DEFAULT(),
                'hasil_ekspertise'        => V::DEFAULT(),
            ],
            [
                'id_hasil_rad'    => [],
                'id_item_rad'     => ['kode_periksa', 'nama_pemeriksaan', 'tarif_dasar'],
                'id_template_rad' => ['nama_template'],
            ],
        );
    }
}
