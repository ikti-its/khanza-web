<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Kota;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class KotaController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KotaModel(),
            [
                ['Lokasi', 'lokasi'],
                ['Kota', 'kota'],
            ],
            'Kota',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id_kota',       'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_provinsi', 'Provinsi'],
                [SHOW, REQUIRED, I::TEXT,  'id_kota_lokal', 'Kode Lokal'],
                [SHOW, REQUIRED, I::TEXT,  'nama_kota',     'Kota'],
            ],
        );
    }

    /**
     * Menampilkan data modal kota
     */
    public function list()
    {
        $tabel = $this->model->table;

        $data = $this->model->builder()
            ->select("
                {$tabel}.id_kota,
                {$tabel}.nama_kota
            ")
            ->where("{$tabel}.id_kota >", 0)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
}
