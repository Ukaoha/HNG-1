<?php
/**
 * Backend Wizards - Stage 0 Task
 * Dynamic Profile Endpoint with Cat Facts Integration
 * 
 * @author Ukaoha Chizoba
 * @email ukaohachizoba6@gmail.com
 */

// Set headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get the path
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/');

// Route handler
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($path === '/me' || $path === '')) {
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
    // User information
    $email = "ukaohachizoba6@gmail.com";
    $name = "Ukaoha Chizoba";
    $stack = "PHP/Laravel";
    
    // Generate current timestamp in ISO 8601 UTC format
    $timestamp = gmdate('Y-m-d\TH:i:s.v\Z');
    
    // Fetch a fresh cat fact from external API
    $fact = fetchCatFact();
    
    // Build response
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
 * @return string Cat fact or fallback message
 */
function fetchCatFact() {
    $url = 'https://catfact.ninja/fact';
    
    // Configure stream context with timeout
    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'method' => 'GET',
            'header' => "User-Agent: PHP-Backend-Wizards/1.0\r\n" .
                       "Accept: application/json\r\n"
        ]
    ]);
    
    // Attempt to fetch cat fact
    $response = @file_get_contents($url, false, $context);
    
    // Handle failed request
    if ($response === false) {
        error_log("Failed to fetch cat fact from API");
        return "Cats are amazing creatures!";
    }
    
    // Parse JSON response
    $data = json_decode($response, true);
    
    // Validate response structure
    if (json_last_error() !== JSON_ERROR_NONE || !isset($data['fact'])) {
        error_log("Invalid JSON response from cat facts API");
        return "Cats are amazing creatures!";
    }
    
    return $data['fact'];
}
?>