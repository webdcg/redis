<?php

namespace Webdcg\Redis\Traits;

use Webdcg\Redis\Exceptions\InvalidArgumentException;
use Webdcg\Redis\Exceptions\SetOperationException;
use Webdcg\Redis\Exceptions\UnsupportedOptionException;

trait SortedSets
{
    /*
     * Available Set Operations
     */
    protected $SET_OPERATIONS = ['SUM', 'MIN', 'MAX'];

    public function bzPop(): bool
    {
        return false;
    }

    /**
     * Add one or more members to a sorted set or update its score if it
     * already exists.
     *
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
     *
     * Note: zSize is an alias for zCard and will be removed in future
     * versions of phpredis.
     *
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
     *
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

    /**
     * Increments the score of a member from a sorted set by a given amount.
     *
     * See: https://redis.io/commands/zincrby.
     *
     * @param  string $key
     * @param  float  $value    (double) value that will be added to the
     *                          member's score).
     * @param  string $member
     *
     * @return float            the new value
     */
    public function zIncrBy(string $key, float $value, $member): float
    {
        return $this->redis->zIncrBy($key, $value, $member);
    }

    /**
     * Creates an intersection of sorted sets given in second argument.
     * The result of the union will be stored in the sorted set defined by the
     * first argument.
     *
     * The third optional argument defines weights to apply to the sorted sets
     * in input. In this case, the weights will be multiplied by the score of
     * each element in the sorted set before applying the aggregation. The
     * forth argument defines the AGGREGATE option which specify how the
     * results of the union are aggregated.
     *
     * See: https://redis.io/commands/zinterstore.
     *
     * @param  string $keyOutput
     * @param  array  $arrayZSetKeys
     * @param  array  $arrayWeights
     * @param  string $aggregateFunction    Either "SUM", "MIN", or "MAX":
     *                                      defines the behaviour to use on
     *                                      duplicate entries during the
     *                                      zInterStore.
     *
     * @return int                          The number of values in the new
     *                                      sorted set.
     */
    public function zInterStore(
        string $keyOutput,
        array $arrayZSetKeys,
        ?array $arrayWeights = null,
        ?string $aggregateFunction = null
    ): int {
        // Validate Aggregate Function
        if (!is_null($aggregateFunction) && !is_null($arrayWeights)) {
            $operation = strtoupper($aggregateFunction);

            if (!in_array($operation, $this->SET_OPERATIONS)) {
                throw new SetOperationException('Operation not supported', 1);
            }

            return $this->redis->zInterStore($keyOutput, $arrayZSetKeys, $arrayWeights, $operation);
        }

        // Call using Weights
        if (!is_null($arrayWeights)) {
            return $this->redis->zInterStore($keyOutput, $arrayZSetKeys, $arrayWeights);
        }

        // Make simplest call just with the required params
        return $this->redis->zInterStore($keyOutput, $arrayZSetKeys);
    }

    /**
     * Creates an intersection of sorted sets given in second argument.
     * The result of the union will be stored in the sorted set defined by the
     * first argument.
     *
     * The third optional argument defines weights to apply to the sorted sets
     * in input. In this case, the weights will be multiplied by the score of
     * each element in the sorted set before applying the aggregation. The
     * forth argument defines the AGGREGATE option which specify how the
     * results of the union are aggregated.
     *
     * Note: zInter is an alias for zinterstore and will be removed in future
     * versions of phpredis.
     *
     * See: https://redis.io/commands/zinterstore.
     *
     * @param  string $keyOutput
     * @param  array  $arrayZSetKeys
     * @param  array  $arrayWeights
     * @param  string $aggregateFunction    Either "SUM", "MIN", or "MAX":
     *                                      defines the behaviour to use on
     *                                      duplicate entries during the
     *                                      zInterStore.
     *
     * @return int                          The number of values in the new
     *                                      sorted set.
     */
    public function zInter(
        string $keyOutput,
        array $arrayZSetKeys,
        ?array $arrayWeights = null,
        ?string $aggregateFunction = null
    ): int {
        // Validate Aggregate Function
        if (!is_null($aggregateFunction) && !is_null($arrayWeights)) {
            $operation = strtoupper($aggregateFunction);

            if (!in_array($operation, $this->SET_OPERATIONS)) {
                throw new SetOperationException('Operation not supported', 1);
            }

            return $this->redis->zInterStore($keyOutput, $arrayZSetKeys, $arrayWeights, $operation);
        }

        // Call using Weights
        if (!is_null($arrayWeights)) {
            return $this->redis->zInterStore($keyOutput, $arrayZSetKeys, $arrayWeights);
        }

        // Make simplest call just with the required params
        return $this->redis->zInterStore($keyOutput, $arrayZSetKeys);
    }

