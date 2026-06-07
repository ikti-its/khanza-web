<?php
declare(strict_types=1);

namespace App\Core\Database\Template;

/** @mago-expect lint:no-redundant-use */
use App\Core\Database\Template\SemanticType as ST;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
use Codeigniter\Database\BaseResult;

/** @mago-expect analysis:unsafe-instantiation
 * @mago-expect lint:excessive-parameter-list
 * @mago-expect lint:too-many-methods
 * @mago-expect lint:kan-defect
 * @mago-expect lint:cyclomatic-complexity
 */
class DatabaseTemplate extends Migration
{
    /** @var non-empty-string $schema */
    public private(set) string $schema;
    /** @var non-empty-string $table */
    public private(set) string $table;

    /** @var array<string, array{
     *     type: string,
     *     null: bool,
     *     constraint?: string|int,
     *     default?: RawSql,
     * }>*/
    public private(set) array $fields = [];
    /** @var non-empty-string $primary_key */
    public private(set) string $primary_key = '';
    
    /** @var list<list<non-empty-string>> */
    public private(set) array $unique_key = [];

    /**
     * @var array<int, array{
     *   0: list<non-empty-string>,
     *   1: class-string<DatabaseTemplate>,
     *   2: list<non-empty-string>,
     * }>
     */
    public private(set) array $foreign_keys = [];

    public private(set) bool $data_is_real = false;
    /** @var non-empty-string $source */
    public private(set) string $source = '';

    /** @var list<list<string>> */
    private array $index = [];
    
    /** @var array<class-string<DatabaseTemplate>, DatabaseTemplate> $ref_class_cache*/
    private static array $ref_class_cache = [];

    
    public function __construct(
        /** @var non-empty-string $schema */
        string $schema = '',
        /** @var non-empty-string $table */
        string $table = '',
        
        /** @var array<non-empty-string, ForgeType> $fields*/
        array $fields = [],
        /** @var non-empty-string $primary_key */
        string $primary_key = '',
        
        /** @var non-empty-string|list<non-empty-string|list<non-empty-string>> */
        array $unique_key = [],
        /**
         * @var array<int, array{
         *   0: non-empty-string|list<non-empty-string>,
         *   1: class-string<DatabaseTemplate>,
         *   2: non-empty-string|list<non-empty-string>,
         * }>
         */
        array $foreign_keys = [],
        bool $data_is_real = false,
        /** @var non-empty-string $source */
        string $source = '', 
    ) {
        parent::__construct();
        $this->schema = $schema;
        $this->table = $table;
        foreach ($fields as $name => $type) {
            $this->fields[$name] = $type->definition();
        }
        $this->primary_key = $primary_key;
        
        if (is_string($unique_key)){
            $unique_key = [$unique_key];
        }
        foreach ($unique_key as $keys) {
            if (is_string($keys)) {
                $keys = [$keys];
            } 
            $this->unique_key[]  = $keys;
        }

        foreach($foreign_keys as $f){
            [$fields, $ref_table, $ref_fields] = $f;
        
            if (is_string($fields)){
                $fields = [$fields];
            }
            if (is_string($ref_fields)){
                $ref_fields = [$ref_fields];
            }
            $this->foreign_keys[] = [$fields, $ref_table, $ref_fields];
        }
        $this->data_is_real = $data_is_real;
        $this->source = $source;
        $this->set_fk_auto();
    }
    
    private function setup() : void {
        $config = new \Config\Database()->default;
        $config['database'] = env('database.default.khanza_db');

        $this->db = \Config\Database::connect($config);
        $this->forge = \Config\Database::forge($this->db);
    }

    private function add_primary_key(): void {
        $pk = $this->primary_key;
        $field_names = implode(', ', array_keys($this->fields));

        assert( array_key_exists($pk, $this->fields),
            "Primary key '{$pk}' is not defined in fields: {$field_names}"
        );

        assert( ! $this->fields[$pk]['null'], 
            "Primary key field '{$pk}' cannot be nullable. " .
            "Schema : {$this->schema}, Table : {$this->table}");

        // Add more comprehensive checks

        $this->forge->addPrimaryKey($this->primary_key);
    }

    private function add_unique_key(): void {
        $pk = $this->primary_key;
        $uk = $this->unique_key;

        foreach ($uk as $keys) {
            foreach ($keys as $key) {
                assert( array_key_exists($key, $this->fields),
                    "Unique key '{$key}' is not defined in fields " 
                    . implode(', ', array_keys($this->fields)),
                );
                assert( $key !== $pk, 
                    "Unique key '{$key}' is the same as the primary key");
                assert( array_count_values($keys)[$key] = 1,
                    "Unique key field '{$key}' is duplicated in unique key pair " 
                    . implode(', ', $keys),
                );
                // Add more exhaustive checks
            }
            $this->forge->addUniqueKey($keys);
        }
    }

