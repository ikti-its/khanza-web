<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Audit extends BaseController
{
    public static function GetAuditData($tabel){
        $tabel = str_replace('/', '', $tabel);

        $db = \Config\Database::connect();
        $query = $db->query(
            "SELECT * FROM sik." . $tabel . "_audit
            LEFT OUTER JOIN 
            (SELECT id, nama FROM sik.pegawai) c
            ON sik." . $tabel . "_audit.changed_by = c.id
            ORDER BY changed_by DESC");
        $results = $query->getResult();

        for($i = 0; $i < sizeof($results); $i++){
            $results[$i] = json_decode(json_encode($results[$i]), true);
        }
        return $results;
    }
}
