<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class StokOpnameController extends BaseController
{
     public function data()
     {
          return view('/admin/inventaris/medis/stok_opname/data');
     }
     public function tambah()
     {
          return view('/admin/inventaris/medis/stok_opname/tambah_opname');
     }
     public function sisastok()
     {
          return view('/admin/inventaris/medis/sisa_stok');
     }
}
