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

    public function sPop(): bool
    {
        return false;
    }

    public function sRandMember(): bool
    {
        return false;
    }

    public function sRem(): bool
    {
        return false;
    }

    public function sRemove(): bool
    {
        return false;
    }

    public function sUnion(): bool
    {
        return false;
    }

    public function sUnionStore(): bool
    {
        return false;
    }

    public function sScan(): bool
    {
        return false;
    }
}
