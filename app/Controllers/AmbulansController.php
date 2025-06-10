<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AmbulansController extends BaseController
{
    public function dataAmbulans()
    {
        $title = 'Data Ambulans';

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $ambulans_url = $this->api_url . '/ambulans';

            $ch = curl_init($ambulans_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_status !== 200) {
                return $this->renderErrorView($http_status);
            }

            $ambulans_data = json_decode($response, true);

            if (!isset($ambulans_data['data'])) {
                return $this->renderErrorView(500);
            }

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
        } else {
            return $this->renderErrorView(401);
        }
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
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $postData = [
                'no_ambulans' => $this->request->getPost('no_ambulans'),
                'status' => $this->request->getPost('status'),
                'supir' => $this->request->getPost('supir')
            ];

            $url = $this->api_url . '/ambulans';
            $json = json_encode($postData);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json),
                'Authorization: Bearer ' . $token
            ]);

            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return ($status === 201)
                ? redirect()->to(base_url('ambulans'))
                : $this->renderErrorView($status);
        }

        return $this->renderErrorView(401);
    }

    public function editAmbulans($noAmbulans)
    {
        if (!session()->has('jwt_token')) return $this->renderErrorView(401);

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/ambulans/' . $noAmbulans;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200) return $this->renderErrorView($status);

        $ambulans_data = json_decode($response, true);

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
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $postData = [
                'no_ambulans' => $this->request->getPost('no_ambulans'),
                'status' => $this->request->getPost('status'),
                'supir' => $this->request->getPost('supir')
            ];

            $json = json_encode($postData);
            $url = $this->api_url . '/ambulans/' . $noAmbulans;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ]);
            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return ($status === 200)
                ? redirect()->to(base_url('ambulans'))->with('success', 'Ambulans berhasil diperbarui.')
                : $this->renderErrorView($status);
        }

        return $this->renderErrorView(401);
    }

    public function hapusAmbulans($noAmbulans)
    {
        if (!session()->has('jwt_token')) return $this->renderErrorView(401);

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/ambulans/' . $noAmbulans;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($status === 200 || $status === 204)
            ? redirect()->to(base_url('ambulans'))->with('success', 'Ambulans berhasil dihapus.')
            : $this->renderErrorView($status);
    }

    public function panggilAmbulans($nomorBed)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $title = 'Edit Kamar';
    $kamar_url = $this->api_url . '/kamar/' . $nomorBed;

    // dd($kamar_url);

    // Make API request to fetch kamar data
    $ch_kamar = curl_init($kamar_url);
    curl_setopt($ch_kamar, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_kamar, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
    ]);
    $response = curl_exec($ch_kamar);
    $http_status = curl_getinfo($ch_kamar, CURLINFO_HTTP_CODE);
    curl_close($ch_kamar);

    // dd($response);

    // Handle API response
    if ($http_status !== 200) {
        return $this->renderErrorView($http_status);
    }

    $kamar_data = json_decode($response, true);

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
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $url = $this->api_url . '/ambulans/terima/' . $noAmbulans;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json',
    ]);

    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_status === 200) {
        return redirect()->to(base_url('ambulans'))->with('success', 'Ambulans telah diterima.');
    } else {
        return $this->renderErrorView($http_status);
    }
}

}
