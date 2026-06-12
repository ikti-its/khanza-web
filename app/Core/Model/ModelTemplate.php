<?php
declare(strict_types=1);

namespace App\Core\Model;
use CodeIgniter\Database\BaseResult;
use CodeIgniter\Model;
use App\Core\Database\Template\DatabaseTemplate;

/** @mago-expect lint:excessive-parameter-list */
class ModelTemplate extends Model
{
    public private(set) DatabaseTemplate $database;
    /** @var 'BASE'| 'JOIN'| 'REFS' */
    public private(set) string $type;
    
    /** @var array<non-empty-string, ValidationType> */
    public private(set) array $fields = [];
    public private(set) array $join = [];
    /** @var array<string, true> */
    private array $selected_aliases = [];

    /** @var array<string, mixed> */
    protected array $runtime_filters  = [];
    private bool  $exclude_zero_pk  = false;

    public function set_filter(string $col, mixed $val): static
    {
        $this->runtime_filters[$col] = $val;
        return $this;
    }

    public function exclude_zero_pk(): static
    {
        $this->exclude_zero_pk = true;
        return $this;
    }

    public function count_filtered(): int
    {
        if (empty($this->runtime_filters) && !$this->exclude_zero_pk) {
            return (int) $this->countAll();
        }
        $builder = $this->db->table($this->table);
        foreach ($this->runtime_filters as $col => $val) {
            $builder->where($col, $val);
        }
        if ($this->exclude_zero_pk) {
            $builder->where($this->primaryKey . ' !=', 0);
        }
        return (int) $builder->countAllResults();
    }

    protected function __construct(
        DatabaseTemplate $database,
        /** @var array<non-empty-string, ValidationType> */
        array $fields,
        array $join,
    ) {
        $this->database = $database;
        $this->table = "{$this->database->schema}.{$this->database->table}";
        $this->primaryKey = $this->database->primary_key;
        
        $this->allowedFields = array_keys($fields);
        if (($key = array_search($this->primaryKey, $this->allowedFields)) !== false) {
            unset($this->allowedFields[$key]);
        }
        
        assert(array_intersect_key($fields, $join) === [], 
            "Fields intersects with join in {$this->table}");
        $this->allowedFields = array_merge($this->allowedFields, array_keys($join));
        $this->join = $join;

        $config = new \Config\Database()->default;
        $config['database'] = env('database.default.khanza_db');
        parent::__construct(\Config\Database::connect($config));

        $validation = [];
        foreach ($this->database->fields as $col => $def) {
            $rules = [];

            // Ambil base type saja (type bisa berisi sufiks "CHECK (...)")
            $base_type = strtoupper(explode(' ', $def['type'] ?? '')[0]);

            if (isset($def['constraint']) && in_array($base_type, ['CHAR', 'VARCHAR'], true)) {
                $rules[] = "max_length[{$def['constraint']}]";
            }

            if (!empty($rules)) {
                $validation[$col]['rules'] = implode('|', $rules);
            }
        }
        if (!empty($validation)) {
            $this->setValidationRules($validation);
        }

        /*
         * All of the settings below are commented
         * because these are already the default.
         * But it is still included to indicate
         * which properties exist on the Model class
         * DO NOT DELETE NOR UNCOMMENT THE CODE BELOW
         */

        // $this->useAutoIncrement = true;

        //// CRUD settings
        // $this->returnType        = 'array';
        // $this->useSoftDeletes    = false;
        // $this->allowEmptyInserts = false;
        // $this->updateOnlyChanged = true;

        //// Validation
        // $this->skipValidation       = false;
        // $this->cleanValidationRules = true;
        // $this->validationMessages   = [];
        // $this->validationRules      = [],

        //// Dates
        // $this->useTimestamps = false;
        // $this->dateFormat   = 'datetime';
        // $this->createdField = 'created_at';
        // $this->updatedField = 'updated_at';
        // $this->deletedField = 'deleted_at';

        //// Callbacks
        // $this->allowCallbacks = false;
        // $this->beforeInsert = [];
        // $this->afterInsert  = [];
        // $this->beforeUpdate = [];
        // $this->afterUpdate  = [];
        // $this->beforeFind   = [];
        // $this->afterFind    = [];
        // $this->beforeDelete = [];
        // $this->afterDelete  = [];
    }