    /**
     * Can pop the highest or lowest scoring members from one ZSETs.
     * There are two commands (ZPOPMIN and ZPOPMAX for popping the lowest and
     * highest scoring elements respectively.).
     *
     * See: https://redis.io/commands/zpopmin.
     * See: https://redis.io/commands/zpopmax.
     *
     * @param  string       $key
     * @param  int|integer  $count
     * @param  bool|boolean $max
     *
     * @return array                Either an array with the key member and
     *                              score of the highest or lowest element
     *                              or an empty array if there is no element
     *                              available.
     */
    public function zPop(string $key, int $count = 1, bool $max = true): array
    {
        return $max ? $this->redis->zPopMax($key, $count) : $this->redis->zPopMin($key, $count);
    }

    /**
     * Can pop the lowest scoring members from one ZSETs.
     *
     * See: https://redis.io/commands/zpopmin.
     *
     * @param  string      $key
     * @param  int|integer $count
     *
     * @return array                Either an array with the key member and
     *                              score of the highest or lowest element
     *                              or an empty array if there is no element
     *                              available.
     */
    public function zPopMin(string $key, int $count = 1): array
    {
        return $this->redis->zPopMin($key, $count);
    }

    /**
     * Can pop the highest scoring members from one ZSETs.
     *
     * See: https://redis.io/commands/zpopmax.
     *
     * @param  string      $key
     * @param  int|integer $count
     *
     * @return array                Either an array with the key member and
     *                              score of the highest or lowest element
     *                              or an empty array if there is no element
     *                              available.
     */
    public function zPopMax(string $key, int $count = 1): array
    {
        return $this->redis->zPopMax($key, $count);
    }

    /**
     * Returns a range of elements from the ordered set stored at the specified
     * key, with values in the range [start, end].
     *
     * Start and stop are interpreted as zero-based indices:
     *     0 the first element, 1 the second ...
     *     -1 the last element, -2 the penultimate ...
     *
     * See: https://redis.io/commands/zrange.
     *
     * @param  string       $key
     * @param  int|integer  $start
     * @param  int|integer  $end
     * @param  bool|boolean $withScores
     *
     * @return array                    Array containing the values in specified range.
     */
    public function zRange(string $key, int $start = 0, int $end = -1, bool $withScores = false): array
    {
        return $this->redis->zRange($key, $start, $end, $withScores);
    }

    /**
     * Returns the elements of the sorted set stored at the specified key which
     * have scores in the range [start,end]. Adding a parenthesis before start
     * or end excludes it from the range. +inf and -inf are also valid limits.
     *
     * See: https://redis.io/commands/zrangebyscore.
     *
     * @param  string     $key
     * @param  mixed|int|string     $start
     * @param  mixed|int|string     $end
     * @param  array|null           $options  Two options are available:
     *                                        - withscores => TRUE,
     *                                        and limit => [$offset, $count]
     *
     * @return array                        Array containing the values in
     *                                      specified range.
     */
    public function zRangeByScore(string $key, $start, $end, ?array $options = null): array
    {
        if (is_null($options)) {
            return $this->redis->zRangeByScore($key, $start, $end);
        }

        $rangeOptions = ['withscores', 'limit'];

        if (count(array_intersect(array_keys($options), $rangeOptions)) != count($options)) {
            throw new UnsupportedOptionException("Option Not Supported", 1);
        }

        return $this->redis->zRangeByScore($key, $start, $end, $options);
    }

    /**
     * Returns the elements of the sorted set stored at the specified key which
     * have scores in the range [start,end]. Adding a parenthesis before start
     * or end excludes it from the range. +inf and -inf are also valid limits.
     *
     * zRevRangeByScore returns the same items in reverse order, when the start
     * and end parameters are swapped.
     *
     * See: https://redis.io/commands/zrevrangebyscore.
     *
     * @param  string     $key
     * @param  mixed|int|string     $start
     * @param  mixed|int|string     $end
     * @param  array|null           $options  Two options are available:
     *                                        - withscores => TRUE,
     *                                        and limit => [$offset, $count]
     *
     * @return array                        Array containing the values in
     *                                      specified range.
     */
    public function zRevRangeByScore(string $key, $start, $end, ?array $options = null): array
    {
        if (is_null($options)) {
            return $this->redis->zRevRangeByScore($key, $start, $end);
        }

        $rangeOptions = ['withscores', 'limit'];

        if (count(array_intersect(array_keys($options), $rangeOptions)) != count($options)) {
            throw new UnsupportedOptionException("Option Not Supported", 1);
        }

        return $this->redis->zRevRangeByScore($key, $start, $end, $options);
    }

