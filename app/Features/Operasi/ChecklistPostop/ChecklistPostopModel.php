<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPostop;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class ChecklistPostopModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ChecklistPostopDatabase(),
            [
                'id_checklist_post'    => V::DEFAULT(),
                'waktu_checklist'      => V::DEFAULT(),
                'sn_cn'                => V::DEFAULT(),
                'tindakan'             => V::DEFAULT(),
                'jenis_cairan_infus'   => V::DEFAULT(),
                'waktu_pasang_kateter' => V::DEFAULT(),
                'jumlah_urine_cc'      => V::DEFAULT(),
                'catatan_luka_operasi' => V::DEFAULT(),
            ],
            [
                'nomor_reg'            => ['nomor_rawat'],
                'kode_dokter_bedah'    => [],
                'kode_dokter_anestesi' => [],
                'id_kesadaran_pascaop' => ['nama_kesadaran'],
                'id_jaringan_pa_vc'    => ['nama_ketersediaan'],
                'id_kateter_urine'     => ['nama_ketersediaan'],
                'id_warna_urine'       => ['nama_warna'],
                'id_petugas_anestesi'  => [],
                'id_petugas_ok'        => [],
            ],
        );
    }
}
