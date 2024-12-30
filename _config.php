<?php 

$conn = new mysqli("HOSTNAME", "USERNAME", "PASSWORD", "DATABASE");

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

$websiteTitle = "AniPaca";
$websiteUrl = "//{$_SERVER['SERVER_NAME']}";
$websiteLogo = $websiteUrl . "/public/logo/logo.png";
$contactEmail = "@gmail.com";

$version = "0.1";

$discord = "https://discord.gg/aVvqx77RGs";
$github = "https://github.com/PacaHat";
$twitter = "https://x.com/PacaHat";

// all the api you need

$api = ""; //https://github.com/ghoshRitesh12/aniwatch-api Also use this in src/component/qtip.php
$zpi = ""; //https://github.com/itzzzme/anime-api
$proxy = ""; //https://github.com/itzzzme/m3u8proxy


$banner = $websiteUrl . "/public/images/banner.png";

?>
    