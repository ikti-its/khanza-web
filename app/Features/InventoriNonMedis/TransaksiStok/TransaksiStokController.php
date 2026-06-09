<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\TransaksiStok;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class TransaksiStokController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TransaksiStokModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Transaksi Stok',      'transaksi_stok'],
            ],
            'Transaksi Stok',
            [
                A::READ,
                A::AUDIT,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_transaksi',             'ID'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'nama_tipe_transaksi_stok', 'Tipe'],
                [SHOW,       REQUIRED, I::DATE,   'tanggal',                  'Tanggal'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'no_permintaan',            'No. Permintaan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'no_keluar',                'No. Keluar'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'no_masuk',                 'No. Masuk'],
                [SHOW,       OPTIONAL, I::TEXT,   'keterangan',               'Keterangan'],
            ],
            child_path: '/inventori-non-medis/transaksi-stok-detail',
            child_fk:   'id_transaksi',
        );
    }
}
