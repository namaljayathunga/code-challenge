<?php
class Cache {

    private const EXPIRE_TIME = 30;
    private const REDIS_SERVER = 'tcp://127.0.0.1:6379';

    /**
     * generate a unique key for the URL with params and data
     */
    public static function generateUniqueKey(string $url, array $parameters = [], array $data = [])
    {
        return md5($url . http_build_query($parameters) . http_build_query($data));
    }

    /**
     * save data in the redis-server
     */
    public static function set(string $key, $value)
    {
        $client = new Predis\Client(self::REDIS_SERVER);
        $client->set($key, $value);
        $client->expire($key, self::EXPIRE_TIME);
    }

    /**
     * get saved data from the redis-server
     */
    public static function get(string $url, array $parameters = [], array $data = [], Closure $callback)
    {
        $client = new Predis\Client(self::REDIS_SERVER);
        $redisKey = self::generateUniqueKey($url, $parameters, $data);
        $value = $client->get($redisKey);
        //check whether data is available in the redis-server
        if ($value == null){
            echo "nocache (for testing purpose) <br>";
            //call annonymouse function (make request if data not available)
            $callbackValue = $callback();
            self::set($redisKey, $callbackValue);
            return $callbackValue;
        }else{
            echo "from cache (for testing purpose) <br>";
            return $value;
        }
    }
}