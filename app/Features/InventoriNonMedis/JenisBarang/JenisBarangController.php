<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\JenisBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\ResponseInterface;

final class JenisBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JenisBarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Jenis Barang',        'jenis_barang'],
            ],
            'Jenis Barang',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_jenis_barang',   'ID'],
                [SHOW, REQUIRED, I::TEXT,  'kode_jenis_barang', 'Kode Jenis'],
                [SHOW, REQUIRED, I::NAME,  'nama_jenis_barang', 'Jenis Barang'],
            ],
        );
    }

    public function list(): ResponseInterface
    {
        $data = $this->model
            ->builder()
            ->select('id_jenis_barang, kode_jenis_barang, nama_jenis_barang')
            ->orderBy('nama_jenis_barang', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }

    // urut berdasarkan nama A-Z
    #[\Override]
    protected function before_read(): void
    {
        $this->model->set_order('nama_jenis_barang', 'ASC');
    }
}
