<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Kamar extends BaseController
{
    public function dataKamar()
    {
        $title = 'Data Kamar';
        
        // Check if the user has a valid session with JWT token
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $kamar_url = $this->api_url . '/kamar';  // Adjusted to use /kamar endpoint
        
            // Initialize cURL to fetch Kamar data from Go API
            $ch_kamar = curl_init($kamar_url);
            curl_setopt($ch_kamar, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_kamar, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response_kamar = curl_exec($ch_kamar);
            $http_status_code_kamar = curl_getinfo($ch_kamar, CURLINFO_HTTP_CODE);
            curl_close($ch_kamar);
        
            // Check API response status
            if ($http_status_code_kamar !== 200) {
                return $this->renderErrorView($http_status_code_kamar);
            }
        
            // Decode JSON response
            $kamar_data = json_decode($response_kamar, true);
    
            // dd($kamar_data);

            // Ensure we have valid data
            if (!isset($kamar_data['data'])) {
                return $this->renderErrorView(500);
            }
        
            // Set up breadcrumbs (for UI navigation)
            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Kamar', 'kamar');  // Adjusted breadcrumb title to Kamar
            $breadcrumbs = $this->getBreadcrumbs();
            
            // Ensure we have valid meta data
            if (!isset($kamar_data['meta_data'])) {
                $meta_data = ['page' => 1, 'size' => 10, 'total' => 1]; // Provide default values
            } else {
                $meta_data = $kamar_data['meta_data'];
                $meta_data['total'] = $meta_data['total'] ?? 1; // Set a default value for 'total' if missing
            }
    
            // Return the view with Kamar data
            return view('/admin/kamar/kamar_data', [  // Adjusted the view path to kamar_data
                'kamar_data' => $kamar_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $meta_data ?? ['page' => 1, 'size' => 10] // Ensure meta_data is always set
                
            ]);
        } else {
            // If no JWT token, return unauthorized error
            return $this->renderErrorView(401);
        }
    }
    
    public function tambahKamar()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $title = 'Tambah Kamar';

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Kamar', 'kamar');
            $this->addBreadcrumb('Tambah', 'tambah');

            $breadcrumbs = $this->getBreadcrumbs();

            return view('/admin/kamar/tambah_kamar', [
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitTambahKamar()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            
            // Get data from the form
            $nomor_bed = $this->request->getPost('nomor_bed');
            $kode_kamar = $this->request->getPost('kode_kamar');
            $nama_kamar = $this->request->getPost('nama_kamar');
            $kelas = $this->request->getPost('kelas');
            $tarif_kamar = intval($this->request->getPost('tarif_kamar'));
            $status_kamar = $this->request->getPost('status_kamar');

            // Prepare data to be inserted into PostgreSQL or passed to another system
            $postDataKamar = [
                'nomor_bed' => $nomor_bed,
                'kode_kamar' => $kode_kamar,
                'nama_kamar' => $nama_kamar,
                'kelas' => $kelas,
                'tarif_kamar' => $tarif_kamar,
                'status_kamar' => $status_kamar,
            ];

            // Example cURL to interact with the Go API
            $kamar_url = $this->api_url . '/kamar';  // Change the endpoint to /kamar
        
            $tambah_kamar_JSON = json_encode($postDataKamar);

            $ch_kamar = curl_init($kamar_url);
            curl_setopt($ch_kamar, CURLOPT_POST, 1);
            curl_setopt($ch_kamar, CURLOPT_POSTFIELDS, $tambah_kamar_JSON);
            curl_setopt($ch_kamar, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_kamar, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($tambah_kamar_JSON),
                'Authorization: Bearer ' . $token,
            ]);
            $response_kamar = curl_exec($ch_kamar);
            $http_status_code_kamar = curl_getinfo($ch_kamar, CURLINFO_HTTP_CODE);

            if ($http_status_code_kamar === 201) {
                return redirect()->to(base_url('kamar'));  // Redirect to the Kamar page after success
            } else {
                return $this->renderErrorView($http_status_code_kamar);
            }

            curl_close($ch_kamar);
        } else {
            return $this->renderErrorView(401);
        }
    }

    // Function to show the 'Edit Kamar' page
    public function editKamar($nomorBed)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Edit Kamar';
        $kamar_url = $this->api_url . '/kamar/' . $nomorBed;

        // dd($kamar_url);

        // Make API request to fetch kamar data
        $ch_kamar = curl_init($kamar_url);
        curl_setopt($ch_kamar, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_kamar, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch_kamar);
        $http_status = curl_getinfo($ch_kamar, CURLINFO_HTTP_CODE);
        curl_close($ch_kamar);

        // dd($response);

        // Handle API response
        if ($http_status !== 200) {
            return $this->renderErrorView($http_status);
        }

        $kamar_data = json_decode($response, true);

        // Breadcrumbs setup
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Kamar', 'kamar');
        $this->addBreadcrumb('Edit', 'edit');
        $breadcrumbs = $this->getBreadcrumbs();
            // dd($kamar_data);
        // Return the edit view with kamar data
        return view('/admin/kamar/edit_kamar', [
            'kamar' => $kamar_data['data'],
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

// Function to handle the submission of the 'Edit Kamar' form
    public function submitEditKamar($nomorBed)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $kamar_url = $this->api_url . '/kamar/' . $nomorBed;  // Ensure this URL is correct

            // Get data from the form
            $nomor_bed = $this->request->getPost('nomor_bed');
            $kode_kamar = $this->request->getPost('kode_kamar');
            $nama_kamar = $this->request->getPost('nama_kamar');
            $kelas = $this->request->getPost('kelas');
            $tarif_kamar = intval($this->request->getPost('tarif_kamar'));
            $status_kamar = $this->request->getPost('status_kamar');

            // Validate that required fields are not empty
            if (empty($kode_kamar) || empty($nomor_bed)) {
                return $this->response->setJSON([
                    'code' => 400,
                    'status' => 'Bad Request',
                    'data' => 'Kode Kamar and Nomor Bed are required.'
                ]);
            }

            // Prepare data to be updated
            $postDataKamar = [
                'nomor_bed' => $nomor_bed,
                'kode_kamar' => $kode_kamar,
                'nama_kamar' => $nama_kamar,
                'kelas' => $kelas,
                'tarif_kamar' => $tarif_kamar,
                'status_kamar' => $status_kamar,
            ];

            // cURL setup for PUT request
            $tambah_kamar_JSON = json_encode($postDataKamar);

            $ch_kamar = curl_init($kamar_url);
            curl_setopt($ch_kamar, CURLOPT_CUSTOMREQUEST, "PUT");  // Use PUT for updating
            curl_setopt($ch_kamar, CURLOPT_POSTFIELDS, $tambah_kamar_JSON);  // Send JSON data
            curl_setopt($ch_kamar, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_kamar, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request
            $response_kamar = curl_exec($ch_kamar);
            if(curl_errno($ch_kamar)) {
                log_message('error', 'cURL error: ' . curl_error($ch_kamar));
            }
            $http_status_code_kamar = curl_getinfo($ch_kamar, CURLINFO_HTTP_CODE);
            curl_close($ch_kamar);

            // Log the response for debugging
            log_message('error', 'Response: ' . $response_kamar);
            log_message('error', 'HTTP Status Code: ' . $http_status_code_kamar);

            // Handle the response based on the HTTP status code
            if ($http_status_code_kamar === 200) {
                return redirect()->to(base_url('kamar'))->with('success', 'Kamar berhasil diperbarui.');  // Success message
            } else {
                // Return the error view if update failed
                return $this->renderErrorView($http_status_code_kamar);
            }
        } else {
            return $this->renderErrorView(401);  // Unauthorized error if no JWT token
        }
    }

    public function hapusKamar($nomorBed)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $delete_url = $this->api_url . '/kamar/' . $nomorBed;  // Updated to use /kamar endpoint

            // cURL setup to DELETE the Kamar data
            $ch_delete = curl_init($delete_url);
            curl_setopt($ch_delete, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch_delete, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_delete, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json',
            ]);

            // Execute the DELETE request
            $response = curl_exec($ch_delete);
            $http_status = curl_getinfo($ch_delete, CURLINFO_HTTP_CODE);
            curl_close($ch_delete);

            // Check if the request was successful (200 or 204)
            if ($http_status === 200 || $http_status === 204) {
                return redirect()->to(base_url('kamar'))->with('success', 'Kamar berhasil dihapus.');  // Redirect to Kamar page
            } else {
                return $this->renderErrorView($http_status);  // Render error view if not successful
            }
        } else {
            return $this->renderErrorView(401);  // Unauthorized error if no JWT token
        }
    }

    public function terimaKamar($nomorBed)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Edit Kamar';
        $kamar_url = $this->api_url . '/kamar/' . $nomorBed;

        // dd($kamar_url);

        // Make API request to fetch kamar data
        $ch_kamar = curl_init($kamar_url);
        curl_setopt($ch_kamar, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_kamar, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch_kamar);
        $http_status = curl_getinfo($ch_kamar, CURLINFO_HTTP_CODE);
        curl_close($ch_kamar);

        // dd($response);

        // Handle API response
        if ($http_status !== 200) {
            return $this->renderErrorView($http_status);
        }

        $kamar_data = json_decode($response, true);

        // Breadcrumbs setup
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Kamar', 'kamar');
        $this->addBreadcrumb('Terima', 'terima');
        $breadcrumbs = $this->getBreadcrumbs();
            // dd($kamar_data);
        // Return the edit view with kamar data
        return view('/admin/kamar/terima_kamar', [
            'kamar' => $kamar_data['data'],
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $pendingRequests = $db->table('registrasi')
            ->where('status_kamar', 'belum')
            ->get()
            ->getResultArray();

        $data['count_notif_kamar'] = count($pendingRequests);
        $data['pending_requests'] = $pendingRequests;

        return view('admin/kamar/kamar_data', $data);
    }
}