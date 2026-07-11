<?php
declare(strict_types=1);

namespace App\Core\Database\Template;

final class Migration
{
    /** @mago-expect lint:excessive-parameter-list */
    public function __construct(
        public string $version,
        public readonly string $name,
        public readonly string $path,
        public readonly string $class,
        public readonly string $namespace,
        public readonly string $uid,
    ) {}
}
