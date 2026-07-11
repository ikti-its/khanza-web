<?php
declare(strict_types=1);

namespace App\Core\Controller\Legacy;

use CodeIgniter\Database\BaseResult;

/** @deprecated "Migrate to ModelTemplate::audit" */
final readonly class Audit
{
    public static function GetAuditData(string $table): array
    {
        $table = str_replace('/', '', $table);

        $db  = \Config\Database::connect();
        $sql = "SELECT * FROM sik.{$table}_audit_view
            LEFT OUTER JOIN 
            (SELECT id, nama FROM sik.pegawai) c
            ON sik.{$table}_audit_view.changed_by = c.id
            ORDER BY changed_by DESC";
        $query = $db->query($sql);
        assert($query instanceof BaseResult, 'There is a problem in Audit query');

        return $query->getResultArray();
    }
}
