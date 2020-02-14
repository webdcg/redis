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

        switch ($command) {
            case 'FLUSH':
            case 'KILL':
                return $this->redis->script($command);

            case 'LOAD':
                if (count($scripts) != 1) {
                    throw new ScriptCommandException('Invalid Number of Scripts to Load', 1);
                }

                return $this->redis->script($command, $scripts[0]);

            case 'EXISTS':
                return $this->redis->script($command, ...$scripts);
        }
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
