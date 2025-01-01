<?php
header('Content-Type: application/json');

$episodeId = $_GET['episodeId'] ?? null;

if (!$episodeId) {
    echo json_encode([
        'success' => false,
        'error' => 'No episode ID provided'
    ]);
    exit;
}

// Debug log
error_log("Fetching servers for episode: " . $episodeId);

$api_url = "your-hosted-api.app/api/v2/hianime/episode/servers?animeEpisodeId=" . urlencode($episodeId); //https://github.com/ghoshRitesh12/aniwatch-api

// Debug log
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

// Debug log
error_log("API Response: " . print_r($data, true));

if (!$data || !isset($data['data'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid API response'
    ]);
    exit;
}

// Check for fallback logic for sub servers only
$sub_servers = $data['data']['sub'] ?? [];
$raw_servers = $data['data']['raw'] ?? [];

// Use `raw` servers as a fallback if `sub` is empty
if (empty($sub_servers) && !empty($raw_servers)) {
    $sub_servers = $raw_servers;
}

// Fetch `dub` servers without fallback
$dub_servers = $data['data']['dub'] ?? [];

// Function to replace specific servers with placeholders
function replaceWithPlaceholder($servers) {
    return array_map(function($server) {
        return [
            'serverName' => $server['serverName'],
            'serverId' => $server['serverId']
        ];
    }, $servers);
}

// Format the response to match what the frontend expects
$result = [
    'sub' => replaceWithPlaceholder($sub_servers),
    'dub' => replaceWithPlaceholder($dub_servers)
];

echo json_encode($result);
?>
