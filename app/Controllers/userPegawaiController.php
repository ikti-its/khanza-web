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
            $akun_url = $this->api_url . '/m/home?tanggal=' . $tanggal;

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


    public function submitEditProfil($pegawaiId)
    {
        if ($this->request->getPost()) {


            $akun = $this->request->getPost('akun');
            $foto = $this->request->getPost('foto');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $alamat = $this->request->getPost('alamat');
            $alamat_lat = floatval($this->request->getPost('alamat_lat'));
            $alamat_lon = floatval($this->request->getPost('alamat_lon'));


            // Prepare the data to be sent to the API
            $postData = [
                'akun' => $akun,
                'foto' => $foto,
                'email' => $email,
                'password' => $password,
                'alamat' => $alamat,
                'alamat_lat' => $alamat_lat,
                'alamat_lon' => $alamat_lon

            ];

            $edit_pegawai_JSON = json_encode($postData);

            $tanggal = date('Y-m-d');
            // URL for fetching akun data
            $pegawai_url = $this->api_url . '/m/profile/' . $pegawaiId;

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
                        $title = 'Data Akun';

                        // Pass the updated account data along with the title to the view
                        return redirect()->to(base_url('profile'));
                    } else {
                        // Error response from the API
                        return "Error updating account: " . $response . $edit_pegawai_JSON;
                    }
                } else {
                    // Error sending request to the API
                    return "Error sending request to the API.";
                }

                // Close the cURL session
                curl_close($ch);
            } else {
                // Email or role is empty
                return "Email and role are required.";
            }
        }
    }

    public function lihatPegawai()
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
            // URL for fetching akun data
            $akun_url = $this->api_url . '/pegawai?page=' . $page . '&size=' . $size;

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

                    return  view('/user/dataPegawai', ['akun_data' => $akun_data['data']['pegawai'], 'meta_data' => $akun_data['data'], 'title' => $title]);
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

    public function detailBerkasPegawai($pegawaiId)
    {

        if (session()->has('jwt_token')) {

            //retrieve the stored JWT Token
            $token = session()->get('jwt_token');

            // Fetch the user data from the API or database based on the user ID
            $user_url = $this->api_url . '/pegawai/' . $pegawaiId;

            //Initialize cURL session
            $ch_user = curl_init($user_url);

            // Set cURL options
            curl_setopt($ch_user, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_user, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request for fetching user data
            $response_user = curl_exec($ch_user);

            // Check the API response for user data

            if ($response_user) {
                $http_status_code = curl_getinfo($ch_user, CURLINFO_HTTP_CODE);

                if ($http_status_code === 200) {
                    //user data fetched successfully
                    $userData = json_decode($response_user, true);

                    // Fetch the user data from the API or database based on the user ID
                    $berkas_url = $this->api_url . '/pegawai/berkas/' . $pegawaiId;

                    //Initialize cURL session
                    $ch_berkas = curl_init($berkas_url);

                    // Set cURL options
                    curl_setopt($ch_berkas, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_berkas, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);

                    // Execute the cURL request for fetching user data
                    $response_berkas = curl_exec($ch_berkas);

                    if ($response_berkas) {
                        $http_status_berkas = curl_getinfo($ch_berkas, CURLINFO_HTTP_CODE);

                        if ($http_status_berkas === 200) {
                            $berkasData = json_decode($response_berkas, true);
                            //Render the view to edit user data, passing the user data
                            return view('/user/berkasPegawai', ['userData' => $userData['data'], 'berkasData' => $berkasData['data'], 'pegawaiId' => $pegawaiId, 'title' => 'Edit Pegawai']);
                        }
                    }
                } else {
                    // Error fetching user data
                    return "Error fetching user data. HTTP Status Code: $http_status_code $pegawaiId";
                }
            } else {
                //Error fetching user data
                return "Error fetching user data.";
            }

            //Close the cURL session for user data
            curl_close($ch_user);
        } else {
            //User not logged in
            return "User not logged in. Please log in first. ";
        }
    }

    public function tampilCatatanKehadiran()
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
            // URL for fetching akun data
            $kehadiran_url = $this->api_url . '/kehadiran/presensi?page=' . $page . '&size=' . $size;

            // Initialize cURL session
            $ch_kehadiran = curl_init($kehadiran_url);

            // Set cURL options
            curl_setopt($ch_kehadiran, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_kehadiran, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request for fetching akun data
            $response_kehadiran = curl_exec($ch_kehadiran);

            // Check the API response for akun data
            if ($response_kehadiran) {
                $http_status_code_akun = curl_getinfo($ch_kehadiran, CURLINFO_HTTP_CODE);

                if ($http_status_code_akun === 200) {
                    // Akun data fetched successfully
                    $kehadiran_data = json_decode($response_kehadiran, true);

                    // $total_pages = $akun_data['data']['total'];

                    return  view('/user/tampilCatatanKehadiran', ['kehadiran_data' => $kehadiran_data['data'], 'title' => $title]);
                } else {
                    // Error fetching akun data
                    return "Error fetching akun data. HTTP Status Code: $http_status_code_akun";
                }
            } else {
                // Error fetching akun data
                return "Error fetching akun data.";
            }

            // Close the cURL session for akun data
            curl_close($ch_kehadiran);
        } else {
            return "User not logged in. Please log in first.";
        }
    }

    public function tampilStatusIzin()
    {
        $title = 'Detail berkas';
        return view('/user/statusPengajuanIzin', ['title' => $title]);
    }

    public function tambahCuti()
    {
        $title = 'Detail berkas';
        return view('/user/izinCuti', ['title' => $title]);
    }

    public function tambahPresensi()
    {
        $title = 'Detail berkas';
        return view('/user/tambahPresensi', ['title' => $title]);
    }

    public function tambahSwafoto()
    {
        $title = 'Detail berkas';
        return view('/user/tambahSwafoto', ['title' => $title]);
    }


}
