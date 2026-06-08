<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\Barang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class BarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new BarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Barang',              'barang'],
            ],
            'Barang',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_barang',       'ID Barang'],
                [SHOW,       REQUIRED, I::TEXT,   'kode_barang',     'Kode Barang'],
                [SHOW,       REQUIRED, I::NAME,   'nama_barang',     'Nama Barang'],
                [SHOW,       REQUIRED, I::SELECT, 'id_satuan',       'Satuan'],
                [SHOW,       REQUIRED, I::SELECT, 'id_jenis_barang', 'Jenis'],
                [TABLE_ONLY, OPTIONAL, I::NUMBER, 'stok',            'Stok'],
                [FORM_ONLY,  OPTIONAL, I::NUMBER, 'stok_minimum',    'Stok Minimum'],
                [SHOW,       OPTIONAL, I::MONEY,  'harga_satuan',    'Harga Satuan'],
            ],
        );
    }

    protected array $row_alert = ['value' => 'stok', 'threshold' => 'stok_minimum'];

    /** @param array<string, scalar|null> $postData */
    protected function before_create(array &$postData): void
    {
        $postData['stok'] = 0;
    }
}
