<?php

namespace Amir2b\Instagram\Web;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Storage;

class Profile
{
    public static function following($end_cursor)
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
        $response = $client->get('graphql/query/', [
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
            'query' => [
                'query_hash' => 'c56ee0ae1f89cdbd1c89e2bc6b8f3d18',
                'variables' => json_encode([
                    'id' => '12043191885',
                    'include_reel' => 'true',
                    'fetch_mutual' => 'false',
                    'first' => 24,
                    'after' => $end_cursor,
                ]),
            ],
        ]);

        ## save cookie
        Storage::put('cookie.json', json_encode($jar->toArray()));

        ## parse json
        $response_json = json_decode($response->getBody(), true);

        if ($response_json['status'] === 'ok') {
            return $response_json['data']['user']['edge_follow'];
        }

        dd($response_json);

        return [];
    }

    public static function followers($end_cursor)
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
        $response = $client->get('graphql/query/', [
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
            'query' => [
                'query_hash' => '56066f031e6239f35a904ac20c9f37d9',
                'variables' => json_encode([
                    'id' => '12043191885',
                    'include_reel' => 'true',
                    'fetch_mutual' => 'false',
                    'first' => 24,
                    'after' => $end_cursor,
                ]),
            ],
        ]);

        ## save cookie
        Storage::put('cookie.json', json_encode($jar->toArray()));

        ## parse json
        $response_json = json_decode($response->getBody(), true);

        if ($response_json['status'] === 'ok') {
            return $response_json['data']['user']['edge_followed_by'];
        }

        dd($response_json);

        return [];
    }
}
