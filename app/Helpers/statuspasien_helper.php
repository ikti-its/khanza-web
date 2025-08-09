<?php

if (!function_exists('badgeStatusPasien')) {
    function badgeStatusPasien(string $status): string
    {
        return match (strtolower($status)) {
            'aktif'     => 'bg-green-500',
            'nonaktif'  => 'bg-yellow-500',
            'meninggal' => 'bg-red-500',
            default     => 'bg-gray-400',
        };
    }
}
