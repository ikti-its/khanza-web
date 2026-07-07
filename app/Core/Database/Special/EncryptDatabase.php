<?php
declare(strict_types=1);

namespace App\Core\Database\Special;

final class EncryptDatabase
{    
    public function run(string $arg): void
    {
        $config = new \Config\Database()->default;
        $config['database'] = env('database.default.khanza_db');
        $db = \Config\Database::connect($config);
        $db->query('CREATE EXTENSION IF NOT EXISTS pgcrypto;');
        $prefix = APPPATH . 'Core/Database/Encrypt/';

        $sql = match ($arg) {
            'rename_table'   => 'rename_original_table.sql',
            'create_table'   => 'create_encrypted_table.sql',
            'create_view'    => 'create_encrypted_view.sql',
            'drop_function'  => 'drop_function.sql',
            'drop_view'      => 'drop_encrypted_view.sql',
            'drop_table'     => 'drop_encrypted_table.sql',
            'original_table' => 'rename_structures_table_to_original.sql',
            default => die("Argument {$arg} not found"),
        };
        $db->query(file_get_contents($prefix . $sql));
    }
}