<?php

namespace Webdcg\Redis\Traits;

use Exception;

trait Lists
{
    /**
     * Is a blocking lPop primitive. If at least one of the lists contains at
     * least one element, the element will be popped from the head of the list
     * and returned to the caller. If all the list identified by the keys
     * passed in arguments are empty, blPop will block during the specified
     * timeout until an element is pushed to one of those lists. This element
     * will be popped.
     * See: https://redis.io/commands/blpop.
     *
     * @param  array  $keys
     * @param  int    $timeout
     *
     * @return array            ['listName', 'element']
     */
    public function blPop(array $keys, int $timeout): array
    {
        return $this->redis->blPop($keys, $timeout);
    }

    /**
     * Is a blocking rPop primitive. If at least one of the lists contains at
     * least one element, the element will be popped from the head of the list
     * and returned to the caller. If all the list identified by the keys
     * passed in arguments are empty, brPop will block during the specified
     * timeout until an element is pushed to one of those lists. This element
     * will be popped.
     * See: https://redis.io/commands/brpop.
     *
     * @param  array  $keys
     * @param  int    $timeout
     *
     * @return array            ['listName', 'element']
     */
    public function brPop(array $keys, int $timeout): array
    {
        return $this->redis->brPop($keys, $timeout);
    }

    public function bRPopLPush(): bool
    {
        return false;
    }

    /**
     * Return the specified element of the list stored at the specified key.
     *     0 the first element, 1 the second ...
     *     -1 the last element, -2 the penultimate ...
     * Return FALSE in case of a bad index or a key that doesn't point to a
     * list.
     * See: https://redis.io/commands/lindex.
     *
     * @param  string $key
     * @param  int    $index
     *
     * @return string       String the element at this index
     *                      Bool FALSE if the key identifies a non-string data
     *                      type, or no value corresponds to this index in the
     *                      list Key.
     */
    public function lIndex(string $key, int $index): string
    {
        return $this->redis->lIndex($key, $index);
    }

    /**
     * Return the specified element of the list stored at the specified key.
     *     0 the first element, 1 the second ...
     *     -1 the last element, -2 the penultimate ...
     * Return FALSE in case of a bad index or a key that doesn't point to a
     * list.
     * Note: lGet is an alias for lIndex and will be removed in future versions
     * of phpredis.
     * See: https://redis.io/commands/lindex.
     *
     * @param  string $key
     * @param  int    $index
     *
     * @return string       String the element at this index
     *                      Bool FALSE if the key identifies a non-string data
     *                      type, or no value corresponds to this index in the
     *                      list Key.
     */
    public function lGet(string $key, int $index): string
    {
        return $this->redis->lIndex($key, $index);
    }

    /**
     * Insert value in the list before or after the pivot value.
     * The parameter options specify the position of the insert (before or
     * after). If the list didn't exists, or the pivot didn't exists, the
     * value is not inserted.
     * See: https://redis.io/commands/linsert.
     *
     * @param  string $key
     * @param  string $position b => Redis::BEFORE | a => Redis::AFTER
     * @param  mixed $pivot
     * @param  mixed $value
     *
     * @return int              The number of the elements in the list, -1 if
     *                          the pivot didn't exists.
     */
    public function lInsert(string $key, string $position, $pivot, $value): int
    {
        if (strtolower($position) == 'a') {
            return $this->redis->lInsert($key, \Redis::AFTER, $pivot, $value);
        } elseif (strtolower($position) == 'b') {
            return $this->redis->lInsert($key, \Redis::BEFORE, $pivot, $value);
        } else {
            throw new Exception("Error Processing Request", 1);
        }
    }

    /**
     * Returns the size of a list identified by Key.
     * If the list didn't exist or is empty, the command returns 0. If the data
     * type identified by Key is not a list, the command return FALSE.
     * See: https://redis.io/commands/llen.
     *
     * @param  string $key
     *
     * @return int          LONG The size of the list identified by Key exists.
     *                      BOOL FALSE if the data type identified by Key is
     *                      not list.
     */
    public function lLen(string $key): int
    {
        return $this->redis->lLen($key);
    }

    /**
     * Returns the size of a list identified by Key.
     * If the list didn't exist or is empty, the command returns 0. If the data
     * type identified by Key is not a list, the command return FALSE.
     * Note: lSize is an alias for lLen and will be removed in future versions
     * of phpredis.
     * See: https://redis.io/commands/llen.
     *
     * @param  string $key
     *
     * @return int          LONG The size of the list identified by Key exists.
     *                      BOOL FALSE if the data type identified by Key is
     *                      not list.
     */
    public function lSize(string $key): int
    {
        return $this->redis->lLen($key);
    }

