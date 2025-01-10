<?php
// Function to fetch all links from a given URL
function fetch_links($url, &$visited) {
    $links = [];
    $html = file_get_contents($url);
    if ($html === false) {
        return $links;
    }

    $dom = new DOMDocument;
    @$dom->loadHTML($html);
    $anchorTags = $dom->getElementsByTagName('a');

    foreach ($anchorTags as $tag) {
        $link = $tag->getAttribute('href');
        // Convert relative URLs to absolute
        $link = resolve_url($url, $link);
        if ($link && !isset($visited[$link])) {
            $visited[$link] = true;
            $links[] = $link;
        }
    }
    return $links;
}

// Function to resolve absolute URLs
function resolve_url($base, $relative) {
    // If already absolute
    if (parse_url($relative, PHP_URL_SCHEME) != '') {
        return $relative;
    }

    // If starts with a slash, it's root-relative
    if ($relative[0] == '/') {
        return parse_url($base, PHP_URL_SCHEME) . '://' . parse_url($base, PHP_URL_HOST) . $relative;
    }

    // Otherwise, it's relative to the base URL
    $basePath = rtrim(dirname(parse_url($base, PHP_URL_PATH)), '/') . '/';
    return parse_url($base, PHP_URL_SCHEME) . '://' . parse_url($base, PHP_URL_HOST) . $basePath . $relative;
}

// Function to generate sitemap XML
function generate_sitemap($urls) {
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

    foreach ($urls as $url) {
        $sitemap .= '  <url>' . PHP_EOL;
        $sitemap .= '    <loc>' . htmlspecialchars($url) . '</loc>' . PHP_EOL;
        $sitemap .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL;
        $sitemap .= '    <changefreq>weekly</changefreq>' . PHP_EOL;
        $sitemap .= '    <priority>0.8</priority>' . PHP_EOL;
        $sitemap .= '  </url>' . PHP_EOL;
    }

    $sitemap .= '</urlset>';
    return $sitemap;
}

// Start crawling from the base URL
$base_url = 'https://anixtv.in'; // Change this to your website's URL
$visited = [];
$toVisit = [$base_url];
$allLinks = [];

while (!empty($toVisit)) {
    $url = array_shift($toVisit);
    if (!isset($visited[$url])) {
        $visited[$url] = true;
        $links = fetch_links($url, $visited);
        $allLinks = array_merge($allLinks, $links);
        $toVisit = array_merge($toVisit, $links);
    }
}

// Generate the sitemap and save it to sitemap.xml
$sitemap = generate_sitemap(array_unique($allLinks));
file_put_contents('sitemap.xml', $sitemap);

echo "Sitemap has been generated and saved to sitemap.xml";
// Start crawling from the base URL
$base_url = 'https://anixtv.in/home'; // Change this to your website's URL
$visited = [];
$toVisit = [$base_url];
$allLinks = [];

while (!empty($toVisit)) {
    $url = array_shift($toVisit);
    if (!isset($visited[$url])) {
        $visited[$url] = true;
        $links = fetch_links($url, $visited);
        $allLinks = array_merge($allLinks, $links);
        $toVisit = array_merge($toVisit, $links);
    }
}

// Generate the sitemap and save it to sitemap.xml
$sitemap = generate_sitemap(array_unique($allLinks));
file_put_contents('sitemaphome.xml', $sitemap);

echo "<br>Sitemap has been generated and saved to sitemaphome.xml";

// Start crawling from the base URL
$base_url = 'https://anixtv.in/anime/most-popular'; // Change this to your website's URL
$visited = [];
$toVisit = [$base_url];
$allLinks = [];

while (!empty($toVisit)) {
    $url = array_shift($toVisit);
    if (!isset($visited[$url])) {
        $visited[$url] = true;
        $links = fetch_links($url, $visited);
        $allLinks = array_merge($allLinks, $links);
        $toVisit = array_merge($toVisit, $links);
    }
}

// Generate the sitemap and save it to sitemap.xml
$sitemap = generate_sitemap(array_unique($allLinks));
file_put_contents('sitemappopular.xml', $sitemap);

echo "<br>Sitemap has been generated and saved to sitemappopular.xml";


// Start crawling from the base URL
$base_url = 'https://anixtv.in/anime/movie'; // Change this to your website's URL
$visited = [];
$toVisit = [$base_url];
$allLinks = [];

while (!empty($toVisit)) {
    $url = array_shift($toVisit);
    if (!isset($visited[$url])) {
        $visited[$url] = true;
        $links = fetch_links($url, $visited);
        $allLinks = array_merge($allLinks, $links);
        $toVisit = array_merge($toVisit, $links);
    }
}

// Generate the sitemap and save it to sitemap.xml
$sitemap = generate_sitemap(array_unique($allLinks));
file_put_contents('sitemapmovie.xml', $sitemap);

echo "<br>Sitemap has been generated and saved to sitemapmovie.xml";

// Start crawling from the base URL
$base_url = 'https://anixtv.in/anime/top-airing'; // Change this to your website's URL
$visited = [];
$toVisit = [$base_url];
$allLinks = [];

while (!empty($toVisit)) {
    $url = array_shift($toVisit);
    if (!isset($visited[$url])) {
        $visited[$url] = true;
        $links = fetch_links($url, $visited);
        $allLinks = array_merge($allLinks, $links);
        $toVisit = array_merge($toVisit, $links);
    }
}

// Generate the sitemap and save it to sitemap.xml
$sitemap = generate_sitemap(array_unique($allLinks));
file_put_contents('sitemapairing.xml', $sitemap);

echo "<br>Sitemap has been generated and saved to sitemapairing.xml";


// Start crawling from the base URL
$base_url = 'https://anixtv.in/anime/most-favorite'; // Change this to your website's URL
$visited = [];
$toVisit = [$base_url];
$allLinks = [];

while (!empty($toVisit)) {
    $url = array_shift($toVisit);
    if (!isset($visited[$url])) {
        $visited[$url] = true;
        $links = fetch_links($url, $visited);
        $allLinks = array_merge($allLinks, $links);
        $toVisit = array_merge($toVisit, $links);
    }
}

// Generate the sitemap and save it to sitemap.xml
$sitemap = generate_sitemap(array_unique($allLinks));
file_put_contents('sitemapfav.xml', $sitemap);

echo "<br>Sitemap has been generated and saved to sitemapfav.xml";

// Start crawling from the base URL
$base_url = 'https://anixtv.in/anime/completed'; // Change this to your website's URL
$visited = [];
$toVisit = [$base_url];
$allLinks = [];

while (!empty($toVisit)) {
    $url = array_shift($toVisit);
    if (!isset($visited[$url])) {
        $visited[$url] = true;
        $links = fetch_links($url, $visited);
        $allLinks = array_merge($allLinks, $links);
        $toVisit = array_merge($toVisit, $links);
    }
}

// Generate the sitemap and save it to sitemap.xml
$sitemap = generate_sitemap(array_unique($allLinks));
file_put_contents('sitemapcompleted.xml', $sitemap);

echo "<br>Sitemap has been generated and saved to sitemapcompleted.xml";


?>
