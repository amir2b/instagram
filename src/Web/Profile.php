<?php

namespace Amir2b\Instagram\Web;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Storage;

class Profile
{
    public static function following()
    {
        ## make cookie
        if (Storage::exists('cookie.json')) {
            $cookies = json_decode(Storage::get('cookie.json'), true);
            $jar = new CookieJar(false, $cookies);
        } else {
            $jar = new CookieJar();
        }

        ## make client
        $client = new Client([
            'base_uri' => 'https://www.instagram.com/',
        ]);

        ## fetch following
        $response = $client->get('graphql/query/?query_hash=c56ee0ae1f89cdbd1c89e2bc6b8f3d18&variables=%7B%22id%22%3A%2212043191885%22%2C%22include_reel%22%3Atrue%2C%22fetch_mutual%22%3Afalse%2C%22first%22%3A24%7D', [
            'cookies' => $jar,
            'headers' => [
                'User-Agent' => config('instagram.user_agent'),
                'Origin' => 'https://www.instagram.com',
                'Referer' => 'https://www.instagram.com/amir.2b/following/',
                'X-Requested-With' => 'XMLHttpRequest',
                'x-ig-app-id' => '936619743392459',
                'X-Instagram-Gis' => 'd6d2c3f877e447f598bf0bed0bfa88e4',
            ],
            'http_errors' => false,
        ]);

        ## save cookie
        Storage::put('cookie.json', json_encode($jar->toArray()));

        ## parse json
        $response_json = json_decode($response->getBody(), true);

        if($response_json['status'] === 'ok') {
            return $response_json['data']['user']['edge_follow'];
        }

        dd($response_json);

        return [];
    }
}