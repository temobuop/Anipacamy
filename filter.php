<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require '_config.php';
session_start();
$keyword = isset($_GET['keyword']) ? urlencode($_GET['keyword']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; 
$query = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$query = isset($_GET['keyword']) ? str_replace(' ', '-', $_GET['keyword']) : '';
echo $query;
if ($query) {  
    $genres = isset($_GET['genres']) ? $_GET['genres'] : '';
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
    $season = isset($_GET['season']) ? $_GET['season'] : '';
    $sub_or_dub = isset($_GET['language']) ? $_GET['language'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $rating = isset($_GET['rated']) ? $_GET['rated'] : '';
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
    $score = isset($_GET['score']) ? $_GET['score'] : '';

   
    $apiUrl = "$api/search?q={$query}&page={$page}&genres={$genres}&type={$type}&sort={$sort}&season={$season}&language={$sub_or_dub}&status={$status}&rated={$rating}&start_date={$start_date}&end_date={$end_date}&score={$score}";

    try {
        $response = file_get_contents($apiUrl);
        if ($response !== false) {
            $data = json_decode($response, true);
            if ($data && isset($data['success']) && $data['success'] && isset($data['data']['animes'])) {
                $searchResults = $data['data']['animes'];
            } else {
                $errorMessage = 'Failed to fetch search results. Please try again later.';
            }
        } else {
            $errorMessage = 'Could not connect to the API.';
        }
    } catch (Exception $e) {
        $errorMessage = 'An error occurred: ' . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>List All Anime with keyword on <?=$websiteTitle?></title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="title" content="List All Anime with keyword on <?=$websiteTitle?>">
    <meta name="description" content="Popular Anime in HD with No Ads. Watch anime online">
    <meta name="keywords"
        content="<?=$websiteTitle?>, watch anime online, free anime, anime stream, anime hd, english sub, kissanime, gogoanime, animeultima, 9anime, 123animes, <?=$websiteTitle?>, vidstreaming, gogo-stream, animekisa, zoro.to, gogoanime.run, animefrenzy, animekisa">
    <meta name="charset" content="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="robots" content="noindex, follow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Language" content="en">
    <meta property="og:title" content="List All Anime with keyword on <?=$websiteTitle?>">
    <meta property="og:description"
        content="List All Anime with keyword on <?=$websiteTitle?> in HD with No Ads. Watch anime online">
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
        <?php include('/src/component/header.php'); ?>
        <div class="clearfix"></div>
        <div id="main-wrapper">
            <div class="container">
                <div id="main-content">
                <section class="block_area block_area_category">
        <div class="block_area-header">
            <div class="float-left bah-heading mr-4">
                <h2 class="cat-heading"><i class="fa fa-search"></i>&nbsp; Search Results</h2>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="tab-content">

<?php include('/src/component/filter.php'); ?>

<section class="w-full flex items-center justify-center">
<section class="block_area block_area_category">
        <div class="text-center">
            <img src="<?=$websiteUrl?>/public/images/construction.gif" alt="under construction image" class="img-fluid">
            <h1 class="text-center mt-3" style="font-size: calc(1.5rem + 1vw);">This page is currently under construction! ⚠️</h1>
        </div>
</section>
</section>
<div class="clearfix"></div>
        
            <div class="block_area-content block_area-list film_list film_list-grid film_list-wfeature">
                <div class="film_list-wrap">
                    <?php if ($query && !empty($searchResults)): ?>
                        <?php foreach ($searchResults as $anime): ?>
                            <div class="flw-item">
                                <div class="film-poster">
                                    <img class="film-poster-img lazyload" data-src="<?=$anime['poster']?>" src="<?=$anime['poster']?>" alt="<?=$anime['name']?>">
                                    <a class="film-poster-ahref" href="/watch/episodes/<?=$anime['id']?>?ep=<?=$anime['episodes'][0]['id']?>" title="<?=$anime['name']?>"></a>
                                </div>
                                <div class="film-detail">
                                    <h3 class="film-name">
                                        <a href="/watch/episodes/<?=$anime['id']?>?ep=<?=$anime['episodes'][0]['id']?>" title="<?=$anime['name']?>"><?=$anime['name']?></a>
                                    </h3>
                                    <div class="fd-infor">
                                        <span class="fdi-item"><?=$anime['type']?></span>
                                        <span class="dot"></span>
                                        <span class="fdi-item"><?=$anime['duration']?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php elseif ($query): ?>
                        <p>No results found for "<?=htmlspecialchars($query)?>".</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
                    <div class="clearfix"></div>
                </div>
                <?php include('/src/component/sidenav.php'); ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <?php include('./src/component/footer.php'); ?>
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
  
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/sw.js?v=0.5');
        });
    }
    $('.anime-request-link').click(function (e) {
        e.preventDefault();
        if (isLoggedIn) {
            window.location.href = $(this).attr('href');
        } else {
            $('#modallogin').modal('show');
        }
    });
</script>
 </div>
</body>

</html>
