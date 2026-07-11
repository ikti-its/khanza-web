<?php

namespace App\Core\Commands;

use CodeIgniter\CLI\BaseCommand;

class CreateSidebar extends BaseCommand
{
    protected $group       = 'Omnia';
    protected $name        = 'omnia:sidebar';
    protected $description = 'Append sidebar variable to App/Views/components/sidebar.php';

    public function run(array $_params)
    {
        $route_group = new \App\Features\AllRoutes();
        file_put_contents(APPPATH . 'Config/GeneratedSidebar.php', $route_group->generate_sidebar());
    }
}
