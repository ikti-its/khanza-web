<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\RingkasanPermintaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RingkasanPermintaanBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RingkasanPermintaanBarangModel(),
            [
                ['Inventori Non Medis',        'inventori_non_medis'],
                ['Ringkasan Permintaan Barang', 'ringkasan_permintaan_barang'],
            ],
            'Ringkasan Permintaan Barang',
            [
                A::READ,
                A::UPDATE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_permintaan',               'ID'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'no_permintaan',               'No. Permintaan'],
                [TABLE_ONLY, REQUIRED, I::DATE,   'tanggal',                     'Tanggal'],
                [TABLE_ONLY, REQUIRED, I::SELECT, 'petugas',                     'Petugas'],
                [TABLE_ONLY, REQUIRED, I::SELECT, 'master_ruangan',              'Ruangan'],
                [SHOW,       REQUIRED, I::SELECT, 'id_status_permintaan_barang', 'Status'],
            ],
            child_path: '/inventori-non-medis/ringkasan-permintaan-barang-detail',
            child_fk:   'id_permintaan',
        );
    }
}
