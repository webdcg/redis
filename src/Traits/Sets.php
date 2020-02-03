<?php

namespace Webdcg\Redis\Traits;

trait Sets
{
    /**
     * Adds a value to the set value stored at key. If this value is already
     * in the set, FALSE is returned.
     * See: https://redis.io/commands/sadd.
     *
     * @param  string $key
     * @param  splat $members
     *
     * @return int              The number of elements added to the set.
     */
    public function sAdd(string $key, ...$members): int
    {
        if (is_array($members[0])) {
            return $this->redis->sAdd($key, ...$members[0]);
        }

        return $this->redis->sAdd($key, ...$members);
    }

    /**
     * Returns the cardinality of the set identified by key.
     * See: https://redis.io/commands/scard.
     *
     * @param  string $key
     *
     * @return int          the cardinality of the set identified by key,
     *                      0 if the set doesn't exist.
     */
    public function sCard(string $key): int
    {
        return $this->redis->sCard($key);
    }

    /**
     * Returns the cardinality of the set identified by key.
     * See: https://redis.io/commands/scard.
     * Note: sSize is an alias for sCard and will be removed in future
     * versions of phpredis.
     *
     * @param  string $key
     *
     * @return int          the cardinality of the set identified by key,
     *                      0 if the set doesn't exist.
     */
    public function sSize(string $key): int
    {
        return $this->redis->sCard($key);
    }

    /**
     * Performs the difference between N sets and returns it.
     *
     * @param  splat $keys
     *
     * @return array
     */
    public function sDiff(...$keys): array
    {
        return $this->redis->sDiff(...$keys);
    }

    /**
     * Performs the same action as sDiff, but stores the result in the first key.
     *
     * @param  string $destinationKey The key to store the diff into.
     * @param  splat $keys            key1, key2, ... , keyN: Any number of keys
     *                                corresponding to sets in redis.
     *
     * @return The cardinality of the resulting set, or FALSE in case of a
     * missing key.
     */
    public function sDiffStore(string $destinationKey, ...$keys): int
    {
        return $this->redis->sDiffStore($destinationKey, ...$keys);
    }

    /**
     * Returns the members of a set resulting from the intersection of all the
     * sets held at the specified keys.
     * If just a single key is specified, then this command produces the members
     * of this set. If one of the keys is missing, FALSE is returned.
     * See: https://redis.io/commands/sinter.
     *
     * @param  splat $keys  key1, key2, keyN: keys identifying the different
     *                      sets on which we will apply the intersection.
     *
     * @return array        Contain the result of the intersection between
     *                      those keys. If the intersection between the
     *                      different sets is empty, the return value will be
     *                      empty array.
     */
    public function sInter(...$keys): array
    {
        return $this->redis->sInter(...$keys);
    }

    /**
     * Performs a sInter command and stores the result in a new set.
     * See: https://redis.io/commands/sinterstore.
     *
     * @param  string $destinationKey   The key to store the diff into.
     * @param  plat $keys               key1, key2... keyN. key1..keyN are
     *                                  intersected as in sInter.
     *
     * @return int                      The cardinality of the resulting set,
     *                                  or FALSE in case of a missing key.
     */
    public function sInterStore(string $destinationKey, ...$keys): int
    {
        return $this->redis->sInterStore($destinationKey, ...$keys);
    }

    /**
     * Checks if member is a member of the set stored at the key key.
     * See: https://redis.io/commands/sismember.
     *
     * @param  string $key
     * @param  mixed $member
     *
     * @return bool             TRUE if value is a member of the set at key key,
     *                          FALSE otherwise.
     */
    public function sIsMember(string $key, $member): bool
    {
        return $this->redis->sIsMember($key, $member);
    }

    /**
     * Checks if member is a member of the set stored at the key key.
     * Note: sContains is an alias for sIsMember and will be removed in future
     * versions of phpredis.
     * See: https://redis.io/commands/sismember.
     *
     * @param  string $key
     * @param  mixed $member
     *
     * @return bool             TRUE if value is a member of the set at key key,
     *                          FALSE otherwise.
     */
    public function sContains(string $key, $member): bool
    {
        return $this->redis->sIsMember($key, $member);
    }

