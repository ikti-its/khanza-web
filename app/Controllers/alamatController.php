<?php

namespace App\Controllers;


class alamatController extends BaseController
{
    protected $api_url;

    public function __construct()
    {
        $this->api_url = getenv('api_URL');
    }

    public function alamatPegawai()
    {
        $title = 'Alamat Pegawai';

        // Retrieve the value of the 'page' parameter from the request, default to 1 if not present
        $page = $this->request->getGet('page') ?? 1;

        // Retrieve the value of the 'size' parameter from the request, default to 5 if not present
        $size = $this->request->getGet('size') ?? 10;

        // Check if the user is logged in
        // Retrieve the stored JWT token
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            // URL for fetching akun data
            $akun_url = $this->api_url . '/akun/alamat?page=' . $page . '&size=' . $size;

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
                    $alamat_data = json_decode($response_akun, true);

                    // $total_pages = $akun_data['data']['total'];

                    return  view('/admin/alamatPegawai', ['alamat_data' => $alamat_data['data']['alamat'], 'meta_data' => $alamat_data['data'], 'title' => $title]);
                } else {
                    // Error fetching akun data
                    return "Error fetching akun data. HTTP Status Code: $http_status_code_akun" ;
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

    public function tambahAlamat()
    {
        $title = 'Tambah Alamat';

        // If the request method is not POST or form data is missing, render the add account view
        echo view('/admin/tambahAlamat', ['title' => $title]);
    }

    public function submitTambahAlamat()
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_akun = $this->request->getPost('id_akun');
            $alamat = $this->request->getPost('alamat');
            $alamat_lat = floatval($this->request->getPost('alamat_lat'));
            $alamat_lon = floatval($this->request->getPost('alamat_lon'));
            $kota = $this->request->getPost('kota');
            $kode_pos = $this->request->getPost('kode_pos');

            // Prepare the data to be sent to the API
            $postData = [
                'id_akun' => $id_akun,
                'alamat' => $alamat,
                'alamat_lat' => $alamat_lat,
                'alamat_lon' => $alamat_lon,
                'kota' => $kota,
                'kode_pos' => $kode_pos
            ];

            $tambah_alamat_JSON = json_encode($postData);

            $akun_url = $this->api_url . '/akun/alamat';

            // Check if email and role are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for email and role

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the POST request
                $ch = curl_init($akun_url);

                // Set cURL options for sending a POST request
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_alamat_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_alamat_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                // Execute the cURL request
                $response = curl_exec($ch);

                // Check if the API request was successful
                if ($response) {

                    // Check if the HTTP status code in the response
                    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($http_status_code === 201) {
                        // Account created successfully
                        $title = 'Data Akun';

                        // Pass the created account data along with the title to the view
                        return redirect()->to(base_url('alamatpegawai?page=1&size=5'));
                    } else {
                        // Error response from the API
                        return "Error creating account: " . $tambah_alamat_JSON;
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

    public function editAlamat($pegawaiId)
    {

        if (session()->has('jwt_token')) {

            //retrieve the stored JWT Token
            $token = session()->get('jwt_token');

            // Fetch the user data from the API or database based on the user ID
            $user_url = $this->api_url . '/akun/alamat/' . $pegawaiId;

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

                    //Render the view to edit user data, passing the user data
                    return view('/admin/editAlamat', ['userData' => $userData['data'], 'pegawaiId' => $pegawaiId, 'title' => 'Edit Alamat']);
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

    public function submitEditAlamat($pegawaiId)
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_akun = $this->request->getPost('id_akun');
            $alamat = $this->request->getPost('alamat');
            $alamat_lat = floatval($this->request->getPost('alamat_lat'));
            $alamat_lon = floatval($this->request->getPost('alamat_lon'));
            $kota = $this->request->getPost('kota');
            $kode_pos = $this->request->getPost('kode_pos');

            // Prepare the data to be sent to the API
            $postData = [
                'id_akun' => $id_akun,
                'alamat' => $alamat,
                'alamat_lat' => $alamat_lat,
                'alamat_lon' => $alamat_lon,
                'kota' => $kota,
                'kode_pos' => $kode_pos
            ];

            $edit_alamat_JSON = json_encode($postData);

            $pegawai_url = $this->api_url . '/akun/alamat/' . $pegawaiId;

            // Check if email and role are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for email and role

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the PUT request
                $ch = curl_init($pegawai_url);

                // Set cURL options for sending a PUT request
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($edit_alamat_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($edit_alamat_JSON),
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
                        return redirect()->to(base_url('alamatpegawai?page=1&size=5'));

                    } else {
                        // Error response from the API
                        return "Error updating account: " . $response . $edit_alamat_JSON;
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

    public function hapusAlamat($pegawaiId)
    {
        // Check if the user is logged in
        if (session()->has('jwt_token')) {
            // Retrieve the stored JWT token
            $token = session()->get('jwt_token');
    
            // URL for deleting the user data
            $delete_url = $this->api_url . '/akun/alamat/' . $pegawaiId;
    
            // Initialize cURL session for sending the DELETE request
            $ch = curl_init($delete_url);
    
            // Set cURL options for sending a DELETE request
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
    
            // Execute the cURL request
            $response = curl_exec($ch);
    
            // Check the HTTP status code in the response
            $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_status_code === 204) {
                // User deleted successfully
                return redirect()->to(base_url('alamatpegawai?page=1&size=5'));
            } else {
                // Error response from the API
                return "Error deleting user: " . $response;
            }
    
            // Close the cURL session
            curl_close($ch);
        } else {
            // User not logged in
            return "User not logged in. Please log in first.";
        }
    }
}
