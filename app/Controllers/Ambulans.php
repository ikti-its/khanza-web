<?php
declare(strict_types=1);

namespace App\Controllers;
use App\Core\Controller\Legacy\ControllerTemplateLegacy;
use App\Core\Controller\ErrorController;
use App\Core\Controller\Legacy\CURL;

class Ambulans extends ControllerTemplateLegacy
{
    protected string $judul = 'Data Ambulans';
    protected string $modul_path = '/ambulans';
    protected string $nama_tabel = 'ambulans';
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'Nomor Ambulans' , 'no_ambulans', 'indeks'],
        [1, 'Supir'          , 'supir'      , 'nama'],
        [1, 'Status Ambulans', 'status'     , 'status']
    ];
    
    public function dataAmbulans()
    {
        $title = 'Data Ambulans';
        $ambulans_data = CURL::call('GET', '/ambulans')['data'];
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Ambulans', 'ambulans');
        
        $meta_data = $ambulans_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1];

        return view('/admin/ambulans/ambulans_data', [
            'ambulans_data' => $ambulans_data['data'],
            'title' => $title,
            'breadcrumbs' => $this->breadcrumbs,
            'meta_data' => $meta_data
        ]);
        
    }

    public function tambahAmbulans()
    {
        if (session()->has('jwt_token')) {
            $title = 'Tambah Ambulans';

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Ambulans', 'ambulans');
            $this->addBreadcrumb('Tambah', 'tambah');

            return view('/admin/ambulans/tambah_ambulans', [
                'title' => $title,
                'breadcrumbs' => $this->breadcrumbs
            ]);
        } else {
            return ErrorController::renderErrorView(401);
        }
    }

    public function submitTambahAmbulans()
    {
        $postData = [
            'no_ambulans' => $this->request->getPost('no_ambulans'),
            'status' => $this->request->getPost('status'),
            'supir' => $this->request->getPost('supir')
        ];
        $_response = CURL::call('POST', '/ambulans', $postData);
        return redirect()->to(base_url('/ambulans'))->with('success', 'Berhasil');
    }

    public function editAmbulans($noAmbulans)
    {
        $ambulans_data = CURL::call('GET', '/ambulans/' . $noAmbulans)['data'];

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Ambulans', 'ambulans');
        $this->addBreadcrumb('Edit', 'edit');

        return view('/admin/ambulans/edit_ambulans', [
            'ambulans' => $ambulans_data['data'],
            'title' => 'Edit Ambulans',
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function submitEditAmbulans($noAmbulans)
    {
        $postData = [
            'no_ambulans' => $this->request->getPost('no_ambulans'),
            'status' => $this->request->getPost('status'),
            'supir' => $this->request->getPost('supir')
        ];
        $_response = CURL::call('PUT', '/ambulans/' . $noAmbulans, $postData);
        return redirect()->to(base_url('/ambulans'))->with('success', 'Berhasil');
    }

    public function hapusAmbulans($noAmbulans)
    {
        $_response = CURL::call('DELETE', '/ambulans/' . $noAmbulans);
        return redirect()->to(base_url('/ambulans'))->with('success', 'Berhasil');
    }

    public function panggilAmbulans($nomorBed)
    {
        $title = 'Edit Kamar';
        $kamar_data = CURL::call('GET', '/kamar/' . $nomorBed)['data'];

        // Breadcrumbs setup
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Kamar', 'kamar');
        $this->addBreadcrumb('Terima', 'terima');
                    // dd($kamar_data);
        // Return the edit view with kamar data
        return view('/admin/kamar/terima_kamar', [
            'kamar' => $kamar_data['data'],
            'title' => $title,
            'breadcrumbs' => $this->breadcrumbs,
        ]);
    }

    public function terimaAmbulans($noAmbulans)
    {
        $_response = CURL::call('PUT', '/ambulans/terima/' . $noAmbulans);
        return redirect()->to(base_url('/ambulans'))->with('success', 'Berhasil');
    }
}