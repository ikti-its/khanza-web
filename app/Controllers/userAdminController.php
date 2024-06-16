<?php

namespace App\Controllers;


class userAdminController extends BaseController
{

    public function tampilCuti($pegawaiId)
    {
        $title = 'Tampil Cuti';

        // Retrieve the value of the 'page' parameter from the request, default to 1 if not present
        $page = $this->request->getGet('page') ?? 1;

        // Retrieve the value of the 'size' parameter from the request, default to 5 if not present
        $size = $this->request->getGet('size') ?? 10;

        // Check if the user is logged in
        // Retrieve the stored JWT token
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            // URL for fetching akun data
            $cuti_url = $this->api_url . '/kehadiran/cuti?page=' . $page . '&size=' . $size;

            // Initialize cURL session
            $ch_cuti = curl_init($cuti_url);

            // Set cURL options
            curl_setopt($ch_cuti, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_cuti, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request for fetching akun data
            $response_cuti = curl_exec($ch_cuti);

            // Check the API response for akun data
            if ($response_cuti) {
                $http_status_code_cuti = curl_getinfo($ch_cuti, CURLINFO_HTTP_CODE);

                if ($http_status_code_cuti === 200) {
                    // Akun data fetched successfully
                    $cuti_data = json_decode($response_cuti, true);

                    // $total_pages = $akun_data['data']['total'];

                    return  view('/admin/tampilStatusCuti', ['cuti_data' => $cuti_data['data'], 'title' => $title]);
                } else {
                    // Error fetching cuti data
                    return $this->renderErrorView($http_status_code_cuti);
                }
            } else {
                // Error fetching cuti data
                return $this->renderErrorView(500); // Assume 500 for cURL error
            }

            // Close the cURL session for akun data
            curl_close($ch_cuti);
        } else {
            // User not logged in
            return $this->renderErrorView(401);
        }
    }



}