<?php

require_once('src/component/qtip.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$urlPath = $_SERVER['REQUEST_URI'];

$streaming = ltrim($urlPath, '/play/');

$parts = explode('?', $streaming);
$animeId = $parts[0];

parse_str($parts[1] ?? '', $queryParams);
$episodeId = $queryParams['ep'] ?? null;
$animeData = fetchAnimeData($animeId);

$episodelistUrl = "$api/anime/$animeId/episodes";
$episodelistResponse = file_get_contents($episodelistUrl);
$episodelistData = json_decode($episodelistResponse, true);

$episodelist = [];

if (!empty($episodelistData['success']) && 
    $episodelistData['success'] === true && 
    !empty($episodelistData['data']['episodes'])) {
    $episodelist = $episodelistData['data']['episodes'];
}

usort($episodelist, function($a, $b) {
    return $a['number'] - $b['number'];
});

$totalEpisodes = count($episodelist);

if (!$animeData) {
    echo "Anime data not found.";
    exit;
}

$parts = parse_url($_SERVER['REQUEST_URI']);
$page_url = explode('/', $parts['path']);
$url = end($page_url);

$pageID = $url;

if (!isset($_SESSION['viewed_pages'])) {
    $_SESSION['viewed_pages'] = [];
}
$counter = 0; 
if (!in_array($pageID, $_SESSION['viewed_pages'])) {
    $query = mysqli_query($conn, "SELECT * FROM `pageview` WHERE pageID = '$pageID'");
    if (!$query) {
        echo "Database query failed: " . mysqli_error($conn);
        exit;
    }
    $rows = mysqli_fetch_array($query);
    $counter = $rows['totalview'] ?? 0; 
    $id = $rows['id'] ?? null;
    if ($counter === 0) {
        $counter = 1;
        $insertQuery = mysqli_query($conn, "INSERT INTO `pageview` (pageID, totalview, like_count, dislike_count, animeID) VALUES('$pageID', '$counter', '1', '0', '$animeId')");       
        if (!$insertQuery) {
            echo "Failed to insert pageview count: " . mysqli_error($conn);
            exit;
        }      
    } else {
        $counter++;
        $updateQuery = mysqli_query($conn, "UPDATE `pageview` SET totalview = '$counter' WHERE pageID = '$pageID'");
        if (!$updateQuery) {
            echo "Failed to update pageview count: " . mysqli_error($conn);
            exit;
        }
    }
    $_SESSION['viewed_pages'][] = $pageID;
}

$like_count = $rows['like_count'] ?? 0;
$dislike_count = $rows['dislike_count'] ?? 0;
$totalVotes = $like_count + $dislike_count;

?>
