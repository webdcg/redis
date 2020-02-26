<?php

namespace Webdcg\Redis\Traits;

trait Transactions
{
    /**
     * Marks the start of a transaction block. Subsequent commands will be
     * queued for atomic execution using EXEC.
     * See: https://redis.io/commands/multi.
     *
     * @return transaction context
     */
    public function multi()
    {
        return $this->redis->multi();
    }


    /**
     * Executes all previously queued commands in a transaction and restores
     * the connection state to normal.
     *
     * See: https://redis.io/commands/exec.
     *
     * @return array    each element being the reply to each of the commands
     *                  in the atomic transaction.
     */
    public function exec($multi): array
    {
        return $multi->exec();
    }


    /**
     * Flushes all previously queued commands in a transaction and restores the
     * connection state to normal.
     *
     * See: https://redis.io/commands/discard.
     *
     * @param   $multi
     *
     * @return bool
     */
    public function discard(\Redis $multi): bool
    {
        return $multi->discard();
    }


    /**
     * Marks the given keys to be watched for conditional execution of a
     * transaction.
     *
     * See: https://redis.io/commands/watch.
     *
     * @param  splat $keys
     *
     * @return mixed
     */
    public function watch(...$keys)
    {
        return $this->redis->watch(...$keys);
    }

    public function unwatch(): bool
    {
        return false;
    }
}
