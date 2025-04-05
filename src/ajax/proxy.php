<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

// Get current host
$currentHost = $_SERVER['HTTP_HOST'];
$allowedDomain = $currentHost;

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$websiteUrl = $protocol . "://" . $_SERVER['HTTP_HOST'];

// Get request origin (check both HTTP_ORIGIN and HTTP_REFERER)
$origin = $_SERVER['HTTP_ORIGIN'] ?? ($_SERVER['HTTP_REFERER'] ?? '');

// Normalize origin for comparison
$originHost = parse_url($origin, PHP_URL_HOST);

// Check if the origin is allowed
if ($originHost !== $allowedDomain && $originHost !== 'localhost') {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(["error" => "Access denied, domain not allowed."]);
    exit;
}

// Allow CORS
header("Access-Control-Allow-Origin: $origin");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Allowed file extensions
$allowedExtensions = [
    'ts', 'm3u8', 'mpd',
    'jpg', 'jpeg', 'png', 'gif', 'webp', 'ico', 'svg',
    'css', 'js', 'html', 'woff', 'woff2', 'ttf', 'otf',
    'txt', 'xml', 'json', 'vtt', 'srt'
];

// Validate URL parameter
if (!isset($_GET['url']) || empty($_GET['url'])) {
    http_response_code(400);
    echo json_encode(["error" => "URL parameter is required"]);
    exit;
}

$url = trim($_GET['url']);

// Security: Validate URL
if (!filter_var($url, FILTER_VALIDATE_URL) || preg_match('/^(file|php|data):/', $url)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid or blocked URL"]);
    exit;
}

$extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));

if (!in_array($extension, $allowedExtensions)) {
    http_response_code(403);
    echo json_encode(["error" => "File type not allowed"]);
    exit;
}

// Fetch content
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Referer: https://megacloud.club/",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)",
    "Accept: */*"
]);

$response = curl_exec($ch);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Handle errors
if ($response === false || $httpCode !== 200) {
    http_response_code($httpCode !== 200 ? $httpCode : 500);
    echo json_encode(["error" => "Failed to fetch resource", "status" => $httpCode]);
    exit;
}

// TS files
if ($extension === 'ts') {
    header("Content-Type: video/mp2t");
    header("Cache-Control: max-age=3600, public");
    echo $response;
    exit;
}

if (in_array($extension, ['m3u8', 'mpd'])) {
    $baseUrl = preg_replace('/[^\/]+$/', '', $url);

    // Use $websiteUrl correctly by importing it into the closure
    $modifiedContent = preg_replace_callback(
        '/(.*?\.(m3u8|ts|mpd|jpg|jpeg|png|webp|html|js|css|ico|txt|gif|svg|xml|json|vtt|srt|woff|woff2|ttf|otf))/',
        function ($matches) use ($baseUrl, $websiteUrl) {
            $absoluteUrl = strpos($matches[1], 'http') === 0
                ? $matches[1]
                : $baseUrl . ltrim($matches[1], '/');

            return $websiteUrl . "/src/ajax/proxy.php?url=" . urlencode($absoluteUrl);
        },
        $response
    );

    // Optional cleanup of audio lines
    $filteredContent = preg_replace('/#EXT-X-MEDIA:TYPE=AUDIO[^\r\n]*/', '', $modifiedContent);

    header("Content-Type: " . ($extension === 'm3u8' ? "application/vnd.apple.mpegurl" : "application/dash+xml"));
    header("Cache-Control: max-age=3600, public");
    echo $filteredContent;
    exit;
}


// Fonts and other files
$mimeTypes = [
    'srt' => 'text/plain; charset=utf-8',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
    'ttf' => 'font/ttf',
    'otf' => 'font/otf'
];

if (isset($mimeTypes[$extension])) {
    header("Content-Type: {$mimeTypes[$extension]}");
} else {
    header("Content-Type: $contentType");
}
header("Cache-Control: max-age=3600, public");
echo $response;
?>
