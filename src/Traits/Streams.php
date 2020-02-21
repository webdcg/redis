<?php

namespace Webdcg\Redis\Traits;

trait Streams
{
    /**
     * Acknowledge one or more pending messages.
     *
     * @param  string $stream
     * @param  string $group
     * @param  array  $messages
     *
     * @return int              The number of messages Redis reports as acknowledged.
     */
    public function xAck(string $stream, string $group, array $messages): int
    {
        return $this->redis->xAck($stream, $group, $messages);
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

    /**
     * This command is used in order to create, destroy, or manage consumer groups.
     *
     * @param  string       $command                [description]
     * @param  string|null  $stream                 [description]
     * @param  string|null  $group                  [description]
     * @param  [type]       $messageId_consumerName [description]
     * @param  bool|boolean $makeStream             [description]
     *
     * @return mixed                                This command returns different
     *                                              types depending on the specific
     *                                              XGROUP command executed.
     */
    public function xGroup(string $command, ?string $stream = null, ?string $group = null, $messageId_consumerName = null, bool $makeStream = false)
    {
        return $this->redis->xGroup($command, $stream, $group, $messageId_consumerName, $makeStream);
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
