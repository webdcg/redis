<?php

namespace Webdcg\Redis\Traits;

trait Scripting
{
    /**
     * Evaluate a LUA script serverside.
     * See: https://redis.io/commands/eval.
     *
     * @param  string      $script
     * @param  array       $arguments
     * @param  int|integer $numKeys
     *
     * @return mixed                    What is returned depends on what the LUA
     *                     script itself returns, which could be a scalar value
     *                     (int/string), or an array. Arrays that are returned
     *                     can also contain other arrays, if that's how it was
     *                     set up in your LUA script. If there is an error
     *                     executing the LUA script, the getLastError()
     *                     function can tell you the message that came back
     *                     from Redis (e.g. compile error).
     */
    public function eval(string $script, array $arguments = [], int $numKeys = 0)
    {
        return $this->redis->eval($script);
    }

    public function evalSha(): bool
    {
        return false;
    }

    public function script(): bool
    {
        return false;
    }

    public function getLastError(): bool
    {
        return false;
    }

    public function clearLastError(): bool
    {
        return false;
    }

    public function prefix(): bool
    {
        return false;
    }

    public function unserialize(): bool
    {
        return false;
    }

    public function serialize(): bool
    {
        return false;
    }
}
