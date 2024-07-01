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

        //Check if the form is submitted
        if ($this->request->getPost()) {

            $title = 'Dashboard';
            //Get the username and password from the form
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            //Create an array with the login data
            $login_data = [
                'email' => $email,
                'password' => $password
            ];

            //Convert the data to JSON
            $login_data_json = json_encode($login_data);

            //Define the API endpoint URL
            $api_url = $this->api_url .  '/auth/login';

            $ch = curl_init($api_url);

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $login_data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($login_data_json)
            ]);

            //Execute the cURL request
            $response = curl_exec($ch);

            // Check the API response
            if ($response) {
                // The API returned a response
                // $response contains the API response in JSON format

                // Check if the HTTP status code in the response
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


                        // Check if the user role is 2
                        if ($user_details['data']['role'] == 2 || $user_details['data']['role'] == 1) {
                            // If the user role is 2, make another API request

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
                                echo "Failed to retrieve user specific data.";
                            }
                        }

                        // Redirect to user dashboard
                        return redirect()->to('/dashboard')->with('title', $title, 'user_details', $user_details);
                    } else {
                        // Failed to get user details
                        echo "Failed to retrieve user details.";
                    }
                } elseif ($http_status_code === 401) {
                    // Wrong password
                    echo "Wrong password. Please check your password and try again";
                } elseif ($http_status_code === 404) {
                    // User not found
                    echo "User not found. Please check your username and try again";
                } else {
                    // Login failed
                    echo "Login failed, please try again.";
                }
            }
            // Close the cURL session
            curl_close($ch);
        } else {
            // The form was not submitted
            echo "Please submit the login form.";
        }
    }
}