    /** @return array<string, array{0: class-string<DatabaseTemplate>, 1: string}> */
    private function build_fk_lookup(DatabaseTemplate $db): array
    {
        $lookup = [];
        foreach ($db->foreign_keys as $fk) {
            [$fields, $ref_class, $ref_fields] = $fk;
            for ($i = 0; $i < count($fields); $i++) {
                $lookup[$fields[$i]] = [$ref_class, $ref_fields[$i]];
            }
        }
        return $lookup;
    }

    private function select_once(
        \CodeIgniter\Database\BaseBuilder $builder,
        string $table_alias,
        string $col_name,
    ): void {
        if (isset($this->selected_aliases[$col_name])) {
            return;
        }
        $this->selected_aliases[$col_name] = true;
        $builder->select("{$table_alias}.{$col_name} AS {$col_name}");
    }

    /** @param array<int|string, mixed> $spec */
    private function apply_join_spec(
        \CodeIgniter\Database\BaseBuilder $builder,
        string $fk_col,
        array $spec,
        string $parent_alias,
        DatabaseTemplate $parent_db,
        int &$idx,
    ): void {
        $fk_lookup = $this->build_fk_lookup($parent_db);
        if (!isset($fk_lookup[$fk_col])) return;

        /** @var class-string<DatabaseTemplate> $ref_class */
        [$ref_class, $ref_on] = $fk_lookup[$fk_col];

        $ref_db    = new $ref_class();
        $ref_table = $ref_db->schema . '.' . $ref_db->table;
        $ref_alias = "j{$idx}";
        $idx++;

        $builder->join(
            "{$ref_table} {$ref_alias}",
            "{$parent_alias}.{$fk_col} = {$ref_alias}.{$ref_on}",
            'left'
        );

        foreach ($spec as $k => $v) {
            if (is_int($k)) {
                assert(is_string($v), "Leaf value di join spec harus string, dapat: " . gettype($v));
                $this->select_once($builder, $ref_alias, $v);
            } else {
                assert(is_array($v), "Nested join spec untuk key '{$k}' harus array");
                $this->apply_join_spec($builder, $k, $v, $ref_alias, $ref_db, $idx);
            }
        }
    }

    /**
     * @param array<int|string, mixed> $spec
     * @return array{0: string, 1: string}|null
     */
    private function resolve_option_source(
        \CodeIgniter\Database\BaseBuilder $builder,
        array $spec,
        string $parent_alias,
        DatabaseTemplate $parent_db,
        int &$idx,
    ): array|null {
        $fk_lookup = $this->build_fk_lookup($parent_db);

        foreach ($spec as $k => $v) {
            if (is_string($k)) {
                if (!isset($fk_lookup[$k])) {
                    continue;
                }

                /** @var class-string<DatabaseTemplate> $ref_class */
                [$ref_class, $ref_on] = $fk_lookup[$k];

                $ref_db    = new $ref_class();
                $ref_table = $ref_db->schema . '.' . $ref_db->table;
                $ref_alias = "j{$idx}";
                $idx++;

                $builder->join(
                    "{$ref_table} {$ref_alias}",
                    "{$parent_alias}.{$k} = {$ref_alias}.{$ref_on}",
                    'left'
                );

                $next_spec = is_array($v) ? $v : [$v];
                $resolved = $this->resolve_option_source(
                    $builder,
                    $next_spec,
                    $ref_alias,
                    $ref_db,
                    $idx,
                );

                if ($resolved !== null) {
                    return $resolved;
                }

                continue;
            }

            if (is_string($v) && $v !== '') {
                return [$parent_alias, $v];
            }

            if (is_array($v)) {
                $resolved = $this->resolve_option_source(
                    $builder,
                    $v,
                    $parent_alias,
                    $parent_db,
                    $idx,
                );

                if ($resolved !== null) {
                    return $resolved;
                }
            }
        }

        return null;
    }

