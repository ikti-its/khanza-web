<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

    }

    // public function checkNotifications()
    // {
    //     // Get user ID from session
    //     dd(session()->get('user_details'));
    //     $userId = session()->get('user_details')['id'];
    //     $token = session()->get('jwt_token');

    //     // API URL to check notifications
    //     $notif_url = $this->api_url . '/w/notification/' . $userId;

    //     // Initialize cURL session for fetching notifications
    //     $ch = curl_init($notif_url);

    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //         'Content-Type: application/json',
    //         'Authorization: Bearer ' . $token,
    //     ]);

    //     // Execute the cURL request
    //     $response = curl_exec($ch);
    //     $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    //     // Check for cURL errors
    //     if ($response === false) {
    //         $error_message = curl_error($ch);
    //         curl_close($ch);
    //         log_message('error', 'cURL Error: ' . $error_message);
    //         return; // Exit method on error
    //     }

    //     curl_close($ch);

    //     // Check HTTP status code
    //     if ($http_status_code !== 200) {
    //         log_message('error', 'HTTP Error: ' . $http_status_code);
    //         return; // Exit method on HTTP error
    //     }

    //     // Decode the JSON response
    //     $data = json_decode($response, true);

    //     // Log the data for debugging purposes
    //     log_message('debug', 'Notification API Response: ' . print_r($data, true));

    //     // Initialize notification count
    //     $notificationCount = 0;

    //     // Check if there are notifications
    //     if (isset($data['data']) && is_array($data['data'])) {
    //         // Count the number of notifications
    //         $notificationCount = count($data['data']);
    //     }

    //     // Store notification count in session or do further processing as needed
    //     session()->set('notification_count', $notificationCount);
    //     session()->set('notif_data', $data['data']);
    // }



    protected $api_url;

    public function __construct()
    {
        $this->api_url = getenv('api_URL');
        // Check notifications and set session variable
        // $this->checkNotifications();
    }
    protected array $breadcrumbs = [];
    protected string $judul;
    protected string $modul_path;
    protected string $api_path;
    protected string $nama_tabel;
    protected string $kolom_id;
    protected array $aksi;
    protected array $konfig;
    protected array $meta_data;

    protected function addBreadcrumb($title, $icon = '')
    {
        $this->breadcrumbs[] = [
            'title' => $title,
            'icon' => $icon
        ];
    }

    protected function getBreadcrumbs()
    {
        return $this->breadcrumbs;
    }

    protected function setBreadcrumbs($array): void
    {
        foreach ($array as $elem) {
            $this->addBreadcrumb($elem, strtolower($elem));
        }
    }


    protected function fetchDataUsingCurl($method, $path, $data = null, $redirect_url = null, $redirect_msg = null)
    {
        $allowed_methods = ['GET', 'POST', 'PUT', 'DELETE'];
        if (!in_array($method, $allowed_methods)) {
            echo $this->renderErrorView(405);
        }

        if (!session()->has('jwt_token')) {
            echo $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $full_url = ($custom_base_url ?? $this->api_url) . $path;
        $ch = curl_init($full_url);

        $headers = [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ];

        if ($method === 'POST' || $method === 'PUT') {
            $postData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: ' . strlen($postData);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // ✅ set merged headers

        // Set method
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
        } elseif ($method === 'PUT' || $method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }

        $response = curl_exec($ch);
        $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $return_data = json_decode($response, true);
        if ($method !== 'GET') {
            $http_success_codes = [200, 201, 204];
            if (!in_array($http_status_code, $http_success_codes)) {
                log_message('error', $path . ' API error. Status: ' . $http_status_code . ', response: ' . $response);
                echo $this->renderErrorView($http_status_code);
            }

            if (json_last_error() !== JSON_ERROR_NONE || !isset($return_data['data'])) {
                log_message('error', 'JSON decode error: ' . json_last_error_msg());
                echo $this->renderErrorView(status_code: 500);
            }
            return redirect()->to(base_url($redirect_url))->with('success', $redirect_msg ?? 'Berhasil');
        }

        return [
            'data' => $return_data,
            'kode' => $http_status_code
        ];
    }

    private function getPostData()
    {
        $KOLOM = 2;
        $JENIS = 3;
        $postData = [];
        foreach ($this->konfig as $k) {
            $kolom = $k[$KOLOM];
            $jenis = $k[$JENIS];
            $raw_data = $this->request->getPost($kolom);
            if (in_array($jenis, ['jumlah', 'uang', 'suhu'])) {
                $raw_data = floatval($raw_data);
            }
            $postData[$kolom] = $raw_data;
        }
        return $postData;
    }

    protected function renderErrorView($status_code, $custom_message = null)
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
                $data['message'] = $custom_message ?? 'Permintaan yang anda buat tidak dapat diproses. Pastikan Anda telah memasukkan informasi dengan benar. Coba periksa kembali dan kirim ulang';
                break;
            case 401:
                $data['title'] = 'Unauthorized';
                $data['errorTitle'] = 'Akses terbatas';
                $data['message'] = $custom_message ?? 'Anda harus login untuk mengakses halaman ini';
                break;
            case 403:
                $data['title'] = 'Forbidden';
                $data['errorTitle'] = 'Access ditolak';
                $data['message'] = $custom_message ?? 'Anda tidak memiliki izin untuk melihat halaman ini. Kalau Anda merasa ini salah, hubungi admin.';
                break;
            case 404:
                $data['title'] = 'Not Found';
                $data['errorTitle'] = 'Halaman tidak ditemukan';
                $data['message'] = $custom_message ?? 'Kami tidak dapat menemukan halaman yang Anda cari. Periksa URL atau kembali ke halaman utama';
                break;
            case 405:
                $data['title'] = 'Method Not Allowed ';
                $data['errorTitle'] = 'Method HTTP yang Anda gunakan tidak tersedia';
                $data['message'] = $custom_message ?? 'Kami tidak menyediakan method HTTP tersebut. Periksa kembali URL dan method http Anda';
                break;
            case 500:
                $data['title'] = 'Internal Server Error';
                $data['errorTitle'] = 'Kesalahan Server';
                $data['message'] = $custom_message ?? 'Terjadi masalah pada server kami. Silakan coba lagi nanti atau hubungi dukungan teknis jika masalah berlanjut';
                break;
            default:
                $data['title'] = 'Error';
                $data['errorTitle'] = 'Unexpected Error';
                $data['message'] = $custom_message ?? "Error fetching data. HTTP Status Code: $status_code";
                break;
        }

        return view('errors/html/general_error', $data);
    }

    public function tampilData()
    {
        $tabel = $this->fetchDataUsingCurl('GET', $this->api_path)['data']['data'];
        return view('/layouts/data', [
            'judul'       => $this->judul,
            'breadcrumbs' => $this->getBreadcrumbs(),
            'meta_data'   => $this->meta_data,
            'modul_path'  => $this->modul_path,
            'kolom_id'    => $this->kolom_id,
            'konfig'      => $this->konfig,
            'aksi'        => $this->aksi,
            'tabel'       => $tabel,
        ]);
    }
    public function tampilAudit()
    {
        $audit_konfig = [
            // [1, 'Nomor Perubahan'  , 'change_id' , 'indeks'],
            [1, 'Nama', 'nama', 'teks'],
            [1, 'Aksi Perubahan', 'action', 'status'],
            [1, 'IP Address', 'user_ip', 'teks'],
            [1, 'MAC Address', 'user_mac', 'teks'],
            // [1, 'Pengubah'         , 'changed_by', 'indeks'],
            [1, 'Tanggal Perubahan', 'changed_at', 'tanggal'],
        ];
        $breadcrumbs = [
            ['title' => 'Audit', 'icon', 'audit']
        ];;
        return view('/layouts/audit', [
            'judul'       => 'Audit ' . $this->judul,
            'breadcrumbs' => array_merge($this->getBreadcrumbs(), $breadcrumbs),
            'meta_data'   => $this->meta_data,
            'modul_path'  => $this->modul_path . '/audit',
            'kolom_id'    => 'action',
            'konfig'      => array_merge($audit_konfig, $this->konfig),
            'tabel'       => Audit::GetAuditData($this->nama_tabel)
        ]);
    }
    public function tampilTambah()
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon', 'tambah']
        ];
        return view('/layouts/tambah_ubah', [
            'judul'       => 'Tambah ' . $this->judul,
            'breadcrumbs' => array_merge($this->getBreadcrumbs(), $breadcrumbs),
            'modul_path'  => $this->modul_path,
            'kolom_id'    => $this->kolom_id,
            'konfig'      => $this->konfig,
            'form_action' => '/submittambah/',
        ]);
    }
    public function tampilUbah($id)
    {
        $breadcrumbs = [
            ['title' => 'Ubah', 'icon', 'Ubah']
        ];
        $baris = $this->fetchDataUsingCurl('GET', $this->api_path . '/' . $id)['data']['data'];
        return view('/layouts/tambah_ubah', [
            'judul'       => 'Ubah ' . $this->judul,
            'breadcrumbs' => array_merge($this->getBreadcrumbs(), $breadcrumbs),
            'modul_path'  => $this->modul_path,
            'kolom_id'    => $this->kolom_id,
            'konfig'      => $this->konfig,
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $baris[$this->kolom_id],
        ]);
    }
    public function simpanTambah()
    {
        $postData = $this->getPostData();
        return $this->fetchDataUsingCurl('POST', $this->api_path, $postData, $this->modul_path);
    }
    public function simpanUbah($id)
    {
        $postData = $this->getPostData();
        return $this->fetchDataUsingCurl('PUT', $this->api_path . '/' . $id, $postData, $this->modul_path, $this->judul . ' berhasil diperbarui.');
    }

    public function hapusData($id)
    {
        return $this->fetchDataUsingCurl('DELETE', $this->api_path . '/' . $id, null, $this->modul_path, $this->judul . ' berhasil dihapus.');
    }
}
