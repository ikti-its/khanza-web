<?php 
namespace App\Libraries;

// Include the library file
require_once APPPATH . 'Libraries/Jwt/JWT.php';

use \Firebase\JWT\JWT as FirebaseJWT;

class JWT extends FirebaseJWT {}