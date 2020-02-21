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

        return is_null($approximate) ?
            $this->redis->xAdd($key, $id, $message, $maxLenght) :
            $this->redis->xAdd($key, $id, $message, $maxLenght, $approximate);
    }


    /**
     * Claim ownership of one or more pending messages.
     *
     * @param  string     $stream
     * @param  string     $group
     * @param  string     $consumer
     * @param  int        $minIdleTime
     * @param  array      $messageIds
     * @param  array|null $options
     *
     * @return array                    Either an array of message IDs along with
     *                                  corresponding data, or just an array of
     *                                  IDs (if the 'JUSTID' option was passed).
     */
    public function xClaim(
        string $stream,
        string $group,
        string $consumer,
        int $minIdleTime,
        array $messageIds,
        ?array $options = null
    ): array {
        if (!is_null($options) && !$this->_checkClaimOptions($options)) {
            throw new \Exception("Bad Claim Options", 1);
        }

        return is_null($options) ?
            $this->redis->xClaim($stream, $group, $consumer, $minIdleTime, $messageIds):
            $this->redis->xClaim($stream, $group, $consumer, $minIdleTime, $messageIds, $options);
    }


    /**
     * Delete one or more messages from a stream.
     *
     * @param  string $stream
     * @param  array  $messageIds
     *
     * @return int                  The number of messages removed.
     */
    public function xDel(string $stream, array $messageIds): int
    {
        return $this->redis->xDel($stream, $messageIds);
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
    public function xGroup(
        string $command,
        ?string $stream = null,
        ?string $group = null,
        $messageId_consumerName = null,
        bool $makeStream = false
    ) {
        return $this->redis->xGroup($command, $stream, $group, $messageId_consumerName, $makeStream);
    }


    /**
     * Get information about a stream or consumer groups.
     *
     * @param  string $command
     * @param  string $stream
     * @param  string $group
     *
     * @return mixed            This command returns different types depending on which subcommand is used.
     */
    public function xInfo(string $command, ?string $stream = null, ?string $group = null)
    {
        $command = strtoupper($command);
        
        if (!$this->_checkInfoCommands($command)) {
            throw new \Exception("Bad Info Command", 1);
        }

        if (is_null($stream) && is_null($group)) {
            return $this->redis->xInfo($command);
        }

        return is_null($group) ?
            $this->redis->xInfo($command, $stream) :
            $this->redis->xInfo($command, $stream, $group);
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

    public function xReadGroup(): array
    {
        return [];
    }

    public function xRevRange(): bool
    {
        return false;
    }

    public function xTrim(): bool
    {
        return false;
    }


    /**
     * ************************************************************************
     * H E L P E R    F U N C T I O N S
     * ************************************************************************
     */


    /**
     * Claim Options available
     *
     * Note:  'TIME', and 'IDLE' are mutually exclusive
     *
     * 'IDLE' => $value, Set the idle time to $value ms
     * 'TIME' => $value, Set the idle time to now - $value
     * 'RETRYCOUNT' => $value, Update message retrycount to $value
     * 'FORCE', Claim the message(s) even if they're not pending anywhere
     * 'JUSTID',Instruct Redis to only return IDs
     *
     * @param  array  $options
     *
     * @return bool
     */
    private function _checkClaimOptions(array $options): bool
    {
        $available = ['IDLE', 'TIME', 'RETRYCOUNT', 'FORCE', 'JUSTID'];
        
        foreach ($options as $key => $value) {
            $check = is_numeric($key) ? $value : $key;
            if (!in_array($check, $available)) {
                return false;
            }
        }

        return !(array_key_exists('IDLE', $options) && array_key_exists('TIME', $options));
    }


    /**
     * [_checkInfoCommands description]
     *
     * @param  string $command
     *
     * @return bool
     */
    private function _checkInfoCommands(string $command) : bool
    {
        $available = ['CONSUMERS', 'GROUPS', 'STREAM', 'HELP'];

        return in_array($command, $available, true);
    }
}
