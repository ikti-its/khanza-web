<?php

namespace App\Controllers;


class headerController extends BaseController
{

    protected $api_url;

    public function __construct()
    {
        $this->api_url = getenv('api_URL');
    }


    public function lihatProfile()
    {
        $title = 'Profile pengguna';

        return  view('/user/homeUser', ['title' => $title]);

    
}

}