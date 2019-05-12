<?php

namespace Amir2b\Instagram\Api;

class Comment
{
    public static function comments($token, $media_id)
    {
        $url = "https://api.instagram.com/v1/media/{$media_id}/comments/?access_token={$token}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $jsonData = curl_exec($ch);
        curl_close($ch);

        return @json_decode($jsonData, true);
    }
}