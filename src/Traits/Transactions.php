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
     * See: https://redis.io/commands/exec.
     *
     * @return array    each element being the reply to each of the commands
     *                  in the atomic transaction.
     */
    public function exec($multi): array
    {
        return $multi->exec();
    }

    public function discard(): bool
    {
        return false;
    }

    public function watch(): bool
    {
        return false;
    }

    public function unwatch(): bool
    {
        return false;
    }
}
