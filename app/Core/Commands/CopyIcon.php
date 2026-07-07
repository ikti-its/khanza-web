<?php

namespace App\Core\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateSidebar extends BaseCommand
{
    protected $group = 'Omnia';
    protected $name = 'omnia:icon';
    protected $description = 'Copy .svg files to public/svg/feature_icons/';

    public function run(array $_params)
    {
        $route_group = new \App\Features\AllRoutes();
        $icon_paths = $route_group->get_icon_paths();
        foreach($icon_paths as $path)
        {
            if(!file_exists($path)){
                CLI::write("Icon file '{$path}' not found");
                continue;
            }
            if(explode('.', $path)[1] !== 'svg'){
                CLI::write("Icon file '{$path}' has to be .svg");
                continue;
            }

            $destination = $this->get_destination_path($path);
            copy($path, $destination);
        }

        $source = FCPATH . 'svg/old_icons';
        $destination = FCPATH . 'svg/feature_icons';

        if (! is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        foreach (glob($source . '/*.svg') as $file) {
            copy(
                $file,
                $destination . '/' . basename($file)
            );
        }
    }

    private function get_destination_path(string $path): string
    {
        $parts = explode("/", $path);
        $len = count($parts);
        $icon_name = $parts[$len-1];
        return FCPATH . 'svg/feature_icons/' . $icon_name;
    }
}