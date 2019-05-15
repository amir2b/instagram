<?php

namespace Amir2b\Instagram\Web;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Storage;

class Auth
{
    /**
     * @param string|null $username
     * @param string|null $password
     * @return array
     */
    public static function login(string $username = null, string $password = null):array
    {
        if ($username === null) {
            $username = config('instagram.web.username');
        }
        if ($password === null) {
            $password = config('instagram.web.password');
        }

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

        ## fetch fresh cookie
        $client->get('accounts/login/', [
            'cookies' => $jar,
            'headers' => [
                'User-Agent' => config('instagram.user_agent'),
            ],
        ]);

        ## send username and password
        $response = $client->post('accounts/login/ajax/', [
            'cookies' => $jar,
            'headers' => [
                'User-Agent' => config('instagram.user_agent'),
                'Origin' => 'https://www.instagram.com',
                'Referer' => 'https://www.instagram.com/accounts/login/',
                'X-Requested-With' => 'XMLHttpRequest',
                'x-csrftoken' => $jar->getCookieByName('csrftoken')->getValue(),
                'x-ig-app-id' => '936619743392459',
                'X-Instagram-AJAX' => '01ad059a0eb4',
            ],
            'form_params' => [
                'username' => $username,
                'password' => $password,
            ],
            'http_errors' => false,
        ]);

        ## save cookie
        Storage::put('cookie.json', json_encode($jar->toArray()));

        ## parse json
        $response_json = json_decode($response->getBody(), true);

        ## if not login
        if (array_key_exists('authenticated', $response_json) && $response_json['authenticated'] === false && $response_json['user'] === false) {
            return [
                'login' => false,
            ];
        }

        ## if need two factor
        if (array_key_exists('two_factor_required', $response_json)) {
            return [
                'login' => false,
                'code' => $response_json['two_factor_info']['two_factor_identifier'],
            ];
        }

        ## if successfully login
        return [
            'login' => true,
        ];
    }

    /**
     * @param string $identifier
     * @param int $code
     * @param string|null $username
     * @return array
     */
    public static function two_factor(string $identifier, int $code, string $username = null):array
    {
        if ($username === null) {
            $username = config('instagram.web.username');
        }

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

        ## send code
        $response = $client->post('accounts/login/ajax/two_factor/', [
            'cookies' => $jar,
            'headers' => [
                'User-Agent' => config('instagram.user_agent'),
                'Origin' => 'https://www.instagram.com',
                'Referer' => 'https://www.instagram.com/accounts/login/two_factor',
                'X-Requested-With' => 'XMLHttpRequest',
                'x-csrftoken' => $jar->getCookieByName('csrftoken')->getValue(),
                'x-ig-app-id' => '936619743392459',
                'X-Instagram-AJAX' => '01ad059a0eb4',
            ],
            'form_params' => [
                'username' => $username,
                'identifier' => $identifier,
                'verificationCode' => $code,
            ],
            'http_errors' => false,
        ]);

        ## save cookie
        Storage::put('cookie.json', json_encode($jar->toArray()));

        ## parse json
        $response_json = json_decode($response->getBody(), true);

        if ($response_json['status'] === 'fail') {
            return [
                'login' => false,
                'mwssage' => $response_json['message'],
            ];
        }

        return [
            'login' => true,
            'user_id' => $response_json['userId'],
            'fr' => $response_json['fr'],
        ];
    }
}