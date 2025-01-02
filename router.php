<?php
// Include the global configuration file
require_once($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

// Get the URI without query strings
$uri = strtok(trim($_SERVER['REQUEST_URI'], '/'), '?');

// Define routes with regex patterns and associated files
$routes = [
  '/^filter$/' => 'filter.php',
    '/^search$/' => 'search.php',
    '/^home$/' => 'home.php',
    '/^details$/' => 'src/pages/details.php',
    '/^random$/' => 'src/component/random.php',
    '/^anime$/' => 'src/pages/anime.php',
    '/^dmca$/' => 'src/pages/extra/dmca.php',
    '/^terms$/' => 'src/pages/extra/terms.php',
    '/^manga$/' => 'src/pages/manga.php',
    '/^az-list(\/[a-zA-Z0-9])?$/' => 'src/pages/az-list.php',
    '/^pre-qtip$/' => 'src/ajax/pre-qtip.php',
    '/^login$/' => 'src/user/login.php',
    '/^register$/' => 'src/user/register.php',
    '/^logout$/' => 'src/user/logout.php',
    '/^profile$/' => 'src/user/profile.php',
    '/^watchlist$/' => 'src/user/watchlist.php',
    '/^watchlist\.php(?:\?.*)?$/' => 'src/user/watchlist.php',
    '/^changepass$/' => 'src/user/changepass.php',
    '/^continue-watching$/' => 'src/user/continue-watching.php',
    '/^streaming$/' => 'src/pages/streaming.php',
    '/^details\/([a-zA-Z0-9\-]+)$/' => 'src/pages/details.php',
    '/^anime\/([a-zA-Z0-9\-]+)$/' => 'src/pages/anime.php',
    '/^watch\/([a-zA-Z0-9\-]+)$/' => 'src/pages/watch.php',
    '/^play\/([a-zA-Z0-9\-]+)$/' => 'src/pages/play.php',
    '/^genre\/([a-zA-Z0-9\-]+)$/' => 'src/pages/genre.php',

    
    // Sitemap Routes
    '/^sitemaps\/recentCN-sitemap\.xml$/' => 'sitemaps/recentCN-sitemap.php',
    '/^sitemaps\/recentDUB-sitemap\.xml$/' => 'sitemaps/recentDUB-sitemap.php',
    '/^sitemaps\/recentSUB-sitemap\.xml$/' => 'sitemaps/recentSUB-sitemap.php',
    '/^sitemaps\/ongoing-sitemap\.xml$/' => 'sitemaps/ongoing-sitemap.php',
    '/^sitemaps\/allanime-sitemap\.xml$/' => 'sitemaps/allanime-sitemap.php',
    '/^sitemaps\/sitemap\.xml$/' => 'sitemaps/sitemap.php',
    '/^sitemap\.xml$/' => 'sitemap.php',
];

// Route matching
$handled = false;

foreach ($routes as $pattern => $file) {
    if (preg_match($pattern, $uri, $matches)) {
        // Pass captured groups as GET parameters
        if (!empty($matches[1])) {
            $_GET['slug'] = $matches[1];
        }
        include $file;
        $handled = true;
        break;
    }
}

// Fallback to 404 if no route matches
if (!$handled) {
    http_response_code(404);
    include '404.php';
}
?>
