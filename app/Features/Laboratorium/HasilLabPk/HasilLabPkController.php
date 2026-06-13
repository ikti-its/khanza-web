<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabPk;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class HasilLabPkController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilLabPkModel(),
            [
                ['Laboratorium', 'laboratorium'],
                ['Hasil Lab PK', 'hasil_lab_pk'],
            ],
            'Hasil Lab PK',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_hasil_pk',           'ID Hasil PK'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_lab',     'ID Permintaan Lab'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_pk_item', 'ID Permintaan PK Item'],
                [SHOW, REQUIRED, I::TEXT,  'id_dokter_pj',          'Kode Dokter PJ'],
                [SHOW, REQUIRED, I::TEXT,  'id_petugas_lab',        'ID Petugas Lab'],
                [SHOW, REQUIRED, I::DTIME, 'tgl_jam_hasil',         'Tanggal dan Jam Hasil'],
                [SHOW, REQUIRED, I::INDEX, 'id_kategori_usia',      'ID Kategori Usia'],
                [SHOW, REQUIRED, I::TEXT,  'nilai_hasil',           'Nilai Hasil'],
                [SHOW, OPTIONAL, I::TEXT,  'keterangan_hasil',      'Keterangan Hasil'],
            ],
        );
    }
}
