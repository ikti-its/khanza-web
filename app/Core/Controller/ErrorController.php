<?php
declare(strict_types=1);

namespace App\Core\Controller;

final class ErrorController
{
    private const CODE = [
        400 => [
            'Bad Request',
            'Oops! ada kesalahan pada permintaan Anda',
            'Permintaan yang anda buat tidak dapat diproses. Coba periksa kembali dan kirim ulang',
        ], 401 => [
            'Unauthorized',
            'Akses terbatas',
            'Anda harus login untuk mengakses halaman ini',
        ], 403 => [
            'Forbidden',
            'Access ditolak',
            'Anda tidak memiliki izin untuk melihat halaman ini. 
                Kalau Anda merasa ini salah, hubungi admin.',
        ], 404 => [
            'Not Found',
            'Halaman tidak ditemukan',
            'Kami tidak dapat menemukan halaman yang Anda cari. Periksa URL atau kembali ke halaman utama',
        ], 405 => [
            'Method Not Allowed',
            'Method HTTP yang Anda gunakan tidak tersedia',
            'Kami tidak menyediakan method HTTP tersebut. Periksa kembali URL dan method http Anda',
        ], 408 => [
            'Timeout',
            'Waktu habis',
            'Permintaan Anda memakan waktu terlalu lama untuk diproses. Silakan coba lagi nanti.',
        ], 500 => [
            'Internal Server Error',
            'Kesalahan Server',
            'Terjadi masalah pada server kami. Silakan coba lagi nanti atau hubungi dukungan teknis jika masalah berlanjut',
        ], 501 => [
            'Not available yet',
            'Fitur belum tersedia',
            'Fitur yang Anda minta belum tersedia. Silakan coba lagi nanti atau hubungi dukungan untuk informasi lebih lanjut',
        ], 502 => [
            'Network error',
            'Kesalahan jaringan',
            'Kami mengalami masalah dengan jaringan kami. Silakan coba lagi nanti',
        ], 503 => [
            'Unavailable',
            'Layanan tidak tersedia',
            'Layanan kami sedang tidak tersedia saat ini. Silakan coba lagi nanti',
        ],
    ];
           
    public static function renderErrorView(int $status_code, ?string $message = null): string
    {
        $http = self::CODE[$status_code] ?? [
            'Error',
            'Unexpected Error',
            "HTTP Status Code: {$status_code}"
        ];
        $data = [
            'code'    => $status_code,
            'title'   => $http[0],
            'error'   => $http[1],
            'message' => $message ?? $http[2],
        ];

        return view('errors/html/general_error', $data);
    }

}