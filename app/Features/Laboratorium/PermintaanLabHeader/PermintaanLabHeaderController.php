<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabHeader;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanLabHeaderController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabHeaderModel(),
            [
                ['Laboratorium',   'laboratorium'],
                ['Permintaan Lab', 'permintaan_lab'],
            ],
            'Permintaan Lab',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::SAMPEL,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_permintaan',        'ID Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'no_permintaan',        'No. Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'id_registrasi',        'Nomor Registrasi'],
                [HIDE, REQUIRED, I::INDEX,  'id_kategori_lab',      'Kategori Lab'],
                [SHOW, REQUIRED, I::TEXT,   'id_dokter_perujuk',    'Kode Dokter Perujuk'],
                [SHOW, REQUIRED, I::DTIME,  'tgl_permintaan',       'Tanggal Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'indikasi_klinis',      'Indikasi Klinis'],
                [SHOW, REQUIRED, I::TEXT,   'informasi_tambahan',   'Informasi Tambahan'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_permintaan', 'Status Permintaan'],
                [SHOW, OPTIONAL, I::DTIME,  'tgl_jam_sampel',       'Waktu Sampel'],
            ],
        );
    }
}
