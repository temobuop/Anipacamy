<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/_config.php'); 
header('Content-Type: application/json');

$episodeId = $_GET['episodeId'] ?? null;

if (!$episodeId) {
    echo json_encode([
        'success' => false,
        'error' => 'No episode ID provided'
    ]);
    exit;
}


error_log("Fetching servers for episode: " . $episodeId);

$api_url = "$api/episode/servers?animeEpisodeId=" . urlencode($episodeId);

error_log("API URL: " . $api_url);

$response = file_get_contents($api_url);

if ($response === false) {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch server data'
    ]);
    exit;
}

$data = json_decode($response, true);

error_log("API Response: " . print_r($data, true));

if (!$data || !isset($data['data'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid API response'
    ]);
    exit;
}


$sub_servers = $data['data']['sub'] ?? [];
$raw_servers = $data['data']['raw'] ?? [];

if (empty($sub_servers) && !empty($raw_servers)) {
    $sub_servers = $raw_servers;
}

$dub_servers = $data['data']['dub'] ?? [];


function replaceWithPlaceholder($servers) {
    return array_map(function($server) {
        return [
            'serverName' => $server['serverName'],
            'serverId' => $server['serverId']
        ];
    }, $servers);
}

$result = [
    'sub' => replaceWithPlaceholder($sub_servers),
    'dub' => replaceWithPlaceholder($dub_servers)
];

echo json_encode($result);
?>
