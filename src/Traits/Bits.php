<?php

namespace Webdcg\Redis\Traits;

trait Bits
{
    /**
     * Count set bits in a string.
     *
     * @param  string $key
     *
     * @return int
     */
    public function bitCount(string $key): int
    {
        return $this->redis->bitCount($key);
    }

    public function bitOp(): bool
    {
        return false;
    }

    public function getBit(): bool
    {
        return false;
    }

    public function setBit(): bool
    {
        return false;
    }
}
