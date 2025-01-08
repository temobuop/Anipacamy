<?php

function fetchAnimeData($animeId) {
    session_start();

    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

    
    global $api;

    if (!isset($api)) {
        echo "API endpoint is not defined in _config.php.";
        exit();
    }

    $url = "$api/anime/$animeId";
    $animeDataResponse = file_get_contents($url);

    if ($animeDataResponse === false) {
        echo "Failed to fetch anime data.";
        exit();
    }

    $animeResponse = json_decode($animeDataResponse, true);

    if ($animeResponse === null) {
        echo "Failed to decode JSON.";
        exit;
    }

    if (!empty($animeResponse['success']) && isset($animeResponse['data']['anime'])) {
        $anime = $animeResponse['data']['anime'];
        $info = $anime['info'] ?? [];
        $more = $anime['moreInfo'] ?? [];
        $sts = $info['stats'] ?? [];
        $season = $animeResponse['data']['seasons'] ?? [];
        $seasonList = [];
        foreach ($season as $season) {
            $seasonList[] = [
                'id' => $season['id'] ?? null,
                'name' => $season['name'] ?? null,
                'title' => $season['title'] ?? null,
                'poster' => $season['poster'] ?? null,
                'isCurrent' => $season['isCurrent'] ?? false
            ];
        }

        $trailers = $info['promotionalVideos'] ?? [];
        $trailerList = [];
        foreach ($trailers as $trailer) {
            $trailerList[] = [
                'title' => $trailer['title'] ?? 'No title',
                'source' => $trailer['source'] ?? '',
                'thumbnail' => $trailer['thumbnail'] ?? ''
            ];
        }

        $characters = $info['charactersVoiceActors'] ?? [];
        $chList = [];
        foreach ($characters as $character) {
            $chList[] = [
                'character' => $character['character'] ?? 'Unknown character',
                'voiceActor' => $character['voiceActor'] ?? 'Unknown voice actor'
            ];
        }

        // Add related animes
        $relatedAnimes = $animeResponse['data']['relatedAnimes'] ?? [];
        $relatedAnimeList = [];
        foreach ($relatedAnimes as $relatedAnime) {
            $relatedAnimeList[] = [
                'id' => $relatedAnime['id'] ?? null,
                'name' => $relatedAnime['name'] ?? $relatedAnime['jname'] ?? null,
                'episodes' => [
                    'sub' => $relatedAnime['episodes']['sub'] ?? null,
                    'dub' => $relatedAnime['episodes']['dub'] ?? null
                ],
                'poster' => $relatedAnime['poster'] ?? null,
                'type' => $relatedAnime['type'] ?? null
            ];
        }

        // Add recommended animes
        $recommendedAnimes = $animeResponse['data']['recommendedAnimes'] ?? [];
        $recommendedAnimeList = [];
        foreach ($recommendedAnimes as $recommendedAnime) {
            $recommendedAnimeList[] = [
                'id' => $recommendedAnime['id'] ?? null,
                'name' => $recommendedAnime['name'] ?? $recommendedAnime['jname'] ?? null,
                'poster' => $recommendedAnime['poster'] ?? null,
                'duration' => $recommendedAnime['duration'] ?? null,
                'type' => $recommendedAnime['type'] ?? null,
                'rating' => $recommendedAnime['rating'] ?? null,
                'episodes' => [
                    'sub' => $recommendedAnime['episodes']['sub'] ?? null,
                    'dub' => $recommendedAnime['episodes']['dub'] ?? null
                ]
            ];
        }

        return [
            'poster' => $info['poster'] ?? 'default_poster.jpg',
            'id' => $info['id'] ?? null,
            'title' => $info['name'] ?? $info['jname'] ?? null,
            'japanese' => $more['japanese'] ?? null,
            'overview' => $info['description'] ?? 'No description',
            'malId' => $info['malId'] ?? null,
            'anilistId' => $info['anilistId'] ?? null,
            'showType' => $sts['type'] ?? null,
            'rating' => $sts['rating'] ?? null,
            'subEp' => $sts['episodes']['sub'] ?? 0,
            'dubEp' => $sts['episodes']['dub'] ?? 0,
            'aired' => $more['aired'] ?? null,
            'premiered' => $more['premiered'] ?? null,
            'malscore' => $more['malscore'] ?? null,
            'status' => $more['status'] ?? null,
            'genres' => $more['genres'] ?? [],
            'quality' => $sts['quality'] ?? null,
            'duration' => $sts['duration'] ?? null,
            'trailers' => $trailerList,
            'actors' => $chList,
            'studio' => $more['studios'] ?? null,
            'producer' => $more['producers'] ?? [],
            'season' => $seasonList,
            'relatedAnimes' => $relatedAnimeList,
            'recommendedAnimes' => $recommendedAnimeList
        ];
    }

    return false;
}
?>
