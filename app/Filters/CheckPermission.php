<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CheckPermission implements FilterInterface
{
    #[\Override()]
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session()->has('user');
        if(!$session)
            return redirect()->to(base_url('/login'));

        /** @var array{'role':string} */
        $user = session()->get('user');
        $role = $user['role'];

        if (!is_array($arguments))
            $arguments = explode(',', '');

        if (!in_array((string) $role, array_map('strval', $arguments), true))
            return redirect()->to('/error/403');

        return null;
    }

    #[\Override()]
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
