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

    protected $breadcrumbs = [];

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

    public function checkNotifications()
    {
        // Get user ID from session
        $userId = session()->get('user_details')['id'];
        $token = session()->get('jwt_token');

        // API URL to check notifications
        $notif_url = $this->api_url . '/w/notification/' . $userId;

        // Initialize cURL session for fetching notifications
        $ch = curl_init($notif_url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ]);

        // Execute the cURL request
        $response = curl_exec($ch);
        $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Check for cURL errors
        if ($response === false) {
            $error_message = curl_error($ch);
            curl_close($ch);
            log_message('error', 'cURL Error: ' . $error_message);
            return; // Exit method on error
        }

        curl_close($ch);

        // Check HTTP status code
        if ($http_status_code !== 200) {
            log_message('error', 'HTTP Error: ' . $http_status_code);
            return; // Exit method on HTTP error
        }

        // Decode the JSON response
        $data = json_decode($response, true);

        // Log the data for debugging purposes
        log_message('debug', 'Notification API Response: ' . print_r($data, true));

        // Initialize notification count
        $notificationCount = 0;

        // Check if there are notifications
        if (isset($data['data']) && is_array($data['data'])) {
            // Count the number of notifications
            $notificationCount = count($data['data']);
        }

        // Store notification count in session or do further processing as needed
        session()->set('notification_count', $notificationCount);
        session()->set('notif_data', $data['data']);
    }



    protected $api_url;

    public function __construct()
    {
        $this->api_url = getenv('api_URL');
        // Check notifications and set session variable
        $this->checkNotifications();
    }

    protected function renderErrorView($status_code, $custom_message = null)
    {
        $data = [
            'title' => '',
            'errorTitle' => '',
            'message' => $custom_message
        ];

        switch ($status_code) {
            case 400:
                $data['kode'] = $status_code;
                $data['title'] = 'Bad Request';
                $data['errorTitle'] = 'Oops! ada kesalahan pada permintaan Anda';
                $data['message'] = $custom_message ?? 'Permintaan yang anda buat tidak dapat diproses. Pastikan Anda telah memasukkan informasi dengan benar. Coba periksa kembali dan kirim ulang';
                break;
            case 401:
                $data['kode'] = $status_code;
                $data['title'] = 'Unauthorized';
                $data['errorTitle'] = 'Akses terbatas';
                $data['message'] = $custom_message ?? 'Anda harus login untuk mengakses halaman ini';
                break;
            case 403:
                $data['kode'] = $status_code;
                $data['title'] = 'Forbidden';
                $data['errorTitle'] = 'Access ditolak';
                $data['message'] = $custom_message ?? 'Anda tidak memiliki izin untuk melihat halaman ini. Kalau Anda merasa ini salah, hubungi admin.';
                break;
            case 404:
                $data['kode'] = $status_code;
                $data['title'] = 'Not Found';
                $data['errorTitle'] = 'Halaman tidak ditemukan';
                $data['message'] = $custom_message ?? 'Kami tidak dapat menemukan halaman yang Anda cari. Periksa URL atau kembali ke halaman utama';
                break;
            case 500:
                $data['kode'] = $status_code;
                $data['title'] = 'Internal Server Error';
                $data['errorTitle'] = 'Kesalahan Server';
                $data['message'] = $custom_message ?? 'Terjadi masalah pada server kami. Silakan coba lagi nanti atau hubungi dukungan teknis jika masalah berlanjut';
                break;
            default:
                $data['kode'] = $status_code;
                $data['title'] = 'Error';
                $data['errorTitle'] = 'Unexpected Error';
                $data['message'] = $custom_message ?? "Error fetching akun data. HTTP Status Code: $status_code";
                break;
        }

        return view('errors/html/general_error', $data);
    }
}
