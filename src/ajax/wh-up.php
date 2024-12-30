<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/_config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/src/user/api/update_anilist.php');


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
$anilistId = $data['anilistId'] ?? null;
$episodeNumber = $data['episodeNumber'] ?? null;

if (!$userId || !$movieId || !$animeName || !$episodeNumber) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

try {
    // Update watch_history table
    $sqlUpdateWatchHistory = "
        INSERT INTO watch_history 
        (user_id, anime_id, anime_name, poster, sub_count, dub_count, anilist_id, episode_number, created_at, updated_at) 
        VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()) 
        ON DUPLICATE KEY UPDATE 
        anime_name = ?, poster = ?, sub_count = ?, dub_count = ?, anilist_id = ?, episode_number = ?, updated_at = NOW()";

    $stmtUpdateWatchHistory = $conn->prepare($sqlUpdateWatchHistory);

    if (!$stmtUpdateWatchHistory) {
        throw new Exception("Failed to prepare statement for watch_history: " . $conn->error);
    }

    $stmtUpdateWatchHistory->bind_param(
        'isssiiisssiiis',
        $userId,           // 1: user_id
        $movieId,         // 2: anime_id
        $animeName,       // 3: anime_name
        $poster,          // 4: poster
        $subCount,        // 5: sub_count
        $dubCount,        // 6: dub_count
        $anilistId,       // 7: anilist_id
        $episodeNumber,   // 8: episode_number
        $animeName,       // 9: anime_name (for update)
        $poster,          // 10: poster (for update)
        $subCount,        // 11: sub_count (for update)
        $dubCount,        // 12: dub_count (for update)
        $anilistId,       // 13: anilist_id (for update)
        $episodeNumber    // 14: episode_number (for update)
    );

    if (!$stmtUpdateWatchHistory->execute()) {
        throw new Exception("Error executing watch_history statement: " . $stmtUpdateWatchHistory->error);
    }
    $stmtUpdateWatchHistory->close();

    // Check if there's an existing entry for the user and anime
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

    // If no existing entry, insert new data, otherwise update
    $episodesArray = $currentEpisodes ? explode(',', $currentEpisodes) : [];

    // If episode not already in list, add and sort
    if (!in_array($episodeNumber, $episodesArray)) {
        $episodesArray[] = $episodeNumber;
        sort($episodesArray);
    }

    $updatedEpisodes = implode(',', $episodesArray);

    // Check if entry already exists, update if so
    $sqlUpdateWatchedEpisodes = "
        INSERT INTO watched_episode 
        (user_id, anime_id, anilist_id, episodes_watched, updated_at) 
        VALUES 
        (?, ?, ?, ?, NOW()) 
        ON DUPLICATE KEY UPDATE 
        episodes_watched = ?, updated_at = NOW()";

    $stmtUpdateWatchedEpisodes = $conn->prepare($sqlUpdateWatchedEpisodes);

    if (!$stmtUpdateWatchedEpisodes) {
        throw new Exception("Failed to prepare statement for watched_episode: " . $conn->error);
    }

    $stmtUpdateWatchedEpisodes->bind_param(
        'issss',
        $userId,          // 1: user_id
        $movieId,         // 2: anime_id
        $anilistId,       // 3: anilist_id
        $updatedEpisodes, // 4: episodes_watched
        $updatedEpisodes  // 5: episodes_watched (for update)
    );

    if (!$stmtUpdateWatchedEpisodes->execute()) {
        throw new Exception("Error executing watched_episode statement: " . $stmtUpdateWatchedEpisodes->error);
    }
    $stmtUpdateWatchedEpisodes->close();

    // Update AniList if we have an AniList ID
    if ($anilistId) {
        $anilistUpdate = updateAniListProgress($userId, $anilistId, $episodeNumber, $conn);
        if (!$anilistUpdate) {
            error_log("Failed to update AniList progress for user $userId");
        }
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>