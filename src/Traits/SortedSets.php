<?php

namespace Webdcg\Redis\Traits;

trait SortedSets
{
    public function bzPop(): bool
    {
        return false;
    }

    /**
     * Add one or more members to a sorted set or update its score if it
     * already exists.
     * See: https://redis.io/commands/zadd.
     *
     * @param  string $key
     * @param  float  $score
     * @param  mixed] $member
     *
     * @return int              1 if the element is added. 0 otherwise.
     */
    public function zAdd(string $key, float $score, $member): int
    {
        return $this->redis->zAdd($key, $score, $member);
    }

    /**
     * Get the number of members in a sorted set.
     * See: https://redis.io/commands/zcard.
     *
     * @param  string $key
     *
     * @return int          The Set's cardinality
     */
    public function zCard(string $key): int
    {
        return $this->redis->zCard($key);
    }

    /**
     * Get the number of members in a sorted set.
     * Note: zSize is an alias for zCard and will be removed in future
     * versions of phpredis.
     * See: https://redis.io/commands/zcard.
     *
     * @param  string $key
     *
     * @return int          The Set's cardinality
     */
    public function zSize(string $key): int
    {
        return $this->redis->zCard($key);
    }

    /**
     * Returns the number of elements of the sorted set stored at the specified
     * key which have scores in the range [start, end]. Adding a parenthesis
     * before start or end excludes it from the range. +inf and -inf are also
     * valid limits.
     * See: https://redis.io/commands/zcount.
     *
     * @param  string $key
     * @param  mixed|int|string $start
     * @param  mixed|int|string $end
     *
     * @return int                      the size of a corresponding zRangeByScore.
     */
    public function zCount(string $key, $start, $end): int
    {
        return $this->redis->zCount($key, $start, $end);
    }

    public function zIncrBy(): bool
    {
        return false;
    }

    public function zinterstore(): bool
    {
        return false;
    }

    public function zInter(): bool
    {
        return false;
    }

    public function zPop(): bool
    {
        return false;
    }

    public function zRange(): bool
    {
        return false;
    }

    public function zRangeByScore(): bool
    {
        return false;
    }

    public function zRevRangeByScore(): bool
    {
        return false;
    }

    public function zRangeByLex(): bool
    {
        return false;
    }

    public function zRank(): bool
    {
        return false;
    }

    public function zRevRank(): bool
    {
        return false;
    }

    public function zRem(): bool
    {
        return false;
    }

    public function zDelete(): bool
    {
        return false;
    }

    public function zRemove(): bool
    {
        return false;
    }

    public function zRemRangeByRank(): bool
    {
        return false;
    }

    public function zDeleteRangeByRank(): bool
    {
        return false;
    }

    public function zRemRangeByScore(): bool
    {
        return false;
    }

    public function zDeleteRangeByScore(): bool
    {
        return false;
    }

    public function zRemoveRangeByScore(): bool
    {
        return false;
    }

    public function zRevRange(): bool
    {
        return false;
    }

    public function zScore(): bool
    {
        return false;
    }

    public function zunionstore(): bool
    {
        return false;
    }

    public function zUnion(): bool
    {
        return false;
    }

    public function zScan(): bool
    {
        return false;
    }
}
