<?php 

$conn = new mysqli("sql207.infinityfree.com", "if0_39053689", "BVeEvm6KU1GRHF", "if0_39053689_anipacamy"); //just like $conn = new mysqli("localhost", "root", "", "anipaca");


if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    echo("Database connection failed.");
}

$websiteTitle = "AniPaca";
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$websiteUrl = "{$protocol}://{$_SERVER['SERVER_NAME']}";
$websiteLogo = $websiteUrl . "/public/logo/logo.png";
$contactEmail = "raisulentertainment@gmail.com";

$version = "1.0.2";

$discord = "https://dcd.gg/anipaca";
$github = "https://github.com/PacaHat";
$telegram = "https://t.me/anipaca";
$instagram = "https://www.instagram.com/pxr15_"; 

// all the api you need
$zpi = "https://api-gamma-lac-17.vercel.app/api"; //https://github.com/PacaHat/zen-api
$proxy = $websiteUrl . "/src/ajax/proxy.php?url=";

//If you want faster loading speed just put // before the first proxy and remove slashes from this one 
//$proxy = "https://your-hosted-proxy.com/proxy?url="; //https://github.com/PacaHat/shrina-proxy


$banner = $websiteUrl . "/public/images/banner.png";

    
