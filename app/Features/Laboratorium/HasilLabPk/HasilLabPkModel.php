<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabPk;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class HasilLabPkModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilLabPkDatabase(),
            [
                'id_hasil_pk'      => V::DEFAULT(),
                'tgl_jam_hasil'    => V::DEFAULT(),
            ],
            [
                'id_permintaan_lab'     => ['nomor_reg'],
                'id_permintaan_pk_item' => [
                    'id_item_pemeriksaan' => ['kode_periksa', 'nama_item', 'tarif'],
                ],
                'id_dokter_pj'          => [
                    'id_orang' => ['nama'],
                ],
                'id_petugas_lab'        => [
                    'id_orang' => ['nama'],
                ],
                'id_kategori_usia'      => ['nama_kategori_usia'],
            ],
        );
    }
}
