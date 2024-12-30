<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Add connection check
if (!$conn || $conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$userId = $_COOKIE['userID'] ?? null;
$movieId = $data['movieId'] ?? null;
$animeName = $data['animeName'] ?? null;
$poster = $data['poster'] ?? null;
$subCount = $data['subCount'] ?? null;
$dubCount = $data['dubCount'] ?? null;
$episodeNumber = $data['episodeNumber'] ?? null;

if (!$userId || !$movieId || !$animeName || !$episodeNumber) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit();
}

try {
    // Update watch_history table
    $sqlUpdateWatchHistory = "
        INSERT INTO watch_history 
        (user_id, anime_id, anime_name, poster, sub_count, dub_count, episode_number, created_at, updated_at) 
        VALUES 
        (?, ?, ?, ?, ?, ?, ?, NOW(), NOW()) 
        ON DUPLICATE KEY UPDATE 
        anime_name = ?, poster = ?, sub_count = ?, dub_count = ?, episode_number = ?, updated_at = NOW()";

    $stmtUpdateWatchHistory = $conn->prepare($sqlUpdateWatchHistory);

    if (!$stmtUpdateWatchHistory) {
        throw new Exception("Failed to prepare statement for watch_history: " . $conn->error);
    }

    $stmtUpdateWatchHistory->bind_param(
        'isssiiisssii',
        $userId, $movieId, $animeName, $poster, $subCount, $dubCount, $episodeNumber,
        $animeName, $poster, $subCount, $dubCount, $episodeNumber
    );

    if (!$stmtUpdateWatchHistory->execute()) {
        throw new Exception("Error executing watch_history statement: " . $stmtUpdateWatchHistory->error);
    }
    $stmtUpdateWatchHistory->close();

    // Fetch watched episodes
    $sqlFetchWatchedEpisodes = "
        SELECT episodes_watched 
        FROM watched_episode 
        WHERE user_id = ? AND anime_id = ?";
    
    $stmtFetchWatchedEpisodes = $conn->prepare($sqlFetchWatchedEpisodes);

    if (!$stmtFetchWatchedEpisodes) {
        throw new Exception("Failed to prepare statement for fetching watched episodes: " . $conn->error);
    }

    $stmtFetchWatchedEpisodes->bind_param('is', $userId, $movieId);
    $stmtFetchWatchedEpisodes->execute();
    $result = $stmtFetchWatchedEpisodes->get_result();
    $currentEpisodes = $result->fetch_assoc()['episodes_watched'] ?? '';

    // Update watched_episode
    $episodesArray = $currentEpisodes ? explode(',', $currentEpisodes) : [];
    if (!in_array($episodeNumber, $episodesArray)) {
        $episodesArray[] = $episodeNumber;
        sort($episodesArray);
    }

    $updatedEpisodes = implode(',', $episodesArray);

    $sqlUpdateWatchedEpisodes = "
        INSERT INTO watched_episode 
        (user_id, anime_id, episodes_watched, updated_at) 
        VALUES 
        (?, ?, ?, NOW()) 
        ON DUPLICATE KEY UPDATE 
        episodes_watched = ?, updated_at = NOW()";

    $stmtUpdateWatchedEpisodes = $conn->prepare($sqlUpdateWatchedEpisodes);

    if (!$stmtUpdateWatchedEpisodes) {
        throw new Exception("Failed to prepare statement for watched_episode: " . $conn->error);
    }

    $stmtUpdateWatchedEpisodes->bind_param(
        'isss',
        $userId, $movieId, $updatedEpisodes, $updatedEpisodes
    );

    if (!$stmtUpdateWatchedEpisodes->execute()) {
        throw new Exception("Error executing watched_episode statement: " . $stmtUpdateWatchedEpisodes->error);
    }
    $stmtUpdateWatchedEpisodes->close();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
