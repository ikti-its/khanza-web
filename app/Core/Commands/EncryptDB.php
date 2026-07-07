<?php

namespace App\Core\Commands;

use CodeIgniter\CLI\BaseCommand;

class EncryptDB extends BaseCommand
{
    protected $group = 'Omnia';
    protected $name = 'omnia:encrypt';
    protected $description = 'Run the encryption PLpgsql script';

    public function run(array $_params)
    {
        $migration = new \App\Core\Database\Special\EncryptDatabase();
        $migration->run('drop_function');
        $migration->run('drop_view');
        $migration->run('drop_table');
        $migration->run('original_table');
        $migration->run('rename_table');
        $migration->run('create_table');
        $migration->run('create_view');

    }
}