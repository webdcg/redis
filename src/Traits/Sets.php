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

    public function sCard(): bool
    {
        return false;
    }

    public function sSize(): bool
    {
        return false;
    }

    public function sDiff(): bool
    {
        return false;
    }

    public function sDiffStore(): bool
    {
        return false;
    }

    public function sInter(): bool
    {
        return false;
    }

    public function sInterStore(): bool
    {
        return false;
    }

    public function sIsMember(): bool
    {
        return false;
    }

    public function sContains(): bool
    {
        return false;
    }

    public function sMembers(): bool
    {
        return false;
    }

    public function sGetMembers(): bool
    {
        return false;
    }

    public function sMove(): bool
    {
        return false;
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
