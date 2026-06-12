<?php
declare(strict_types=1);

namespace App\Features\Operasi\PengkajianPreAnestesi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PengkajianPreAnestesiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengkajianPreAnestesiDatabase(),
            [
                'id_pre_anestesi'      => V::DEFAULT(),
                'waktu_pengkajian'     => V::DEFAULT(),
                'diagnosa'             => V::DEFAULT(),
                'rencana_tindakan'     => V::DEFAULT(),
                'tanggal_operasi'      => V::DEFAULT(),
                'tinggi_badan'         => V::DEFAULT(),
                'berat_badan'          => V::DEFAULT(),
                'sistolik'             => V::DEFAULT(),
                'diastolik'            => V::DEFAULT(),
                'saturasi_o2'          => V::DEFAULT(),
                'nadi'                 => V::DEFAULT(),
                'suhu'                 => V::DEFAULT(),
                'pernapasan'           => V::DEFAULT(),
                'fisik_cardiovascular' => V::DEFAULT(),
                'fisik_paru'           => V::DEFAULT(),
                'fisik_abdomen'        => V::DEFAULT(),
                'fisik_extrimitas'     => V::DEFAULT(),
                'fisik_endokrin'       => V::DEFAULT(),
                'fisik_ginjal'         => V::DEFAULT(),
                'fisik_obat_obatan'    => V::DEFAULT(),
                'fisik_laboratorium'   => V::DEFAULT(),
                'fisik_penunjang'      => V::DEFAULT(),
                'alergi_obat'          => V::DEFAULT(),
                'alergi_lainnya'       => V::DEFAULT(),
                'riwayat_terapi'       => V::DEFAULT(),
                'is_merokok'           => V::DEFAULT(),
                'jumlah_rokok'         => V::DEFAULT(),
                'is_alkohol'           => V::DEFAULT(),
                'jumlah_alkohol'       => V::DEFAULT(),
                'ket_obat'             => V::DEFAULT(),
                'rw_cardiovascular'    => V::DEFAULT(),
                'rw_respiratory'       => V::DEFAULT(),
                'rw_endocrine'         => V::DEFAULT(),
                'rw_lainnya'           => V::DEFAULT(),
                'waktu_puasa'          => V::DEFAULT(),
                'rencana_perawatan'    => V::DEFAULT(),
                'catatan_khusus'       => V::DEFAULT(),
            ],
            [
                'id_jadwal'           => [],
                'id_dokter_anestesi'  => [
                    'id_orang'  => ['nama']
                ],
                'id_obat_bebas'       => ['nama_kategori'],
                'id_rencana_anestesi' => ['nama_rencana'],
                'id_asa'              => ['nama_asa'],
            ],
        );
    }
}
