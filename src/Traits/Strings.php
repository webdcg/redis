<?php

namespace Webdcg\Redis\Traits;

trait Strings
{
    /**
     * Set the string value in argument as value of the key. If you're using Redis >= 2.6.12, you can pass extended
     * options as explained below
     *
     * @param string $key
     * @param mixed $value
     * @param mixed $args Timeout or Options Array (optional). If you pass an integer, phpredis will redirect to SETEX,
     *                    and will try to use Redis >= 2.6.12 extended options if you pass an array with valid values
     */
    public function set(string $key, $value, ...$args) : bool
    {
        if (empty($args)) {
            return $this->redis->set($key, $value);
        }

        return $this->redis->set($key, $value, $args[0]);
    }
}
