<?php

namespace App\Core\Commands;

use CodeIgniter\CLI\BaseCommand;

class DatabaseAudit extends BaseCommand
{
    protected $group = 'Omnia';
    protected $name = 'omnia:audit';
    protected $description = 'Run the audit PLpgsql script';

    public function run(array $_params)
    {
        $migration = new \App\Core\Database\Special\AuditDatabase();
        $migration->run('create_table');
        $migration->run('create_view');
    }
}