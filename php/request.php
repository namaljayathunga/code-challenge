<?php

require_once 'cache.php';

class SimpleJsonRequest
{
    private static function makeRequest(string $method, string $url, array $parameters = null, array $data = null)
    {
        $opts = [
            'http' => [
                'method'  => $method,
                'header'  => 'Content-type: application/json',
                'content' => $data ? json_encode($data) : null
            ]
        ];

        $url .= ($parameters ? '?' . http_build_query($parameters) : '');
        return file_get_contents($url, false, stream_context_create($opts));
    }

    public static function get(string $url, array $parameters = [])
    {
        $response = Cache::get($url, $parameters, [], function() use ($url, $parameters) {
            return self::makeRequest('GET', $url, $parameters);
        });

        return json_decode($response);
    }

    //usually, it is better to keep uncached post request
    public static function post(string $url, array $parameters = [], array $data)
    {
        return json_decode(self::makeRequest('POST', $url, $parameters, $data));
    }

    public static function put(string $url, array $parameters = [], array $data)
    {
        $response = Cache::get($url, $parameters, $data, function() use ($url, $parameters, $data) {
            return self::makeRequest('PUT', $url, $parameters, $data);
        });
        return json_decode($response);
    }   

    public static function patch(string $url, array $parameters = [], array $data)
    {
        $response = Cache::get($url, $parameters, $data, function() use ($url, $parameters, $data) {
            return self::makeRequest('PATCH', $url, $parameters, $data);
        });
        return json_decode($response);
    }

    //usually, it is better to keep uncached delete request
    public static function delete(string $url, array $parameters = [], array $data = [])
    {
        return json_decode(self::makeRequest('DELETE', $url, $parameters, $data));
    }

}