<?php

namespace Webdcg\Redis\Traits;

/**
 * Support for Redis Stream Data Structure
 *
 * See: https://redis.io/topics/streams-intro
 * See: https://university.redislabs.com/courses
 */
trait Streams
{
    /**
     * Acknowledge one or more pending messages.
     *
     * See: https://redis.io/commands/xack.
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
     *
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
     * See: https://redis.io/commands/xclaim.
     *
     * Note:  'TIME', and 'IDLE' are mutually exclusive
     *
     * 'IDLE' => $value, Set the idle time to $value ms
     * 'TIME' => $value, Set the idle time to now - $value
     * 'RETRYCOUNT' => $value, Update message retrycount to $value
     * 'FORCE', Claim the message(s) even if they're not pending anywhere
     * 'JUSTID',Instruct Redis to only return IDs
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
            $this->redis->xClaim($stream, $group, $consumer, $minIdleTime, $messageIds) :
            $this->redis->xClaim($stream, $group, $consumer, $minIdleTime, $messageIds, $options);
    }


    /**
     * Delete one or more messages from a stream.
     *
     * See: https://redis.io/commands/xdel.
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
     * See: https://redis.io/commands/xgroup.
     *
     * @param  string       $command
     * @param  string|null  $stream
     * @param  string|null  $group
     * @param  string|null  $messageId_consumerName
     * @param  bool|boolean $makeStream
     *
     * @return mixed                                This command returns different
     *                                              types depending on the specific
     *                                              XGROUP command executed.
     */
    public function xGroup(
        string $command,
        ?string $stream = null,
        ?string $group = null,
        ?string $messageId_consumerName = null,
        bool $makeStream = false
    ) {
        $command = strtoupper($command);

        if (!$this->_checkGroupCommands($command)) {
            throw new \Exception("Bad Group Command", 1);
        }

        switch ($command) {
            case 'CREATE':
                return $this->redis->xGroup($command, $stream, $group, $messageId_consumerName, $makeStream);
            case 'SETID':
                return $this->redis->xGroup($command, $stream, $group, $messageId_consumerName);
            case 'DESTROY':
                return $this->redis->xGroup($command, $stream, $group);
            case 'DELCONSUMER':
                return $this->redis->xGroup($command, $stream, $group, $messageId_consumerName);
        }

        return $this->redis->xGroup($command);
    }


    /**
     * Get information about a stream or consumer groups.
     *
     * See: https://redis.io/commands/xinfo.
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


    /**
     * Get the length of a given stream.
     *
     * See: https://redis.io/commands/xlen.
     *
     * @param  string $stream
     *
     * @return The number of messages in the stream.
     */
    public function xLen(string $stream): int
    {
        return $this->redis->xLen($stream);
    }


    /**
     * Get information about pending messages in a given stream.
     *
     * See: https://redis.io/commands/xpending.
     *
     * @param  string      $stream
     * @param  string      $group
     * @param  string|null $start
     * @param  string|null $end
     * @param  int|null    $count
     * @param  string|null $consumer
     *
     * @return array                    Information about the pending messages,
     *                                  in various forms depending on the specific
     *                                  invocation of XPENDING.
     */
    public function xPending(
        string $stream,
        string $group,
        ?string $start = null,
        ?string $end = null,
        ?int $count = null,
        ?string $consumer = null
    ): array {
        return is_null($start) || is_null($end) || is_null($count) || is_null($consumer) ?
            $this->redis->xPending($stream, $group) :
            $this->redis->xPending($stream, $group, $start, $end, $count, $consumer);
    }


    /**
     * Get a range of messages from a given stream.
     *
     * See: https://redis.io/commands/xrange.
     *
     * @param  string   $stream
     * @param  string   $start
     * @param  string   $end
     * @param  int|null $count
     *
     * @return array            The messages in the stream within the requested range.
     */
    public function xRange(string $stream, string $start, string $end, ?int $count = null): array
    {
        return is_null($count) ?
            $this->redis->xRange($stream, $start, $end) :
            $this->redis->xRange($stream, $start, $end, $count);
    }


    /**
     * Read data from one or more streams and only return IDs greater than sent in the command.
     *
     * See: https://redis.io/commands/xread.
     *
     * @param  array    $streams
     * @param  int|null $count
     * @param  int|null $block
     *
     * @return array            The messages in the stream newer than the IDs passed to Redis (if any).
     */
    public function xRead(array $streams, ?int $count = null, ?int $block = null): array
    {
        if (!is_null($count) && !is_null($block)) {
            return $this->redis->xRead($streams, $count, $block);
        }

        return is_null($count) ? $this->redis->xRead($streams) : $this->redis->xRead($streams, $count);
    }


    /**
     * This method is similar to xRead except that it supports reading messages for a specific consumer group.
     *
     * See: https://redis.io/commands/xreadgroup.
     *
     * @param  string   $group
     * @param  string   $consumer
     * @param  array    $streams
     * @param  int|null $count
     * @param  int|null $block
     *
     * @return array                The messages delivered to this consumer group (if any).
     */
    public function xReadGroup(
        string $group,
        string $consumer,
        array $streams,
        ?int $count = null,
        ?int $block = null
    ): array {
        if (!is_null($count) && !is_null($block)) {
            return $this->redis->xReadGroup($group, $consumer, $streams, $count, $block);
        }

        return is_null($count) ?
            $this->redis->xReadGroup($group, $consumer, $streams) :
            $this->redis->xReadGroup($group, $consumer, $streams, $count);
    }


    /**
     * This is identical to xRange except the results come back in reverse order.
     * Also note that Redis reverses the order of "start" and "end".
     *
     * See: https://redis.io/commands/xrevrange.
     *
     * @param  string $stream
     * @param  string $end
     * @param  string $start
     * @param  int|null $count
     *
     * @return array            The messages in the range specified.
     */
    public function xRevRange(string $stream, string $end, string $start, ?int $count = null): array
    {
        return is_null($count) ?
            $this->redis->xRevRange($stream, $end, $start) :
            $this->redis->xRevRange($stream, $end, $start, $count);
    }


    /**
     * Trim the stream length to a given maximum. If the "approximate" flag is
     * pasesed, Redis will use your size as a hint but only trim trees in whole
     * nodes (this is more efficient).
     *
     * See: https://redis.io/commands/xtrim.
     *
     * @param  string    $stream
     * @param  int       $maxLenght
     * @param  bool|null $approximate
     *
     * @return int                      The number of messages trimmed from the stream.
     */
    public function xTrim(string $stream, int $maxLenght, ?bool $approximate = null): int
    {
        return is_null($approximate) ?
            $this->redis->xTrim($stream, $maxLenght) :
            $this->redis->xTrim($stream, $maxLenght, $approximate);
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
        foreach ($options as $key => $value) {
            $check = is_numeric($key) ? $value : $key;
            if (!in_array($check, ['IDLE', 'TIME', 'RETRYCOUNT', 'FORCE', 'JUSTID'])) {
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
    private function _checkInfoCommands(string $command): bool
    {
        return in_array($command, ['CONSUMERS', 'GROUPS', 'STREAM', 'HELP'], true);
    }

    /**
     * [_checkGroupCommands description]
     *
     * @param  string $command
     *
     * @return bool
     */
    private function _checkGroupCommands(string $command): bool
    {
        return in_array($command, ['HELP', 'CREATE', 'SETID', 'DESTROY', 'DELCONSUMER'], true);
    }
}
