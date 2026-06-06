<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PenyerahanDarah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PenyerahanDarahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenyerahanDarahModel(),
            [
                ['Pelayanan Darah',  'pelayanan_darah'],
                ['Penyerahan Darah', 'penyerahan_darah'],
            ],
            'Penyerahan Darah',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_penyerahan',        'ID Penyerahan'],
                [SHOW, REQUIRED, I::TEXT,   'no_penyerahan',        'Nomor Penyerahan'],
                [SHOW, REQUIRED, I::INDEX,  'id_permintaan',        'ID Permintaan'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_penyerahan',   'Tanggal Penyerahan'],
                [SHOW, REQUIRED, I::INDEX,  'id_shift',             'ID Shift'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas_cross',     'ID Petugas Crossmatch'],
                [SHOW, REQUIRED, I::TEXT,   'keterangan',           'Keterangan'],
                [SHOW, REQUIRED, I::INDEX,  'id_rekening',          'ID Rekening'],
                [SHOW, REQUIRED, I::NAME,   'pengambil_darah',      'Pengambil Darah'],
                [SHOW, REQUIRED, I::TEXT,   'alamat_pengambil',     'Alamat Pengambil'],
                [SHOW, REQUIRED, I::INDEX,  'id_penanggung_jawab',  'ID Penanggung Jawab'],
                [SHOW, REQUIRED, I::FLOAT,  'besar_ppn',            'PPN (%)'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_pembayaran', 'ID Status Pembayaran'],
            ],
        );
    }
}
