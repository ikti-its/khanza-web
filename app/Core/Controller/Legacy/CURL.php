<?php
declare(strict_types=1);

namespace App\Core\Controller\Legacy;

use App\Core\Controller\ErrorController;

/** @deprecated "Migrate to ControllerTemplate inheritence" */
final readonly class CURL
{
    /**
     * @param 'GET'|'POST'|'PUT'|'DELETE' $method
     * @return array{data: array|null, kode: int}
     */
    public static function call(string $method, string $path, array|null $data = null): array
    {
        $allowed_methods = ['GET', 'POST', 'PUT', 'DELETE'];
        if (!in_array($method, $allowed_methods, true)) {
            echo ErrorController::renderErrorView(405);
        }

        if (!session()->has('jwt_token')) {
            echo ErrorController::renderErrorView(401);
        }

        /** @var string */
        $token   = session()->get('jwt_token');
        $headers = [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ];

        /** @var string */
        $url      = getenv('api_URL');
        $full_url = $url . $path;
        $ch       = curl_init($full_url);
        assert($ch !== false, "Curl initialization failed for {$full_url}");

        if ($method === 'POST' || $method === 'PUT') {
            $postData = json_encode($data);
            assert($postData !== false, "JSON data format is incorrect for {$full_url}");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: ' . strlen($postData);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Set method
        match ($method) {
            'POST'          => curl_setopt($ch, CURLOPT_POST, true),
            'PUT', 'DELETE' => curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method),
            default         => "Unknown HTTP Method '{$method}'",
        };

        $response = curl_exec($ch);
        assert($response !== true && $response !== false, "Error executing curl for  {$full_url}");

        $http_status_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        /** @var array<string, mixed>|null $return_data */
        $return_data = json_decode($response, true);

        $http_success_codes = [200, 201, 204];
        if (!in_array($http_status_code, $http_success_codes, true)) {
            echo ErrorController::renderErrorView($http_status_code);
        }

        if (json_last_error() !== JSON_ERROR_NONE || !isset($return_data['data'])) {
            echo ErrorController::renderErrorView(status_code: 500);
        }
        return [
            'data' => $return_data,
            'kode' => $http_status_code,
        ];
    }
}
