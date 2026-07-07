<?php

namespace App\Core\Commands;

use CodeIgniter\CLI\BaseCommand;

class DatabaseDecrypt extends BaseCommand
{
    protected $group = 'Omnia';
    protected $name = 'omnia:decrypt';
    protected $description = 'Run the decryption PLpgsql script';

    public function run(array $_params)
    {
        $migration = new \App\Core\Database\Special\EncryptDatabase();
        $migration->run('drop_function');
        $migration->run('drop_view');
        $migration->run('drop_table');
        $migration->run('original_table');
    }
}