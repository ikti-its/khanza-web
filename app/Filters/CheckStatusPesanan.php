<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CheckStatusPesanan implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // $role = session()->get('user_details')['role'];
        // if ($role != 4002) {
        //     return redirect()->to(base_url())->setStatusCode(403);
        // }
        $status = session()->get('status_pesanan')['status_pesanan'];
        $requiredStatus = $arguments; // Arguments passed to the filter
        foreach ($status as $s) {
            if($s !== '3'){
                return redirect()->to('/error_403');
            }
        } // Assuming roles are an array
        // if ($userRole !== null) {

        //     if (!in_array($userRole, $requiredRoles)) {
        //         // Redirect to a "no access" page or show an error
        //         return redirect()->to('/error_403');
        //     }
        // } else {
        //     return redirect()->to('/login')->with('error', 'Session telah habis, mohon login kembali');
        // }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
