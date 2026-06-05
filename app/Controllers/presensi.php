<?php
declare(strict_types=1);

namespace App\Controllers;
use App\Core\Controller\Legacy\ControllerTemplateLegacy;

class presensi extends ControllerTemplateLegacy
{
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