    /**
     * Return and remove the first element of the list.
     * See: https://redis.io/commands/lpop.
     *
     * @param  string $key
     *
     * @return string       STRING if command executed successfully
     *                      BOOL FALSE in case of failure (empty list)
     */
    public function lPop(string $key): string
    {
        return $this->redis->lPop($key);
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

    /**
     * Removes the first count occurrences of the value element from the list.
     * If count is zero, all the matching elements are removed. If count is
     * negative, elements are removed from tail to head.
     * Note: The argument order is not the same as in the Redis documentation.
     * This difference is kept for compatibility reasons.
     * See: https://redis.io/commands/lrem.
     *
     * @param  string      $key
     * @param  mixed       $value
     * @param  int|integer $count
     *
     * @return int                  LONG the number of elements to remove.
     *                              BOOL FALSE if the value identified by
     *                              key is not a list.
     */
    public function lRem(string $key, $value, int $count = 0): int
    {
        return $this->redis->lRem($key, $value, $count);
    }

    /**
     * Removes the first count occurrences of the value element from the list.
     * If count is zero, all the matching elements are removed. If count is
     * negative, elements are removed from tail to head.
     * Note: The argument order is not the same as in the Redis documentation.
     * This difference is kept for compatibility reasons.
     * Note: lRemove is an alias for lRem and will be removed in future
     * versions of phpredis.
     * See: https://redis.io/commands/lrem.
     *
     * @param  string      $key
     * @param  mixed       $value
     * @param  int|integer $count
     *
     * @return int                  LONG the number of elements to remove.
     *                              BOOL FALSE if the value identified by
     *                              key is not a list.
     */
    public function lRemove(string $key, $value, int $count = 0): int
    {
        return $this->redis->lRem($key, $value, $count);
    }

    /**
     * Set the list at index with the new value.
     *
     * @param  string $key
     * @param  int    $index
     * @param  mixed  $value
     *
     * @return bool             TRUE if the new value was set. FALSE if the
     *                          index is out of range, or data type identified
     *                          by key is not a list.
     */
    public function lSet(string $key, int $index, $value): bool
    {
        return $this->redis->lSet($key, $index, $value);
    }

    /**
     * Trims an existing list so that it will contain only a specified range
     * of elements.
     * See: https://redis.io/commands/ltrim.
     *
     * @param  string      $key
     * @param  int|integer $start
     * @param  int|integer $stop
     *
     * @return bool                 return FALSE if the key identify a non-list value.
     */
    public function lTrim(string $key, int $start = 0, int $stop = -1): bool
    {
        return $this->redis->lTrim($key, $start, $stop);
    }

    /**
     * Trims an existing list so that it will contain only a specified range
     * of elements.
     * Note: listTrim is an alias for lTrim and will be removed in future
     * versions of phpredis.
     * See: https://redis.io/commands/ltrim.
     *
     * @param  string      $key
     * @param  int|integer $start
     * @param  int|integer $stop
     *
     * @return bool                 return FALSE if the key identify a non-list value.
     */
    public function listTrim(string $key, int $start = 0, int $stop = -1): bool
    {
        return $this->redis->lTrim($key, $start, $stop);
    }

    /**
     * Returns and removes the last element of the list.
     * See: https://redis.io/commands/rpop.
     *
     * @param  string $key
     *
     * @return string        STRING if command executed successfully
     *                      BOOL FALSE in case of failure (empty list)
     */
    public function rPop(string $key): string
    {
        return $this->redis->rPop($key);
    }

    /**
     * Pops a value from the tail of a list, and pushes it to the front of
     * another list. Also return this value. (redis >= 1.1)
     * See: https://redis.io/commands/rpoplpush.
     *
     * @param  string $sourceKey
     * @param  string $destinationKey
     *
     * @return string                   The element that was moved in case of
     *                                  success, FALSE in case of failure.
     */
    public function rPopLPush(string $sourceKey, string $destinationKey): string
    {
        return $this->redis->rPopLPush($sourceKey, $destinationKey);
    }

    /**
     * Adds the string value to the tail (right) of the list. Creates the list
     * if the key didn't exist. If the key exists and is not a list, FALSE is
     * returned.
     * See: https://redis.io/commands/rpush.
     *
     * @param  string $key
     * @param  mixed $value
     *
     * @return int          LONG The new length of the list in case of success,
     *                      FALSE in case of Failure.
     */
    public function rPush(string $key, $value): int
    {
        return $this->redis->rPush($key, $value);
    }

    /**
     * Adds the string value to the tail (right) of the list if the list exists.
     * FALSE in case of Failure.
     * See: https://redis.io/commands/rpushx.
     *
     * @param  string $key
     * @param  mixed $value
     *
     * @return int          LONG The new length of the list in case of success,
     *                      FALSE in case of Failure.
     */
    public function rPushX(string $key, $value): int
    {
        return $this->redis->rPushX($key, $value);
    }
}
