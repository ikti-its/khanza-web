<?php

declare(strict_types=1);

namespace App\Core\Auth;

/**
 * Dipakai untuk guard hak akses di logika bisnis (lihat method current()).
 * Nilai harus sinkron dengan seed auth.ref_role (ref_role.csv); nama role
 * untuk tampilan diambil dari tabel tersebut. Hak akses per grup fitur
 * (baca/tulis) adalah data di auth.akses_fitur, lihat App\Core\Auth\AccessMatrix.
 */
enum Role: int
{
    case ADMIN = 1;
    case PETUGAS = 2;
    case DOKTER = 3;
    case PERAWAT = 4;

    /** Role user yang sedang login, null jika belum login atau role tak dikenal */
    public static function current(): ?self
    {
        $user = session('user');
        if (!is_array($user) || !is_int($user['role'] ?? null)) {
            return null;
        }
        return self::tryFrom($user['role']);
    }

    /** @param list<self> $roles */
    public static function current_is(self ...$roles): bool
    {
        $current = self::current();
        return $current !== null && in_array($current, $roles, strict: true);
    }
}
