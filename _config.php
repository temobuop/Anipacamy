<?php 

$conn = new mysqli("localhost", "anipacat_anipaca", "iJeTUMxpeR#R", "anipacat_anime");

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
$telegram = "https://t.me/PacaHat";

// all the api you need

$api = "https://hi.anime.org.im/api/v2/hianime"; //https://github.com/ghoshRitesh12/aniwatch-api Also use this in src/component/qtip.php
$zpi = "https://zen.anime.org.im/api"; //https://github.com/itzzzme/anime-api
$proxy = "your-hosted-proxy.com/cors?url="; //https://github.com/shashstormer/m3u8_proxy-cors


$banner = $websiteUrl . "/public/images/banner.png";

    
