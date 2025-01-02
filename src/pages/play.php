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

if (isset($episodelistData['success']) && 
    $episodelistData['success'] === true && 
    isset($episodelistData['data']['episodes'])) {
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

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-F7PYH8JF75"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-F7PYH8JF75');
</script>

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
                                                <a href="javascript:;" id="media-resize" class="btn btn-sm" onclick="toggleFullScreen()"><i class="fas fa-expand mr-1"></i>Expand</a>
                                            </div>
                                            
                                            
                                            <div class="pc-item pc-toggle pc-autoplay">
                                                <div class="toggle-basic quick-settings" data-option="auto_play">
                                                    <span class="tb-name">Auto Play</span>
                                                    <span class="tb-result"></span>
                                                </div>
                                            </div>
                                            <div class="pc-item pc-toggle pc-autonext">
                                                <div class="toggle-basic quick-settings off" data-option="auto_next">
                                                    <span class="tb-name">Auto Next</span>
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
                                <?php if (!empty($animeData['season'])): ?>
                                <div class="other-season">
                                    <div class="inner">
                                        <div class="os-title">Watch more seasons of this anime</div>
                                        <div class="os-list">                                            
                                            <?php foreach ($animeData['season'] as $season): ?>
                                                <a href="<?= htmlspecialchars($websiteUrl) ?>/src/pages/details.php/<?= htmlspecialchars($season['id']) ?>" class="os-item <?= $season['id'] === $animeId ? 'active' : '' ?>" title="<?= htmlspecialchars($season['title']) ?>">
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
                                                        $isFiller = isset($episode['isFiller']) && $episode['isFiller'] === true;
                                                        $fillerClass = $isFiller ? 'ssl-item-filler' : '';
                                                        ?>
                                                        <a class="ssl-item ep-item <?= $fillerClass ?>" 
                                                           href="javascript:void(0);" 
                                                           data-number="<?= htmlspecialchars($episode['number']) ?>" 
                                                           data-id="<?= htmlspecialchars($episode['episodeId']) ?>"
                                                           <?php if ($isFiller): ?>data-toggle="tooltip" title="Filler Episode"<?php endif; ?>>
                                                            <div class="ssli-order"><?= htmlspecialchars($episode['number']) ?></div>
                                                            <div class="ssli-detail">
                                                                <div class="ep-name dynamic-name" 
                                                                     data-jname="<?= htmlspecialchars($episode['title'] ?? "Episode " . $episode['number']) ?>" 
                                                                     title="<?= htmlspecialchars($episode['title'] ?? "Episode " . $episode['number']) ?>">
                                                                    <?= htmlspecialchars($episode['title'] ?? "Episode " . $episode['number']) ?>
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
                                                <div class="tick-item tick-eps"><?= htmlspecialchars($totalEpisodes) ?></div>
                                                <span class="dot"></span>
                                                <span class="item"><?= htmlspecialchars($animeData['showType']) ?></span>
                                                <span class="dot"></span>
                                                <span class="item"><?= htmlspecialchars($animeData['duration']) ?></span>
                                                <span class="dot"></span>
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
                    <?php if (!empty($animeData['actors'])): ?>
                    <section class="block_area block_area-actors">
                        <div class="block_area-header">
                            <div class="float-left bah-heading mr-4">
                                <h2 class="cat-heading">Characters &amp; Voice Actors</h2>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="block-actors-content">
                            <div class="bac-list-wrap">
                                <?php foreach ($animeData['actors'] as $entry): ?>
                                    <div class="bac-item">
                                        <div class="per-info ltr">
                                            <a href="/character/<?= htmlspecialchars(strtolower(str_replace(" ", "-", $entry['character']['name']))) ?>" class="pi-avatar">
                                                <img data-src="<?= htmlspecialchars($entry['character']['poster']) ?>" alt="<?= htmlspecialchars($entry['character']['name']) ?>" class="lazyloaded" src="<?= htmlspecialchars($entry['character']['poster']) ?>"></a>
                                            <div class="pi-detail">
                                                <h4 class="pi-name"><a href="/character/<?= htmlspecialchars(strtolower(str_replace(" ", "-", $entry['character']['name']))) ?>"><?= htmlspecialchars($entry['character']['name']) ?></a></h4>
                                                <span class="pi-cast">Main</span>
                                            </div>
                                        </div>
                                        <div class="per-info rtl">
                                            <a href="/people/<?= htmlspecialchars(strtolower(str_replace(" ", "-", $entry['voiceActor']['name']))) ?>" class="pi-avatar">
                                                <img data-src="<?= htmlspecialchars($entry['voiceActor']['poster']) ?>" class="lazyloaded" alt="<?= htmlspecialchars($entry['voiceActor']['name']) ?>" src="<?= htmlspecialchars($entry['voiceActor']['poster']) ?>"></a>
                                            <div class="pi-detail">
                                                <h4 class="pi-name"><a href="/people/<?= htmlspecialchars(strtolower(str_replace(" ", "-", $entry['voiceActor']['name']))) ?>"><?= htmlspecialchars($entry['voiceActor']['name']) ?></a></h4>
                                                <span class="pi-cast">Japanese</span>
                                            </div>
                                        </div>
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
                        // Get the anime ID directly from the URL path
                        $animeId = trim(explode('?', $streaming)[0]); 
                        
                        // Get episode ID from query parameters
                        $episodeId = isset($_GET['ep']) ? $_GET['ep'] : '1';
                        
                        // Get user data from session/cookie
                        $user_id = $_COOKIE['userID'] ?? null;
                        $user = null;
                        
                        if ($user_id) {
                            $stmt = $conn->prepare("SELECT username, image, avatar_url FROM users WHERE id = ?");
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $user = $stmt->get_result()->fetch_assoc();
                        }
                        
                        // Create comment data array
                        $commentData = [
                            'episode_id' => $episodeId,
                            'anime_id' => $animeId,
                            'user_profile' => [
                                'user_id' => $user_id,
                                'username' => $user['username'] ?? '',
                                'avatar_url' => !empty($user['image']) ? $user['image'] : ($user['avatar_url'] ?? ''),
                            ]
                        ];
                        
                        include('src/component/comment.php');
                        ?>
                    </section>

                    <section class="w-full flex items-center justify-center">
                        <section class="block_area block_area_category">
                            <div class="text-center">
                                <img src="<?= htmlspecialchars($websiteUrl) ?>/public/images/construction.gif" alt="under construction image" class="img-fluid">
                                <h1 class="text-center mt-3" style="font-size: calc(1.5rem + 1vw);">This part is currently under construction! ??</h1>
                            </div>
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
        <?php include('src/component/sidenav.php'); ?>
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
    let autoNextEnabled = false; // Initialize auto-next feature as disabled

    // Function to toggle auto-next feature
    function toggleAutoNext() {
        autoNextEnabled = !autoNextEnabled;
        const toggleResult = document.querySelector(".pc-autonext .tb-result");
        toggleResult.textContent = autoNextEnabled ? "" : "";
        console.log(`Auto-next is now ${autoNextEnabled ? "disabled" : "enabled"}.`);
    }

    // Attach event listener to the auto-next toggle element
    document.querySelector(".pc-autonext").addEventListener("click", toggleAutoNext);

    // Function to handle auto-next feature
    function handleAutoNext() {
        const videoPlayer = iframe.contentWindow.document.querySelector('video');
        if (videoPlayer) {
            videoPlayer.addEventListener('ended', function() {
                if (autoNextEnabled) {
                    nextEpisode();
                }
            });
        }
    }

   

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
                    movieId: '<?= htmlspecialchars($animeId) ?>',
                    animeName: '<?= htmlspecialchars($animeData['title']) ?>',
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
                const serverId = this.getAttribute("data-server-id");
                const serverType = this.getAttribute("data-server-type");
                let serverName = this.getAttribute("data-server-name");
                const episodeId = this.getAttribute("data-episode-id");

                // Check if the server name is "streamsb" or "streamtape" and adjust the serverName
                if ((serverName === "streamsb" && serverId === "5") || (serverName === "streamtape" && serverId === "3")) {
                    serverName = "hd-2";
                }

                serverButtons.forEach(btn => btn.classList.remove("active"));
                this.classList.add("active");
                currentServerType = serverType;
                const playerUrl = `<?= $websiteUrl ?>/src/player/${serverType}.php?id=${currentEpisodeId}&server=${serverName}&embed=true`;
                console.log('Setting player URL:', playerUrl);
                iframe.src = playerUrl;
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
            
            const newUrl = `/play/${animeId}?ep=${episodeNumber}`;
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

    </div>
</body>

</html>