    /**
     * Returns the contents of a set.
     * The order is random and corresponds to redis' own internal representation
     * of the set structure.
     * See: https://redis.io/commands/smembers.
     *
     * @param  string $key
     *
     * @return array        An array of elements, the contents of the set.
     */
    public function sMembers(string $key): array
    {
        return $this->redis->sMembers($key);
    }

    /**
     * Returns the contents of a set.
     * The order is random and corresponds to redis' own internal representation
     * of the set structure.
     * Note: sGetMembers is an alias for sMembers and will be removed in future
     * versions of phpredis.
     * See: https://redis.io/commands/smembers.
     *
     * @param  string $key
     *
     * @return array        An array of elements, the contents of the set.
     */
    public function sGetMembers(string $key): array
    {
        return $this->redis->sMembers($key);
    }

    /**
     * Moves the specified member from the set at sourceKey to the set at
     * destinationKey.
     * See: https://redis.io/commands/smove.
     *
     * @param  string $sourceKey
     * @param  string $destinationKey
     * @param  mixed $member
     *
     * @return bool                 If the operation is successful, return TRUE.
     *                              If the sourceKey and/or destinationKey didn't
     *                              exist, and/or the member didn't exist in sourceKey,
     *                              FALSE is returned.
     */
    public function sMove(string $sourceKey, string $destinationKey, $member): bool
    {
        return $this->redis->sMove($sourceKey, $destinationKey, $member);
    }

    /**
     * Removes and returns a random element from the set value at Key.
     * See: https://redis.io/commands/spop.
     *
     * @param  string      $key
     * @param  int|integer $count Number of elemets to be returned
     *
     * @return mixed|string|array   String "popped" value.
     *                              Array Member(s) returned or an empty array
     *                              if the set doesn't exist
     *                              FALSE on error if the key is not a set.
     */
    public function sPop(string $key, int $count = 1)
    {
        if ($count > 1) {
            return $this->redis->sPop($key, $count);
        }
        return $this->redis->sPop($key);
    }

    /**
     * Returns a random element from the set value at Key, without removing it.
     * See: https://redis.io/commands/srandmember.
     *
     * @param  string      $key
     * @param  int|integer $count
     *
     * @return mixed|string|array   If no count is provided, a random String
     *                              value from the set will be returned. If a
     *                              count is provided, an array of values from
     *                              the set will be returned. Read about the
     *                              different ways to use the count here:
     *                              SRANDMEMBER Bool FALSE if set identified by
     *                              key is empty or doesn't exist.
     */
    public function sRandMember(string $key, int $count = 1)
    {
        if ($count > 1) {
            return $this->redis->sRandMember($key, $count);
        }
        return $this->redis->sRandMember($key);
    }

    /**
     * Removes the specified member from the set value stored at key.
     * See: https://redis.io/commands/srem.
     *
     * @param  string $key
     * @param  splat $members
     *
     * @return int              The number of elements removed from the set.
     */
    public function sRem(string $key, ...$members): int
    {
        return $this->redis->sRem($key, ...$members);
    }

    /**
     * Removes the specified member from the set value stored at key.
     * Note: sRemove is an alias for sRem and will be removed in future
     * versions of phpredis.
     * See: https://redis.io/commands/srem.
     *
     * @param  string $key
     * @param  splat $members
     *
     * @return int              The number of elements removed from the set.
     */
    public function sRemove(string $key, ...$members): int
    {
        return $this->redis->sRem($key, ...$members);
    }

    /**
     * Performs the union between N sets and returns it.
     * See: https://redis.io/commands/sunion.
     *
     * @param  splat $keys
     *
     * @return array        key1, key2, ... , keyN: Any number of keys
     *                      corresponding to sets in redis.
     */
    public function sUnion(...$keys): array
    {
        return $this->redis->sUnion(...$keys);
    }

    /**
     * Performs the same action as sUnion, but stores the result in the first
     * key.
     * See: https://redis.io/commands/sunionstore.
     *
     * @param  string $destinationKey
     * @param  splat $keys
     *
     * @return int                      The cardinality of the resulting set,
     *                                  or FALSE in case of a missing key.
     */
    public function sUnionStore(string $destinationKey, ...$keys): int
    {
        if (is_array($keys[0])) {
            return $this->redis->sUnionStore($destinationKey, ...$keys[0]);
        }

        return $this->redis->sUnionStore($destinationKey, ...$keys);
    }

    public function sScan(): bool
    {
        return false;
    }
}
