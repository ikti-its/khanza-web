<?php
declare(strict_types=1);

namespace App\Core\Config;
use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\Migration;
use CodeIgniter\Database\MigrationRunner;

/** @mago-expect lint:kan-defect */
final class KhanzaMigrationRunner extends MigrationRunner
{
    /** @var string */
    protected $regex = '/\A(\w+Database)\z/';
    
    /** @var array<class-string<DatabaseTemplate>, DatabaseTemplate> */
    private static array $ref_class_cache = [];

    /** @param array<class-string<DatabaseTemplate>, Migration> $ref_table_classes 
     * @return array<class-string<DatabaseTemplate>, list<class-string<DatabaseTemplate>>> 
    */
    private static function buildGraph(array $ref_table_classes): array
    {
        $graph = [];
        $classes = array_keys($ref_table_classes);
        $all = [];
        foreach ($classes as $class){
            $all[$class] = true;
        }
            
        foreach ($classes as $class) {
            /** @mago-expect analysis:unsafe-instantiation */
            if(!isset(self::$ref_class_cache[$class])){
                self::$ref_class_cache[$class] = new $class();
            }
            $ref_table = self::$ref_class_cache[$class];
            $deps = $ref_table->dependencies();
            foreach ($deps as $dep) {
                assert(isset($all[$dep]),
                    "Migration dependency not found: {$dep} -> {$class}");
            }
            $graph[$class] = $deps;
        }

        return $graph;
    }

    /**
     * @param array<class-string<DatabaseTemplate>, list<class-string<DatabaseTemplate>>> $graph
     * @return list<class-string<DatabaseTemplate>>
     */
    private static function topoSort(array $graph): array
    {
        /** @var array<class-string<DatabaseTemplate>, bool> $visited */
        $visited = [];

        /** @var array<class-string<DatabaseTemplate>, bool> $visiting */
        $visiting = [];

        /** @var list<class-string<DatabaseTemplate>> $result */
        $result = [];

        foreach (array_keys($graph) as $node) {
            self::visitNode(
                $node,
                $graph,
                $visited,
                $visiting,
                $result
            );
        }

        return $result;
    }

    /**
     * @param class-string<DatabaseTemplate> $node
     * @param array<class-string<DatabaseTemplate>, list<class-string<DatabaseTemplate>>> $graph
     * @param array<class-string<DatabaseTemplate>, bool> $visited
     * @param array<class-string<DatabaseTemplate>, bool> $visiting
     * @param  list<class-string<DatabaseTemplate>> $result
     */
    private static function visitNode(
        string $node,
        array $graph,
        array &$visited,
        array &$visiting,
        array &$result
    ): void {
        if (isset($visited[$node])){
            return;
        } 

        assert(! isset($visiting[$node]),  "Circular dependency detected at {$node}");

        $visiting[$node] = true;

        // Recurse through dependencies
        foreach ($graph[$node] as $dep) {
            self::visitNode(
                $dep,
                $graph,
                $visited,
                $visiting,
                $result
            );
        }

        unset($visiting[$node]);
        $visited[$node] = true;
        $result[] = $node;
    }

    /**
     * @param list<class-string<DatabaseTemplate>> $ordered
     * @return array<class-string<DatabaseTemplate>, string>
     */
    private static function assignVersions(array $ordered): array
    {
        $versions = [];
        for($i = 0; $i < count($ordered); $i++) {
            $class = $ordered[$i];
            $versions[$class] = str_pad((string)($i+1), 14, '0', STR_PAD_LEFT);
        }

        return $versions;
    }

    #[\Override]
    public function findMigrations(): array
    {
        /** @var array<class-string<DatabaseTemplate>, object{
         *     version:string,
         *     name:string,
         *     path:string,
         *     class:string,
         *     namespace:string,
         *     uid:string
         * }> $ci_migrations */
        $ci_migrations = parent::findMigrations();

        $migrations = [];
        foreach ($ci_migrations as $key => $m){
            $migration = new Migration(
                $m->version,
                $m->name,
                $m->path,
                $m->class,
                $m->namespace,
                $m->uid,
            );
            $migrations[$key] = $migration;
        }

        $all_migrations = $migrations;

        unset($migrations[\App\Core\Database\Special\InitDatabase::class]);
        unset($migrations[\App\Core\Database\Special\SearchPathDatabase::class]);
        unset($migrations[\App\Core\Database\Special\EncryptDatabase::class]);
        unset($migrations[\App\Core\Database\Special\AuditDatabase::class]);
        $graph    = self::buildGraph($migrations);
        $ordered  = self::topoSort($graph);

        /**
         * @var list<class-string<DatabaseTemplate>> $ordered
         */
        $ordered = [
            \App\Core\Database\Special\InitDatabase::class,
            ...$ordered,
            \App\Core\Database\Special\SearchPathDatabase::class,
        ];
        $versions = self::assignVersions($ordered);

        $result = [];

        foreach ($ordered as $class) {
            $migration = $all_migrations[$class];
            $migration->version = $versions[$class];
            $result[$versions[$class]] = $migration;
        }
        
        return $result;
    }

    #[\Override()]
    protected function getMigrationName(string $migration): string
    {
        $matches = [];
        preg_match($this->regex, $migration, $matches);
        return $matches[1] ?? '';
    }

    /**
     * CodeIgniter 4.7.4 hardcoded the parent implementation to only scan each
     * namespace's "/Database/Migrations/" subfolder. This app's ref-table
     * classes are identified by naming convention (ending in "...Database"),
     * not by folder location — they live under app/Core/Database/Special and
     * scattered across app/Features/* — so the whole namespace still needs
     * to be scanned, matching the pre-4.7.4 behavior.
     */
    #[\Override]
    public function findNamespaceMigrations(string $namespace): array
    {
        $migrations = [];
        $locator    = service('locator', true);

        if (! empty($this->path)) {
            helper('filesystem');
            $dir   = rtrim($this->path, DIRECTORY_SEPARATOR) . '/';
            $files = get_filenames($dir, true, false, false);
        } else {
            $files = $locator->listNamespaceFiles($namespace, '/');
        }

        foreach ($files as $file) {
            $file = empty($this->path) ? $file : $this->path . str_replace($this->path, '', $file);

            if ($migration = $this->migrationFromFile($file, $namespace)) {
                $migrations[] = $migration;
            }
        }

        return $migrations;
    }

    /**
     * @param string $direction # 'up'|'down'
     * @param object $migration # Migration
     * @throws \RuntimeException
     * @return bool
     */
    #[\Override]
    protected function migrate( $direction, $migration): bool
    {
        if($migration instanceof Migration){
            /** @var string $path */
            include_once $migration->path;
            $this->setName($migration->name);
            
            /** @var class-string<DatabaseTemplate> */
            $class = $migration->class;
            /** @mago-expect analysis:unsafe-instantiation */
            $instance = new $class();

            match ($direction) {
                'up'   => $instance->up(),
                'down' => $instance->down(),
                default=> die("Wrong migrate direction {$direction}")
            };
        }

        return true;
    }
}
