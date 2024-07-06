<?php

namespace App\Controllers;

use App\Libraries\JWT;

class authController extends BaseController
{

    protected $api_url;

    public function __construct()
    {
        $this->api_url = getenv('api_URL');
    }

    public function index()
    {
        return view('login');
    }


    public function dashboard()
    {

        $data = [
            'title' => 'Dashboard Admin'
        ];

        return  view('/user/dashboard', $data);
    }


    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url("/login"));
    }


    public function login()
    {
        // Check if the form is submitted
        if ($this->request->getPost()) {

            // Define validation rules
            $validationRules = [
                'email' => 'required|valid_email',
                'password' => 'required|min_length[5]'
            ];

            // Set validation messages
            $validationMessages = [
                'email' => [
                    'required' => 'E-mail dibutuhkan',
                    'valid_email' => 'Tolong masukkan alamat email yang benar'
                ],
                'password' => [
                    'required' => 'Password dibutuhkan',
                    'min_length' => 'Password minimal harus 6 digit'
                ]
            ];

            // Validate the input data
            if (!$this->validate($validationRules, $validationMessages)) {
                // Validation failed, return to the login form with errors
                return view('login', [
                    'validation' => $this->validator
                ]);
            }

            $title = 'Dashboard';
            // Get the email and password from the form
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            // Create an array with the login data
            $login_data = [
                'email' => $email,
                'password' => $password
            ];

            // Convert the data to JSON
            $login_data_json = json_encode($login_data);

            // Define the API endpoint URL
            $api_url = $this->api_url . '/auth/login';

            $ch = curl_init($api_url);

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $login_data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($login_data_json)
            ]);

            // Execute the cURL request
            $response = curl_exec($ch);

            // Check the API response
            if ($response) {
                // The API returned a response
                $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($http_status_code === 200) {
                    // Login success
                    $response_data = json_decode($response, true);
                    $token = $response_data['data']['token'];

                    // Store the token securely (in a session variable)
                    session()->set('jwt_token', $token);

                    // URL for fetching akun data
                    $user_details_url = $this->api_url . '/auth';

                    // Set the Authorization header with the JWT token
                    $headers = [
                        'Authorization: Bearer ' . $token,
                        'Content-Type: application/json'
                    ];

                    // Initialize cURL session for user details
                    $user_details_ch = curl_init($user_details_url);
                    curl_setopt($user_details_ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($user_details_ch, CURLOPT_HTTPHEADER, $headers);

                    // Execute the cURL request for user details
                    $user_details_response = curl_exec($user_details_ch);

                    // Check the user details API response
                    if ($user_details_response) {
                        // Decode the JSON response
                        $user_details = json_decode($user_details_response, true);

                        // Store user details in session along with the token
                        session()->set('user_details', $user_details['data']);

                        // Close the cURL session for user details
                        curl_close($user_details_ch);

                        // Check if the user role is 2 or 1
                        if ($user_details['data']['role'] == 2 || $user_details['data']['role'] == 1) {
                            // If the user role is 2 or 1, make another API request
                            $tanggal = date('Y-m-d');
                            $user_specific_url = $this->api_url . '/m/home?tanggal=' . $tanggal;

                            // Initialize cURL session for user specific data
                            $user_specific_ch = curl_init($user_specific_url);
                            curl_setopt($user_specific_ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($user_specific_ch, CURLOPT_HTTPHEADER, $headers);

                            // Execute the cURL request for user specific data
                            $user_specific_response = curl_exec($user_specific_ch);

                            if ($user_specific_response) {
                                // Decode the JSON response
                                $user_specific_data = json_decode($user_specific_response, true);

                                // Store the user specific data in session or handle it as needed
                                session()->set('user_specific_data', $user_specific_data['data']);
                            } else {
                                // Failed to get user specific data
                                return $this->renderErrorView(500, "Failed to retrieve user specific data.");
                            }
                        }

                        // Redirect to user dashboard
                        return redirect()->to('/dashboard')->with('title', $title, 'user_details', $user_details);
                    } else {
                        // Failed to get user details
                        return $this->renderErrorView(500, "Tidak ada respons dari server");
                    }
                } else {
                    // Wrong password
                    return $this->renderErrorView(401, "Email atau password anda salah");
                }
            } else {
                // No response from API
                return $this->renderErrorView(500, "Tidak ada respons dari Server");
            }

            // Close the cURL session
            curl_close($ch);
        }  // If the form was not submitted or any other case where it doesn't validate or process login
        return view('login'); // Return the login view by default
    }
}
