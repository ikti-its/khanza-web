<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\BaseController;

class Ijin extends BaseController implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {

        $role = session()->get('user_details')['role'];
        
        if ($role == 2){
            return redirect()->to('/test-403');
        }

       
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
