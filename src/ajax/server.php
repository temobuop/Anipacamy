<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_config.php');
$episodeParam = $_GET['episodeId'] ?? null;
$api_url = "$zpi/servers/" . $episodeParam;

$response = @file_get_contents($api_url);

if ($response === false) {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch from API',
        
    ]);
    exit;
}

$data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid API response',
        'debug' => [
            'raw_response' => $response,
            'url_called' => $api_url
        ]
    ]);
    exit;
}
if (empty($data['success'])) {
    echo json_encode([
        'success' => false,
        'error' => 'API reported no servers',
        'api_response' => $data
    ]);
    exit;
}
if (empty($data['results'])) {
    echo json_encode([
        'success' => false,
        'error' => 'No servers available',
        'debug' => [
            'api_url' => $api_url,
            'note' => 'API returned success but empty results'
        ]
    ]);
    exit;
}
$response = [
    'success' => true,
    'sub' => [],
    'dub' => []
];
foreach ($data['results'] as $server) {
    $type = $server['type'] ?? 'sub';
    if (in_array($type, ['sub', 'dub'])) {
        $response[$type][] = [
            'serverName' => $server['serverName'] ?? 'Unknown',
            'serverId' => $server['server_id'] ?? '0'
        ];
    }
}
if (empty($response['sub'])) {
    foreach ($data['results'] as $server) {
        if (($server['type'] ?? '') === 'raw') {
            $response['sub'][] = [
                'serverName' => $server['serverName'] ?? 'Unknown',
                'serverId' => $server['server_id'] ?? '0'
            ];
        }
    }
}

echo json_encode($response);
?>