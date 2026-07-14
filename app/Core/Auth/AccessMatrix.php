<?php

declare(strict_types=1);

namespace App\Core\Auth;

use App\Features\Auth\AksesFitur\AksesFiturModel;

/**
 * Sumber kebenaran hak akses per grup fitur adalah tabel auth.akses_fitur
 * (dikelola lewat fitur Akses Fitur), bukan kode. Dipakai oleh filter
 * CheckPermission, ControllerTemplate (sembunyikan tombol tulis), dan
 * sidebar (components/menu/menu.php).
 */
final class AccessMatrix
{
    /** @var array<string, array{baca: bool, tulis: bool}>|null */
    private static ?array $cache = null;

    /** @return array<string, array{baca: bool, tulis: bool}> */
    private static function rows_for_current_role(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        self::$cache = [];

        $role = Role::current();
        if ($role === null) {
            return self::$cache;
        }

        $rows = new AksesFiturModel()
            ->set_filter('id_role', $role->value)
            ->findAll();

        foreach ($rows as $row) {
            self::$cache[$row['feature_group']] = [
                'baca' => self::to_bool($row['boleh_baca']),
                'tulis' => self::to_bool($row['boleh_tulis']),
            ];
        }

        return self::$cache;
    }

    /**
     * Driver Postgre CI4 (pg_fetch_assoc) mengembalikan BOOLEAN sebagai
     * string 't'/'f', bukan bool asli — (bool) 'f' salah karena string
     * non-kosong selalu truthy di PHP.
     */
    private static function to_bool(mixed $value): bool
    {
        return $value === true || $value === 't' || $value === '1' || $value === 1;
    }

    /** Tidak ada baris untuk (role, grup) berarti tolak akses (secure by default). */
    public static function can_read(string $group): bool
    {
        return self::rows_for_current_role()[$group]['baca'] ?? false;
    }

    public static function can_write(string $group): bool
    {
        return self::rows_for_current_role()[$group]['tulis'] ?? false;
    }
}
