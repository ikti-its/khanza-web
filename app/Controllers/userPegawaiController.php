<?php

namespace App\Controllers;


class userPegawaiController extends BaseController
{



    public function lihatProfil()
    {
        $title = 'Data Akun';

        // Retrieve the value of the 'page' parameter from the request, default to 1 if not present
        $page = $this->request->getGet('page') ?? 1;

        // Retrieve the value of the 'size' parameter from the request, default to 5 if not present
        $size = $this->request->getGet('size') ?? 10;

        // Check if the user is logged in
        // Retrieve the stored JWT token
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $tanggal = date('Y-m-d');
            // URL for fetching akun data
            $akun_url =$this->api_url . '/w/home/pegawai?tanggal=' . $tanggal;

            // Initialize cURL session
            $ch_akun = curl_init($akun_url);

            // Set cURL options
            curl_setopt($ch_akun, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_akun, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request for fetching akun data
            $response_akun = curl_exec($ch_akun);

            // Check the API response for akun data
            if ($response_akun) {
                $http_status_code_akun = curl_getinfo($ch_akun, CURLINFO_HTTP_CODE);

                if ($http_status_code_akun === 200) {
                    // Akun data fetched successfully
                    $akun_data = json_decode($response_akun, true);

                    // $total_pages = $akun_data['data']['total'];

                    return  view('/user/homeUser', ['akun_data' => $akun_data['data'], 'title' => $title]);
                } else {
                    // Error fetching akun data
                    return "Error fetching akun data. HTTP Status Code: $http_status_code_akun";
                }
            } else {
                // Error fetching akun data
                return "Error fetching akun data.";
            }

            // Close the cURL session for akun data
            curl_close($ch_akun);
        } else {
            return "User not logged in. Please log in first.";
        }
    }

    
}
