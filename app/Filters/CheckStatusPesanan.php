<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CheckStatusPesanan implements FilterInterface
{
    #[\Override()]
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session()->has('status_pesanan');
        if(! $session)
            return redirect()->to(base_url('/login'));

        /** @var array{'status_pesanan':list<string>} */
        $user_data = session()->get('status_pesanan');
        $status = $user_data['status_pesanan'];
        foreach ($status as $s) {
            if($s !== '3'){
                return redirect()->to('/error/403');
            }
        }
        return null;
    }

    #[\Override()]
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
