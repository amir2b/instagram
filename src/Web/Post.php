<?php

namespace Amir2b\Instagram\Web;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Storage;

class Post
{
    public static function likes(string $code, $end_cursor = null)
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
                'query_hash' => 'e0f59e4a1c8d78d0161873bc2ee7ec44',
                'variables' => json_encode([
                    'shortcode' => $code,
                    'include_reel' => true,
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
            return $response_json['data']['shortcode_media']['edge_liked_by'];
        }

        dd($response_json);

        return [];
    }

    public static function get(string $code)
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
                'query_hash' => '477b65a610463740ccdb83135b2014db',
                'variables' => json_encode([
                    'shortcode' => $code,
                    'child_comment_count' => 3,
                    'fetch_comment_count' => 40,
                    'parent_comment_count' => 24,
                    'has_threaded_comments' => true,
                ]),
            ],
        ]);

        ## save cookie
        Storage::put('cookie.json', json_encode($jar->toArray()));

        ## parse json
        $response_json = json_decode($response->getBody(), true);

        if ($response_json['status'] === 'ok') {
            return $response_json['data']['shortcode_media'];
        }

        dd($response_json);

        return [];
    }
}