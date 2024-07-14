<?php

namespace App\Controllers;

use App\Libraries\JWT;

class authController extends BaseController
{

    protected $api_url;

    public function __construct()
    {
        $this->api_url = getenv('api_URL');
    }

    public function index()
    {
        return view('login');
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard Admin'
        ];

        return  view('/user/dashboard', $data);
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url("/login"));
    }

    public function login()
    {
        if ($this->request->getPost()) {

            $title = 'Dashboard';

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $login_data = [
                'email' => $email,
                'password' => $password
            ];
            $login_data_json = json_encode($login_data);

            $api_url = $this->api_url .  '/auth/login';

            $ch = curl_init($api_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $login_data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($login_data_json)
            ]);
            $response = curl_exec($ch);

            if ($response) {
                $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($http_status_code === 200) {
                    $response_data = json_decode($response, true);
                    $token = $response_data['data']['token'];

                    session()->set('jwt_token', $token);

                    $user_details_url = $this->api_url . '/auth';

                    $headers = [
                        'Authorization: Bearer ' . $token,
                        'Content-Type: application/json'
                    ];

                    $user_details_ch = curl_init($user_details_url);
                    curl_setopt($user_details_ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($user_details_ch, CURLOPT_HTTPHEADER, $headers);
                    $user_details_response = curl_exec($user_details_ch);

                    if ($user_details_response) {
                        $user_details = json_decode($user_details_response, true);
                        session()->set('user_details', $user_details['data']);
                        curl_close($user_details_ch);

                        if ($user_details['data']['role'] == 2 || $user_details['data']['role'] == 1) {

                            $tanggal = date('Y-m-d');
                            $user_specific_url = $this->api_url . '/m/home?tanggal=' . $tanggal;

                            $user_specific_ch = curl_init($user_specific_url);
                            curl_setopt($user_specific_ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($user_specific_ch, CURLOPT_HTTPHEADER, $headers);
                            $user_specific_response = curl_exec($user_specific_ch);

                            if ($user_specific_response) {
                                $user_specific_data = json_decode($user_specific_response, true);
                                session()->set('user_specific_data', $user_specific_data['data']);
                            } else {
                                echo "Failed to retrieve user specific data.";
                            }
                        }
                        return redirect()->to('/dashboard')->with('title', $title, 'user_details', $user_details);
                    } else {
                        echo "Failed to retrieve user details.";
                    }
                } elseif ($http_status_code === 401) {
                    return redirect('login')->with('passwordsalah', 'Password salah, mohon dicoba kembali');
                } elseif ($http_status_code === 404) {
                    return redirect('login')->with('akunsalah', 'Akun tidak ditemukan, mohon hubungi admin');
                } else {
                    return redirect('login')->with('akunsalah', 'Akun tidak ditemukan, mohon hubungi admin');
                }
            }
            curl_close($ch);
        } else {
            echo "Please submit the login form.";
        }
    }
}