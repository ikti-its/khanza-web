<?php
declare(strict_types=1);

namespace App\Controllers;
use App\Core\Controller\Legacy\ControllerTemplateLegacy;

class admin extends ControllerTemplateLegacy
{
    public function dataPegawai()
    {
        echo view('/layouts/header');
        echo view('/admin/dataPegawai');

        if ($this->request->getGet()) {
        }
    }

    public function loadDataPegawai()
    {
    }


    public function daftarPegawai()
    {
        echo view('/layouts/header');
        echo view('/admin/daftarPegawai');
    }

    public function editPegawai()
    {
        echo view('/layouts/header');
        echo view('/admin/editPegawai');
    }

    public function presensiPegawai()
    {
        echo view('/layouts/header');
        echo view('/admin/presensiPegawai');
    }
}
