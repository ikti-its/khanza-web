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
    ){}
}