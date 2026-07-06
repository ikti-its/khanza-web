<?php

namespace App\Core\Commands;

use CodeIgniter\CLI\BaseCommand;

class CreateRoute extends BaseCommand
{
    protected $group = 'Omnia';
    protected $name = 'omnia:route';
    protected $description = 'Append template routes to Config/Routes.php';

    public function run(array $_params)
    {
        $route_group = new \App\Features\AllRoutes();
        file_put_contents(
            APPPATH . 'Config/GeneratedRoutes.php',
            $route_group->generate_php()
        );
    }
}