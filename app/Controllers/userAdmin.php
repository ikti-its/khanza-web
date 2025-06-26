<?php

namespace App\Controllers;


class userAdmin extends BaseController
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
            // URL for fetching cuti data
            $cuti_url = $this->api_url . '/kehadiran/cuti?page=' . $page . '&size=' . $size;

            // Initialize cURL session
            $ch_cuti = curl_init($cuti_url);

            // Set cURL options
            curl_setopt($ch_cuti, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_cuti, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request for fetching cuti data
            $response_cuti = curl_exec($ch_cuti);

            // Check the API response for cuti data
            if ($response_cuti) {
                $http_status_code_cuti = curl_getinfo($ch_cuti, CURLINFO_HTTP_CODE);

                if ($http_status_code_cuti === 200) {
                    // Cuti data fetched successfully
                    $cuti_data = json_decode($response_cuti, true);

                    // Filter cuti_data to only include entries with status 'Diproses'
                    $cuti_entries_diproses = array_filter($cuti_data['data']['cuti'], function ($cutiEntry) {
                        return $cutiEntry['status'] == 'Diproses';
                    });

                    // Fetch employee names and append to $cuti_entries_diproses
                    foreach ($cuti_entries_diproses as &$cutiEntry) {
                        // Make API call to fetch employee details
                        $pegawai_url = $this->api_url . '/pegawai/' . $cutiEntry['id_pegawai'];

                        // Initialize cURL session for fetching employee details
                        $ch_pegawai = curl_init($pegawai_url);
                        curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                            'Authorization: Bearer ' . $token,
                        ]);

                        // Execute cURL request for fetching employee details
                        $response_pegawai = curl_exec($ch_pegawai);

                        if ($response_pegawai) {
                            $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);

                            if ($http_status_code_pegawai === 200) {
                                // Employee details fetched successfully
                                $pegawai_data = json_decode($response_pegawai, true);

                                // Assign employee name to the current $cutiEntry
                                $cutiEntry['nama_pegawai'] = $pegawai_data['data']['nama'];
                            } else {
                                // Error fetching employee details
                                // Handle error response as per your application's logic
                                $cutiEntry['nama_pegawai'] = 'Error: Failed to fetch name';
                            }
                        } else {
                            // Error fetching employee details
                            // Handle cURL error or API response error
                            $cutiEntry['nama_pegawai'] = 'Error: Failed to fetch name';
                        }

                        // Close the cURL session for employee details
                        curl_close($ch_pegawai);
                    }

                    // Return the view with filtered cuti data and additional employee names
                    return view('/admin/tampilStatusCuti', [
                        'cuti_data' => $cuti_entries_diproses, // Filtered data with employee names
                        'meta_data' => $cuti_data['data'],
                        'title' => $title
                    ]);
                } else {
                    // Error fetching cuti data
                    return $this->renderErrorView($http_status_code_cuti);
                }
            } else {
                // Error fetching cuti data
                return $this->renderErrorView(500); // Assume 500 for cURL error
            }

            // Close the cURL session for cuti data
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
                        var_dump($postData);
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

    public function submitKirimNotifikasi()
    {
        if ($this->request->getPost()) {
            // Retrieve the form data from the POST request
            $recipient = $this->request->getPost('id_penerima');
            $judul = $this->request->getPost('judul');
            $pesan = $this->request->getPost('pesan');
            $tanggal = date('Y-m-d');

            // Check if required fields are provided
            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');

                // Fetch id_akun based on id_penerima
                $pegawai_url = $this->api_url . '/pegawai/' . $recipient;

                // Initialize cURL session for fetching employee details
                $ch_pegawai = curl_init($pegawai_url);
                curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                ]);

                $pegawai_response = curl_exec($ch_pegawai);
                $pegawai_http_status_code = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);

                // Check for cURL errors
                if ($pegawai_response === false) {
                    $error_message = curl_error($ch_pegawai);
                    curl_close($ch_pegawai);
                    log_message('error', 'cURL Error: ' . $error_message);
                    return $this->renderErrorView(500); // Exit method on cURL error
                }

                // Close the cURL session
                curl_close($ch_pegawai);

                // Log the response for debugging
                log_message('debug', 'Pegawai Response: ' . print_r($pegawai_response, true));

                // Decode the JSON response
                $pegawai_data = json_decode($pegawai_response, true);

                // Check HTTP status code
                if ($pegawai_http_status_code === 200) {
                    // Debug using var_dump
                    var_dump($pegawai_data);

                    // Check if id_akun exists in the response
                    if (isset($pegawai_data['data']['id_akun'])) {
                        $id_akun = $pegawai_data['data']['id_akun'];

                        // Prepare the data to be sent to the API
                        $postData = [
                            'recipient' => $id_akun,
                            'tanggal' => $tanggal,
                            'judul' => $judul,
                            'pesan' => $pesan,
                        ];

                        $tambah_notif_JSON = json_encode($postData);

                        $notif_url = $this->api_url . '/w/notification';

                        // Initialize cURL session for sending the POST request
                        $ch = curl_init($notif_url);

                        // Set cURL options for sending a POST request
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $tambah_notif_JSON);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($tambah_notif_JSON),
                            'Authorization: Bearer ' . $token,
                        ]);

                        // Execute the cURL request
                        $response = curl_exec($ch);

                        // Check if the API request was successful
                        if ($response) {
                            // Check the HTTP status code in the response
                            $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                            if ($http_status_code === 201) {
                                // Notification created successfully
                                return view('/user/dashboard', ['title' => 'Dashboard pegawai']); // Redirect to desired page
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
                        // Log or handle the error
                        log_message('error', 'id_akun not found in the response: ' . print_r($pegawai_data, true));
                        return $this->renderErrorView(404);
                    }
                } else {
                    // Error response from the employee details API
                    log_message('error', 'HTTP Error: ' . $pegawai_http_status_code);
                    return $this->renderErrorView($pegawai_http_status_code);
                }
            } else {
                // User is not logged in
                return redirect()->to('/login')->with('error', 'User not logged in. Please log in first.');
            }
        }
    }
}