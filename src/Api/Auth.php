<?php

namespace Amir2b\Instagram\Api;

class Auth
{
    //https://www.instagram.com/developer/endpoints/
    /**
     * @return string
     */
    public static function login(): string
    {
        $client_id = config('instagram.api.client_id');
        $redirect_uri = config('instagram.api.redirect_uri');
        $scope = 'basic';
        //$scope = 'basic+likes+comments+relationships';

        return "https://api.instagram.com/oauth/authorize?client_id={$client_id}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}";
    }

    public static function callback(): array
    {
        $apiData = [
            'client_id' => config('instagram.api.client_id'),
            'client_secret' => config('instagram.api.client_secret'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => config('instagram.api.redirect_uri'),
            'code' => request('code'),
        ];

        $apiHost = 'https://api.instagram.com/oauth/access_token';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiHost);
        curl_setopt($ch, CURLOPT_POST, count($apiData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $jsonData = curl_exec($ch);
        curl_close($ch);

        $result = @json_decode($jsonData, true);

        if (empty($result['access_token'])) {
            die($result);
        }

        return $result;
    }
}