<?php

namespace Webdcg\Redis\Traits;

use Webdcg\Redis\Exceptions\ScriptCommandException;

trait Scripting
{
    /*
     * Available Script Commands
     */
    protected $SCRIPT_COMMANDS = ['LOAD', 'FLUSH', 'KILL', 'EXISTS'];

    /**
     * Evaluate a LUA script serverside.
     *
     * See: https://redis.io/commands/eval.
     *
     * @param  string      $script
     * @param  array       $arguments
     * @param  int|integer $numKeys
     *
     * @return mixed       What is returned depends on what the LUA script
     *                     itself returns, which could be a scalar value
     *                     (int/string), or an array. Arrays that are returned
     *                     can also contain other arrays, if that's how it was
     *                     set up in your LUA script. If there is an error
     *                     executing the LUA script, the getLastError()
     *                     function can tell you the message that came back
     *                     from Redis (e.g. compile error).
     */
    public function eval(string $script, ?array $arguments = null, ?int $numKeys = null)
    {
        if (!is_null($arguments) && !is_null($numKeys)) {
            return $this->redis->eval($script, $arguments, $numKeys);
        }

        return $this->redis->eval($script);
    }


    /**
     * Evaluate a LUA script serverside, from the SHA1 hash of the script instead
     * of the script itself.
     *
     * In order to run this command Redis will have to have already loaded the
     * script, either by running it or via the SCRIPT LOAD command.
     *
     * See: https://redis.io/commands/evalsha.
     *
     * @param  string     $sha1         The sha1 encoded hash of the script you want to run.
     * @param  array|null $arguments    Arguments to pass to the LUA script.
     * @param  int|null   $numKeys      The number of arguments that should go into the
     *                                  KEYS array, vs. the ARGV array when Redis spins
     *                                  the script (optional).
     *
     * @return mixed
     */
    public function evalSha(string $sha1, ?array $arguments = null, ?int $numKeys = null)
    {
        if (!is_null($arguments) && !is_null($numKeys)) {
            return $this->redis->evalSha($sha1, $arguments, $numKeys);
        }

        return $this->redis->evalSha($sha1);
    }


    /**
     * Execute the Redis SCRIPT command to perform various operations on the
     * scripting subsystem.
     *
     * See: https://redis.io/commands/script-load.
     * See: https://redis.io/commands/script-flush.
     *
     * @param  string $command
     * @param  splat $scripts
     *
     * @return mixed            SCRIPT LOAD will return the SHA1 hash of the
     *                                 passed script on success, and FALSE on
     *                                 failure.
     *                          SCRIPT FLUSH should always return TRUE
     *                          SCRIPT KILL will return true if a script was
     *                                  able to be killed and false if not.
     *                          SCRIPT EXISTS will return an array with TRUE
     *                                  or FALSE for each passed script.
     */
    public function script(string $command, ...$scripts)
    {
        $command = strtoupper($command);

        if (!in_array($command, $this->SCRIPT_COMMANDS)) {
            throw new ScriptCommandException('Script Command not supported', 1);
        }

        if ($command == 'FLUSH' || $command == 'KILL') {
            return $this->redis->script($command);
        }

        if ($command == 'EXISTS') {
            return $this->redis->script($command, ...$scripts);
        }

        if (count($scripts) != 1) {
            throw new ScriptCommandException('Invalid Number of Scripts to Load', 1);
        }

        return $this->redis->script($command, $scripts[0]);
    }


    /**
     * The last error message (if any)
     *
     *
     * @return mixed|string|null    A string with the last returned script
     *                              based error message, or NULL if there
     *                              is no error.
     */
    public function getLastError(): ?string
    {
        return $this->redis->getLastError();
    }


    /**
     * Clear the last error message
     *
     * @return bool     true
     */
    public function clearLastError(): bool
    {
        return $this->redis->clearLastError();
    }


    /**
     * A utility method to prefix the value with the prefix setting for phpredis.
     *
     * @param  string $key  The value you wish to prefix
     *
     * @return string       If a prefix is set up, the value now prefixed.
     *                      If there is no prefix, the value will be returned
     *                      unchanged.
     */
    public function _prefix(string $key): string
    {
        return $this->redis->_prefix($key);
    }

    /**
     * A utility method to serialize values manually.
     *
     * This method allows you to serialize a value with whatever serializer is
     * configured, manually. This can be useful for serialization/unserialization
     * of data going in and out of EVAL commands as phpredis can't automatically
     * do this itself. Note that if no serializer is set, phpredis will change
     * Array values to 'Array', and Objects to 'Object'.
     *
     * @param  mixed|string|array|obhect    $value  The value to be serialized.
     *
     * @return mixed                        The serialized value.
     */
    public function _serialize($value)
    {
        return $this->redis->_serialize($value);
    }


    /**
     * A utility method to unserialize data with whatever serializer is set up.
     *
     * If there is no serializer set, the value will be returned unchanged.
     * If there is a serializer set up, and the data passed in is malformed,
     * an exception will be thrown. This can be useful if phpredis is
     * serializing values, and you return something from redis in a LUA script
     * that is serialized.
     *
     * @param  string $value    The value to be unserialized
     *
     * @return mixed            Unserialized value
     */
    public function _unserialize(string $value)
    {
        return $this->redis->_unserialize($value);
    }
}
