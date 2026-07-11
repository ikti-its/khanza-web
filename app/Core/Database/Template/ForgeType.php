<?php
declare(strict_types=1);

namespace App\Core\Database\Template;

use CodeIgniter\Database\RawSql;

final readonly class ForgeType
{
    public function __construct(
        private string $type,
        private string|int|null $constraint = null,
        private null|string $check = null,
        private bool $null = false,
        private null|RawSql $default = null,
    ) {}

    /**
     * @return array{
     *     type: string,
     *     null: bool,
     *     constraint?: string|int,
     *     default?: RawSql,
     * }
     */
    public function definition(): array
    {
        $arr = [
            'type' => $this->type,
            'null' => $this->null,
        ];
        if ($this->check !== null) {
            $arr['type'] .= " CHECK ( {$this->check} )";
        }
        if ($this->default !== null) {
            $arr['default'] = $this->default;
        }
        if ($this->constraint !== null) {
            $arr['constraint'] = $this->constraint;
        }
        return $arr;
    }

    public function nullable(): self
    {
        return new self(
            type: $this->type,
            constraint: $this->constraint,
            check: $this->check,
            null: true,
            default: $this->default,
        );
    }
}
