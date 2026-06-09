<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengadaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PengadaanBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengadaanBarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Pengadaan Barang',    'pengadaan_barang'],
            ],
            'Pengadaan Barang',
            [
                A::READ,
                A::CREATE,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_pengadaan',               'ID Pengadaan'],
                [HIDE,       OPTIONAL, I::INDEX,  'id_pengajuan',               'ID Pengajuan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'no_pengadaan',               'No. Pengadaan'],
                [SHOW,       REQUIRED, I::SELECT, 'id_suplier',                 'Suplier'],
                [SHOW,       REQUIRED, I::DATE,   'tanggal',                    'Tanggal'],
                [SHOW,       OPTIONAL, I::SELECT, 'id_status_pengadaan_barang', 'Status'],
                [SHOW,       OPTIONAL, I::TEXT,   'catatan',                    'Catatan'],
            ],
            child_path: '/inventori-non-medis/detail-pengadaan-barang',
            child_fk:   'id_pengadaan',
        );
    }

    protected function before_create(array &$postData): void
    {
        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.pengadaan_barang', 'no_pengadaan', 'id_pengadaan');
        $postData['no_pengadaan'] = generateNextNoPengadaanBarang($lastNo, $postData['tanggal'] ?? null);
    }
}
