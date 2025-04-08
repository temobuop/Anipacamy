<?php

require_once('src/component/anime/qtip.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$urlPath = $_SERVER['REQUEST_URI'];

$streaming = ltrim($urlPath, '/watch/');

$parts = explode('?', $streaming);
$animeId = $parts[0];

parse_str($parts[1] ?? '', $queryParams);
$episodeId = $queryParams['ep'] ?? null;
$animeData = fetchAnimeData($animeId);

$episodelistUrl = "$zpi/episodes/$animeId";
$episodelistResponse = file_get_contents($episodelistUrl);
$episodelistData = json_decode($episodelistResponse, true);

$episodelist = [];

if (!empty($episodelistData['success']) && 
    $episodelistData['success'] === true && 
    !empty($episodelistData['results']['episodes'])) {
    $episodelist = $episodelistData['results']['episodes'];
}

usort($episodelist, function($a, $b) {
    return $a['episode_no'] - $b['episode_no'];
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



<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Watch <?= htmlspecialchars($animeData['title']) ?> on <?= htmlspecialchars($websiteTitle) ?></title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="title" content="Watch <?= htmlspecialchars($animeData['title']) ?> on <?= htmlspecialchars($websiteTitle) ?>">
    <meta name="description" content="<?= htmlspecialchars(substr($animeData['overview'], 0, 150)) ?> ... at <?= htmlspecialchars($websiteUrl) ?>">
    
    <meta name="charset" content="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="robots" content="index, follow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Language" content="en">
    <meta property="og:title" content="Watch <?= htmlspecialchars($episode['title']) ?> on <?= htmlspecialchars($websiteTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars(substr($animeData['overview'], 0, 150)) ?> ... at <?= htmlspecialchars($websiteUrl) ?>">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= htmlspecialchars($websiteTitle) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($websiteUrl) ?>/anime/<?= htmlspecialchars($url) ?>">
    <meta itemprop="image" content="<?= htmlspecialchars($animeData['poster']) ?>">



    <meta property="twitter:title" content="Watch on <?= htmlspecialchars($websiteTitle) ?>">
    <meta property="twitter:description" content="<?= htmlspecialchars(substr($animeData['overview'], 0, 150)) ?> ... at <?= htmlspecialchars($websiteUrl) ?>">
    <meta property="twitter:url" content="<?= htmlspecialchars($websiteUrl) ?>/anime/<?= htmlspecialchars($url) ?>">
    <meta property="twitter:card" content="summary">
    <meta name="apple-mobile-web-app-status-bar" content="#202125">
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-63430163bc99824a"></script>
    <meta name="theme-color" content="#202125">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" type="text/css">
    <link rel="apple-touch-icon" href="<?= htmlspecialchars($websiteUrl) ?>/favicon.png?v=<?= htmlspecialchars($version) ?>" />
    <link rel="shortcut icon" href="<?= htmlspecialchars($websiteUrl) ?>/favicon.png?v=<?= htmlspecialchars($version) ?>" type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?= htmlspecialchars($websiteUrl) ?>/public/logo/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= htmlspecialchars($websiteUrl) ?>/public/logo/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= htmlspecialchars($websiteUrl) ?>/public/logo/favicon-16x16.png">
    <link rel="mask-icon" href="<?= htmlspecialchars($websiteUrl) ?>/public/logo/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="icon" sizes="192x192" href="<?= htmlspecialchars($websiteUrl) ?>/public/logo/touch-icon-192x192.png?v=<?= htmlspecialchars($version) ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($websiteUrl) ?>/src/assets/css/styles.min.css?v=<?= htmlspecialchars($version) ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($websiteUrl) ?>/src/assets/css/min.css?v=<?= htmlspecialchars($version) ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($websiteUrl) ?>/src/assets/css/new.css?v=<?= htmlspecialchars($version) ?>">
    
    <script>
    setTimeout(function() {
        const cssFiles = [
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css'
        ];

        const firstLink = document.getElementsByTagName('link')[0];

        cssFiles.forEach(file => {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = `${file}?v=<?= htmlspecialchars($version) ?>`;
            link.type = 'text/css';
            firstLink.parentNode.insertBefore(link, firstLink);
        });
    }, 500);
    </script>

    <noscript>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" />
    </noscript>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-R34F2GCSBW');
    </script>
 
    <link rel="stylesheet" href="<?= htmlspecialchars($websiteUrl) ?>/src/assets/css/search.css">
    <script src="<?= htmlspecialchars($websiteUrl) ?>/src/assets/js/search.js"></script>
    <style>
        .pizza{ margin: 2rem auto; width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; padding: 1.5rem; border-radius: .6rem; text-align: center; color: #000; font-size: 16px; font-weight: 400; background-color: #FAACA8; background-image: linear-gradient(19deg, #FAACA8 0%, #DDD6F3 100%);}
        .pizza a{ color: #000; font-weight: 500;text-shadow: 0 1px 0 #fff;}
        .pizza a:hover{ text-shadow: 0 1px 3px #fff;}
        .pizza-x{ max-width: 800px; width: calc(100% - 32px); min-height: 90px;}
        .pizza-y{ max-width: 400px; min-height: 300px;}
        .pizza .in-text{ font-size: 1.5em; font-weight: 600;}
        .pizza .in-contact{ font-size: 1em;}
        @media screen and (max-width: 479px){
            .pizza{ font-size: 13px; gap: .6rem}
            .pizza-y{ min-height: 200px;}
        }




    </style>



<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.2/dist/index.min.js"></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=67521dcc10699f0019237fbb&product=inline-share-buttons&source=platform" async="async"></script>


    <script type="text/javascript" src="<?= htmlspecialchars($websiteUrl) ?>/src/assets/js/play.js"></script>

</head>

<body data-page="movie_watch">
    <div id="sidebar_menu_bg"></div>
    <div id="wrapper" data-page="movie_watch">
        <?php include('src/component/header.php'); ?>
        <div class="clearfix"></div>
        <div id="main-wrapper" class="layout-page layout-page-detail layout-page-watchtv">
            <div id="ani_detail">
                <div class="ani_detail-stage">
                    <div class="container">
                        <div class="anis-cover-wrap">
                            <div class="anis-cover" style="background-image: url('<?= htmlspecialchars($animeData['poster']) ?>')"></div>
                        </div>
                        <div class="anis-watch-wrap">
                            <div class="prebreadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/home" title="Home">Home</a></li>
                                        <li class="breadcrumb-item"><a href="/tv">TV</a></li>
                                        <li class="breadcrumb-item dynamic-name active" data-jname="<?= htmlspecialchars($animeData['title']) ?>"><?= htmlspecialchars($animeData['title']) ?></li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="anis-watch anis-watch-tv">
                                <div class="watch-player">
                                    <div class="player-frame">
                                        <div class="loading-relative loading-box" id="embed-loading">
                                            <div class="loading">
                                                <div class="span1"></div>
                                                <div class="span2"></div>
                                                <div class="span3"></div>
                                            </div>
                                        </div>
                               
                                      <iframe id="iframe-embed" src="" frameborder="0" referrerpolicy="strict-origin" allow="autoplay; fullscreen; geolocation; display-capture; picture-in-picture" webkitallowfullscreen mozallowfullscreen></iframe> 


                                    </div>
                                    <div class="player-controls">
                                            <div class="pc-item pc-resize">
                                                <a href="javascript:;" id="media-resize" class="btn btn-sm" onclick="toggleTheaterMode()"><i class="fas fa-expand mr-1"></i>Expand</a>
                                            </div>
                                            
                                            <div class="pc-item pc-toggle pc-autoplay">
                                                <div class="toggle-basic quick-settings on" data-option="auto_play">
                                                    <span class="tb-name">Auto Play</span>
                                                    <span class="tb-result"></span>
                                                </div>
                                            </div>
                                            <div class="pc-item pc-toggle pc-autonext">
                                                <div class="toggle-basic quick-settings on" data-option="auto_next">
                                                    <span class="tb-name">Auto Next</span>
                                                    <span class="tb-result"></span>
                                                </div>
                                            </div>
                                            <div class="pc-item pc-toggle pc-autoskip">
                                                <div class="toggle-basic quick-settings on" data-option="auto_skip">
                                                    <span class="tb-name">Auto Skip</span>
                                                    <span class="tb-result"></span>
                                                </div>
                                            </div>
                                            <div class="pc-right">
                                                <div class="pc-item pc-control block-prev" style="display:block;">
                                                    <a class="btn btn-sm btn-prev" href="javascript:;" onclick="prevEpisode()"><i class="fas fa-backward mr-2"></i>Prev</a>
                                                </div>
                                                <div class="pc-item pc-control block-next" style="display:block;">
                                                    <a id="btn-next-main" class="btn btn-sm btn-next" href="javascript:;" onclick="nextEpisode()">Next<i class="fas fa-forward ml-2"></i></a>
                                                </div>
                                                <div class="pc-item pc-fav" id="watch-list-content">
                                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-sm dropdown-toggle">
                                                        <i class="fas fa-plus mr-2"></i>Add to List
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-model dropdown-menu-normal" aria-labelledby="ssc-list">
                                                        <a class="wl-item dropdown-item" data-type="1" data-movieid="<?= htmlspecialchars($animeData['id']); ?>" data-page="watch" href="javascript:;">Watching</a>
                                                        <a class="wl-item dropdown-item" data-type="2" data-movieid="<?= htmlspecialchars($animeData['id']); ?>" data-page="watch" href="javascript:;">On-Hold</a>
                                                        <a class="wl-item dropdown-item" data-type="3" data-movieid="<?= htmlspecialchars($animeData['id']); ?>" data-page="watch" href="javascript:;">Plan to watch</a>
                                                        <a class="wl-item dropdown-item" data-type="4" data-movieid="<?= htmlspecialchars($animeData['id']); ?>" data-page="watch" href="javascript:;">Dropped</a>
                                                        <a class="wl-item dropdown-item" data-type="5" data-movieid="<?= htmlspecialchars($animeData['id']); ?>" data-page="watch" href="javascript:;">Completed</a>
                                                    </div>
                                                    <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    const dropdownToggle = document.querySelector('#watch-list-content .dropdown-toggle');
                                                    const dropdownMenu = document.querySelector('#watch-list-content .dropdown-menu');
                                                    if (dropdownToggle) {
                                                        dropdownToggle.addEventListener('click', function(e) {
                                                            e.preventDefault();
                                                            e.stopPropagation();
                                                            if (dropdownMenu) {
                                                                dropdownMenu.classList.toggle('show');
                                                            }
                                                        });
                                                    }
                                                    const watchlistItems = document.querySelectorAll('.wl-item');
                                                    watchlistItems.forEach(item => {
                                                        item.addEventListener('click', function(event) {
                                                            event.preventDefault();
                                                            event.stopPropagation();
                                                            
                                                            console.log('Watchlist item clicked');
                                                            
                                                            const type = this.getAttribute('data-type');
                                                            const movieId = this.getAttribute('data-movieid');
                                                            const animeName = document.querySelector('.film-name')?.textContent?.trim() || '';
                                                            const poster = '<?= htmlspecialchars($animeData['poster']) ?>';
                                                            const subCount = <?= htmlspecialchars($animeData['subEp'] ?? 0) ?>;
                                                            const dubCount = <?= htmlspecialchars($animeData['dubEp'] ?? 0) ?>;
                                                            const animeType = '<?= htmlspecialchars($animeData['showType']) ?>';
                                                            const anilistId = '<?= htmlspecialchars($animeData['anilistId'] ?? '') ?>';

                                                            const data = {
                                                                type: type,
                                                                movieId: movieId,
                                                                animeName: animeName,
                                                                poster: poster,
                                                                subCount: subCount,
                                                                dubCount: dubCount,
                                                                animeType: animeType,
                                                                anilistId: anilistId
                                                            };
                                                            console.log('Sending data:', data); 
                                                            fetch('/src/ajax/wl-up.php', {
                                                                method: 'POST',
                                                                headers: {
                                                                    'Content-Type': 'application/json',
                                                                },
                                                                body: JSON.stringify(data)
                                                            })
                                                            .then(response => response.json())
                                                            .then(result => {
                                                                if (result.success) {
                                                                
                                                                    dropdownToggle.innerHTML = `<i class="fas fa-check mr-2"></i>${this.textContent}`;
                                                                    alert(result.message);
                                                                } else {
                                                                    alert('Error: ' + result.message);
                                                                }
                                                            
                                                                dropdownMenu.classList.remove('show');
                                                            })
                                                            .catch(error => {
                                                                console.error('Error uploading to watchlist:', error);
                                                                alert('An error occurred while updating the watchlist.');
                                                            });
                                                        });
                                                    });
                                                    document.addEventListener('click', function(e) {
                                                        if (!e.target.closest('#watch-list-content')) {
                                                            if (dropdownMenu) {
                                                                dropdownMenu.classList.remove('show');
                                                            }
                                                        }
                                                    });
                                                });
                                                </script>
                                                </div>
                                            </div>
                                          
                                            <div class="clearfix"></div>
                                        </div>           
                                </div>
                                <div class="player-servers">
                                    <div id="servers-content">
                                        <div class="ps_-status">
                                            <div class="content">
                                                <div class="server-notice"><strong>Please select the <b> Episode you want to watch</b></strong> Click on the servers manually in case of error.</div>
                                            </div>
                                        </div>
                                        <div class="ps_-block ps_-block-sub servers-mixed">
                                            <div class="ps__-title"><i class="fa-regular fa-closed-captioning"></i> SUB:</div>
                                            <div class="ps__-list">
                                                <!-- SUB servers will be loaded here dynamically -->
                                                <div class="loading-relative loading-box">
                                                    <div class="loading">
                                                        <div class="span1"></div>
                                                        <div class="span2"></div>
                                                        <div class="span3"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ps_-block ps_-block-dub servers-mixed">
                                            <div class="ps__-title"><i class="fa-solid fa-microphone-lines"></i> DUB:</div>
                                            <div class="ps__-list">
                                                <!-- DUB servers will be loaded here dynamically -->
                                                <div class="loading-relative loading-box">
                                                    <div class="loading">
                                                        <div class="span1"></div>
                                                        <div class="span2"></div>
                                                        <div class="span3"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div id="source-guide"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="schedule-alert" id="schedule-alert" style="display: none;">
                                <div class="alert small">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                                <span class="icon-16 mr-1">🚀</span> Estimated the next episode will come at
                                <span data-value="" id="schedule-date">Loading...</span>
                                <span id="countdown"></span> <!-- Countdown will appear here -->
                            </div>
                            </div>

                            <script>
                                const animeId = <?= json_encode($animeData['id']) ?>; // Assuming $animeId is available
                                fetch(`<?= $zpi ?>/schedule/${animeId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            const scheduleDate = new Date(data.results.nextEpisodeSchedule);
                                            scheduleDate.setHours(scheduleDate.getHours() + 6); // Add 6 hours delay
                                            document.getElementById('schedule-date').textContent = scheduleDate.toLocaleString();
                                            document.getElementById('schedule-date').setAttribute('data-value', scheduleDate.toISOString());
                                            // Show the schedule alert
                                            document.getElementById('schedule-alert').style.display = 'block';
                                            // Start the countdown
                                            startCountdown(scheduleDate);
                                        } else {
                                            document.getElementById('schedule-date').textContent = 'No schedule available. Check back later!';
                                            document.getElementById('schedule-date').style.color = 'orange'; // Highlight the message
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error fetching schedule:', error);
                                        document.getElementById('schedule-date').textContent =
                                            'Unable to load schedule. Please try again later.';
                                        document.getElementById('schedule-date').style.color = 'red'; // Highlight the error message
                                    });

                                function startCountdown(targetDate) {
                                    const countdownElement = document.getElementById('countdown');

                                    function updateCountdown() {
                                        const now = new Date();
                                        const timeDifference = targetDate - now;
                                        if (timeDifference > 0) {
                                            const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                                            const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                            const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                                            const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);
                                            countdownElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                                        } else {
                                            countdownElement.textContent = 'The episode is available!';
                                            clearInterval(interval);
                                        }
                                    }
                                    // Update the countdown every second
                                    const interval = setInterval(updateCountdown, 1000);
                                    updateCountdown(); // Initial call to set the countdown immediately
                                }
                            </script>

                                <?php if (!empty($animeData['season'])): ?>
                                <div class="other-season">
                                    <div class="inner">
                                        <div class="os-title">Watch more seasons of this anime</div>
                                        <div class="os-list">                                            
                                            <?php foreach ($animeData['season'] as $season): ?>
                                                <a href="<?= htmlspecialchars($websiteUrl) ?>/details/<?= htmlspecialchars($season['id']) ?>" class="os-item <?= $season['id'] === $animeId ? 'active' : '' ?>" title="<?= htmlspecialchars($season['title']) ?>">
                                                    <div class="title"><?= htmlspecialchars($season['name']) ?></div>
                                                    <div class="season-poster" style="background-image: url(<?= htmlspecialchars($season['poster']) ?>);"></div>
                                                </a>
                                            <?php endforeach; ?>                                       
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <?php endif; ?>
                                <div id="episodes-content">
                                    <div class="seasons-block seasons-block-max">
                                        <div id="detail-ss-list" class="detail-seasons">
                                            <div class="detail-infor-content">
                                                <div class="ss-choice">
                                                    <div class="ssc-list">
                                                        <div id="ssc-list" class="ssc-button">
                                                            <div class="ssc-label">List of Episodes:

                                                            <?php if (count($episodelist) > 24): ?>
                                                            <select id="episode-range" class="form-control" style="width: 150px; display: inline-block; margin-left: 10px;">
                                                                <?php 
                                                                $totalRanges = ceil(count($episodelist) / 100);
                                                                for ($i = 0; $i < $totalRanges; $i++) {
                                                                    $start = ($i * 100) + 1;
                                                                    $end = min(($i + 1) * 100, count($episodelist));
                                                                    echo "<option value='$i'>EPS. " . sprintf('%03d', $start) . "-" . sprintf('%03d', $end) . "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                            <?php endif; ?>


                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="ssc-quick"></div>
                                                </div>
                                                
                                                <?php
                                                $chunkedEpisodes = array_chunk($episodelist, 100);
                                                foreach ($chunkedEpisodes as $index => $chunk):
                                                ?>
                                                <div id="episodes-page-<?= $index + 1 ?>" class="ss-list <?= count($episodelist) > 24 ? 'ss-list-min' : '' ?>" style="display: <?= $index === 0 ? 'block' : 'none' ?>;">
                                                    <?php foreach ($chunk as $episode): ?>
                                                        <?php 
                                                        $isFiller = isset($episode['filler']) && $episode['filler'] === true;
                                                        $fillerClass = $isFiller ? 'ssl-item-filler' : '';
                                                        ?>
                                                        <a class="ssl-item ep-item <?= $fillerClass ?>" 
                                                           href="javascript:void(0);" 
                                                           data-number="<?= htmlspecialchars($episode['episode_no']) ?>" 
                                                           data-id="<?= htmlspecialchars($episode['id']) ?>"
                                                           <?php if ($isFiller): ?>data-toggle="tooltip" title="Filler Episode"<?php endif; ?>>
                                                            <div class="ssli-order"><?= htmlspecialchars($episode['episode_no']) ?></div>
                                                            <div class="ssli-detail">
                                                                <div class="ep-name dynamic-name" 
                                                                     data-jname="<?= htmlspecialchars($episode['title'] ?? "Episode " . $episode['episode_no']) ?>" 
                                                                     title="<?= htmlspecialchars($episode['title'] ?? "Episode " . $episode['episode_no']) ?>">
                                                                    <?= htmlspecialchars($episode['title'] ?? "Episode " . $episode['episode_no']) ?>
                                                                    <?php if ($isFiller): ?>
                                                                        <span class="filler-badge">Filler</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="ssli-btn">
                                                                <div class="btn btn-circle"><i class="fas fa-play"></i></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                                <?php endforeach; ?>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="anis-watch-detail">
                                <div class="anis-content">
                                    <div class="anisc-poster">
                                        <div class="film-poster">
                                            <img src="<?= htmlspecialchars($animeData['poster']) ?>" data-src="<?= htmlspecialchars($animeData['poster']) ?>" class="film-poster-img" alt="<?= htmlspecialchars($animeData['title']) ?>">
                                        </div>
                                    </div>
                                    <div class="anisc-detail flex-grow-1">
                                        <h2 class="film-name">
                                            <a href="/details/<?= htmlspecialchars($animeId) ?>" class="text-white dynamic-name" title="<?= htmlspecialchars($animeData['title']) ?>" data-jname="<?= htmlspecialchars($animeData['title']) ?>">
                                                <?= htmlspecialchars($animeData['title']) ?>
                                            </a>
                                        </h2>
                                        <div class="film-stats">
                                            <div class="tick">
                                                <div class="tick-item tick-pg"><?= htmlspecialchars($animeData['rating']) ?></div>
                                                <div class="tick-item tick-quality"><?= htmlspecialchars($animeData['quality']) ?></div>
                                                <div class="tick-item tick-sub">
                                                    <i class="fas fa-closed-captioning mr-1"></i>
                                                    <?= htmlspecialchars($animeData['subEp']) ?>
                                                </div>
                                                <div class="tick-item tick-dub">
                                                    <i class="fas fa-microphone mr-1"></i>
                                                    <?= htmlspecialchars($animeData['dubEp']) ?>
                                                </div>
                                                
                                                <div class="tac tick-item tick-eps">
                                                <?php
                                                $query = mysqli_query($conn, "SELECT totalview FROM `pageview` WHERE pageID = '$pageID'");
                                                if ($query) {
                                                    $row = mysqli_fetch_assoc($query);
                                                    $counter = $row['totalview'] ?? 0;
                                                    echo "VIEWS: " . $counter;
                                                } else {
                                                    echo "Failed to retrieve views.";
                                                }
                                                ?>
                                                </div>
                                                <div class="clearfix"></div>
                                                </div> 
                                        </div>
                                        <div class="film-description m-hide">
                                            <div class="text">
                                                <?= htmlspecialchars($animeData['overview']) ?>
                                            </div>
                                        </div>
                                        <div class="film-text m-hide mb-3">
                                            <?= htmlspecialchars($websiteTitle) ?> is the best site to watch <strong><?= htmlspecialchars($animeData['title']) ?></strong> SUB online, or you can even watch <strong><?= htmlspecialchars($animeData['title']) ?></strong> DUB in HD quality.
                                        </div>
                                        <div class="block">
                                            <a href="/details/<?= htmlspecialchars($animeData['id']) ?>" class="btn btn-xs btn-light">View detail</a>
                                        </div>
                                    </div>
                                    
                                       
                                    
                                    <div class="dt-rate">
                                        <div id="vote-info">
                                            <div class="block-rating">
                                                <div class="description" style="padding-top: 10px;">Do you think this website is useful?</div>
                                                <div class="button-rate">
                                                    <button type="button" onclick="alert('Donation feature coming soon!')" class="btn btn-emo rate-good btn-vote" style="width:50%">
                                                        <i class="fas fa-donate" style="color:#08c"></i>
                                                        <span class="ml-2">Donate</span>
                                                    </button>
                                                    <button type="button" onclick="window.open('<?= htmlspecialchars($discord) ?>')" class="btn btn-emo rate-bad btn-vote" style="width:50%">
                                                        <i class="fab fa-discord" style="color:#6f85d5"></i>
                                                        <span class="ml-2">Join Discord</span>
                                                    </button>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="share-buttons share-buttons-detail">
                <div class="container">
                    <div class="share-buttons-block">
                        <div class="sbb-title mr-3">
                            <span>Share <?= htmlspecialchars($websiteTitle) ?></span>
                            <p class="mb-0">to your friends</p>
                        </div>
                        <div class="sharethis-inline-share-buttons"></div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <!-- Future Ads -->
            <!--
            <div class="pizza pizza-x">
                <div class="in-text">Want your Ads here?</div>
                <div class="in-contact">Contact us at: <a style="margin-left: 4px;" href="/cdn-cgi/l/email-protection#56373225163e3f37383f3b333833222139243d7835393b" target="_blank"><span class="__cf_email__" data-cfemail="5f3e3b2c1f37363e3136323a313a2b28302d34713c3032">[email&#160;protected]</span></a></div>
            </div> -->

            <div class="container">
                <div id="main-content">

                 <!-- Characters & Voice Actors -->
                 <?php if (!empty($animeData['actors'])): ?>
                <section class="block_area block_area-actors">
                    <div class="block_area-header">
                        <div class="float-left bah-heading mr-4">
                            <h2 class="cat-heading">Characters &amp; Voice Actors</h2>
                        </div>
                        <div class="float-right viewmore">
                            <a class="btn" data-toggle="modal" data-target="#modalVoiceActors">View more<i class="fas fa-angle-right ml-2"></i></a>
                        </div>                        
                        <div class="clearfix"></div>
                    </div>
                    <div class="block-actors-content">
                        <div class="bac-list-wrap">
                            <?php foreach ($animeData['actors'] as $entry): ?>
                                <div class="bac-item">
                                    <div class="per-info ltr">
                                        <a href="/character/<?= htmlspecialchars($entry['character']['id']) ?>" class="pi-avatar" rel="noopener noreferrer">
                                            <img data-src="<?= htmlspecialchars($entry['character']['poster']) ?>" alt="<?= htmlspecialchars($entry['character']['name']) ?>" class="lazyloaded" src="<?= htmlspecialchars($entry['character']['poster']) ?>">
                                        </a>
                                        <div class="pi-detail">
                                            <h4 class="pi-name">
                                                <a href="/character/<?= htmlspecialchars($entry['character']['id']) ?>" rel="noopener noreferrer">
                                                    <?= htmlspecialchars($entry['character']['name']) ?>
                                                </a>
                                            </h4>
                                            <span class="pi-cast"><?= htmlspecialchars($entry['character']['cast']) ?></span>
                                        </div>
                                    </div> 

                                    <?php if (!empty($entry['voiceActors']) && is_array($entry['voiceActors'])): ?>
                                        <?php $voiceActor = $entry['voiceActors'][0]; // Get the first voice actor ?>
                                        <div class="per-info rtl">
                                            <a href="/actors/<?= htmlspecialchars($voiceActor['id']) ?>" class="pi-avatar" rel="noopener noreferrer">
                                                <img data-src="<?= htmlspecialchars($voiceActor['poster']) ?>" class="lazyloaded" alt="<?= htmlspecialchars($voiceActor['name']) ?>" src="<?= htmlspecialchars($voiceActor['poster']) ?>">
                                            </a>
                                            <div class="pi-detail">
                                                <h4 class="pi-name">
                                                    <a href="/actors/<?= htmlspecialchars($voiceActor['id']) ?>" rel="noopener noreferrer">
                                                        <?= htmlspecialchars($voiceActor['name']) ?>
                                                    </a>
                                                </h4>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="clearfix"></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </section>
                <?php endif; ?> 

                    <section class="block_area block_area-comment" id="comment-block">
                        <?php 
                        $animeId = trim(explode('?', $streaming)[0]); 
                        $episodeId = isset($_GET['ep']) ? $_GET['ep'] : '1';
                        $user_id = $_COOKIE['userID'] ?? null;
                        $user = null;
                        
                        if ($user_id) {
                            $stmt = $conn->prepare("SELECT username, image FROM users WHERE id = ?");
                            if ($stmt === false) {
                                die("Database prepare failed: " . mysqli_error($conn));
                            }
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $user = $stmt->get_result()->fetch_assoc();
                        }
                        $commentData = [
                            'episode_id' => $episodeId,
                            'anime_id' => $animeId,
                            'user_profile' => [
                                'user_id' => $user_id,
                                'username' => $user['username'] ?? '',
                                'avatar_url' => !empty($user['image']) ? $user['image'] : '',
                            ]
                        ];
                        
                        include('src/component/comment.php');
                        ?>
                    </section>

                    <section class="w-full flex items-center justify-center">
                        <section class="block_area block_area_category">
                            
                        </section>
                    </section>

                      <!-- Recommended for you -->
                <section class="block_area block_area_category">
                    <div class="block_area-header">
                        <div class="float-left bah-heading mr-4">
                            <h2 class="cat-heading">Recommended for you</h2>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="tab-content">
                        <div class="block_area-content block_area-list film_list film_list-grid film_list-wfeature">
                            <div class="film_list-wrap">
                                <?php if (!empty($animeData['recommendedAnimes'])): ?>
                                    <?php foreach ($animeData['recommendedAnimes'] as $recommendedAnime): ?>
                                        <div class="flw-item">
                                            <div class="film-poster">
                                                <div class="tick ltr">
                                                    <?php if (!empty($recommendedAnime['episodes']['sub'])): ?>
                                                        <div class="tick-item tick-sub"><i class="fas fa-closed-captioning mr-1"></i><?= htmlspecialchars($recommendedAnime['episodes']['sub']) ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($recommendedAnime['episodes']['dub'])): ?>
                                                        <div class="tick-item tick-dub"><i class="fas fa-microphone mr-1"></i><?= htmlspecialchars($recommendedAnime['episodes']['dub']) ?></div>
                                                    <?php endif; ?>
                                                    <div class="tick-item tick-eps"><?= htmlspecialchars($recommendedAnime['episodes']['sub'] ?? $recommendedAnime['episodes']['dub']) ?></div>
                                                </div>
                                                <img data-src="<?= htmlspecialchars($recommendedAnime['poster']) ?>" class="film-poster-img lazyloaded" alt="<?= htmlspecialchars($recommendedAnime['name'] ?? $recommendedAnime['jname']) ?>" src="<?= htmlspecialchars($recommendedAnime['poster']) ?>">
                                                <a href="/details/<?= htmlspecialchars(strtolower(str_replace(' ', '-', $recommendedAnime['id']))) ?>" class="film-poster-ahref item-qtip" data-id="<?= htmlspecialchars($recommendedAnime['id']) ?>" data-hasqtip="0" oldtitle="<?= htmlspecialchars($recommendedAnime['name'] ?? $recommendedAnime['jname']) ?>" title="" aria-describedby="qtip-0"><i class="fas fa-play"></i></a>
                                            </div>
                                            <div class="film-detail">
                                               <h3 class="film-name"><a href="/details/<?= htmlspecialchars($recommendedAnime['id']) ?>" title="<?= htmlspecialchars($recommendedAnime['name'] ?? $recommendedAnime['jname']) ?>"><?= htmlspecialchars($recommendedAnime['name'] ?? $recommendedAnime['jname']) ?></a></h3>
                                                <div class="fd-infor">
                                                    <span class="fdi-item"><?= htmlspecialchars($recommendedAnime['type']) ?></span>
                                                    <span class="dot"></span>
                                                    <span class="fdi-item fdi-duration"><?= htmlspecialchars($recommendedAnime['duration']) ?></span>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No recommended animes available</p>
                                <?php endif; ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </section>


        </div>
        <?php include('src/component/anime/sidenav.php'); ?>
        <div class="clearfix"></div>
    </div>
    </div>
    <?php include('src/component/footer.php'); ?>
    <div id="mask-overlay"></div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
    <script type="text/javascript" src="<?= htmlspecialchars($websiteUrl) ?>/src/assets/js/app.js"></script>
    <script type="text/javascript" src="<?= htmlspecialchars($websiteUrl) ?>/src/assets/js/comman.js"></script>
    <script type="text/javascript" src="<?= htmlspecialchars($websiteUrl) ?>/src/assets/js/movie.js"></script>
    <link rel="stylesheet" href="<?= htmlspecialchars($websiteUrl) ?>/src/assets/css/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="<?= htmlspecialchars($websiteUrl) ?>/src/assets/js/function.js"></script>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const iframe = document.getElementById("iframe-embed");
        let currentServerType = 'sub';
        let currentEpisodeId = '<?= htmlspecialchars($streaming) ?>';
        let animeId = '<?= htmlspecialchars($animeId) ?>';
        let autoNextEnabled = true;
        let autoSkipEnabled = true; // Initialize auto-next feature as disabled

        // Function to toggle auto-next feature
        function toggleAutoNext() {
            autoNextEnabled = !autoNextEnabled;
            const toggleResult = document.querySelector(".pc-autonext .tb-result");
            toggleResult.textContent = autoNextEnabled ? "" : ""; // Update text based on state
            console.log(`Auto-next is now ${autoNextEnabled ? "enabled" : "disabled"}.`);
        }

         // Function to toggle auto-skip feature
    function toggleAutoSkip() {
        autoSkipEnabled = !autoSkipEnabled;
        const toggleResult = document.querySelector(".pc-autoskip .tb-result");
        toggleResult.textContent = autoSkipEnabled ? "" : "";
        console.log(`Auto-skip is now ${autoSkipEnabled ? "enabled" : "disabled"}.`);
    }

        // Attach event listener to the auto-next toggle element
        document.querySelector(".pc-autonext").addEventListener("click", toggleAutoNext);
        document.querySelector(".pc-autoskip").addEventListener("click", toggleAutoSkip);

        // Function to handle auto-next feature
        function handleAutoNext() {
            const videoPlayer = iframe.contentWindow.document.querySelector('video');
            if (videoPlayer) {
                videoPlayer.addEventListener('ended', function() {
                    if (autoNextEnabled) {
                        nextEpisode(); // Call nextEpisode if autoNext is enabled
                    }
                });
            }
        }

        // Ensure this line is present to initialize the auto-next feature
        iframe.addEventListener('load', handleAutoNext);

        function updateWatchedEpisodeUI(episodeNumber) {
            const episodeItem = document.querySelector(`.ssl-item[data-number="${episodeNumber}"]`);
            if (episodeItem && !episodeItem.classList.contains('watched')) {
                episodeItem.classList.add('watched');
            }
        }

        async function markEpisodeAsWatched(anilistId, episodeNumber) {
            console.log('Marking episode as watched:', episodeNumber);
            try {
                updateWatchedEpisodeUI(episodeNumber);
            } catch (error) {
                console.error('Error marking episode as watched:', error);
            }
        }

        async function updateWatchHistory(episodeData) {
            try {
                const response = await fetch('/src/ajax/wh-up.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        animeId: '<?= htmlspecialchars($animeId) ?>',
                        animeName: '<?= htmlspecialchars(str_replace("'", "\'", $animeData['title'])) ?>',
                        poster: '<?= htmlspecialchars($animeData['poster']) ?>',
                        subCount: <?= htmlspecialchars($animeData['subEp']) ?>,
                        dubCount: <?= htmlspecialchars($animeData['dubEp']) ?>,
                        anilistId: '<?= htmlspecialchars($animeData['anilistId'] ?? '') ?>',
                        episodeNumber: episodeData.episodeNumber
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    markEpisodeAsWatched(data.anilistId, episodeData.episodeNumber);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error updating watch history:', error);
                markEpisodeAsWatched(null, episodeData.episodeNumber);
            }
        }

        async function fetchServers(episodeId) {
            try {
                const response = await fetch(`/src/ajax/server.php?episodeId=${episodeId}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();
                console.log('Fetched servers:', data);
                return data;
            } catch (error) {
                console.error('Error fetching servers:', error);
                return null;
            }
        }

        async function updateServerList(episodeId) {
            const servers = await fetchServers(episodeId);
        if (!servers) return;
        const subServerList = document.querySelector('.ps_-block-sub .ps__-list');
        if (servers.sub && servers.sub.length > 0) {
            subServerList.innerHTML = servers.sub.map((server, index) => `
                <div class="item">
                    <button 
                        class="btn btn-server ${currentServerType === 'sub' && index === 0 ? 'active' : ''}"
                        data-episode-id="${episodeId}"
                        data-server-id="${server.serverId}"
                        data-server-type="sub"
                        data-server-name="${server.serverName}"
                    >${server.serverName}</button>
                </div>
            `).join('');
        } else {
            subServerList.innerHTML = '<div class="item">Please select an episode</div>';
        }
        const dubServerList = document.querySelector('.ps_-block-dub .ps__-list');
        if (servers.dub && servers.dub.length > 0) {
            dubServerList.innerHTML = servers.dub.map((server, index) => `
                <div class="item">
                    <button 
                        class="btn btn-server ${currentServerType === 'dub' && index === 0 ? 'active' : ''}"
                        data-episode-id="${episodeId}"
                        data-server-id="${server.serverId}"
                        data-server-type="dub"
                        data-server-name="${server.serverName}"
                    >${server.serverName}</button>
                </div>
            `).join('');
        } else {
            dubServerList.innerHTML = '<div class="item">No DUB servers available</div>';
        }
        attachServerListeners();
        const activeServer = document.querySelector('.btn-server.active');
        if (!activeServer) {
            const firstServer = document.querySelector(`.ps_-block-${currentServerType} .btn-server`);
            if (firstServer) {
                firstServer.click();
            }
        }
    }

    function attachServerListeners() {
        const serverButtons = document.querySelectorAll(".btn-server");
        serverButtons.forEach(button => {
            button.addEventListener("click", function() {
                // Remove 'active' class from all server buttons
                serverButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add 'active' class to the clicked button
                this.classList.add('active');

                const serverId = this.getAttribute("data-server-id");
                const serverType = this.getAttribute("data-server-type");
                let serverName = this.getAttribute("data-server-name");
                const episodeId = this.getAttribute("data-episode-id");
                const urlParams = new URLSearchParams(window.location.search);
                const episodeNumber = urlParams.get('ep');

                // Check if the server name is "streamsb" or "streamtape" and adjust the serverName
                if ((serverName === "streamsb" && serverId === "5") || (serverName === "streamtape" && serverId === "3")) {
                    serverName = "hd-2";
                }

                // Add skip parameter if auto-skip is enabled
                const skipParam = autoSkipEnabled ? "&skip=true" : "&skip=false";
                const playerUrl = `<?= $websiteUrl ?>/src/player/${serverType}.php?id=${currentEpisodeId}&server=${serverName}&embed=true&ep=${episodeNumber}${skipParam}`;
                console.log('Setting player URL:', playerUrl);
                iframe.src = playerUrl;
                const pcAutoskipButton = document.querySelector(".pc-autoskip");
                if (pcAutoskipButton) {
                    pcAutoskipButton.removeEventListener("click", reloadPlayer); // Remove any existing event listener
                    pcAutoskipButton.addEventListener("click", reloadPlayer);
                }

                function reloadPlayer() {
                    const skipParam = autoSkipEnabled ? "&skip=true" : "&skip=false";
                    const playerUrl = `<?= $websiteUrl ?>/src/player/${currentServerType}.php?id=${currentEpisodeId}&server=${serverName}&embed=true&ep=${episodeNumber}${skipParam}`;
                    console.log('Reloading player URL:', playerUrl);
                    iframe.src = playerUrl;
                }
                localStorage.setItem("selectedServerType", serverType);
                localStorage.setItem("selectedServerId", serverId);
            });
        });
    }


    function selectEpisode(episodeNumber) {
        console.log('Selecting episode:', episodeNumber);
        const episodeItem = document.querySelector(`.ssl-item[data-number="${episodeNumber}"]`);
        if (episodeItem) {
            episodeItem.click();
            return true;
        }
        console.log('Episode not found:', episodeNumber);
        return false;
    }

    function selectFirstEpisode() {
        console.log('Attempting to select first episode');
        const firstEpisode = document.querySelector(".ssl-item");
        if (firstEpisode) {
            console.log('First episode found, clicking');
            firstEpisode.click();
        } else {
            console.log('No episodes found');
        }
    }

    const episodeItems = document.querySelectorAll(".ssl-item");
    episodeItems.forEach(item => {
        item.addEventListener("click", async function() {
            const episodeId = this.getAttribute("data-id");
            const episodeNumber = this.getAttribute("data-number");
            currentEpisodeId = episodeId;
            
            episodeItems.forEach(ep => ep.classList.remove("active"));
            this.classList.add("active");
            
            const newUrl = `/watch/${animeId}?ep=${episodeNumber}`;
            history.pushState({}, '', newUrl);
            
            const serverNotice = document.querySelector(".server-notice strong");
            if (serverNotice) {
                serverNotice.innerHTML = `Currently watching <b>Episode ${episodeNumber}</b>`;
            }

            await updateWatchHistory({
                episodeNumber: parseInt(episodeNumber)
            });
            
            await updateServerList(episodeId);
            
            const firstServer = document.querySelector(`.ps_-block-${currentServerType} .btn-server`);
            if (firstServer) {
                firstServer.click();
            }
            updateNavigationButtons();
        });
    });

    function initializeEpisodeSelection() {
        console.log('Initializing episode selection');
        
        const urlParams = new URLSearchParams(window.location.search);
        const epFromUrl = urlParams.get('ep');
        
        if (epFromUrl) {
            console.log('Episode from URL:', epFromUrl);
            if (!selectEpisode(epFromUrl)) {
                selectFirstEpisode();
            }
        } else {
            const lastWatchedEp = getLastWatchedEpisode();
            console.log('Last watched episode:', lastWatchedEp);
            
            if (lastWatchedEp > 1) {
                if (!selectEpisode(lastWatchedEp)) {
                    selectFirstEpisode();
                }
            } else {
                selectFirstEpisode();
            }
        }
    }

    setTimeout(initializeEpisodeSelection, 100);

    function updateNavigationButtons() {
        const currentEpisode = document.querySelector(".ssl-item.active");
        const prevButton = document.querySelector(".block-prev");
        const nextButton = document.querySelector(".block-next");
        
        if (currentEpisode) {
            let hasPrev = false;
            let previousEpisode = currentEpisode.previousElementSibling;
            while (previousEpisode) {
                if (previousEpisode.classList.contains("ssl-item")) {
                    hasPrev = true;
                    break;
                }
                previousEpisode = previousEpisode.previousElementSibling;
            }
            
            let hasNext = false;
            let nextEpisode = currentEpisode.nextElementSibling;
            while (nextEpisode) {
                if (nextEpisode.classList.contains("ssl-item")) {
                    hasNext = true;
                    break;
                }
                nextEpisode = nextEpisode.nextElementSibling;
            }
            
            prevButton.style.display = hasPrev ? "block" : "none";
            nextButton.style.display = hasNext ? "block" : "none";
        }
    }

    function prevEpisode() {
        const currentEpisode = document.querySelector(".ssl-item.active");
        if (currentEpisode) {
            let previousEpisode = currentEpisode.previousElementSibling;
            while (previousEpisode && !previousEpisode.classList.contains("ssl-item")) {
                previousEpisode = previousEpisode.previousElementSibling;
            }
            if (previousEpisode) {
                previousEpisode.click();
            }
        }
    }

    function nextEpisode() {
        const currentEpisode = document.querySelector(".ssl-item.active");
        if (currentEpisode) {
            let nextEpisode = currentEpisode.nextElementSibling;
            while (nextEpisode && !nextEpisode.classList.contains("ssl-item")) {
                nextEpisode = nextEpisode.nextElementSibling;
            }
            if (nextEpisode) {
                nextEpisode.click();
            }
        }
    }

    document.querySelector(".btn-prev").addEventListener("click", prevEpisode);
    document.querySelector(".btn-next").addEventListener("click", nextEpisode);

    // Load watched episodes when page loads
    loadWatchedEpisodes();
    // Initialize auto-next feature
    iframe.addEventListener('load', handleAutoNext);
});

</script>

<script>
// Event listener for episode range selection
document.getElementById('episode-range').addEventListener('change', function() {
    // Hide all episode pages
    document.querySelectorAll('.ss-list').forEach(page => {
        page.style.display = 'none';
    });
    
    // Show selected page
    const selectedPage = document.getElementById('episodes-page-' + (parseInt(this.value) + 1));
    if (selectedPage) {
        selectedPage.style.display = 'block';
    }
});
</script>

<script>
// Load watched episodes on initial page load
document.addEventListener("DOMContentLoaded", async function() {
    function updateWatchedEpisodeUI(episodeNumber) {
        const episodeItem = document.querySelector(`.ssl-item[data-number="${episodeNumber}"]`);
        if (episodeItem && !episodeItem.classList.contains('watched')) {
            episodeItem.classList.add('watched');
        }
    }

    try {
        const animeId = <?= isset($animeData['id']) ? json_encode($animeData['id']) : 'null' ?>; // Corrected to use 'id' instead of 'animeId'
        const response = await fetch(`/src/ajax/wh-get.php?animeId=${animeId}`); // Fixed template literal syntax
        const data = await response.json();
        
        if (data.success && Array.isArray(data.watchedEpisodes)) {
            data.watchedEpisodes.forEach(episodeNumber => {
                updateWatchedEpisodeUI(episodeNumber);
            });
        } else {
            console.error('Invalid response format from get-watch-history.php');
        }
    } catch (error) {
        console.error('Error fetching watch history:', error);
    }
});
</script>

<script>
function toggleTheaterMode() {
    const playerFrame = document.documentElement; 
    if (!document.fullscreenElement) {
        playerFrame.requestFullscreen().catch(err => {
            console.error(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
        });
    } else {
        document.exitFullscreen();
    }
}
document.addEventListener('click', function() {
    if (document.fullscreenElement) {
        document.exitFullscreen();
    }
});

const mediaResizeButton = document.querySelector('#media-resize');
const expandIcon = mediaResizeButton.querySelector('i');

mediaResizeButton.addEventListener('click', function() {
    if (document.fullscreenElement) {
        expandIcon.classList.remove('fa-compress');
        expandIcon.classList.add('fa-expand'); 
    } else {
        expandIcon.classList.remove('fa-expand');
        expandIcon.classList.add('fa-compress'); 
    }
});
</script>


    </div>
</body>

</html>
