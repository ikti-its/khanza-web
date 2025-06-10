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
    $title = 'Profile Pengguna';

    $userDetails = session()->get('user_details');

    if (!is_array($userDetails)) {
        return redirect()->to('/login')->with('error', 'Session expired. Please log in again.');
    }

    return view('/user/homeUser', [
        'title' => $title,
        'user_details' => $userDetails
    ]);
}


}