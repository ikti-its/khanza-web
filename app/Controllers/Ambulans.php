<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Ambulans extends BaseController
{
    protected string $judul = 'Data Ambulans';
    protected string $modul_path = '/ambulans';
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
        $ambulans_data = $this->fetchDataUsingCurl('GET', '/ambulans')['data'];
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Ambulans', 'ambulans');
        $breadcrumbs = $this->getBreadcrumbs();

        $meta_data = $ambulans_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1];

        return view('/admin/ambulans/ambulans_data', [
            'ambulans_data' => $ambulans_data['data'],
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
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
                'breadcrumbs' => $this->getBreadcrumbs()
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitTambahAmbulans()
    {
        $postData = [
            'no_ambulans' => $this->request->getPost('no_ambulans'),
            'status' => $this->request->getPost('status'),
            'supir' => $this->request->getPost('supir')
        ];
        return $this->fetchDataUsingCurl('POST', '/ambulans', $postData, 'ambulans');
    }

    public function editAmbulans($noAmbulans)
    {
        $ambulans_data = $this->fetchDataUsingCurl('GET', '/ambulans/' . $noAmbulans)['data'];

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Ambulans', 'ambulans');
        $this->addBreadcrumb('Edit', 'edit');

        return view('/admin/ambulans/edit_ambulans', [
            'ambulans' => $ambulans_data['data'],
            'title' => 'Edit Ambulans',
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }

    public function submitEditAmbulans($noAmbulans)
    {
        $postData = [
            'no_ambulans' => $this->request->getPost('no_ambulans'),
            'status' => $this->request->getPost('status'),
            'supir' => $this->request->getPost('supir')
        ];
        return $this->fetchDataUsingCurl('PUT', '/ambulans/' . $noAmbulans, $postData, 'ambulans', 'Ambulans berhasil diperbarui.');
    }

    public function hapusAmbulans($noAmbulans)
    {
        return $this->fetchDataUsingCurl('DELETE', '/ambulans/' . $noAmbulans, null, 'ambulans', 'Ambulans berhasil dihapus.' );
    }

    public function panggilAmbulans($nomorBed)
    {
        $title = 'Edit Kamar';
        $kamar_data = $this->fetchDataUsingCurl('GET', '/kamar/' . $nomorBed)['data'];

        // Breadcrumbs setup
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Kamar', 'kamar');
        $this->addBreadcrumb('Terima', 'terima');
        $breadcrumbs = $this->getBreadcrumbs();
            // dd($kamar_data);
        // Return the edit view with kamar data
        return view('/admin/kamar/terima_kamar', [
            'kamar' => $kamar_data['data'],
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function terimaAmbulans($noAmbulans)
    {
        return $this->fetchDataUsingCurl('PUT', '/ambulans/terima/' . $noAmbulans, null, 'ambulans', 'Ambulans telah diterima.');
    }
}