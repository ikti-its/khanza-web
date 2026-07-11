<?php
declare(strict_types=1);

namespace App\Core\Database\Special;

use App\Core\Database\Template\DatabaseTemplate;
use CodeIgniter\Database\BaseResult;

final class SearchPathDatabase extends DatabaseTemplate
{
    #[\Override]
    public function up(): void
    {
        $db_name = env('database.default.khanza_db');
        assert(is_string($db_name), 'Env variable database.default.khanza_db must be a string');
        assert($db_name !== '', 'Env variable database.default.khanza_db must be filled');

        $config             = new \Config\Database()->default;
        $config['database'] = $db_name;
        $this->db           = \Config\Database::connect($config);
        $this->forge        = \Config\Database::forge($this->db);

        $query = $this->db->query("SELECT schema_name
            FROM information_schema.schemata
            WHERE schema_name NOT LIKE 'pg_%'
            AND schema_name <> 'information_schema'
            ORDER BY schema_name;");
        assert(!is_bool($query), 'There is a problem setting search_path');
        assert($query instanceof BaseResult, 'Query in search_path is not of BaseResult type');

        $schemas = $query->getResultArray();

        /** @var list<array{schema_name:string}> $schemas */
        $schema_list = [];
        foreach ($schemas as $s) {
            $schema_list[] = $s['schema_name'];
        }

        $schema_string = '' . implode(', ', $schema_list);
        $this->db->query("
            ALTER DATABASE {$db_name}
            SET search_path TO {$schema_string}
        ");
    }

    #[\Override]
    public function down(): void
    {
        // $config = new \Config\Database()->default;
        // $db_name = env('database.default.khanza_db');
        // $config['database'] = $db_name;
        // $this->db = \Config\Database::connect($config);
        // $this->forge = \Config\Database::forge($this->db);
        // $this->db->query("
        //     ALTER DATABASE $db_name
        //     RESET search_path
        // ");
    }

    #[\Override]
    public function dependencies(): array
    {
        return [];
    }
}
