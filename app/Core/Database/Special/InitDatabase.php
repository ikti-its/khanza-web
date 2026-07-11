<?php
declare(strict_types=1);

namespace App\Core\Database\Special;

use App\Core\Database\Template\DatabaseTemplate;
use CodeIgniter\Database\Exceptions\DatabaseException;

final class InitDatabase extends DatabaseTemplate
{
    #[\Override]
    public function up(): void
    {
        $_db    = \Config\Database::connect();
        $_forge = \Config\Database::forge($_db);
        try {
            $_forge->createDatabase('khanza_db', true);
        } catch (DatabaseException $e) {
            die('There is a problem during db initilization in InitDatabase');
        }

        $config             = new \Config\Database()->default;
        $config['database'] = env('database.default.khanza_db');

        $db   = \Config\Database::connect($config);
        $path = APPPATH . 'Core/Database/Backup/';

        foreach (['migration', 'function'] as $type) {
            $file = $path . $type . '.sql';
            assert(file_exists($file), "SQL file for '{$type}' not found at '{$file}'");
            $sql = (string) file_get_contents($file);
            $db->query($sql);
        }
    }

    #[\Override]
    public function down(): void
    {
        // $forge = \Config\Database::forge();
        // $forge->dropDatabase('khanza_db');
    }

    #[\Override]
    public function dependencies(): array
    {
        return [];
    }
}
