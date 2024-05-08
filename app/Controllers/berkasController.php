<?php

namespace App\Controllers;


class berkasController extends BaseController
{
    protected $api_url;

    public function __construct()
    {
        $this->api_url = getenv('api_URL');
    }

    public function berkasPegawai()
    {
        $title = 'Berkas Pegawai';

        // Retrieve the value of the 'page' parameter from the request, default to 1 if not present
        $page = $this->request->getGet('page') ?? 1;

        // Retrieve the value of the 'size' parameter from the request, default to 5 if not present
        $size = $this->request->getGet('size') ?? 10;

        // Check if the user is logged in
        // Retrieve the stored JWT token
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            // URL for fetching akun data
            $akun_url = $this->api_url . '/pegawai/berkas?page=' . $page . '&size=' . $size;

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
                    $berkas_data = json_decode($response_akun, true);

                    // $total_pages = $akun_data['data']['total'];

                    return  view('/admin/berkasPegawai', ['berkas_data' => $berkas_data['data']['berkas_pegawai'], 'meta_data' => $berkas_data['data'], 'title' => $title]);
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

    public function tambahBerkas()
    {
        $title = 'Tambah Alamat';

        // If the request method is not POST or form data is missing, render the add account view
        echo view('/admin/tambahBerkas', ['title' => $title]);
    }

    public function submitTambahBerkas()
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_pegawai = $this->request->getPost('id_pegawai');
            $nik = $this->request->getPost('nik');
            $tempat_lahir = $this->request->getPost('tempat_lahir');
            $tanggal_lahir = $this->request->getPost('tanggal_lahir');
            $agama = $this->request->getPost('agama');
            $pendidikan = $this->request->getPost('pendidikan');

            // $kk = $this->request->getPost('kk');
            // $npwp = $this->request->getPost('npwp');
            // $bpjs = $this->request->getPost('bpjs');
            // $ijazah = $this->request->getPost('ijazah');
            // $skck = $this->request->getPost('skck');
            // $str = $this->request->getPost('str');
            // $serkom = $this->request->getPost('serkom');

            //upload KTP, Ijazah, Skck, Str, Serkom file to get its URL

            $ktp = $this->request->getFile('af-submit-ktp-upload-images');
            $kk = $this->request->getFile('af-submit-kk-upload-images');
            $npwp = $this->request->getFile('af-submit-npwp-upload-images');
            $bpjs = $this->request->getFile('af-submit-bpjs-upload-images');
            $ijazah = $this->request->getFile('af-submit-ijazah-upload-images');
            $skck = $this->request->getFile('af-submit-skck-upload-images');
            $str = $this->request->getFile('af-submit-str-upload-images');
            $serkom = $this->request->getFile('af-submit-serkom-upload-images');


            // Generate a unique name for the file to avoid conflicts
            $ktpFileName = $ktp->getRandomName();
            $kkFileName = $kk->getRandomName();
            $npwpFileName = $npwp->getRandomName();
            $bpjsFileName = $bpjs->getRandomName();
            $ijazahFileName = $ijazah->getRandomName();
            $skckFileName = $skck->getRandomName();
            $strFileName = $str->getRandomName();
            $serkomFileName = $str->getRandomName();

            // Move the uploaded file to a desired directory with the new name and extension

            $ktp->move(ROOTPATH . 'public/uploads/', $ktpFileName);
            $kk->move(ROOTPATH . 'public/uploads/', $kkFileName);
            $npwp->move(ROOTPATH . 'public/uploads/', $npwpFileName);
            $bpjs->move(ROOTPATH . 'public/uploads/', $bpjsFileName);
            $ijazah->move(ROOTPATH . 'public/uploads/', $ijazahFileName);
            $skck->move(ROOTPATH . 'public/uploads/', $skckFileName);
            $str->move(ROOTPATH . 'public/uploads/', $strFileName);
            $serkom->move(ROOTPATH . 'public/uploads/', $serkomFileName);

            // URL of the uploaded file
            $ktp_url =  ROOTPATH . 'public\uploads\\' . $ktpFileName;
            $kk_url =  ROOTPATH . 'public\uploads\\' . $kkFileName;
            $npwp_url =  ROOTPATH . 'public\uploads\\' . $npwpFileName;
            $bpjs_url =  ROOTPATH . 'public\uploads\\' . $bpjsFileName;
            $ijazah_url =  ROOTPATH . 'public\uploads\\' . $ijazahFileName;
            $skck_url =  ROOTPATH . 'public\uploads\\' . $skckFileName;
            $str_url =  ROOTPATH . 'public\uploads\\' . $strFileName;
            $serkom_url =  ROOTPATH . 'public\uploads\\' . $serkomFileName;


            $ktp_url2 = $this->uploadFileImg($ktp_url);
            $kk_url2 = $this->uploadFileImg($kk_url);
            $npwp_url2 = $this->uploadFileImg($npwp_url);
            $bpjs_url2 = $this->uploadFileImg($bpjs_url);
            $ijazah_url2 = $this->uploadFileImg($ijazah_url);
            $skck_url2 = $this->uploadFileImg($skck_url);
            $str_url2 = $this->uploadFileImg($str_url);
            $serkom_url2 = $this->uploadFileImg($serkom_url);

            // Delete the uploaded file if the final URL was successfully obtained
            if ($ktp_url2 && $kk_url2 && $npwp_url2 && $bpjs_url2 && $ijazah_url2 && $skck_url2 && $str_url2 && $serkom_url2) {
                unlink($ktp_url); // Delete the file
                unlink($kk_url); // Delete the file
                unlink($npwp_url); // Delete the file
                unlink($bpjs_url); // Delete the file
                unlink($ijazah_url); // Delete the file
                unlink($skck_url); // Delete the file
                unlink($str_url); // Delete the file
                unlink($serkom_url); // Delete the file
            }

            // Prepare the data to be sent to the API
            $postData = [
                'id_pegawai' => $id_pegawai,
                'nik' => $nik,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'pendidikan' => $pendidikan,
                'ktp' => $ktp_url2,
                'kk' => $kk_url2,
                'npwp' => $npwp_url2,
                'bpjs' => $bpjs_url2,
                'ijazah' => $ijazah_url2,
                'skck' => $skck_url2,
                'str' => $str_url2,
                'serkom' => $serkom_url2
            ];


            $tambah_berkas_JSON = json_encode($postData);

            $akun_url = $this->api_url . '/pegawai/berkas';

            // Check if email and role are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for email and role

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the POST request
                $ch = curl_init($akun_url);

                // Set cURL options for sending a POST request
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_berkas_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_berkas_JSON),
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
                        return redirect()->to(base_url('berkaspegawai?page=1&size=5'));
                    } else {
                        // Error response from the API
                        return "Error creating account: " . $tambah_berkas_JSON;
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

    private function uploadFileImg($file_path)
    {
        // Check if the file exists
        if (!file_exists($file_path)) {
            return "Error: File not found.";
        }

        // Check if email and role are provided
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            // Initialize cURL session for sending the POST request to upload KTP image
            $ch = curl_init($this->api_url . '/file/img');

            // Set cURL options for sending a POST request to upload KTP image
            $file_data = ['file' => new \CurlFile($file_path)]; // Create CurlFile object with the file path
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $file_data); // Send as multipart form data
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request to upload KTP image
            $response = curl_exec($ch);

            // Check for errors
            if (curl_errno($ch)) {
                $error_message = curl_error($ch);
                curl_close($ch);
                return "Error uploading KTP image: " . $error_message;
            }

            // Close the cURL session for uploading KTP image
            curl_close($ch);

            // Decode the response
            $responseData = json_decode($response, true);

            // Check if the response contains URL
            if (isset($responseData['data']['url'])) {
                return $responseData['data']['url']; // Return the URL of the uploaded KTP image
            } else {
                return "Error uploading KTP image: Response does not contain URL. $response";
            }
        } else {
            // Email or role is empty
            return "Error: Email and role are required.";
        }
    }



    public function editBerkas($pegawaiId)
    {

        if (session()->has('jwt_token')) {

            //retrieve the stored JWT Token
            $token = session()->get('jwt_token');

            // Fetch the user data from the API or database based on the user ID
            $user_url = $this->api_url . '/pegawai/berkas/' . $pegawaiId;

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
                    return view('/admin/editBerkas', ['userData' => $userData['data'], 'pegawaiId' => $pegawaiId, 'title' => 'Edit Berkas']);
                } else {
                    // Error fetching user data
                    return "Error fetching user data. HTTP Status Code: $http_status_code";
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



    public function submitEditBerkas($pegawaiId)
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_pegawai = $this->request->getPost('id_pegawai');
            $nik = $this->request->getPost('nik');
            $tempat_lahir = $this->request->getPost('tempat_lahir');
            $tanggal_lahir = $this->request->getPost('tanggal_lahir');
            $agama = $this->request->getPost('agama');
            $pendidikan = $this->request->getPost('pendidikan');
            $ktp = $this->request->getPost('ktp_url');
            $kk = $this->request->getPost('kk_url');
            $npwp = $this->request->getPost('npwp_url');
            $bpjs = $this->request->getPost('bpjs_url');
            $ijazah = $this->request->getPost('ijazah_url');
            $skck = $this->request->getPost('skck_url');
            $str = $this->request->getPost('str_url');
            $serkom = $this->request->getPost('serkom_url');


            // Prepare the data to be sent to the API
            $postData = [
                'id_pegawai' => $id_pegawai,
                'nik' => $nik,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'pendidikan' => $pendidikan,
                'ktp' => $ktp,
                'kk' => $kk,
                'npwp' => $npwp,
                'bpjs' => $bpjs,
                'ijazah' => $ijazah,
                'skck' => $skck,
                'str' => $str,
                'serkom' => $serkom,
            ];

            $edit_berkas_data_JSON = json_encode($postData);

            $pegawai_url = $this->api_url . '/pegawai/berkas/' . $pegawaiId;

            // Check if email and role are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for email and role

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the PUT request
                $ch = curl_init($pegawai_url);

                // Set cURL options for sending a PUT request
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($edit_berkas_data_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($edit_berkas_data_JSON),
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
                        return redirect()->to(base_url('berkaspegawai?page=1&size=5'));
                    } else {
                        // Error response from the API
                        return "Error updating account: " . $response . $edit_berkas_data_JSON;
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

    public function submitEditKtp($pegawaiId)
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_pegawai = $this->request->getPost('id_pegawai_hidden');
            $nik = $this->request->getPost('nik_hidden');
            $tempat_lahir = $this->request->getPost('tempat_lahir_hidden');
            $tanggal_lahir = $this->request->getPost('tanggal_lahir_hidden');
            $agama = $this->request->getPost('agama_hidden');
            $pendidikan = $this->request->getPost('pendidikan_hidden');

            $kk = $this->request->getPost('kk_url');
            $npwp = $this->request->getPost('npwp_url');
            $bpjs = $this->request->getPost('bpjs_url');
            $ijazah = $this->request->getPost('ijazah_url');
            $skck = $this->request->getPost('skck_url');
            $str = $this->request->getPost('str_url');
            $serkom = $this->request->getPost('serkom_url');

            $ktp = $this->request->getFile('af-submit-ktp-upload-images');

            //generate a unique name for the file
            $ktpFileName = $ktp->getRandomName();

            //Move the uploaded file to a desired directory
            $ktp->move(ROOTPATH . 'public/uploads/', $ktpFileName);

            //URL of the uploaded file
            $ktp_url =  ROOTPATH . 'public\uploads\\' . $ktpFileName;

            $ktp_url2 = $this->uploadFileImg($ktp_url);

            //Delete the uploaded file if the final URL was successfully obtained
            if ($ktp_url2) {
                unlink($ktp_url); //delete the file
            }

            // Prepare the data to be sent to the API
            $postData = [
                'id_pegawai' => $id_pegawai,
                'nik' => $nik,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'pendidikan' => $pendidikan,
                'ktp' => $ktp_url2,
                'kk' => $kk,
                'npwp' => $npwp,
                'bpjs' => $bpjs,
                'ijazah' => $ijazah,
                'skck' => $skck,
                'str' => $str,
                'serkom' => $serkom
            ];

            $tambah_berkas_JSON = json_encode($postData);

            $akun_url = $this->api_url . '/pegawai/berkas/' . $pegawaiId;

            // Check if email and role are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for email and role

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the POST request
                $ch = curl_init($akun_url);

      
                // Set cURL options for sending a POST request
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_berkas_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_berkas_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response = curl_exec($ch);

                if ($response) {
                    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($http_status_code === 200) {
                        //Account created successfully
                        $title = "Berkas Akun";

                        //Pass the created account data along with the title to the view
                        return redirect()->to(base_url('editberkas/' . $pegawaiId));
                    } else {
                        //Error sending request from the API
                        return "Error creating account: " .$http_status_code . $pegawaiId;
                    }
                } else {
                    //Error sending request to the API
                    return "Error sending request to the API.";
                }

                curl_close($ch);

            } else {
                //Email or role is empty
                return "Email and role are required";
            }

            
        }

    }

    public function submitEditKK($pegawaiId)
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_pegawai = $this->request->getPost('id_pegawai_hidden');
            $nik = $this->request->getPost('nik_hidden');
            $tempat_lahir = $this->request->getPost('tempat_lahir_hidden');
            $tanggal_lahir = $this->request->getPost('tanggal_lahir_hidden');
            $agama = $this->request->getPost('agama_hidden');
            $pendidikan = $this->request->getPost('pendidikan_hidden');

            $ktp = $this->request->getPost('ktp_url');
            $npwp = $this->request->getPost('npwp_url');
            $bpjs = $this->request->getPost('bpjs_url');
            $ijazah = $this->request->getPost('ijazah_url');
            $skck = $this->request->getPost('skck_url');
            $str = $this->request->getPost('str_url');
            $serkom = $this->request->getPost('serkom_url');

            $kk = $this->request->getFile('af-submit-kk-upload-images');

            //generate a unique name for the file
            $kkFileName = $kk->getRandomName();

            //Move the uploaded file to a desired directory
            $kk->move(ROOTPATH . 'public/uploads/', $kkFileName);

            //URL of the uploaded file
            $kk_url =  ROOTPATH . 'public\uploads\\' . $kkFileName;

            $kk_url2 = $this->uploadFileImg($kk_url);

            //Delete the uploaded file if the final URL was successfully obtained
            if ($kk_url2) {
                unlink($kk_url); //delete the file
            }

            // Prepare the data to be sent to the API
            $postData = [
                'id_pegawai' => $id_pegawai,
                'nik' => $nik,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'pendidikan' => $pendidikan,
                'ktp' => $ktp,
                'kk' => $kk_url2,
                'npwp' => $npwp,
                'bpjs' => $bpjs,
                'ijazah' => $ijazah,
                'skck' => $skck,
                'str' => $str,
                'serkom' => $serkom
            ];



            $tambah_berkas_JSON = json_encode($postData);

            $akun_url = 'https://api.fathoor.dev/v1/pegawai/berkas/' . $pegawaiId;

            // Check if email and role are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for email and role

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the POST request
                $ch = curl_init($akun_url);

      
                // Set cURL options for sending a POST request
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_berkas_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_berkas_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response = curl_exec($ch);

                if ($response) {
                    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($http_status_code === 200) {
                        //Account created successfully
                        $title = "Berkas Akun";

                        //Pass the created account data along with the title to the view
                        return redirect()->to(base_url('editberkas/' . $pegawaiId));
                    } else {
                        //Error sending request from the API
                        return "Error creating account: " .$http_status_code . $pegawaiId;
                    }
                } else {
                    //Error sending request to the API
                    return "Error sending request to the API.";
                }

                curl_close($ch);

            } else {
                //Email or role is empty
                return "Email and role are required";
            }

            

            
        }

    }


    public function submitEditNPWP($pegawaiId)
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_pegawai = $this->request->getPost('id_pegawai_hidden');
            $nik = $this->request->getPost('nik_hidden');
            $tempat_lahir = $this->request->getPost('tempat_lahir_hidden');
            $tanggal_lahir = $this->request->getPost('tanggal_lahir_hidden');
            $agama = $this->request->getPost('agama_hidden');
            $pendidikan = $this->request->getPost('pendidikan_hidden');

            $ktp = $this->request->getPost('ktp_url');
            $kk = $this->request->getPost('kk_url');
            $bpjs = $this->request->getPost('bpjs_url');
            $ijazah = $this->request->getPost('ijazah_url');
            $skck = $this->request->getPost('skck_url');
            $str = $this->request->getPost('str_url');
            $serkom = $this->request->getPost('serkom_url');

            $npwp = $this->request->getFile('af-submit-npwp-upload-images');

            //generate a unique name for the file
            $npwpFileName = $npwp->getRandomName();

            //Move the uploaded file to a desired directory
            $npwp->move(ROOTPATH . 'public/uploads/', $npwpFileName);

            //URL of the uploaded file
            $npwp_url =  ROOTPATH . 'public\uploads\\' . $npwpFileName;

            $npwp_url2 = $this->uploadFileImg($npwp_url);

            //Delete the uploaded file if the final URL was successfully obtained
            if ($npwp_url2) {
                unlink($npwp_url); //delete the file
            }

            // Prepare the data to be sent to the API
            $postData = [
                'id_pegawai' => $id_pegawai,
                'nik' => $nik,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'pendidikan' => $pendidikan,
                'ktp' => $ktp,
                'kk' => $kk,
                'npwp' => $npwp_url2,
                'bpjs' => $bpjs,
                'ijazah' => $ijazah,
                'skck' => $skck,
                'str' => $str,
                'serkom' => $serkom
            ];



            $tambah_berkas_JSON = json_encode($postData);

            $akun_url = 'https://api.fathoor.dev/v1/pegawai/berkas/' . $pegawaiId;

            // Check if email and role are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for email and role

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the POST request
                $ch = curl_init($akun_url);

      
                // Set cURL options for sending a POST request
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_berkas_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_berkas_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response = curl_exec($ch);

                if ($response) {
                    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($http_status_code === 200) {
                        //Account created successfully
                        $title = "Berkas Akun";

                        //Pass the created account data along with the title to the view
                        return redirect()->to(base_url('editberkas/' . $pegawaiId));
                    } else {
                        //Error sending request from the API
                        return "Error creating account: " .$http_status_code . $pegawaiId;
                    }
                } else {
                    //Error sending request to the API
                    return "Error sending request to the API.";
                }

                curl_close($ch);

            } else {
                //Email or role is empty
                return "Email and role are required";
            }

            

            
        }

    }


    public function submitEditBPJS($pegawaiId)
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_pegawai = $this->request->getPost('id_pegawai_hidden');
            $nik = $this->request->getPost('nik_hidden');
            $tempat_lahir = $this->request->getPost('tempat_lahir_hidden');
            $tanggal_lahir = $this->request->getPost('tanggal_lahir_hidden');
            $agama = $this->request->getPost('agama_hidden');
            $pendidikan = $this->request->getPost('pendidikan_hidden');

            $ktp = $this->request->getPost('ktp_url');
            $kk = $this->request->getPost('kk_url');
            $npwp = $this->request->getPost('npwp_url');
            $ijazah = $this->request->getPost('ijazah_url');
            $skck = $this->request->getPost('skck_url');
            $str = $this->request->getPost('str_url');
            $serkom = $this->request->getPost('serkom_url');

            $bpjs = $this->request->getFile('af-submit-bpjs-upload-images');

            //generate a unique name for the file
            $bpjsFileName = $bpjs->getRandomName();

            //Move the uploaded file to a desired directory
            $bpjs->move(ROOTPATH . 'public/uploads/', $bpjsFileName);

            //URL of the uploaded file
            $bpjs_url =  ROOTPATH . 'public\uploads\\' . $bpjsFileName;

            $bpjs_url2 = $this->uploadFileImg($bpjs_url);

            //Delete the uploaded file if the final URL was successfully obtained
            if ($bpjs_url2) {
                unlink($bpjs_url); //delete the file
            }

            // Prepare the data to be sent to the API
            $postData = [
                'id_pegawai' => $id_pegawai,
                'nik' => $nik,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'pendidikan' => $pendidikan,
                'ktp' => $ktp,
                'kk' => $kk,
                'npwp' => $npwp,
                'bpjs' => $bpjs_url2,
                'ijazah' => $ijazah,
                'skck' => $skck,
                'str' => $str,
                'serkom' => $serkom
            ];



            $tambah_berkas_JSON = json_encode($postData);

            $akun_url = 'https://api.fathoor.dev/v1/pegawai/berkas/' . $pegawaiId;

            // Check if email and role are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for email and role

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the POST request
                $ch = curl_init($akun_url);

      
                // Set cURL options for sending a POST request
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_berkas_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_berkas_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response = curl_exec($ch);

                if ($response) {
                    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($http_status_code === 200) {
                        //Account created successfully
                        $title = "Berkas Akun";

                        //Pass the created account data along with the title to the view
                        return redirect()->to(base_url('editberkas/' . $pegawaiId));
                    } else {
                        //Error sending request from the API
                        return "Error creating account: " .$http_status_code . $pegawaiId;
                    }
                } else {
                    //Error sending request to the API
                    return "Error sending request to the API.";
                }

                curl_close($ch);

            } else {
                //Email or role is empty
                return "Email and role are required";
            }

            

            
        }

    }

    public function submitEditSkck($pegawaiId)
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_pegawai = $this->request->getPost('id_pegawai_hidden');
            $nik = $this->request->getPost('nik_hidden');
            $tempat_lahir = $this->request->getPost('tempat_lahir_hidden');
            $tanggal_lahir = $this->request->getPost('tanggal_lahir_hidden');
            $agama = $this->request->getPost('agama_hidden');
            $pendidikan = $this->request->getPost('pendidikan_hidden');

            $ktp = $this->request->getPost('ktp_url');
            $kk = $this->request->getPost('kk_url');
            $npwp = $this->request->getPost('npwp_url');
            $bpjs = $this->request->getPost('bpjs_url');
            $ijazah = $this->request->getPost('ijazah_url');
            $str = $this->request->getPost('str_url');
            $serkom = $this->request->getPost('serkom_url');

            $skck = $this->request->getFile('af-submit-skck-upload-images');

            //generate a unique name for the file
            $skckFileName = $skck->getRandomName();

            //Move the uploaded file to a desired directory
            $skck->move(ROOTPATH . 'public/uploads/', $skckFileName);

            //URL of the uploaded file
            $skck_url =  ROOTPATH . 'public\uploads\\' . $skckFileName;

            $skck_url2 = $this->uploadFileImg($skck_url);

            //Delete the uploaded file if the final URL was successfully obtained
            if ($skck_url2) {
                unlink($skck_url); //delete the file
            }

            // Prepare the data to be sent to the API
            $postData = [
                'id_pegawai' => $id_pegawai,
                'nik' => $nik,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'pendidikan' => $pendidikan,
                'ktp' => $ktp,
                'kk' => $kk,
                'npwp' => $npwp,
                'bpjs' => $bpjs,
                'ijazah' => $ijazah,
                'skck' => $skck_url2,
                'str' => $str,
                'serkom' => $serkom
            ];



            $tambah_berkas_JSON = json_encode($postData);

            $akun_url = 'https://api.fathoor.dev/v1/pegawai/berkas/' . $pegawaiId;

            // Check if email and role are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for email and role

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the POST request
                $ch = curl_init($akun_url);

      
                // Set cURL options for sending a POST request
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_berkas_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_berkas_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response = curl_exec($ch);

                if ($response) {
                    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($http_status_code === 200) {
                        //Account created successfully
                        $title = "Berkas Akun";

                        //Pass the created account data along with the title to the view
                        return redirect()->to(base_url('editberkas/' . $pegawaiId));
                    } else {
                        //Error sending request from the API
                        return "Error creating account: " .$http_status_code . $pegawaiId;
                    }
                } else {
                    //Error sending request to the API
                    return "Error sending request to the API.";
                }

                curl_close($ch);

            } else {
                //Email or role is empty
                return "Email and role are required";
            }

            

            
        }

    }
    
    public function submitEditStr($pegawaiId)
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_pegawai = $this->request->getPost('id_pegawai_hidden');
            $nik = $this->request->getPost('nik_hidden');
            $tempat_lahir = $this->request->getPost('tempat_lahir_hidden');
            $tanggal_lahir = $this->request->getPost('tanggal_lahir_hidden');
            $agama = $this->request->getPost('agama_hidden');
            $pendidikan = $this->request->getPost('pendidikan_hidden');

            $ktp = $this->request->getPost('ktp_url');
            $kk = $this->request->getPost('kk_url');
            $npwp = $this->request->getPost('npwp_url');
            $bpjs = $this->request->getPost('bpjs_url');
            $ijazah = $this->request->getPost('ijazah_url');
            $skck = $this->request->getPost('skck_url');
            $serkom = $this->request->getPost('serkom_url');

            $str = $this->request->getFile('af-submit-str-upload-images');

            //generate a unique name for the file
            $strFileName = $str->getRandomName();

            //Move the uploaded file to a desired directory
            $str->move(ROOTPATH . 'public/uploads/', $strFileName);

            //URL of the uploaded file
            $str_url =  ROOTPATH . 'public\uploads\\' . $strFileName;

            $str_url2 = $this->uploadFileImg($str_url);

            //Delete the uploaded file if the final URL was successfully obtained
            if ($str_url2) {
                unlink($str_url); //delete the file
            }

            // Prepare the data to be sent to the API
            $postData = [
                'id_pegawai' => $id_pegawai,
                'nik' => $nik,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'pendidikan' => $pendidikan,
                'ktp' => $ktp,
                'kk' => $kk,
                'npwp' => $npwp,
                'bpjs' => $bpjs,
                'ijazah' => $ijazah,
                'skck' => $skck,
                'str' => $str_url2,
                'serkom' => $serkom
            ];



            $tambah_berkas_JSON = json_encode($postData);

            $akun_url = 'https://api.fathoor.dev/v1/pegawai/berkas/' . $pegawaiId;

            // Check if email and role are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for email and role

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the POST request
                $ch = curl_init($akun_url);

      
                // Set cURL options for sending a POST request
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_berkas_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_berkas_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response = curl_exec($ch);

                if ($response) {
                    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($http_status_code === 200) {
                        //Account created successfully
                        $title = "Berkas Akun";

                        //Pass the created account data along with the title to the view
                        return redirect()->to(base_url('editberkas/' . $pegawaiId));
                    } else {
                        //Error sending request from the API
                        return "Error creating account: " .$http_status_code . $pegawaiId;
                    }
                } else {
                    //Error sending request to the API
                    return "Error sending request to the API.";
                }

                curl_close($ch);

            } else {
                //Email or role is empty
                return "Email and role are required";
            }

            

            
        }

    }

    public function submitEditSerkom($pegawaiId)
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_pegawai = $this->request->getPost('id_pegawai_hidden');
            $nik = $this->request->getPost('nik_hidden');
            $tempat_lahir = $this->request->getPost('tempat_lahir_hidden');
            $tanggal_lahir = $this->request->getPost('tanggal_lahir_hidden');
            $agama = $this->request->getPost('agama_hidden');
            $pendidikan = $this->request->getPost('pendidikan_hidden');

            $ktp = $this->request->getPost('ktp_url');
            $kk = $this->request->getPost('kk_url');
            $npwp = $this->request->getPost('npwp_url');
            $bpjs = $this->request->getPost('bpjs_url');
            $ijazah = $this->request->getPost('ijazah_url');
            $skck = $this->request->getPost('skck_url');
            $str = $this->request->getPost('str_url');

            $serkom = $this->request->getFile('af-submit-serkom-upload-images');

            //generate a unique name for the file
            $serkomFileName = $serkom->getRandomName();

            //Move the uploaded file to a desired directory
            $serkom->move(ROOTPATH . 'public/uploads/', $serkomFileName);

            //URL of the uploaded file
            $serkom_url =  ROOTPATH . 'public\uploads\\' . $serkomFileName;

            $serkom_url2 = $this->uploadFileImg($serkom_url);

            //Delete the uploaded file if the final URL was successfully obtained
            if ($serkom_url2) {
                unlink($serkom_url); //delete the file
            }

            // Prepare the data to be sent to the API
            $postData = [
                'id_pegawai' => $id_pegawai,
                'nik' => $nik,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'pendidikan' => $pendidikan,
                'ktp' => $ktp,
                'kk' => $kk,
                'npwp' => $npwp,
                'bpjs' => $bpjs,
                'ijazah' => $ijazah,
                'skck' => $skck,
                'str' => $str,
                'serkom' => $serkom_url2
            ];



            $tambah_berkas_JSON = json_encode($postData);

            $akun_url = 'https://api.fathoor.dev/v1/pegawai/berkas/' . $pegawaiId;

            // Check if email and role are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for email and role

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the POST request
                $ch = curl_init($akun_url);

      
                // Set cURL options for sending a POST request
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_berkas_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_berkas_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response = curl_exec($ch);

                if ($response) {
                    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($http_status_code === 200) {
                        //Account created successfully
                        $title = "Berkas Akun";

                        //Pass the created account data along with the title to the view
                        return redirect()->to(base_url('editberkas/' . $pegawaiId));
                    } else {
                        //Error sending request from the API
                        return "Error creating account: " .$http_status_code . $pegawaiId;
                    }
                } else {
                    //Error sending request to the API
                    return "Error sending request to the API.";
                }

                curl_close($ch);

            } else {
                //Email or role is empty
                return "Email and role are required";
            }

            

            
        }

    }
        

    public function hapusBerkas($pegawaiId)
    {
        // Check if the user is logged in
        if (session()->has('jwt_token')) {
            // Retrieve the stored JWT token
            $token = session()->get('jwt_token');

            // URL for deleting the user data
            $delete_url =  'https://api.fathoor.dev/v1/pegawai/berkas/' . $pegawaiId;

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
                return redirect()->to(base_url('berkaspegawai?page=1&size=5'));
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
