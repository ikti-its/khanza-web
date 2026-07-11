<?php
declare(strict_types=1);

namespace App\Core\Database\Special;

final class AuditDatabase
{
    public function run(string $arg): void
    {
        $config             = new \Config\Database()->default;
        $config['database'] = env('database.default.khanza_db');
        $db                 = \Config\Database::connect($config);
        $db->query('CREATE EXTENSION IF NOT EXISTS pgcrypto;');
        $prefix = APPPATH . 'Core/Database/Audit/';

        $sql = match ($arg) {
            'drop_view'    => 'drop_audit_view.sql',
            'drop_table'   => 'drop_audit_table.sql',
            'create_table' => 'create_audit_table.sql',
            'create_view'  => 'create_audit_view.sql',
            default        => die("Argument {$arg} not found"),
        };
        $db->query(file_get_contents($prefix . $sql));
    }
}
