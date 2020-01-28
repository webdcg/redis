<?php

namespace Webdcg\Redis\Traits;

trait Streams
{
    public function xAck(): bool
    {
        return false;
    }

    /**
     * Appends the specified stream entry to the stream at the specified key.
     * If the key does not exist, as a side effect of running this command the
     * key is created with a stream value.
     * See: https://redis.io/commands/xadd.
     *
     * @param  string      $key
     * @param  string      $id
     * @param  array       $message
     * @param  int|integer $maxLenght
     * @param  bool        $approximate
     *
     * @return string                   The added message ID
     */
    public function xAdd(
        string $key,
        string $id,
        array $message,
        ?int $maxLenght = null,
        ?bool $approximate = null
    ): string {
        if (is_null($maxLenght) && is_null($approximate)) {
            return $this->redis->xAdd($key, $id, $message);
        }

        return $this->redis->xAdd($key, $id, $message, $maxLenght, $approximate);
    }

    public function xClaim(): bool
    {
        return false;
    }

    public function xDel(): bool
    {
        return false;
    }

    public function xGroup(): bool
    {
        return false;
    }

    public function xInfo(): bool
    {
        return false;
    }

    public function xLen(): bool
    {
        return false;
    }

    public function xPending(): bool
    {
        return false;
    }

    public function xRange(): bool
    {
        return false;
    }

    public function xRead(): bool
    {
        return false;
    }

    public function xReadGroup(): bool
    {
        return false;
    }

    public function xRevRange(): bool
    {
        return false;
    }

    public function xTrim(): bool
    {
        return false;
    }
}
