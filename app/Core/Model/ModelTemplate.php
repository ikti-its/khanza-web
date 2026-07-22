<?php
declare(strict_types=1);

namespace App\Core\Model;

use App\Core\Database\Template\DatabaseTemplate;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\BaseResult;
use CodeIgniter\Model;

/** @mago-expect lint:excessive-parameter-list */
class ModelTemplate extends Model
{
    // Widened to public; parent keeps these protected and only exposes them via __get().
    /** @var BaseConnection */
    public $db;
    /** @var string */
    public $primaryKey;
    /** @var string */
    public $table;

    public private(set) DatabaseTemplate $database;
    /** @var 'BASE'| 'JOIN'| 'REFS' */
    public private(set) string $type;

    /** @var array<non-empty-string, ValidationType> */
    public private(set) array $fields = [];
    public private(set) array $join   = [];
    /** @var array<string, true> */
    private array $selected_aliases = [];

    /** @var array<string, mixed> */
    protected array $runtime_filters = [];
    /** @var array<string, string> */
    protected array $runtime_orders = [];
    private bool $exclude_zero_pk = false;

    public function set_filter(string $col, mixed $val): static
    {
        $this->runtime_filters[$col] = $val;
        return $this;
    }

    public function set_order(string $col, string $dir = 'ASC'): static
    {
        $this->runtime_orders[$col] = $dir;
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
            is_array($val) ? $builder->whereIn($col, $val) : $builder->where($col, $val);
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
        $this->database   = $database;
        $this->table      = "{$this->database->schema}.{$this->database->table}";
        $this->primaryKey = $this->database->primary_key;

        $this->allowedFields = array_keys($fields);
        if (($key = array_search($this->primaryKey, $this->allowedFields)) !== false) {
            unset($this->allowedFields[$key]);
        }

        assert(array_intersect_key($fields, $join) === [], "Fields intersects with join in {$this->table}");
        $this->allowedFields = array_merge($this->allowedFields, array_keys($join));
        $this->join          = $join;

        $config             = new \Config\Database()->default;
        $config['database'] = env('database.default.khanza_db');
        parent::__construct(\Config\Database::connect($config));

        $validation = [];
        $messages   = [];
        foreach ($this->database->fields as $col => $def) {
            $rules = [];

            // Ambil base type saja (type bisa berisi sufiks "CHECK (...)")
            $base_type = strtoupper(explode(' ', $def['type'] ?? '')[0]);

            if (isset($def['constraint']) && in_array($base_type, ['CHAR', 'VARCHAR'], true)) {
                $rules[]                      = "max_length[{$def['constraint']}]";
                $namaKolom                    = str_replace('_', ' ', $col);
                $messages[$col]['max_length'] = "Field {$namaKolom} tidak boleh lebih dari {$def['constraint']} karakter.";
            }

            if (!empty($rules)) {
                $validation[$col]['rules'] = implode('|', $rules);
            }
        }
        if (!empty($validation)) {
            $this->setValidationRules($validation);
            $this->setValidationMessages($messages);
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

    /** @return array<string, array{0: class-string<DatabaseTemplate>, 1: list<string>, 2: list<string>}> */
    private function build_fk_lookup(DatabaseTemplate $db): array
    {
        $lookup = [];
        foreach ($db->foreign_keys as $fk) {
            [$fields, $ref_class, $ref_fields] = $fk;
            $fields               = (array) $fields;
            $ref_fields           = (array) $ref_fields;
            $lookup[end($fields)] = [$ref_class, $fields, $ref_fields];
        }
        return $lookup;
    }

    /** @param list<string> $fields @param list<string> $ref_fields */
    private function composite_join_condition(
        string $parent_alias,
        array $fields,
        string $ref_alias,
        array $ref_fields,
    ): string {
        $conditions = [];
        foreach ($fields as $i => $field) {
            $conditions[] = "{$parent_alias}.{$field} = {$ref_alias}.{$ref_fields[$i]}";
        }
        return implode(' AND ', $conditions);
    }

    private function select_once(
        \CodeIgniter\Database\BaseBuilder $builder,
        string $table_alias,
        string $col_name,
        string $root_fk = '',
    ): void {
        if (isset($this->selected_aliases[$col_name])) {
            $alias = "{$root_fk}_{$col_name}";
        } else {
            $this->selected_aliases[$col_name] = true;
            $alias                             = $col_name;
        }
        $builder->select("{$table_alias}.{$col_name} AS {$alias}");
    }

    /** @param array<int|string, mixed> $spec */
    private function apply_join_spec(
        \CodeIgniter\Database\BaseBuilder $builder,
        string $fk_col,
        array $spec,
        string $parent_alias,
        DatabaseTemplate $parent_db,
        int &$idx,
        string $root_fk = '',
    ): void {
        $fk_lookup = $this->build_fk_lookup($parent_db);
        if (!isset($fk_lookup[$fk_col]))
            return;

        if ($root_fk === '')
            $root_fk = $fk_col;

        /** @var class-string<DatabaseTemplate> $ref_class */
        [$ref_class, $fk_fields, $ref_fields] = $fk_lookup[$fk_col];

        $ref_db    = new $ref_class();
        $ref_table = $ref_db->schema . '.' . $ref_db->table;
        $ref_alias = "j{$idx}";
        $idx++;

        $builder->join(
            "{$ref_table} {$ref_alias}",
            $this->composite_join_condition($parent_alias, $fk_fields, $ref_alias, $ref_fields),
            'left',
        );

        foreach ($spec as $k => $v) {
            if (is_int($k)) {
                assert(is_string($v), 'Leaf value di join spec harus string, dapat: ' . gettype($v));
                $this->select_once($builder, $ref_alias, $v, $root_fk);
            } else {
                assert(is_array($v), "Nested join spec untuk key '{$k}' harus array");
                $this->apply_join_spec($builder, $k, $v, $ref_alias, $ref_db, $idx, $root_fk);
            }
        }
    }

    /**
     * Computes the actual SELECT aliases that will be used for each join spec's leaf columns.
     * Mirrors the same conflict-resolution logic as select_once/apply_join_spec so that
     * ControllerTemplate can build correct column display configs without running a query.
     *
     * @return array<string, list<string>> root_fk_col => list of actual aliases
     */
    public function compute_leaf_aliases(): array
    {
        $used = []; // col_name => alias (mirrors selected_aliases)
        $result = [];

        foreach ($this->join as $root_fk => $spec) {
            $result[$root_fk] = $this->spec_to_aliases($spec, $root_fk, $used);
        }

        return $result;
    }

    /** @param array<int|string, mixed> $spec */
    private function spec_to_aliases(array $spec, string $root_fk, array &$used): array
    {
        $aliases = [];
        foreach ($spec as $k => $v) {
            if (is_int($k) && is_string($v)) {
                if (isset($used[$v])) {
                    $alias = $root_fk !== '' ? "{$root_fk}_{$v}" : $v;
                } else {
                    $alias    = $v;
                    $used[$v] = $alias;
                }
                $aliases[] = $alias;
            } elseif (is_string($k) && is_array($v)) {
                $aliases = array_merge($aliases, $this->spec_to_aliases($v, $root_fk, $used));
            }
        }
        return $aliases;
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
                [$ref_class, $fk_fields, $ref_fields] = $fk_lookup[$k];

                $ref_db    = new $ref_class();
                $ref_table = $ref_db->schema . '.' . $ref_db->table;
                $ref_alias = "j{$idx}";
                $idx++;

                $builder->join(
                    "{$ref_table} {$ref_alias}",
                    $this->composite_join_condition($parent_alias, $fk_fields, $ref_alias, $ref_fields),
                    'left',
                );

                $next_spec = is_array($v) ? $v : [$v];
                $resolved  = $this->resolve_option_source($builder, $next_spec, $ref_alias, $ref_db, $idx);

                if ($resolved !== null) {
                    return $resolved;
                }

                continue;
            }

            if (is_string($v) && $v !== '') {
                return [$parent_alias, $v];
            }

            if (is_array($v)) {
                $resolved = $this->resolve_option_source($builder, $v, $parent_alias, $parent_db, $idx);

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
        $main                   = 'm';
        $builder                = $this->db->table("{$this->table} {$main}");
        $builder->select("{$main}.*");

        $idx = 0;
        foreach ($this->join as $fk_col => $spec) {
            $this->apply_join_spec($builder, $fk_col, $spec, $main, $this->database, $idx);
        }

        $builder->where("{$main}.{$this->primaryKey}", $id);

        $result = $builder->get();
        assert($result instanceof BaseResult, "find_one JOIN query failed on table: {$this->table}");

        $row = $result->getRowArray();
        return is_array($row) ? $row : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    #[\Override]
    public function findAll(int|null $limit = 0, int $offset = 0): array
    {
        if ($this->join === []) {
            foreach ($this->runtime_filters as $col => $val) {
                is_array($val) ? $this->whereIn($col, $val) : $this->where($col, $val);
            }
            foreach ($this->runtime_orders as $col => $dir) {
                $this->orderBy($col, $dir);
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
            is_array($val) ? $builder->whereIn("{$main}.{$col}", $val) : $builder->where("{$main}.{$col}", $val);
        }
        foreach ($this->runtime_orders as $col => $dir) {
            $builder->orderBy($col, $dir);
        }
        if ($this->exclude_zero_pk) {
            $builder->where("{$main}.{$this->primaryKey} !=", 0);
        }

        if ($limit !== null && $limit > 0) {
            $builder->limit($limit, $offset);
        }

        $result = $builder->get();
        assert($result instanceof BaseResult, "findAll JOIN query failed on table: {$this->table}");

        /** @var list<array<string, mixed>> */
        return $result->getResultArray();
    }

    /** @return array<string, list<list<string>>> */
    public function get_all_options(): array
    {
        if (empty($this->join))
            return [];

        $options = [];
        foreach ($this->join as $fk_col => $display_cols) {
            $fk_lookup = $this->build_fk_lookup($this->database);
            if (!isset($fk_lookup[$fk_col]))
                continue;

            /** @var class-string<DatabaseTemplate> $ref_class */
            [$ref_class, , $ref_fields] = $fk_lookup[$fk_col];
            $ref_id_col = end($ref_fields);

            $ref_db    = new $ref_class();
            $ref_table = $ref_db->schema . '.' . $ref_db->table;
            $main      = 'm';
            $builder   = $this->db->table("{$ref_table} {$main}");
            $idx       = 0;

            $specs    = is_array($display_cols) ? $display_cols : [$display_cols];
            $resolved = $this->resolve_option_source($builder, $specs, $main, $ref_db, $idx);

            if ($resolved === null)
                continue;

            [$display_alias, $display_col] = $resolved;

            $builder->select("{$main}.{$ref_id_col} AS option_value");
            $builder->select("{$display_alias}.{$display_col} AS display_value");

            $query = $builder->get();
            assert($query instanceof BaseResult, "get_all_options query failed for: {$fk_col}");

            $rows             = $query->getResultArray();
            $options[$fk_col] = array_map(fn(array $row): array => [
                $row['display_value'],
                (string) $row['option_value'],
            ], $rows);
        }

        return $options;
    }

    final public function audit(): array
    {
        $view = "{$this->database->schema}.{$this->database->table}_audit_view";
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

        if (!$query instanceof BaseResult)
            return [];
        return $query->getResultArray();
    }
}
