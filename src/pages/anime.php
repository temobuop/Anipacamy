<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/_config.php');
session_start();

$category = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)); 
if (empty($category)) {
    $category = 'most-popular';
}

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; 

$apiUrl = "$api/category/{$category}?page={$page}"; 

try {
    $response = file_get_contents($apiUrl);
    if ($response !== false) {
        $data = json_decode($response, true);
        if ($data && isset($data['success']) && $data['success'] && isset($data['data']['animes'])) {
            $searchResults = $data['data']['animes'];
            $currentPage = $data['data']['currentPage'];
            $totalPages = $data['data']['totalPages'];
        } else {
            $errorMessage = 'Failed to fetch popular anime. Please try again later.';
        }
    } else {
        $errorMessage = 'Could not connect to the API.';
    }
} catch (Exception $e) {
    $errorMessage = 'An error occurred: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>List All <?= str_replace('-', ' ', ucfirst($category)) ?> Anime on <?=$websiteTitle?></title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="title" content="List All <?= str_replace('-', ' ', ucfirst($category)) ?> Anime on <?=$websiteTitle?>">
    <meta name="description" content="Popular Anime in HD with No Ads. Watch anime online">
    <meta name="keywords"
        content="<?=$websiteTitle?>, watch anime online, free anime, anime stream, anime hd, english sub, kissanime, gogoanime, animeultima, 9anime, 123animes, <?=$websiteTitle?>, vidstreaming, gogo-stream, animekisa, zoro.to, gogoanime.run, animefrenzy, animekisa">
    <meta name="charset" content="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="robots" content="noindex, follow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Language" content="en">
    <meta property="og:title" content="List All <?= str_replace('-', ' ', ucfirst($category)) ?> Anime on <?=$websiteTitle?>">
    <meta property="og:description"
        content="List All <?= str_replace('-', ' ', ucfirst($category)) ?> Anime on <?=$websiteTitle?> in HD with No Ads. Watch anime online">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?=$websiteTitle?>">
    <meta itemprop="image" content="<?=$banner?>">
    <meta property="og:image" content="<?=$banner?>">
    <meta property="og:image:width" content="650">
    <meta property="og:image:height" content="350">
    <meta property="twitter:card" content="summary">
    <meta name="apple-mobile-web-app-status-bar" content="#202125">
    <meta name="theme-color" content="#202125">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css?v=<?=$version?>"
        type="text/css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css?v=<?=$version?>"
        type="text/css">
    <link rel="apple-touch-icon" href="<?=$websiteUrl?>/public/favicon.png?v=<?=$version?>" />
    <link rel="shortcut icon" href="<?=$websiteUrl?>/public/favicon.png?v=<?=$version?>" type="image/x-icon"/>
    <link rel="apple-touch-icon" sizes="180x180" href="<?=$websiteUrl?>/public/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=$websiteUrl?>/public/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=$websiteUrl?>/public/favicon-16x16.png">
    <link rel="mask-icon" href="<?=$websiteUrl?>/public/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="icon" sizes="192x192" href="<?=$websiteUrl?>/src/assets/images/touch-icon-192x192.png?v=<?=$version?>">
    <link rel="stylesheet" href="<?=$websiteUrl?>/src/assets/css/styles.min.css?v=<?=$version?>">
    <link rel="stylesheet" href="<?=$websiteUrl?>/src/assets/css/min.css?v=<?=$version?>">
    <link rel="stylesheet" href="<?=$websiteUrl?>/src/assets/css/new.css?v=<?=$version?>">
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
        link.href = `${file}?v=<?=$version?>`;
        link.type = 'text/css';
        firstLink.parentNode.insertBefore(link, firstLink);
                });
        }, 500);
    </script>

    <noscript>
        <link rel="stylesheet" type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css?v=<?=$version?>" />
        <link rel="stylesheet" type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css?v=<?=$version?>" />
    </noscript>
    <script></script>
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


    <link rel="stylesheet" href="<?=$websiteUrl?>/src/assets/css/search.css">
    <script src="<?=$websiteUrl?>/src/assets/js/search.js"></script>

