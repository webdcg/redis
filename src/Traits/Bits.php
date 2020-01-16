<?php

namespace Webdcg\Redis\Traits;

trait Bits
{
    /*
     * Available Bit Operations
     */
    protected $BIT_OPERATIONS = ['AND', 'OR', 'XOR', 'NOT'];

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

    /**
     * Perform bitwise operations between strings
     *
     * @param  string $operation AND, OR, NOT, XOR
     * @param  string $returnKey Return Key
     * @param  splat $keys      List of keys for input
     * ]
     * @return int              The size of the string stored in the destination key.
     */
    public function bitOp(string $operation, string $returnKey, ...$keys): int
    {
        $operation = strtoupper($operation);

        if (!in_array($operation, $this->BIT_OPERATIONS)) {
            throw new \Exception("Operation not supported", 1);
        }

        return $this->redis->bitOp($operation, $returnKey, ...$keys);
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
