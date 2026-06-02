<?php
declare(strict_types=1);

namespace App\Core\Controller\Legacy;

/** @deprecated "Migrate to ControllerTemplate inheritence" */
final readonly class HTTPError
{
    public static function renderErrorView(int $status_code, ?string $custom_message = null): string
    {
        $data = [
            'kode'       => $status_code,
            'title'      => '',
            'errorTitle' => '',
            'message'    => $custom_message
        ];

        switch ($status_code) {
            case 400:
                $data['title'] = 'Bad Request';
                $data['errorTitle'] = 'Oops! ada kesalahan pada permintaan Anda';
                $data['message'] ??= 'Permintaan yang anda buat tidak dapat diproses. 
                    Pastikan Anda telah memasukkan informasi dengan benar. Coba periksa 
                    kembali dan kirim ulang';
                break;
            case 401:
                $data['title'] = 'Unauthorized';
                $data['errorTitle'] = 'Akses terbatas';
                $data['message'] ??= 'Anda harus login untuk mengakses halaman ini';
                break;
            case 402:
                $data['title'] = 'Unauthorized';
                $data['errorTitle'] = 'Akses terbatas';
                $data['message'] ??= 'Anda harus login untuk mengakses halaman ini';
                break;
            case 403:
                $data['title'] = 'Forbidden';
                $data['errorTitle'] = 'Access ditolak';
                $data['message'] ??= 'Anda tidak memiliki izin untuk melihat halaman ini. 
                    Kalau Anda merasa ini salah, hubungi admin.';
                break;
            case 404:
                $data['title'] = 'Not Found';
                $data['errorTitle'] = 'Halaman tidak ditemukan';
                $data['message'] ??= 'Kami tidak dapat menemukan halaman yang Anda cari. 
                    Periksa URL atau kembali ke halaman utama';
                break;
            case 405:
                $data['title'] = 'Method Not Allowed';
                $data['errorTitle'] = 'Method HTTP yang Anda gunakan tidak tersedia';
                $data['message'] ??= 'Kami tidak menyediakan method HTTP tersebut. 
                    Periksa kembali URL dan method http Anda';
                break;
            case 408:
                $data['title'] = 'Timeout';
                $data['errorTitle'] = 'Waktu habis';
                $data['message'] ??= 'Permintaan Anda memakan waktu terlalu lama untuk diproses. Silakan coba lagi nanti.';
                break;
            case 500:
                $data['title'] = 'Internal Server Error';
                $data['errorTitle'] = 'Kesalahan Server';
                $data['message'] ??= 'Terjadi masalah pada server kami. 
                Silakan coba lagi nanti atau hubungi dukungan teknis jika masalah berlanjut';
                break;
            case 501:
                $data['title'] = 'Not available yet';
                $data['errorTitle'] = 'Fitur belum tersedia';
                $data['message'] ??= 'Fitur yang Anda minta belum tersedia. 
                    Silakan coba lagi nanti atau hubungi dukungan untuk 
                    informasi lebih lanjut';
                break;
            case 502:
                $data['title'] = 'Network';
                $data['errorTitle'] = 'Kesalahan jaringan';
                $data['message'] ??= 'Kami mengalami masalah dengan jaringan kami. Silakan coba lagi nanti';
                break;
            case 503:
                $data['title'] = 'Unavailable';
                $data['errorTitle'] = 'Layanan tidak tersedia';
                $data['message'] ??= 'Layanan kami sedang tidak tersedia saat ini. Silakan coba lagi nanti';
                break;
            default:
                $data['title'] = 'Error';
                $data['errorTitle'] = 'Unexpected Error';
                $data['message']    ??= "Error fetching data. HTTP Status Code: {$status_code}";
                break;
        }

        return view('errors/html/general_error', $data);
    }

}