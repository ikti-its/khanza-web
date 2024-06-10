<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

// class ErrorController extends BaseController
// {
//     public function show404()
//     {

//         $title = 'Halaman tidak ditemukan';
//         // Make sure to set the response code to 404
//         $this->response->setStatusCode(404);
//         // Return the custom view for 404 errors
//         return view('errorPage/errorPage', ['title' => $title]);
//     }
// }


// app/Controllers/ErrorHandler.php
namespace App\Controllers;

use CodeIgniter\Controller;

class ErrorHandler extends Controller
{
    public function show400()
    {
        return view('errors/html/error_400');
    }

    public function show401()
    {
        return view('errors/html/error_401');
    }

    public function show403()
    {
        return view('errors/html/error_403');
    }

    public function show404()
    {
        return view('errors/html/error_404');
    }

    public function show500()
    {
        return view('errors/html/error_500');
    }
}