    /**
     * Returns a lexicographical range of members in a sorted set, assuming the
     * members have the same score. The min and max values are required to start
     * with '(' (exclusive), '[' (inclusive), or be exactly the values
     * '-' (negative inf) or '+' (positive inf).
     *
     * The command must be called with either three or five arguments or will
     * return FALSE.
     *
     * See: https://redis.io/commands/zrangebylex
     *
     * @param  string   $key        The ZSET you wish to run against
     * @param  mixed|string $min    The minimum alphanumeric value you wish to get
     * @param  mixed|string $max    The maximum alphanumeric value you wish to get
     * @param  int|null $offset     Optional argument if you wish to start
     *                              somewhere other than the first element.
     * @param  int|null $limit      Optional argument if you wish to limit the
     *                              number of elements returned.
     *
     * @return array                Array containing the values in the specified
     *                              range.
     */
    public function zRangeByLex(string $key, $min, $max, ?int $offset = null, ?int $limit = null): array
    {
        if (!$this->_validateLexParams($min, $max)) {
            throw new InvalidArgumentException("Redis::zRangeByLex(): min and max arguments must start with '[' or '('", 1);
        }

        if (is_null($offset) && is_null($limit)) {
            return $this->redis->zRangeByLex($key, $min, $max);
        }

        if (!is_null($offset) && !is_null($limit)) {
            return $this->redis->zRangeByLex($key, $min, $max, $offset, $limit);
        }

        throw new InvalidArgumentException("The provided parameters do not match the required.", 1);
    }

    /**
     * Returns the rank of a given member in the specified sorted set, starting
     * at 0 for the item with the smallest score.
     *
     * See: https://redis.io/commands/zrank.
     *
     * @param  string $key                      The ZSET you wish to run against
     * @param  mixed|string|int|float $member   The member to look for
     *
     * @return int                              The member's rank position
     */
    public function zRank(string $key, $member): int
    {
        return $this->redis->zRank($key, $member);
    }

    /**
     * Returns the rank of a given member in the specified sorted set, starting
     * at 0 for the item with the smallest score.
     *
     * zRevRank starts at 0 for the item with the largest score.
     *
     * See: https://redis.io/commands/zrevrank.
     *
     * @param  string $key                      The ZSET you wish to run against
     * @param  mixed|string|int|float $member   The member to look for
     *
     * @return int                              The member's rank position
     */
    public function zRevRank(string $key, $member): int
    {
        return $this->redis->zRevRank($key, $member);
    }

    /**
     * Delete one or more members from a sorted set.
     *
     * See: https://redis.io/commands/zrem.
     *
     * @param  string $key      The ZSET you wish to run against
     * @param  splay $members   Member(s) to be removed from the ZSET
     *
     * @return int              The number of members deleted.
     */
    public function zRem(string $key, ...$members): int
    {
        return $this->redis->zRem($key, ...$members);
    }

    /**
     * Delete one or more members from a sorted set.
     *
     * Note: zDelete and zRemove are an alias for zRem and will be removed
     * in future versions of phpredis.
     *
     * See: https://redis.io/commands/zrem.
     *
     * @param  string $key      The ZSET you wish to run against
     * @param  splay $members   Member(s) to be removed from the ZSET
     *
     * @return int              The number of members deleted.
     */
    public function zDelete(string $key, ...$members): int
    {
        return $this->redis->zRem($key, ...$members);
    }

    /**
     * Delete one or more members from a sorted set.
     *
     * Note: zDelete and zRemove are an alias for zRem and will be removed
     * in future versions of phpredis.
     *
     * See: https://redis.io/commands/zrem.
     *
     * @param  string $key      The ZSET you wish to run against
     * @param  splay $members   Member(s) to be removed from the ZSET
     *
     * @return int              The number of members deleted.
     */
    public function zRemove(string $key, ...$members): int
    {
        return $this->redis->zRem($key, ...$members);
    }

    /**
     * Deletes the elements of the sorted set stored at the specified key which
     * have rank in the range [start,end].
     *
     * See: https://redis.io/commands/zremrangebyrank.
     *
     * @param  string $key      The ZSET you wish to run against
     * @param  int    $start
     * @param  int    $end
     *
     * @return int              The number of values deleted from the sorted set
     */
    public function zRemRangeByRank(string $key, int $start, int $end): int
    {
        if ($end < $start) {
            throw new InvalidArgumentException("End should be greater than Start.", 1);
        }

        return $this->redis->zRemRangeByRank($key, $start, $end);
    }

    /**
     * Deletes the elements of the sorted set stored at the specified key which
     * have rank in the range [start,end].
     *
     * Note: zDeleteRangeByRank is an alias for zRemRangeByRank and will be
     * removed in future versions of phpredis.
     *
     * See: https://redis.io/commands/zremrangebyrank.
     *
     * @param  string $key      The ZSET you wish to run against
     * @param  int    $start
     * @param  int    $end
     *
     * @return int              The number of values deleted from the sorted set
     */
    public function zDeleteRangeByRank(string $key, int $start, int $end): int
    {
        return $this->redis->zRemRangeByRank($key, $start, $end);
    }


    /**
     * ========================================================================
     * H E L P E R   M E T H O D S
     * ========================================================================
     */


    /**
     * Validate Lex Params
     *
     * @param  splat $params
     *
     * @return bool
     */
    protected function _validateLexParams(...$params)
    {
        return count(preg_grep("/^(\+|\-)?(\({1}.)?(\[{1}.)?$/", $params)) == count($params);
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
