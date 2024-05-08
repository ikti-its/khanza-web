<?php

namespace App\Controllers;


class pegawaiController extends BaseController
{

    protected $api_url;

    public function __construct()
    {
        $this->api_url = getenv('api_URL');
    }


    public function dataPegawai()
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
            $akun_url =$this->api_url . '/pegawai?page=' . $page . '&size=' . $size;

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

                    return  view('/admin/dataPegawai', ['akun_data' => $akun_data['data']['pegawai'], 'meta_data' => $akun_data['data'], 'title' => $title]);
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

    public function tambahPegawai()
    {
        $title = 'Tambah Pegawai';

        // If the request method is not POST or form data is missing, render the add account view
        echo view('/admin/tambahPegawai', ['title' => $title]);
    }

    public function submitTambahPegawai()
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_akun = $this->request->getPost('id_akun');
            $nip = $this->request->getPost('nip');
            $nama = $this->request->getPost('nama');
            $jenis_kelamin = $this->request->getPost('jenis_kelamin');
            $jabatan = intval($this->request->getPost('jabatan'));
            $departemen = intval($this->request->getPost('departemen'));
            $status_aktif = $this->request->getPost('status_aktif');
            $jenis_pegawai = $this->request->getPost('jenis_pegawai');
            $telepon = $this->request->getPost('telepon');
            $tanggal_masuk = $this->request->getPost('tanggal_masuk');

            // Prepare the data to be sent to the API
            $postData = [
                'id_akun' => $id_akun,
                'nip' => $nip,
                'nama' => $nama,
                'jenis_kelamin' => $jenis_kelamin,
                'jabatan' => $jabatan,
                'departemen' => $departemen,
                'status_aktif' => $status_aktif,
                'jenis_pegawai' => $jenis_pegawai,
                'telepon' => $telepon,
                'tanggal_masuk' => $tanggal_masuk

            ];

            $tambah_akun_JSON = json_encode($postData);

            $akun_url = $this->api_url . '/pegawai';

            // Check if email and role are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for email and role

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the POST request
                $ch = curl_init($akun_url);

                // Set cURL options for sending a POST request
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_akun_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_akun_JSON),
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
                        return redirect()->to(base_url('datapegawai?page=1&size=5'));
                    } else {
                        // Error response from the API
                        return "Error creating account: " . $tambah_akun_JSON;
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

    public function editPegawai($pegawaiId)
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

                    //Render the view to edit user data, passing the user data
                    return view('/admin/editPegawai', ['userData' => $userData['data'], 'pegawaiId' => $pegawaiId, 'title' => 'Edit Pegawai']);
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

    public function submitEditPegawai($pegawaiId)
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_akun = $this->request->getPost('id_akun');
            $nip = $this->request->getPost('nip');
            $nama = $this->request->getPost('nama');
            $jenis_kelamin = $this->request->getPost('jenis_kelamin');
            $jabatan = intval($this->request->getPost('jabatan'));
            $departemen = intval($this->request->getPost('departemen'));
            $status_aktif = $this->request->getPost('status_aktif');
            $jenis_pegawai = $this->request->getPost('jenis_pegawai');
            $telepon = $this->request->getPost('telepon');
            $tanggal_masuk = $this->request->getPost('tanggal_masuk');

            // Prepare the data to be sent to the API
            $postData = [
                'id_akun' => $id_akun,
                'nip' => $nip,
                'nama' => $nama,
                'jenis_kelamin' => $jenis_kelamin,
                'jabatan' => $jabatan,
                'departemen' => $departemen,
                'status_aktif' => $status_aktif,
                'jenis_pegawai' => $jenis_pegawai,
                'telepon' => $telepon,
                'tanggal_masuk' => $tanggal_masuk

            ];

            $edit_pegawai_JSON = json_encode($postData);

            $pegawai_url =$this->api_url . '/pegawai/' . $pegawaiId;

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
                        return redirect()->to(base_url('datapegawai?page=1&size=5'));

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

    public function hapusPegawai($pegawaiId)
    {
        // Check if the user is logged in
        if (session()->has('jwt_token')) {
            // Retrieve the stored JWT token
            $token = session()->get('jwt_token');
    
            // URL for deleting the user data
            $delete_url = $this->api_url . '/pegawai/' . $pegawaiId;
    
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
                return redirect()->to(base_url('datapegawai?page=1&size=5'));
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
