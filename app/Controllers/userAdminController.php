<?php

namespace App\Controllers;


class userAdminController extends BaseController
{

    public function lihatStatusCuti()
    {
        $title = 'Tampil Status Cuti';

        // Retrieve the value of the 'page' parameter from the request, default to 1 if not present
        $page = $this->request->getGet('page') ?? 1;

        // Retrieve the value of the 'size' parameter from the request, default to 10 if not present
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

                    return  view('/admin/tampilStatusCuti', ['cuti_data' => $cuti_data['data']['cuti'], 'meta_data' => $cuti_data['data'], 'title' => $title]);
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

    public function submitEditStatusCuti($pegawaiId)
    {
        if ($this->request->getPost()) {


            $id_pegawai = $this->request->getPost('id_pegawai');
            $tanggal_mulai = $this->request->getPost('tanggal_mulai');
            $tanggal_selesai = $this->request->getPost('tanggal_selesai');
            $status = $this->request->getPost('status');
            $id_alasan_cuti = $this->request->getPost('id_alasan_cuti');
 


            // Prepare the data to be sent to the API
            $postData = [
                'id_pegawai' => $id_pegawai,
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_selesai' => $tanggal_selesai,
                'status' => $status,
                'id_alasan_cuti' => $id_alasan_cuti

            ];

            $edit_pegawai_JSON = json_encode($postData);

            $tanggal = date('Y-m-d');
            // URL for fetching akun data
            $pegawai_url = $this->api_url . '/kehadiran/cuti/' . $pegawaiId;

            // Check if email and role are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for email and role

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the PUT request
                $ch = curl_init($pegawai_url);

                // Set cURL options for sending a PUT request
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($edit_pegawai_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($edit_pegawai_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                // Execute the cURL request
                $response = curl_exec($ch);

                // Check if the API request was successful
                if ($response) {

                    // Check if the HTTP status code in the response
                    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($http_status_code === 200) {
                        // Account updated successfully
                        $title = 'Tampil Status Cuti';

                        // Pass the updated account data along with the title to the view
                        return redirect()->to(base_url('lihatstatuscuti'));
                    } else {
                        // Error response from the API
                        return $this->renderErrorView($http_status_code);
                    }
                } else {
                    // Error sending request to the API
                    return $this->renderErrorView(500);
                }

                // Close the cURL session
                curl_close($ch);
            } else {
                // User not logged in
                return $this->renderErrorView(401);
            }
        }
    }

}
