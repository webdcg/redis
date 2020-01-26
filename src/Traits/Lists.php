<?php

namespace Webdcg\Redis\Traits;

trait Lists
{
    public function blPop(): bool
    {
        return false;
    }

    public function brPop(): bool
    {
        return false;
    }

    public function bRPopLPush(): bool
    {
        return false;
    }

    public function lIndex(): bool
    {
        return false;
    }

    public function lGet(): bool
    {
        return false;
    }

    public function lInsert(): bool
    {
        return false;
    }

    public function lLen(): bool
    {
        return false;
    }

    public function lSize(): bool
    {
        return false;
    }

    public function lPop(): bool
    {
        return false;
    }

    /**
     * Adds the string value to the head (left) of the list. Creates the list
     * if the key didn't exist. If the key exists and is not a list, FALSE is
     * returned.
     * See: https://redis.io/commands/lpush.
     *
     * @param  string $key
     * @param  mixed $value     Value to push in key
     *
     * @return int              LONG The new length of the list in case of
     *                          success, FALSE in case of Failure.
     */
    public function lPush(string $key, $value): int
    {
        return $this->redis->lPush($key, $value);
    }

    /**
     * Adds the string value to the head (left) of the list if the list exists.
     * See: https://redis.io/commands/lpushx.
     *
     * @param  string $key
     * @param  mixed $value    Value to push in key
     *
     * @return int              LONG The new length of the list in case of
     *                          success, FALSE in case of Failure.
     */
    public function lPushx(string $key, $value): int
    {
        return $this->redis->lPushx($key, $value);
    }

    /**
     * Returns the specified elements of the list stored at the specified key
     * in the range [start, end]. start and stop are interpreted as indices:
     *     0 the first element, 1 the second ...
     *     -1 the last element, -2 the penultimate ...
     * See: https://redis.io/commands/lrange.
     *
     * @param  string      $key
     * @param  int|integer $start
     * @param  int|integer $end
     *
     * @return array                Array containing the values in specified range.
     */
    public function lRange(string $key, int $start = 0, int $end = -1): array
    {
        return $this->redis->lRange($key, $start, $end);
    }

    /**
     * Returns the specified elements of the list stored at the specified key
     * in the range [start, end]. start and stop are interpreted as indices:
     *     0 the first element, 1 the second ...
     *     -1 the last element, -2 the penultimate ...
     * Note: lGetRange is an alias for lRange and will be removed in future
     * versions of phpredis.
     * See: https://redis.io/commands/lrange.
     *
     * @param  string      $key
     * @param  int|integer $start
     * @param  int|integer $end
     *
     * @return array                Array containing the values in specified range.
     */
    public function lGetRange(string $key, int $start = 0, int $end = -1): array
    {
        return $this->redis->lRange($key, $start, $end);
    }

    public function lRem(): bool
    {
        return false;
    }

    public function lRemove(): bool
    {
        return false;
    }

    public function lSet(): bool
    {
        return false;
    }

    public function lTrim(): bool
    {
        return false;
    }

    public function listTrim(): bool
    {
        return false;
    }

    public function rPop(): bool
    {
        return false;
    }

    public function rPopLPush(): bool
    {
        return false;
    }

    public function rPush(): bool
    {
        return false;
    }

    public function rPushX(): bool
    {
        return false;
    }
}
