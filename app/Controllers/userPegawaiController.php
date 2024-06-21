<?php

namespace App\Controllers;



class userPegawaiController extends BaseController
{



    public function lihatProfil()
    {
        $title = 'Data Akun';

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $tanggal = date('Y-m-d');
            $akun_url = $this->api_url . '/m/home?tanggal=' . $tanggal;
            $ch_akun = curl_init($akun_url);

            curl_setopt($ch_akun, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_akun, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_akun = curl_exec($ch_akun);

            if ($response_akun) {
                $http_status_code_akun = curl_getinfo($ch_akun, CURLINFO_HTTP_CODE);

                if ($http_status_code_akun === 200) {
                    $akun_data = json_decode($response_akun, true);
                    return view('/user/homeUser', ['akun_data' => $akun_data['data'], 'title' => $title]);
                } else {
                    return $this->renderErrorView($http_status_code_akun);
                }
            } else {
                return $this->renderErrorView(500);
            }

            curl_close($ch_akun);
        } else {
            return $this->renderErrorView(401);
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



    public function lihatPegawai()
    {
        $title = 'Data Akun';

        // Retrieve the value of the 'page' parameter from the request, default to 1 if not present
        $page = $this->request->getGet('page') ?? 1;

        // Retrieve the value of the 'size' parameter from the request, default to 10 if not present
        $size = $this->request->getGet('size') ?? 10;

        // Check if the user is logged in
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            // URL for fetching akun data
            $akun_url = $this->api_url . '/pegawai?page=' . $page . '&size=' . $size;

            // Initialize cURL session for akun data
            $ch_akun = curl_init($akun_url);
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

                    // Close the cURL session for akun data
                    curl_close($ch_akun);

                    // Now fetch data from ketersediaan_url
                    $tanggal = date('Y-m-d');
                    $ketersediaan_url = $this->api_url . '/m/ketersediaan?tanggal=' . $tanggal . '&page=' . $page . '&size=' . $size;

                    // Initialize cURL session for ketersediaan data
                    $ch_ketersediaan = curl_init($ketersediaan_url);
                    curl_setopt($ch_ketersediaan, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_ketersediaan, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);

                    // Execute the cURL request for fetching ketersediaan data
                    $response_ketersediaan = curl_exec($ch_ketersediaan);

                    // Check the API response for ketersediaan data
                    if ($response_ketersediaan) {
                        $http_status_code_ketersediaan = curl_getinfo($ch_ketersediaan, CURLINFO_HTTP_CODE);

                        if ($http_status_code_ketersediaan === 200) {
                            // Ketersediaan data fetched successfully
                            $ketersediaan_data = json_decode($response_ketersediaan, true);

                            // Coordinates of the origin (assuming these are fixed for demonstration)
                            $latitudeOrg = -7.281696177416258; // User's latitude or fixed origin point
                            $longitudeOrg = 112.79505404588785; // User's longitude or fixed origin point

                            // Calculate distances using Haversine formula and assign to each entry
                            foreach ($ketersediaan_data['data']['ketersediaan'] as &$item) {
                                if (isset($item['latitude']) && isset($item['longitude'])) {
                                    // Extract latitude and longitude from the current item
                                    $ketersediaanLat = $item['latitude'];
                                    $ketersediaanLng = $item['longitude'];

                                    // Calculate distance using Haversine formula
                                    $distance = $this->calculateDistance($latitudeOrg, $longitudeOrg, $ketersediaanLat, $ketersediaanLng);

                                    // Assign distance to the current item
                                    $item['distance'] = $distance;
                                }
                            }

                            // Close the cURL session for ketersediaan data
                            curl_close($ch_ketersediaan);

                            // Pass data to the view, including the distances array
                            return view('/user/dataPegawai', [
                                'akun_data' => $akun_data['data']['pegawai'],
                                'meta_data' => $akun_data['data'],
                                'ketersediaan_data' => $ketersediaan_data['data']['ketersediaan'],
                                'meta_ketersediaan_data' => $ketersediaan_data['data'],
                                'title' => $title,
                            ]);
                        } else {
                            // Error fetching ketersediaan data
                            curl_close($ch_ketersediaan);
                            return $this->renderErrorView($http_status_code_ketersediaan);
                        }
                    } else {
                        // Error fetching ketersediaan data
                        curl_close($ch_ketersediaan);
                        return $this->renderErrorView(500); // Assume 500 for cURL error
                    }
                } else {
                    // Error fetching akun data
                    curl_close($ch_akun);
                    return $this->renderErrorView($http_status_code_akun);
                }
            } else {
                // Error fetching akun data
                curl_close($ch_akun);
                return $this->renderErrorView(500); // Assume 500 for cURL error
            }
        } else {
            // User not logged in
            return $this->renderErrorView(401);
        }
    }

    // Function to calculate distance between two points using Haversine formula
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius of the earth in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c; // Distance in kilometers

        return $distance;
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
                        } else {
                            // Error fetching file data
                            return $this->renderErrorView($http_status_berkas);
                        }
                    } else {
                        // Error fetching file data
                        return $this->renderErrorView(500); // Assume 500 for cURL error
                    }
                } else {
                    // Error fetching user data
                    return $this->renderErrorView($http_status_code);
                }
            } else {
                // Error fetching file data
                return $this->renderErrorView(500); // Assume 500 for cURL error
            }

            //Close the cURL session for user data
            curl_close($ch_user);
        } else {
            // User not logged in
            return $this->renderErrorView(401);
        }
    }

    public function tampilCatatanKehadiran($pegawaiId)
    {
        $title = 'Tampil Catatan Kehadiran';

        // Check if the user is logged in
        // Retrieve the stored JWT token
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            // URL for fetching akun data
            $kehadiran_url = $this->api_url . '/kehadiran/presensi/pegawai/' . $pegawaiId;

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
                $http_status_code_kehadiran = curl_getinfo($ch_kehadiran, CURLINFO_HTTP_CODE);

                if ($http_status_code_kehadiran === 200) {
                    // Akun data fetched successfully
                    $kehadiran_data = json_decode($response_kehadiran, true);

                    // $total_pages = $akun_data['data']['total'];

                    return  view('/user/tampilCatatanKehadiran', ['kehadiran_data' => $kehadiran_data['data'], 'title' => $title]);
                } else {
                    // Error fetching kehadiran data
                    return $this->renderErrorView($http_status_code_kehadiran);
                }
            } else {
                // Error fetching kehadiran data
                return $this->renderErrorView(500); // Assume 500 for cURL error
            }

            // Close the cURL session for akun data
            curl_close($ch_kehadiran);
        } else {
            // User not logged in
            return $this->renderErrorView(401);
        }
    }

    public function tampilCuti($pegawaiId)
    {
        $title = 'Tampil Cuti';

        // // Retrieve the value of the 'page' parameter from the request, default to 1 if not present
        // $page = $this->request->getGet('page') ?? 1;

        // // Retrieve the value of the 'size' parameter from the request, default to 5 if not present
        // $size = $this->request->getGet('size') ?? 10;

        // Check if the user is logged in
        // Retrieve the stored JWT token
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            // URL for fetching akun data
            $kehadiran_url = $this->api_url . '/kehadiran/cuti/pegawai/' . $pegawaiId;

            // Initialize cURL session
            $ch_cuti = curl_init($kehadiran_url);

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

                    return  view('/user/tampilCatatanCuti', ['cuti_data' => $cuti_data['data'], 'title' => $title]);
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


    public function tampilJadwal($pegawaiId)
    {
        $title = 'Tampil Jadwal';

        // // Retrieve the value of the 'page' parameter from the request, default to 1 if not present
        // $page = $this->request->getGet('page') ?? 1;

        // // Retrieve the value of the 'size' parameter from the request, default to 5 if not present
        // $size = $this->request->getGet('size') ?? 10;

        // Check if the user is logged in
        // Retrieve the stored JWT token
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            // URL for fetching akun data
            $jadwal_url = $this->api_url . '/kehadiran/jadwal/pegawai/' . $pegawaiId;

            // Initialize cURL session
            $ch_jadwal = curl_init($jadwal_url);

            // Set cURL options
            curl_setopt($ch_jadwal, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_jadwal, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request for fetching akun data
            $response_jadwal = curl_exec($ch_jadwal);

            // Check the API response for akun data
            if ($response_jadwal) {
                $http_status_code_jadwal = curl_getinfo($ch_jadwal, CURLINFO_HTTP_CODE);

                if ($http_status_code_jadwal === 200) {
                    // Akun data fetched successfully
                    $jadwal_data = json_decode($response_jadwal, true);

                    // $total_pages = $akun_data['data']['total'];

                    return  view('/user/tampilJadwalPegawai', ['kehadiran_data' => $jadwal_data['data'], 'title' => $title]);
                } else {
                    // Error fetching jadwal data
                    return $this->renderErrorView($http_status_code_jadwal);
                }
            } else {
                // Error fetching jadwal data
                return $this->renderErrorView(500); // Assume 500 for cURL error
            }

            // Close the cURL session for akun data
            curl_close($ch_jadwal);
        } else {
            // User not logged in
            return $this->renderErrorView(401);
        }
    }

    // public function tampilStatusIzin()
    // {
    //     $title = 'Detail berkas';
    //     return view('/user/statusPengajuanIzin', ['title' => $title]);
    // }

    public function tambahCuti()
    {
        $title = 'Tambah Cuti';

        // Check if the user is logged in by checking the presence of the JWT token in the session
        if (session()->has('jwt_token')) {
            // Return the view with the title
            return view('/user/izinCuti', ['title' => $title]);
        } else {
            // Redirect to login page or show an appropriate message
            return redirect()->to('/login')->with('error', 'User not logged in. Please log in first.');
        }
    }

    public function submitTambahCuti()
    {
        if ($this->request->getPost()) {

            // Retrieve the form data from the POST request
            $id_pegawai = $this->request->getPost('id_pegawai');
            $tanggal_mulai = $this->request->getPost('tanggal_mulai');
            $tanggal_selesai = $this->request->getPost('tanggal_selesai');
            $id_alasan_cuti = $this->request->getPost('id_alasan_cuti');
            $status = 'Diproses';

            // Prepare the data to be sent to the API
            $postData = [
                'id_pegawai' => $id_pegawai,
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_selesai' => $tanggal_selesai,
                'id_alasan_cuti' => $id_alasan_cuti,
                'status' => $status
            ];

            $tambah_cuti_JSON = json_encode($postData);

            $cuti_url = $this->api_url . '/kehadiran/cuti';

            // Check if required fields are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for the required fields

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the POST request
                $ch = curl_init($cuti_url);

                // Set cURL options for sending a POST request
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_cuti_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_cuti_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                // Execute the cURL request
                $response = curl_exec($ch);

                // Check if the API request was successful
                if ($response) {

                    // Check if the HTTP status code in the response
                    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($http_status_code === 201) {
                        // Leave (cuti) created successfully
                        return redirect()->to(base_url('izincuti'));
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
                // User is not logged in
                return redirect()->to('/login')->with('error', 'User not logged in. Please log in first.');
            }
        }
    }

    public function lihatOpsiHadir()
    {
        $title = 'OpsiHadir';
        return view('/user/opsiHadir', ['title' => $title]);
    }


    public function lihatDashboard()
    {
        $title = 'Dashboard';
        return view('/user/dashboard', ['title' => $title]);
    }

    public function tambahPresensi()
    {
        $title = 'Tambah Presensi';

        // Retrieve session data
        $pegawaiId = session()->get('user_specific_data')['pegawai'];
        $namaPegawai = session()->get('user_specific_data')['nama'];
        $jwtToken = session()->get('jwt_token');
        $api_url = $this->api_url . '/pegawai/foto'; // Example API URL

        // Check if the user is logged in
        if ($jwtToken) {
            // Get latitude and longitude from the POST data (example values)
            $latitude = -7.280280992376773;
            $longitude = 112.79587908222712;

            // Define the latitude and longitude range
            $minLatitude = -7.285;
            $maxLatitude = -7.279;
            $minLongitude = 112.775;
            $maxLongitude = 112.797;

            // Check if latitude and longitude are within the specified range
            if ($latitude >= $minLatitude && $latitude <= $maxLatitude && $longitude >= $minLongitude && $longitude <= $maxLongitude) {

                // User's IP address (local IP simulated for testing)
                $ipAddress = $_SERVER['REMOTE_ADDR'];
                if ($ipAddress === '::1' || $ipAddress === '127.0.0.1') {
                    $ipAddress = '10.183.0.1'; // Example local IP for testing
                }

                // Check if the IP address starts with '10.183'
                if (strpos($ipAddress, '10.183') === 0) {

                    // Initialize cURL session
                    $foto_url = $this->api_url . '/pegawai/foto/' . $pegawaiId;
                    $ch_foto = curl_init($foto_url);

                    // Set cURL options
                    curl_setopt($ch_foto, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_foto, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $jwtToken,
                    ]);

                    // Execute the cURL request for fetching employee photo data
                    $response_foto = curl_exec($ch_foto);

                    // Check for cURL errors
                    if ($response_foto === false) {
                        $error_message = curl_error($ch_foto);
                        curl_close($ch_foto);
                        return $this->renderErrorView(500, 'Error fetching employee photo data: ' . $error_message);
                    }

                    // Get HTTP status code
                    $http_status_response_foto = curl_getinfo($ch_foto, CURLINFO_HTTP_CODE);

                    // Close cURL session
                    curl_close($ch_foto);

                    // Check HTTP status
                    if ($http_status_response_foto === 200) {
                        // Parse JSON response
                        $foto_data = json_decode($response_foto, true);


                        // Render the view with fetched data
                        return view('/user/tambahPresensi', [
                            'api_url' => $api_url,
                            'pegawaiId' => $pegawaiId,
                            'namaPegawai' => $namaPegawai,
                            'jwtToken' => $jwtToken,
                            'title' => $title,
                            'foto_data' => $foto_data['data']['foto'],
                        ]);
                    } else {
                        // Handle HTTP error
                        return $this->renderErrorView($http_status_response_foto, 'Error fetching employee photo data');
                    }
                } else {
                    // User's IP address does not start with '10.183'
                    return $this->renderErrorView(403, 'Anda tidak memiliki izin untuk melakukan presensi, harap gunakan jaringan internal rumah sakit'); // Forbidden with custom message
                }
            } else {
                // Latitude or longitude is outside the allowed area
                return $this->renderErrorView(403, 'Anda tidak memiliki izin untuk melakukan presensi, harap berada pada area rumah sakit'); // Forbidden with custom message
            }
        } else {
            // User not logged in
            return $this->renderErrorView(401); // Unauthorized
        }
    }



    public function tambahSwafoto()
    {
        $title = 'Detail berkas';
        return view('/user/tambahSwafoto', ['title' => $title]);
    }


    public function lihatAbsen()
    {

        $title = 'Lihat Absen';

        // Check if the user is logged in by checking the presence of the JWT token in the session
        if (session()->has('jwt_token')) {
            // Return the view with the title
            return view('/user/pilihAbsen', ['title' => $title]);
        } else {
            // Redirect to login page or show an appropriate message
            return redirect()->to('/login')->with('error', 'User not logged in. Please log in first.');
        }
    }

    public function lihatAbsenMasuk($pegawaiId)
    {
        $title = 'Tambah Absen Masuk';

        // Check if the user is logged in
        // Retrieve the stored JWT token
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            // URL for fetching akun data
            $jadwal_url = $this->api_url . '/kehadiran/jadwal/pegawai/' . $pegawaiId;

            // Initialize cURL session
            $ch_jadwal = curl_init($jadwal_url);

            // Set cURL options
            curl_setopt($ch_jadwal, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_jadwal, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request for fetching akun data
            $response_jadwal = curl_exec($ch_jadwal);

            // Check the API response for akun data
            if ($response_jadwal) {
                $http_status_code_jadwal = curl_getinfo($ch_jadwal, CURLINFO_HTTP_CODE);


                if ($http_status_code_jadwal === 200) {
                    // Akun data fetched successfully
                    $jadwal_data = json_decode($response_jadwal, true)['data'];

                    // Get the current day of the week (1 for Monday, 7 for Sunday)
                    $currentDay = date('N'); // Returns 1 (Monday) to 7 (Sunday)

                    // Find the schedule data for today (id_hari = $currentDay)
                    $jadwal_today = array_values(array_filter($jadwal_data, function ($item) use ($currentDay) {
                        return $item['id_hari'] == $currentDay;
                    }));

                    // Check if there is a schedule for today
                    if (!empty($jadwal_today)) {
                        return view('/user/tampilAbsenMasuk', [
                            'kehadiran_data' => $jadwal_today,
                            'title' => $title
                        ]);
                    } else {
                        // No schedule found for today
                        return $this->renderErrorView(404); // Replace with appropriate error code
                    }
                } else {
                    // Error fetching jadwal data
                    return $this->renderErrorView($http_status_code_jadwal);
                }
            } else {
                // Error fetching jadwal data
                return $this->renderErrorView(500); // Assume 500 for cURL error
            }

            // Close the cURL session for akun data
            curl_close($ch_jadwal);
        } else {
            // User not logged in
            return $this->renderErrorView(401);
        }
    }

    public function submitTambahAbsenMasuk()
    {
        if ($this->request->getPost()) {

            // Retrieve session data
            $pegawaiId = session()->get('user_specific_data')['pegawai'];
            // Retrieve the form data from the POST request
            $id_pegawai = $this->request->getPost('id_pegawai');
            $id_jadwal = $this->request->getPost('id_jadwal');
            $tanggal = $this->request->getPost('tanggal');


            // Prepare the data to be sent to the API
            $postData = [
                'id_pegawai' => $id_pegawai,
                'id_jadwal_pegawai' => $id_jadwal,
                'tanggal' => $tanggal,
            ];

            $tambah_cuti_JSON = json_encode($postData);

            var_dump($tambah_cuti_JSON);

            $cuti_url = $this->api_url . '/kehadiran/presensi/attend';

            // Check if required fields are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for the required fields

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the POST request
                $ch = curl_init($cuti_url);

                // Set cURL options for sending a POST request
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_cuti_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_cuti_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                // Execute the cURL request
                $response = curl_exec($ch);

                // Check if the API request was successful
                if ($response) {

                    // Check if the HTTP status code in the response
                    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    // Decode the response
                    $response_data = json_decode($response, true);

                    // Store user details in session along with the token
                    session()->set('response_data', $response_data['data']['id']);

                    if ($http_status_code === 201) {
                        // Leave (cuti) created successfully

                        // Set the Authorization header with the JWT token
                        $headers = [
                            'Authorization: Bearer ' . $token,
                            'Content-Type: application/json'
                        ];

                        $user_specific_url = $this->api_url . '/m/home?tanggal=' . $tanggal;

                        // Initialize cURL session for user specific data
                        $user_specific_ch = curl_init($user_specific_url);
                        curl_setopt($user_specific_ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($user_specific_ch, CURLOPT_HTTPHEADER, $headers);

                        // Execute the cURL request for user specific data
                        $user_specific_response = curl_exec($user_specific_ch);

                        if ($user_specific_response) {
                            // Decode the JSON response
                            $user_specific_data = json_decode($user_specific_response, true);

                            // Store the user specific data in session or handle it as needed
                            session()->set('user_specific_data', $user_specific_data['data']);
                        } else {
                            // Failed to get user specific data
                            echo "Failed to retrieve user specific data.";
                        }

                        return redirect()->to(base_url('dashboard'));
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
                // User is not logged in
                return redirect()->to('/login')->with('error', 'User not logged in. Please log in first.');
            }
        }
    }


    public function lihatAbsenPulang($pegawaiId)
    {
        $title = 'Tambah Absen Masuk';

        // Check if the user is logged in
        // Retrieve the stored JWT token
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            // URL for fetching akun data
            $jadwal_url = $this->api_url . '/kehadiran/jadwal/pegawai/' . $pegawaiId;

            // Initialize cURL session
            $ch_jadwal = curl_init($jadwal_url);

            // Set cURL options
            curl_setopt($ch_jadwal, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_jadwal, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request for fetching akun data
            $response_jadwal = curl_exec($ch_jadwal);

            // Check the API response for akun data
            if ($response_jadwal) {
                $http_status_code_jadwal = curl_getinfo($ch_jadwal, CURLINFO_HTTP_CODE);


                if ($http_status_code_jadwal === 200) {
                    // Akun data fetched successfully
                    $jadwal_data = json_decode($response_jadwal, true)['data'];

                    // Get the current day of the week (1 for Monday, 7 for Sunday)
                    $currentDay = date('N'); // Returns 1 (Monday) to 7 (Sunday)

                    // Find the schedule data for today (id_hari = $currentDay)
                    $jadwal_today = array_values(array_filter($jadwal_data, function ($item) use ($currentDay) {
                        return $item['id_hari'] == $currentDay;
                    }));

                    // Check if there is a schedule for today
                    if (!empty($jadwal_today)) {
                        return view('/user/tampilAbsenPulang', [
                            'kehadiran_data' => $jadwal_today,
                            'title' => $title
                        ]);
                    } else {
                        // No schedule found for today
                        return $this->renderErrorView(404); // Replace with appropriate error code
                    }
                } else {
                    // Error fetching jadwal data
                    return $this->renderErrorView($http_status_code_jadwal);
                }
            } else {
                // Error fetching jadwal data
                return $this->renderErrorView(500); // Assume 500 for cURL error
            }

            // Close the cURL session for akun data
            curl_close($ch_jadwal);
        } else {
            // User not logged in
            return $this->renderErrorView(401);
        }
    }



    public function submitTambahAbsenPulang()
    {
        if ($this->request->getPost()) {

            // Retrieve session data
            $abseniId = session()->get('response_data');
            $pegawaiId = session()->get('user_specific_data')['pegawai'];


            // Prepare the data to be sent to the API
            $postData = [
                'id' => $abseniId,
                'id_pegawai' => $pegawaiId
            ];

            $tambah_pulang_JSON = json_encode($postData);

            $pulang_url = $this->api_url . '/kehadiran/presensi/leave';

            // Check if required fields are provided
            if (session()->has('jwt_token')) {
                // Assume you have some validation logic here for the required fields

                $token = session()->get('jwt_token');

                // Initialize cURL session for sending the POST request
                $ch = curl_init($pulang_url);

                // Set cURL options for sending a POST request
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_pulang_JSON));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_pulang_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                // Execute the cURL request
                $response = curl_exec($ch);

                // Check if the API request was successful
                if ($response) {

                    // Check if the HTTP status code in the response
                    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($http_status_code === 200) {
                        // Leave (cuti) created successfully
                        return redirect()->to(base_url('dashboard'));
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
                // User is not logged in
                return redirect()->to('/login')->with('error', 'User not logged in. Please log in first.');
            }
        }
    }

    


}
