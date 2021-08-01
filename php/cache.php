<?php

include_once 'redis.php';

class Cache {

    private static $cacheInstance;

    
    /**
     * singleton & factory design pattern were used here
     */
    private static function getCacheInstance()
    {
        if (self::$cacheInstance == null){
            $cacheServer = CACHE_SERVER;
            if ($cacheServer == 'redis')
                self::$cacheInstance = new RedisClient;
        }

        return self::$cacheInstance;
    }

    /**
     * save data in the relavant cache-server
     */
    public static function set(string $key, string $value)
    {
        $client = self::getCacheInstance(); 
        $client->set($key, $value);
    }

    /**
     * get saved data from the relavant cache-server
     */
    public static function get(string $url, array $parameters = [], array $data = [], Closure $callback)
    {
        $client = self::getCacheInstance(); 
        $redisKey = self::generateUniqueKey($url, $parameters, $data);
        $value = $client->get($redisKey, $callback);
        return $value;
    }

    /**
     * delete cache
     */
    public static function delete(string $key)
    {
        $client = self::getCacheInstance();
        $client->delete($key);
    }

    /**
     * generate a unique key for the URL with params and data
     */
    private static function generateUniqueKey(string $url, array $parameters = [], array $data = [])
    {
        return md5($url . http_build_query($parameters) . http_build_query($data));
    }
}