    /**
     * Like find() but applies JOINs so display columns (e.g. nama_barang) are included.
     * Use this when the result is passed to a form view that needs joined column values.
     *
     * @return array<string, mixed>|null
     */
    public function find_one(int|string $id): array|null
    {
        if ($this->join === []) {
            $row = parent::find($id);
            return is_array($row) ? $row : null;
        }

        $this->selected_aliases = [];
        $main    = 'm';
        $builder = $this->db->table("{$this->table} {$main}");
        $builder->select("{$main}.*");

        $idx = 0;
        foreach ($this->join as $fk_col => $spec) {
            $this->apply_join_spec($builder, $fk_col, $spec, $main, $this->database, $idx);
        }

        $builder->where("{$main}.{$this->primaryKey}", $id);

        $result = $builder->get();
        assert($result instanceof BaseResult,
            "find_one JOIN query failed on table: {$this->table}");

        $row = $result->getRowArray();
        return is_array($row) ? $row : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    #[\Override]
    public function findAll(int|null $limit = 10, int $offset = 0): array
    {
        if ($this->join === []) {
            foreach ($this->runtime_filters as $col => $val) {
                $this->where($col, $val);
            }
            if ($this->exclude_zero_pk) {
                $this->where($this->primaryKey . ' !=', 0);
            }
            /** @var list<array<string, mixed>> */
            return parent::findAll($limit, $offset);
        }

        $this->selected_aliases = [];

        $main    = 'm';
        $builder = $this->db->table("{$this->table} {$main}");
        $builder->select("{$main}.*");

        $idx = 0;
        foreach ($this->join as $fk_col => $spec) {
            $this->apply_join_spec($builder, $fk_col, $spec, $main, $this->database, $idx);
        }

        foreach ($this->runtime_filters as $col => $val) {
            $builder->where("{$main}.{$col}", $val);
        }
        if ($this->exclude_zero_pk) {
            $builder->where("{$main}.{$this->primaryKey} !=", 0);
        }

        if ($limit !== null && $limit > 0) {
            $builder->limit($limit, $offset);
        }

        $result = $builder->get();
        assert($result instanceof BaseResult,
            "findAll JOIN query failed on table: {$this->table}");

        /** @var list<array<string, mixed>> */
        return $result->getResultArray();
    }

    /** @return array<string, list<list<string>>> */
    public function get_all_options(): array
    {
        if (empty($this->join)) return [];

        $options = [];
        foreach ($this->join as $fk_col => $display_cols) {
            $fk_lookup = $this->build_fk_lookup($this->database);
            if (!isset($fk_lookup[$fk_col])) continue;

            /** @var class-string<DatabaseTemplate> $ref_class */
            [$ref_class, $ref_id_col] = $fk_lookup[$fk_col];

            $ref_db    = new $ref_class();
            $ref_table = $ref_db->schema . '.' . $ref_db->table;
            $main      = 'm';
            $builder   = $this->db->table("{$ref_table} {$main}");
            $idx       = 0;

            $specs = is_array($display_cols) ? $display_cols : [$display_cols];
            $resolved = $this->resolve_option_source(
                $builder,
                $specs,
                $main,
                $ref_db,
                $idx,
            );

            if ($resolved === null) continue;

            [$display_alias, $display_col] = $resolved;

            $builder->select("{$main}.{$ref_id_col} AS option_value");
            $builder->select("{$display_alias}.{$display_col} AS display_value");

            $query = $builder->get();
            assert($query instanceof BaseResult,
                "get_all_options query failed for: {$fk_col}");

            $rows = $query->getResultArray();
            $options[$fk_col] = array_map(
                fn(array $row): array => [$row['display_value'], (string)$row['option_value']],
                $rows
            );
        }

        return $options;
    }

    final public function audit(): array {
        $view = "{$this->schema}.{$this->table_name}_audit_view";
        $sql  = "SELECT * FROM {$view}
            LEFT OUTER JOIN
            (SELECT id, nama FROM sik.pegawai) c
            ON {$view}.changed_by = c.id
            ORDER BY changed_by DESC";

        try {
            $query = $this->db->query($sql);
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException) {
            return [];
        }

        if (! $query instanceof BaseResult) return [];
        return $query->getResultArray();
    }
}
