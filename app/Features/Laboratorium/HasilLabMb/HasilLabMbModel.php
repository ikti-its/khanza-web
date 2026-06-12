<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabMb;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class HasilLabMbModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilLabMbDatabase(),
            [
                'id_hasil_mb'              => V::DEFAULT(),
                'tgl_jam_hasil'            => V::DEFAULT(),
                'nilai_hasil'              => V::DEFAULT(),
                'keterangan_hasil'         => V::DEFAULT(),
            ],
            [
                'id_permintaan_lab'        => [],
                'id_registrasi'            => ['nomor_reg'],
                'id_dokter_pj'             => [
                    'id_orang'  => ['nama']
                ],
                'id_petugas_lab'           => [
                    'id_orang'  => ['nama']
                ],
                'id_item_pemeriksaan'      => ['kode_periksa', 'nama_item', 'tarif'],
                'id_parameter_pemeriksaan' => ['nama_parameter', 'satuan', 'nilai_rujukan', 'biaya_item'],
            ],
        );
    }
}