    private function add_foreign_key(): void {
        foreach ($this->foreign_keys as $fk) {
            [$fields, $ref_table_name, $ref_fields] = $fk;
            foreach ($fields as $field) {
                assert( array_key_exists($field, $this->fields),
                    "Foreign key field {$field} not found in fields:" 
                    . implode(', ', array_keys($this->fields)),
                );
            }

            if (!isset(self::$ref_class_cache[$ref_table_name])) {
                self::$ref_class_cache[$ref_table_name] = new $ref_table_name();
            }
            $ref_table_class = self::$ref_class_cache[$ref_table_name];

            foreach ($ref_fields as $ref_field) {
                assert(
                    array_key_exists($ref_field, $ref_table_class->fields),
                    "Foreign key field {$ref_field} not found in reference table"
                    . implode(', ', array_keys($ref_table_class->fields))
                );
            }

            assert(count($fields) === count($ref_fields),
                'The fields ' . implode(',', $fields) . 
                'has more columns than the referenced fields' . implode(',', $ref_fields),
            );

            // Add more comprehensive checks
            try {
                $ref_table_name = "{$ref_table_class->schema}.{$ref_table_class->table}";
                $this->forge->addForeignKey($fields, $ref_table_name, $ref_fields);
            } catch (DatabaseException $e){
                die($e->getMessage());
            }  
        }
    }

    private function set_fk_auto(): void {
        $fks = $this->foreign_keys;

        foreach($fks as $fk){
            [$fields, $ref_table_name, $ref_fields] = $fk;

            if (!isset(self::$ref_class_cache[$ref_table_name])) {
                self::$ref_class_cache[$ref_table_name] = new $ref_table_name();
            }
            $ref_table_class = self::$ref_class_cache[$ref_table_name];

            for($i = 0; $i < count($fields); $i++){
                $original_null = $this->fields[$fields[$i]]['null'];
                $ref_def = $ref_table_class->fields[$ref_fields[$i]];
                $ref_def['type'] = (string) preg_replace('/GENERATED\s+(ALWAYS|BY\s+DEFAULT)\s+AS\s+IDENTITY/i', '', $ref_def['type']);
                unset($ref_def['default']);
                $ref_def['null'] = $original_null;
                $this->fields[$fields[$i]] = $ref_def;
            }
        }
    }

    private function add_index(): void {
        foreach ($this->index as $keys) {
            foreach ($keys as $field) {
                assert(array_key_exists($field, $this->fields), 
                    "Index field '{$field}' is not defined in fields");
                assert($field !== $this->primary_key, 
                    "Index field '{$field}' is already a primary key");

                // Add more comprehensive checks
            }
            $this->forge->addKey($keys);
        }
    }

    private function get_seeder_file(): string
    {
        if ($this->source === '') {
            return '';
        }

        try {
            $reflection = new \ReflectionClass($this);
        } catch (\ReflectionException $e){
            die($e->getMessage());
        }
        $filename = $reflection->getFileName();
        assert($filename !== false, 'File name for seeder not found');
        
        $dir = dirname($filename);
        $csv_file = $dir . '/' . $this->source;
        assert(file_exists($csv_file), "Data file '{$csv_file}' does not exist");

        $csv_file = str_replace('\\', '/', $csv_file); 

        return $csv_file;
    }
    private function seed(): void
    {
        $csv_file = $this->get_seeder_file();
        if($csv_file === '') return;

        $tbl = "{$this->schema}.{$this->table}";
        $sql = "COPY {$tbl} FROM STDIN WITH (FORMAT csv,HEADER true)";

        $result = $this->db->query($sql);
        assert($result !== false, "Failed copy query {$sql}");

        $file = fopen($csv_file, 'r');
        assert($file !== false, "Error opening file {$csv_file}");

        while (($line = fgets($file)) !== false) {
            pg_put_copy_data($this->db->connID, $line);
        }
        pg_put_copy_end($this->db->connID);
        pg_get_result($this->db->connID);
        fclose($file);
    }

    private function adjust_db_key_sequence(): void
    {
        $seq_result = $this->db->query("
            SELECT pg_get_serial_sequence(
                '{$this->schema}.{$this->table}',
                '{$this->primary_key}'
            ) AS seq_name
        ");
        assert($seq_result instanceof BaseResult,
            'Failed to query pg_get_serial_sequence');
        $seq_row = $seq_result->getRowArray();
        
        if(!isset($seq_row['seq_name'])) return;
        $this->db->query("
            SELECT setval('{$seq_row['seq_name'] }',
                COALESCE(
                    (SELECT MAX({$this->primary_key}::bigint)
                        FROM {$this->schema}.{$this->table}), 1)
                )
        ");
    }

    #[\Override()]
    public function up(): void
    {
        $this->setup();
        $this->db->query('CREATE SCHEMA IF NOT EXISTS ' . $this->schema);
        $this->db->query("SET search_path TO {$this->schema}, public");

        $this->forge->addField($this->fields);

        $this->add_primary_key();
        $this->add_unique_key();
        $this->add_foreign_key();
        $this->add_index();

        try {
            $this->forge->createTable($this->table);
            $this->seed();
            $this->adjust_db_key_sequence();
        } catch (DatabaseException $e) {
            die($e->getMessage());
        }
    }

    #[\Override()]
    public function down(): void
    {
        $this->db->query("SET search_path TO public, {$this->schema}");
        try {
            $this->forge->dropTable($this->table);
        } catch (DatabaseException $e) {
            die($e->getMessage());
        }
        
    }

    /** @return list<class-string<DatabaseTemplate>> */
    public function dependencies(): array {
        $dependencies = [];
        foreach($this->foreign_keys as $fk){
            [$_fields, $ref_table_name, $_ref_fields] = $fk;
            assert($ref_table_name !== static::class, 
                "Foreign key refer to itself : {$ref_table_name}");

            $dependencies[] = $ref_table_name;
        }

        return array_values(array_unique($dependencies));
    }
}
