<?php
declare(strict_types=1);

namespace App\Core\Controller\Legacy;
use App\Core\Model\ModelTemplate;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * @deprecated "Migrate to ControllerTemplate"
 * @mago-expect lint:excessive-parameter-list
 */
class ControllerTemplateLegacy extends Controller
{
    protected $api_url;

    public function __construct(
        protected ModelTemplate|null $model = null,
        protected array $breadcrumbs = [],
        protected string $judul = '',
        protected string $modul_path = '',
        protected string $api_path = '',
        protected string $nama_tabel = '',
        protected string $kolom_id = '',
        /** @var array<string, bool> */
        protected array $aksi = [],
        /** @var list<array{
         * 0:0|1,
         * 1:string,
         * 2:string,
         * 3:string}> $konfig*/
        protected array $konfig = [],
        protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1],
    ) {
        $this->api_url = (string) getenv('api_URL');
        // Check notifications and set session variable
        // $this->checkNotifications();
    }

    final protected function addBreadcrumb(string $title, string $icon = ''): void
    {
        $this->breadcrumbs[] = [
            'title' => $title,
            'icon' => $icon
        ];
    }

    private function getPostData(): array
    {
        $KOLOM = 2;
        $JENIS = 3;
        $postData = [];
        foreach ($this->konfig as $k) {
            $kolom = $k[$KOLOM];
            $jenis = $k[$JENIS];
            $raw_data = $this->request->getPost($kolom);
            if (in_array($jenis, ['jumlah', 'uang', 'suhu'], true)) {
                $raw_data = floatval($raw_data);
            }
            $postData[$kolom] = $raw_data;
        }
        return $postData;
    }

    final public function tampilData(): string
    {
        /** @var array{'data':array{'data':array}} */
        $hasil = CURL::call('GET', $this->api_path);
        $tabel = $hasil['data']['data'];
        return view('/layouts/data', [
            'judul'       => $this->judul,
            'breadcrumbs' => $this->breadcrumbs,
            'meta_data'   => $this->meta_data,
            'modul_path'  => $this->modul_path,
            'kolom_id'    => $this->kolom_id,
            'konfig'      => $this->konfig,
            'aksi'        => $this->aksi,
            'tabel'       => $tabel,
        ]);
    }
    final public function tampilAudit(): string
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
        ];
        return view('/layouts/audit', [
            'judul'       => 'Audit ' . $this->judul,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
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
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
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
        /** @var array{'data':array{'data':array{'kolom_id':string}}} */
        $hasil = CURL::call('GET', $this->api_path . '/' . $id);
        $baris = $hasil['data']['data'];
        return view('/layouts/tambah_ubah', [
            'judul'       => 'Ubah ' . $this->judul,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
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
        $_response = CURL::call('POST', $this->api_path, $postData);
        return redirect()->to(base_url($this->modul_path))->with('success', 'Berhasil');    
    }
    public function simpanUbah($id)
    {
        $postData = $this->getPostData();
        $_response = CURL::call('PUT', $this->api_path . '/' . $id, $postData);
        return redirect()->to(base_url($this->modul_path))->with('success', $this->judul . ' berhasil diperbarui.');   
        
    }

    final public function hapusData($id)
    {
        $_response = CURL::call('DELETE', $this->api_path . '/' . $id);
        return redirect()->to(base_url($this->modul_path))->with('success', $this->judul . ' berhasil dihapus.');   
        
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
    //         log_message('error', 'cURL Error: ' . $error_message);
    //         return; // Exit method on error
    //     }

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
}
