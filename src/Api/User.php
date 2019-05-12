<?php

namespace Amir2b\Instagram\Api;

class User
{
    public static function self(string $token)
    {
        $url = "https://api.instagram.com/v1/users/self/?access_token={$token}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $jsonData = curl_exec($ch);
        curl_close($ch);

        return @json_decode($jsonData, true);
    }

    public static function recent($token)
    {
        $url = "https://api.instagram.com/v1/users/self/media/recent/?access_token={$token}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $jsonData = curl_exec($ch);
        curl_close($ch);

        return @json_decode($jsonData, true);
    }
}