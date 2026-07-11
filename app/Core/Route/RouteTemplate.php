<?php
declare(strict_types=1);

namespace App\Core\Route;

use App\Core\Controller\ControllerTemplate;

class RouteTemplate
{
    public function __construct(
        /** @var non-empty-string $feature_group */
        public private(set) string $feature_group = '',
        /** @var list<class-string<ControllerTemplate>> $feature_group */
        public private(set) array $features = [],
        public private(set) string $icon = '',
    ) {}

    final public function get_icon_path(): string
    {
        try {
            $reflection = new \ReflectionClass($this);
        } catch (\ReflectionException $e) {
            die($e->getMessage());
        }

        $filename = $reflection->getFileName();
        assert($filename !== false, 'File name for Routes not found');

        $dir       = dirname($filename);
        $icon_path = $dir . '/' . $this->icon;
        assert(file_exists($icon_path), "Icon file '{$icon_path}' does not exist");

        $icon_path = str_replace('\\', '/', $icon_path);
        return $icon_path;
    }
}
