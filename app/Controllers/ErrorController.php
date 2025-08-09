<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ErrorController extends BaseController
{
    public function noAccess403()
    {
        $data['kode'] = 403;
        $data['title'] = 'Forbidden';
        $data['errorTitle'] = 'Akses ditolak';
        $data['message'] = 'Anda tidak memiliki izin untuk melihat halaman ini. Kalau Anda merasa ini salah, hubungi admin.';
        return view('errors/html/error_403', ['data' => $data]);
    }
}
