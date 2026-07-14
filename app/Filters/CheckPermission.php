<?php

namespace App\Filters;

use App\Core\Auth\AccessMatrix;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CheckPermission implements FilterInterface
{
    #[\Override]
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->has('user'))
            return redirect()->to(base_url('/login'));

        // $arguments = [group_path, 'read'|'write'], lihat RouteGroup::create_routes()
        if (!is_array($arguments) || count($arguments) < 2)
            return redirect()->to('/error/403');

        [$group, $mode] = $arguments;

        $allowed = $mode === 'write' ? AccessMatrix::can_write($group) : AccessMatrix::can_read($group);

        if (!$allowed)
            return redirect()->to('/error/403');

        return null;
    }

    #[\Override]
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
