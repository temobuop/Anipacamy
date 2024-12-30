<?php
session_start();
if (!isset($_COOKIE['userID'])) {
    header('Location: login.php');
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/_config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/src/user/api/update_anilist.php');

// Add connection check
if (!$conn || $conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$data = json_decode(file_get_contents('php://input'), true);
$type = $data['type'] ?? null;
$movieId = $data['movieId'] ?? null;
$animeName = $data['animeName'] ?? null;
$poster = $data['poster'] ?? null;
$subCount = $data['subCount'] ?? null;
$dubCount = $data['dubCount'] ?? null;
$animeType = $data['animeType'] ?? null;
$anilistId = $data['anilistId'] ?? null;

if (!$type || !$movieId || !$animeName) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}


try {
    $sql = "INSERT INTO watchlist (user_id, anime_id, anime_name, type, poster, sub_count, dub_count, anime_type, anilist_id, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()) 
            ON DUPLICATE KEY UPDATE anime_name = ?, type = ?, poster = ?, sub_count = ?, dub_count = ?, anime_type = ?, anilist_id = ?, updated_at = NOW()";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param(
            'issssiiissssisss',
            $_COOKIE['userID'], 
            $movieId,           
            $animeName,        
            $type,             
            $poster,           
            $subCount,         
            $dubCount,         
            $animeType,        
            $anilistId,        
            $animeName,        
            $type,             
            $poster,           
            $subCount,         
            $dubCount,         
            $animeType,        
            $anilistId         
        );

        if ($stmt->execute()) {
            if ($anilistId) {
                $userId = $_COOKIE['userID'];
             
                $anilistUpdate = updateAniListProgress($userId, $anilistId, 0, $conn, 'PLANNING');
                if (!$anilistUpdate) {
                    error_log("Failed to update AniList watchlist for user $userId");
                }
            }
            
            echo json_encode(['success' => true, 'message' => 'Watchlist updated successfully']);
        } else {
            throw new Exception("SQL execution failed: " . $stmt->error);
        }

        $stmt->close();
    } else {

        error_log("Error preparing statement: " . $conn->error);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error updating watchlist: ' . $e->getMessage()
    ]);
    exit();
}
?>