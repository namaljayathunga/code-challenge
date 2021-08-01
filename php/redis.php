<?php

class RedisClient
{
    private $redisInstance;

    /**
     * Conent to the redis server
     */
    public function __construct()
    {
        try {
            //we can also dereclty connet phpredis here.
            $this->redisInstance = new Predis\Client(REDIS_HOST.':'.REDIS_PORT);
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    /**
     * save data in the redis-server
     */
    public function set(string $key, string $value)
    {
        $this->redisInstance->set($key, $value);
        $this->redisInstance->expire($key, REDIS_EXPIRE);
    }

    /**
     * get saved data from the redis-server
     */
    public function get(string $redisKey, Closure $callback)
    {
        $value = $this->redisInstance->get($redisKey);
        //check whether data is available in the redis-server
        if ($value == null){
            echo "data from nocache (for demonstrate purpose) <br><br>";
            //call annonymouse function (make request if data not available)
            $callbackValue = $callback();
            self::set($redisKey, $callbackValue);
            return $callbackValue;
        }else{
            echo "data from cache (for demonstrate purpose) <br><br>";
            return $value;
        }
    }

    /**
     * delete cache
     */
    public function delete(string $key)
    {
        $this->redisInstance->del($key);
        return true;
    }
}
