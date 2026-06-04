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
                'id_hasil_pk'              => V::DEFAULT(),
                'tgl_jam_hasil'            => V::DEFAULT(),
                'nilai_hasil'              => V::DEFAULT(),
                'keterangan_hasil'         => V::DEFAULT(),
            ],
            [
                'id_permintaan_lab'        => [],
                'nomor_reg'                => ['nomor_rawat'],
                'kode_dokter_pj'           => [],
                'id_petugas_lab'           => [],
                'kode_dokter_perujuk'      => [],
                'id_kategori_usia'         => ['nama_kategori_usia'],
                'id_item_pemeriksaan'      => ['kode_periksa', 'nama_item', 'tarif'],
                'id_parameter_pemeriksaan' => ['nama_parameter', 'satuan', 'nilai_rujukan', 'biaya_item'],
            ],
        );
    }
}
