<?php

namespace Webdcg\Redis\Traits;

trait HyperLogLogs
{
    /**
     * Adds the specified elements to the specified HyperLogLog.
     *
     * @param  string $key
     * @param  array  $elements
     *
     * @return int              1 if at least 1 HyperLogLog internal register
     *                          was altered. 0 otherwise.
     */
    public function pfAdd(string $key, array $elements): int
    {
        return $this->redis->pfAdd($key, $elements);
    }

    /**
     * Return the approximated cardinality of the set(s) observed by the HyperLogLog at key(s)
     *
     * @return [type] [description]
     */
    public function pfCount(): bool
    {
        return false;
    }

    /**
     * Adds the specified elements to the specified HyperLogLog
     *
     * @return [type] [description]
     */
    public function pfMerge(): bool
    {
        return false;
    }
}
