<?php
declare(strict_types=1);

namespace App\Controllers;
use App\Core\Controller\Legacy\ControllerTemplateLegacy;

class header extends ControllerTemplateLegacy
{
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