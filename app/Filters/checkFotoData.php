<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CheckFotoData implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {

        if(!session() -> get('jwt_token'))
        {
            return redirect()->to(base_url("/login"));
        }
        // Check if 'foto_data' exists in session
        if (!session()->has('foto_data')) {
            // Redirect to dashboard or any other route you prefer
            return redirect()->to('/dashboard');
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here after processing the request
    }
}