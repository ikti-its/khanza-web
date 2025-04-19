<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PemberianObatController extends BaseController
{


    public function dataPemberianObat()
    {
        $title = 'Data Pemberian Obat';
    
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
    
            // ✅ Fetch pemberian_obat data
            $obat_url = $this->api_url . '/pemberian-obat';
            $ch = curl_init($obat_url);
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
    
            $obat_data = json_decode($response, true);
            if (!isset($obat_data['data'])) {
                return $this->renderErrorView(500);
            }
    
            // ✅ Breadcrumbs
            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Pemberian Obat', 'pemberianobat');
            $breadcrumbs = $this->getBreadcrumbs();
    
            return view('/admin/pemberianobat/pemberianobat_data', [
                'pemberianobat_data' => $obat_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $obat_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }
}    