</head>

<body data-page="page_anime">
    <div id="sidebar_menu_bg"></div>
    <div id="wrapper" data-page="page_home">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/src/component/header.php'; ?>
        <div class="clearfix"></div>
        <div id="main-wrapper">
            <div class="container">
                <div id="main-content">
<section class="block_area block_area_category">
    <div class="block_area-header">
        <div class="float-left bah-heading mr-4">
            <h2 class="cat-heading"> <?= str_replace('-', ' ', ucfirst($category)) ?>  </h2>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="tab-content">
        <div class="block_area-content block_area-list film_list film_list-grid film_list-wfeature">
            <div class="film_list-wrap">
                <?php if (!empty($searchResults)): ?>
                    <?php 
                    $displayedResults = array_slice($searchResults, 0, 12);
                    foreach ($displayedResults as $anime): ?>
                        <div class="flw-item">
                            <div class="film-poster">
                                <?php if (!empty($anime['rating'])): ?>
                                    <div class="tick ltr" style="position: absolute; top: 10px; left: 10px;">
                                        <div class="tick-item tick-age amp-algn">18+</div>
                                    </div>
                                <?php endif; ?>
                                <div class="tick ltr" style="position: absolute; bottom: 10px; left: 10px;">
                                    <?php if (!empty($anime['episodes']['sub'])): ?>
                                        <div class="tick-item tick-sub amp-algn" style="text-align: left;">
                                            <i class="fas fa-closed-captioning"></i> &nbsp;<?= $anime['episodes']['sub'] ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($anime['episodes']['dub'])): ?>
                                        <div class="tick-item tick-dub amp-algn" style="text-align: left;">
                                            <i class="fas fa-microphone"></i> &nbsp;<?= $anime['episodes']['dub'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <img class="film-poster-img lazyload" 
                                     data-src="<?= $anime['poster'] ?>" 
                                     src="<?= $anime['poster'] ?>" 
                                     alt="<?= htmlspecialchars($anime['name']) ?>">
                                <a class="film-poster-ahref" href="/details/<?= $anime['id'] ?>"></a>
                            </div>
                            <div class="film-detail">
                                <h3 class="film-name">
                                    <a href="/details/<?= $anime['id'] ?>" 
                                       title="<?= htmlspecialchars($anime['name']) ?>">
                                       <?= htmlspecialchars($anime['name']) ?>
                                    </a>
                                </h3>
                                <div class="fd-infor">
                                    <span class="fdi-item"><?= htmlspecialchars($anime['type']) ?></span>
                                    <span class="dot"></span>
                                    <span class="fdi-item"><?= htmlspecialchars($anime['duration']) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="clearfix"></div>

                <?php else: ?>
                    <p>No results found.</p>
                <?php endif; ?>
            </div>
             <!-- Pagination -->
             <div class="pre-pagination mt-5 mb-5">
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-lg justify-content-center">
                                <?php for ($i = 1; $i <= min(3, $totalPages); $i++): ?>
                                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" 
                                           href="?page=<?= $i ?>" 
                                           title="Page <?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" 
                                           href="?page=<?= $currentPage + 1 ?>" 
                                           title="Next">›</a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($totalPages > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" 
                                           href="?page=<?= $totalPages ?>" 
                                           title="Last">»</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                    <div class="clearfix"></div>
        </div>
    </div>
</section>


                    <div class="clearfix"></div>
                </div>
                <?php include('src/component/sidenav.php'); ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/src/component/footer.php'; ?>
        <div id="mask-overlay"></div>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js">
        </script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
        <script type="text/javascript" src="<?= $websiteUrl ?>/src/assets/js/app.js"></script>
        <script type="text/javascript" src="<?= $websiteUrl ?>/src/assets/js/comman.js"></script>
        <script type="text/javascript" src="<?= $websiteUrl ?>/src/assets/js/movie.js"></script>
        <link rel="stylesheet" href="<?= $websiteUrl ?>/src/assets/css/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script type="text/javascript" src="<?= $websiteUrl ?>/src/assets/js/function.js"></script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    </div>
</body>

</html>
