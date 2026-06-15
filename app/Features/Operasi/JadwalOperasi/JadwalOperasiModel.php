<?php
declare(strict_types=1);

namespace App\Features\Operasi\JadwalOperasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class JadwalOperasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JadwalOperasiDatabase(),
            [
                'id_jadwal'            => V::DEFAULT(),
                'tanggal'              => V::DEFAULT(),
                'waktu_mulai'          => V::DEFAULT(),
                'waktu_selesai'        => V::DEFAULT(),
            ],
            [
                'id_permintaan'        => [],
                'id_ruangan'           => ['kode_ruangan', 'nama_ruangan'],
                'id_dokter_bedah'      => [
                    'id_orang'  => ['nama']
                ],
                'id_dokter_anestesi'   => [
                    'id_orang'  => ['nama']
                ],
                'id_status'            => ['nama_status'],
            ],
        );
    }
}
