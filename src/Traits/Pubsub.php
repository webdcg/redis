<?php

namespace Webdcg\Redis\Traits;

trait PubSub
{
    public function pSubscribe(): bool
    {
        return false;
    }

    /**
     * Publish messages to channels. Warning: this function will probably change
     * in the future.
     *
     * @param  string $channel  A channel to publish to.
     * @param  string $message  The message to be broadcasted.
     *
     * @return int              the number of clients that received the message.
     */
    public function publish(string $channel, string $message): int
    {
        return $this->redis->publish($channel, $message);
    }

    public function subscribe(): bool
    {
        return false;
    }

    public function pubSub(): bool
    {
        return false;
    }
}
