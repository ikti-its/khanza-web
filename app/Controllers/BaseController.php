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

    protected $api_url;

    public function __construct()
    {
        $this->api_url = getenv('api_URL');
    }
    protected array $breadcrumbs = [];
    protected string $judul;
    protected string $modul_path;
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
        foreach($array as $elem){
            $this->addBreadcrumb($elem, strtolower($elem));
        }
    }


    protected function fetchDataUsingCurl($method, $url, $data = null, $redirect_url = null, $redirect_msg = null)
    {
        $allowed_methods = ['GET', 'POST', 'PUT', 'DELETE'];
        if(!in_array($method, $allowed_methods)){
            echo $this->renderErrorView(405);
        }

        if (!session()->has('jwt_token')) {
            echo $this->renderErrorView(401);
        }
        $token = session()->get('jwt_token');
        
        $full_url = $this->api_url . $url;
        $ch = curl_init($full_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);

        if($method === 'POST' || $method === 'PUT'){
            $postData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData)
            ]);
        }

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
        } elseif ($method === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $response         = curl_exec($ch);
        $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $http_success_codes = [200, 201, 204];
        if (!in_array($http_status_code, $http_success_codes)) {
            log_message('error', $url . ' API error. Status: ' . $http_status_code .', response: ' . $response);
            echo $this->renderErrorView($http_status_code);
        }

        $return_data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($return_data['data'])) {
            log_message('error', 'JSON decode error: ' . json_last_error_msg());
            echo $this->renderErrorView(500);
        }

        if($method !== 'GET'){
            return redirect()->to(base_url($redirect_url))->with('success', $redirect_msg ?? 'Berhasil');
        }
        
        return  [
            'data' => $return_data, 
            'kode' => $http_status_code];
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
        return view('/layouts/data', [
            'judul'       => $this->judul,
            'breadcrumbs' => $this->getBreadcrumbs(),
            'meta_data'   => $this->meta_data,
            'full_url'    => $this->api_url . $this->modul_path,
            'modul_path'  => $this->modul_path,
            'kolom_id'    => $this->kolom_id,
            'konfig'      => $this->konfig,
            'aksi'        => $this->aksi, 
            'tabel'       => $this->fetchDataUsingCurl('GET', $this->modul_path)['data']['data']
        ]);
    }
}