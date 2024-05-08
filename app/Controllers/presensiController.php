<?php

namespace App\Controllers;


class presensiController extends BaseController
{

    protected $api_url;

    public function __construct()
    {
        $this->api_url = getenv('api_URL');
    }

    public function halamanPresensi()
    {
        $data = [
            'title' => 'Halaman presensi'
        ];
        return  view('/admin/presensi', $data);
    }

    public function script()
    {
        // Set the content type to JavaScript
        $this->response->setContentType('application/javascript');

        // Get the contents of the JavaScript file
        $scriptContent = file_get_contents(APPPATH . 'Views/presensiJS/loadModel.js');

        // Set the body of the response to the JavaScript content
        $this->response->setBody($scriptContent);

        // Return the response
        return $this->response;
    }
}
