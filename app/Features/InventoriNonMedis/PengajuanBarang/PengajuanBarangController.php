<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengajuanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PengajuanBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengajuanBarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Pengajuan Barang',    'pengajuan_barang'],
            ],
            'Pengajuan Barang',
            [
                A::READ,
                A::CREATE,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_pengajuan',               'ID Pengajuan'],
                [HIDE,       OPTIONAL, I::INDEX,  'id_permintaan',              'ID Permintaan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'no_pengajuan',               'No. Pengajuan'],
                [SHOW,       REQUIRED, I::DATE,   'tanggal',                    'Tanggal'],
                [SHOW,       OPTIONAL, I::SELECT, 'id_status_pengajuan_barang', 'Status'],
                [SHOW,       OPTIONAL, I::TEXT,   'catatan',                    'Catatan'],
                [SHOW,       OPTIONAL, I::TEXT,   'catatan_atasan',             'Catatan Atasan'],
            ],
            child_path: '/inventori-non-medis/detail-pengajuan-barang',
            child_fk:   'id_pengajuan',
        );
    }

    protected function before_create(array &$postData): void
    {
        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.pengajuan_barang', 'no_pengajuan', 'id_pengajuan');
        $postData['no_pengajuan'] = generateNextNoPengajuanBarang($lastNo, $postData['tanggal'] ?? null);
    }
}
