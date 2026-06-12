<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PermintaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanBarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Permintaan Barang',   'permintaan_barang'],
            ],
            'Permintaan Barang',
            [
                A::READ,
                A::CREATE,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,    'id_permintaan',                 'ID'],
                [SHOW, OPTIONAL, I::READONLY, 'no_permintaan',                 'No. Permintaan'],
                [SHOW, REQUIRED, I::DTIME,    'tanggal',                       'Tanggal'],
                [SHOW, REQUIRED, I::SELECT,   'petugas',                       'Petugas'],
                [SHOW, REQUIRED, I::SELECT,   'master_ruangan',                'Ruangan'],
                [TABLE_ONLY, OPTIONAL, I::SELECT,   'id_status_permintaan_barang',   'Status'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_status_permintaan_barang', 'Status'],
                [SHOW, OPTIONAL, I::READONLY, 'no_keluar',                     'No. Keluar'],
                [SHOW, OPTIONAL, I::READONLY, 'petugas_gudang',                'Petugas Gudang'],
                [SHOW, OPTIONAL, I::READONLY, 'tanggal_disetujui',             'Tgl. Disetujui'],
            ],
            child_path: '/inventori-non-medis/detail-permintaan-barang',
            child_fk:   'id_permintaan',
        );
    }

    // auto no_permintaan + status awal = 1
    protected function before_create(array &$postData): void
    {
        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.permintaan_barang', 'no_permintaan', 'id_permintaan');
        $postData['no_permintaan']               = generateNextNoPermintaanBarang($lastNo, $postData['tanggal'] ?? null);
        $postData['id_status_permintaan_barang'] = 1;
    }

    // status dikelola lewat Ringkasan, jangan ikut ke-submit
    protected function before_update(array &$postData, int|string $id): void
    {
        unset($postData['id_status_permintaan_barang']);
    }
}
