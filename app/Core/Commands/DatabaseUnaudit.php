<?php

namespace App\Core\Commands;

use CodeIgniter\CLI\BaseCommand;

class DatabaseUnaudit extends BaseCommand
{
    protected $group = 'Omnia';
    protected $name = 'omnia:unaudit';
    protected $description = 'Run the unaudit PLpgsql script';

    public function run(array $_params)
    {
        $migration = new \App\Core\Database\Special\AuditDatabase();
        $migration->run('drop_view');
        $migration->run('drop_table');
    }
}