<?php



// Get date from request
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
    $selectedDate = date('Y-m-d');
}

// Fetch schedule data from API
$scheduleUrl ="$api/schedule?date=" . $selectedDate;
try {
    $scheduleResponse = @file_get_contents($scheduleUrl);
    if ($scheduleResponse === false) {
        $scheduleData = null;
    } else {
        $scheduleData = json_decode($scheduleResponse, true);
    }
} catch (Exception $e) {
    $scheduleData = null;
}

// Helper function for time formatting
function formatTime($time) {
    $dateTime = DateTime::createFromFormat('H:i:s', $time);
    return $dateTime ? $dateTime->format('h:i A') : $time;
}

// Return only the schedule list HTML
if ($scheduleData && isset($scheduleData['data']['scheduledAnimes'])) {
    foreach ($scheduleData['data']['scheduledAnimes'] as $anime) {
        $timeFormatted = formatTime($anime['time']);
        ?>
        <li>
            <a href="/details/<?= $anime['id'] ?>" class="tsl-link">
                <div class="time"><?= $timeFormatted ?></div>
                <div class="film-detail">
                    <h3 class="film-name dynamic-name" data-jname="<?= htmlspecialchars($anime['jname']) ?>">
                        <?= htmlspecialchars($anime['name']) ?>
                    </h3>
                    <div class="fd-play">
                        <button type="button" class="btn btn-sm btn-play">
                            <i class="fas fa-play mr-2"></i>Watch Now
                        </button>
                    </div>
                </div>
            </a>
        </li>
    <?php
    }
} else {
    echo '<li class="no-data">No schedule data available for this date.</li>';
}
?> 
