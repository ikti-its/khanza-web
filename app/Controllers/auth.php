<?php
declare(strict_types=1);

namespace App\Controllers;
use App\Core\Controller\Legacy\ControllerTemplateLegacy;
use App\Core\Controller\Legacy\CURL;

class auth extends ControllerTemplateLegacy
{
    public function __construct(
    ){
        parent::__construct();
    }
    public function index()
    {
        return view('login');
    }

    public function dashboard()
    {
        $data = ['title' => 'Dashboard Admin'];
        return  view('/dashboard/dashboard', $data);
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url("/login"));
    }

    public function login()
    {
        if (!$this->request->getPost()) {
            return view('login');
        }

        $login_data = [
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password')
        ];

        $response = CURL::call('POST', '/auth/login', $login_data);
        $http_status_code = $response['kode'];

        if ($http_status_code === 401) {
            return redirect('login')->with('passwordsalah', 'Password salah, mohon dicoba kembali');
        } elseif ($http_status_code === 404 || $http_status_code !== 200) {
            return redirect('login')->with('akunsalah', 'Akun tidak ditemukan, mohon hubungi admin');
        }

        // Login success -> Receive JWT token
        $token = $response['data']['data']['token'];
        session()->set('jwt_token', $token);

        // Store user details in session along with the token
        $user_details = CURL::call('GET', '/auth'); 
        session()->set('user_details', $user_details['data']['data']);

        // Check if the user role is 2 or 1
        $role = $user_details['data']['data']['role'];
        if ($role === 2 || $role === 1) {
            $tanggal = date('Y-m-d');
            $user_specific_response = CURL::call('GET', '/w/home/pegawai?tanggal=' . $tanggal);
            session()->set('user_specific_data', $user_specific_response['data']['data']);
        }
        return redirect()->to('/dashboard')
            ->with('title', 'Dashboard')
            ->with('user_details', $user_details);
    }
}
