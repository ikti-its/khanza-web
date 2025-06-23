<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Audit extends BaseController
{
    public static function GetAuditData($tabel){
        $db = \Config\Database::connect();
        $query = $db->query("SELECT * FROM sik." . $tabel . "_audit ORDER BY changed_by DESC");
        $results = $query->getResult();

        for($i = 0; $i < sizeof($results); $i++){
            $results[$i] = json_decode(json_encode($results[$i]), true);
        }
        return $results;
    }
}
