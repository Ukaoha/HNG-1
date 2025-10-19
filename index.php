<?php
/**
 * Backend Wizards - Stage 0 Task
 * Dynamic Profile Endpoint with Cat Facts Integration
 *
 * @author Ukaoha Chizoba
 * @email ukaohachizoba6@gmail.com
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/'); 

// Route handler
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $path === '/me') {
    getProfile();
    exit();
} else {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Endpoint not found. Use GET /me'
    ]);
    exit();
}

/**
 * Get user profile with dynamic timestamp and cat fact
 */
function getProfile() {
    // User information (hardcoded as per requirements)
    $email = "ukaohachizoba6@gmail.com";
    $name = "Ukaoha Chizoba";
    $stack = "PHP";

    // Generate current timestamp in ISO 8601 UTC format with milliseconds
    $now = DateTime::createFromFormat('U.u', microtime(true));
    $timestamp = $now->format('Y-m-d\TH:i:s.v\Z');

    // Fetch a fresh cat fact from external API
    $fact = fetchCatFact();

    // Build response according to required schema
    $response = [
        "status" => "success",
        "user" => [
            "email" => $email,
            "name" => $name,
            "stack" => $stack
        ],
        "timestamp" => $timestamp,
        "fact" => $fact
    ];

    // Return JSON response with 200 OK status
    http_response_code(200);
    echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

/**
 * Fetch a random cat fact from external API
 *
 * @return string 
 */
function fetchCatFact() {
    $url = 'https://catfact.ninja/fact';


    $context = stream_context_create([
        'http' => [
            'timeout' => 10, 
            'method' => 'GET',
            'header' => "User-Agent: PHP-Backend-Wizards/1.0\r\n" .
                       "Accept: application/json\r\n"
        ]
    ]);


    $response = @file_get_contents($url, false, $context);

    // Handle failed request
    if ($response === false) {
        error_log("Failed to fetch cat fact from API");
        return "Unable to fetch cat fact at this time. Please try again later.";
    }

    $data = json_decode($response, true);


    if (json_last_error() !== JSON_ERROR_NONE || !isset($data['fact'])) {
        error_log("Invalid JSON response from cat facts API");
        return "Unable to fetch cat fact at this time. Please try again later.";
    }

    return $data['fact'];
}
?>
