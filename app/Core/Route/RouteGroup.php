<?php
declare(strict_types=1);

namespace App\Core\Route;

use App\Core\Controller\ControllerTemplate;
use CodeIgniter\Router\RouteCollection;

class RouteGroup
{
    /** @var list<class-string<RouteTemplate>> */
    private array $routes = [];

    public function __construct(array $routes){
        $this->routes = $routes;
    }

    private static function create_path_from_name(string $name): string
    {
        $value = preg_replace(
            '/([a-z])([A-Z])/',
            '$1-$2',
            $name
        );

        $value = preg_replace(
            '/([A-Z]+)([A-Z][a-z])/',
            '$1-$2',
            $value
        );
        $value = strtolower($value ?? '');
        $value = str_replace(' ', '-', $value);
        return $value;
    }

    /** @return array<string, list<class-string<ControllerTemplate>>> */
    private function get_controler_classes(): array {
        /** @var array<string, list<class-string<ControllerTemplate>>> */
        $all_routes = [];
        foreach($this->routes as $route){
            $r = new $route();
            $all_routes[$r->feature_group] = $r->features;
        }
        return $all_routes;
    }

    final public function create_routes(RouteCollection &$routes): void {
        $filter = ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004'];
        $all_routes = $this->get_controler_classes();
        foreach ($all_routes as $group => $features) {
            $group_path = self::create_path_from_name($group);

            foreach ($features as $f => $show) {
                if($show !== 'HIDE'){
                    $f = $show;
                }
                $title = new $f()->title;
                $feature_path = self::create_path_from_name($title);;
                $path = "{$group_path}/{$feature_path}/";

                $routes->group($path, ['filter' => 'auth'], function ($routes) use ($filter, $f) {
                    $routes->get('data',                  "\\$f::index", $filter);
                    $routes->get('audit',                 "\\$f::audit", $filter);
                    $routes->get('tambah',                "\\$f::create_page", $filter);
                    $routes->post('submittambah',         "\\$f::create", $filter);
                    $routes->get('edit/(:segment)',       "\\$f::update_page/$1", $filter);
                    $routes->post('submitedit/(:segment)',"\\$f::update/$1", $filter);
                    // $routes->patch('patch/(:segment)',    "\\$f::patch/$1", $filter);
                    $routes->delete('hapus/(:segment)' ,  "\\$f::delete/$1", $filter);
                    $routes->get('cetak/(:segment)',      "\\$f::print/$1", $filter);
                    $routes->get('modal/list',            "\\$f::list", $filter);
                    $routes->post('sampel/(:segment)',    "\\$f::sampel/$1", $filter);
                    $routes->post('bayar/(:segment)',     "\\$f::bayar/$1", $filter);
                });
            }
        }
    }

    private function get_icons(): array {
        /** @var array<string, string> */
        $all_icons = [];
        foreach($this->routes as $route){
            $r = new $route();
            $all_icons[$r->feature_group] = $r->icon;
        }
        return $all_icons;
    }
    final public function create_header()
    {
        $persetujuanrole   = [1337, 1, 2, 4001, 5001];
        $petugasrole       = [1337, 1, 2, 4001, 5001];
        $petugasdokterrole = [1337, 1, 2, 3, 4001, 5001];
        $dokterrole        = [1337, 1, 3, 4001, 5001];
        $loginadmin        = [1337, 1];
        $loginpetugas      = 2;
        $logindokter       = 3;

        $all_routes = $this->get_controler_classes();
        $all_icons  = $this->get_icons();
        $all_header = [];

        foreach ($all_routes as $group => $features) {
            $group_path = self::create_path_from_name($group);
            $icon = $all_icons[$group];

            if (count($features) === 1) {
                $title = new $features[0]()->title;
                $feature_path = self::create_path_from_name($title);
                $header = [$group, '/' . $group_path . '/' . $feature_path . '/data', $icon, '', $petugasrole, []];
            } else {
                $header = [$group, '', $icon, '/' . $group_path, $petugasrole];
                foreach ($features as $feature => $show) {
                    if($show !== 'HIDE'){
                        $feature = $show;
                    } else {
                        continue;
                    }

                    $f = new $feature();
                    $title = $f->title;
                    $feature_path = self::create_path_from_name($title);
                    $header[5][] = [$title, '/' . $feature_path, ''];
                }
            }
            
            $all_header[] = $header;
        }

        return $all_header;
    }
}