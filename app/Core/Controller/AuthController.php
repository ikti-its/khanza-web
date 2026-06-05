<?php
declare(strict_types=1);

namespace App\Core\Controller;

use CodeIgniter\Controller;
use App\Core\Model\ModelTemplate;
use CodeIgniter\HTTP\RedirectResponse;

final class AuthController extends Controller
{
    public function __construct(
        private ModelTemplate $model = new \App\Features\Akun\Auth\AuthModel(),
    ){}

    public function index(): string|RedirectResponse
    {
        if(session()->has('user')){
            return redirect()->to('/dashboard');
        }
        return view('layouts/login');
    }

    public function login(): RedirectResponse
    {
        $login_data = [
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password')
        ];

        if (!isset($login_data['email'], $login_data['password'])) {
            return redirect()->back()->withInput()->with('error', 'Masukkan email dan password');
        }
            

        $user = $this->model->where('email', $login_data['email'])->first();
        
        if(!$user){
            return redirect()->back()->withInput()
                ->with('error', 'Akun tidak ditemukan, mohon hubungi admin');
        }
            

        if (isset($user['password']) 
            && is_string($user['password'])
            && is_string($login_data['password'])
            &&!password_verify($login_data['password'], $user['password'])) 
        {
            return redirect()->back()->withInput()
                ->with('error', 'Password salah, mohon dicoba kembali');
        }

        if (isset($user['id'], $user['email'], $user['role'])) {
            $user = [
                'id'    => $user['id'],
                'email' => $user['email'],
                'role'  => (int) $user['role'],
            ];
        }
            
        session()->set('user', $user);
        // session()->set('user_specific_data', 'Akun not found');

        return redirect()->to('/dashboard')
            ->with('title', 'Dashboard')
            ->with('user_details', '');
    }

    public function logout() : RedirectResponse
    {
        session()->destroy();
        return redirect()->to(base_url("/login"));
    }

    public function dashboard(): string
    {
        $title = 'Dashboard';
        date_default_timezone_set('Asia/Bangkok');
        return view('/dashboard/dashboard', ['title' => $title]);
    }
}