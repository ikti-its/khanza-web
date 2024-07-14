<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CheckPermission implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // $role = session()->get('user_details')['role'];
        // if ($role != 4002) {
        //     return redirect()->to(base_url())->setStatusCode(403);
        // }

        $userRole = session()->get('user_details')['role'] ?? null; // Assuming roles are an array
        $requiredRoles = $arguments; // Arguments passed to the filter
        if ($userRole !== null) {

            if (!in_array($userRole, $requiredRoles)) {
                // Redirect to a "no access" page or show an error
                return redirect()->to('/error_403');
            }
        } else {
            return redirect()->to('/login')->with('error', 'Session telah habis, mohon login kembali');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
