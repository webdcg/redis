<?php

namespace Webdcg\Redis\Traits;

trait Pubsub
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


    /**
     * Subscribe to channels. Warning: this function will probably change in the future.
     *
     * @param  array  $channels     An array of channels to subscribe to.
     * @param  [type] $callback     Either a string or an Array($instance,
     *                              'method_name'). The callback function
     *                              receives 3 parameters:
     *                              - the redis instance,
     *                              - the channel name,
     *                              - and the message.
     *
     * @return mixed                Any non-null return value in the callback
     *                              will be returned to the caller.
     */
    public function subscribe(array $channels)
    {
        $redis = $this->redis;
        $response = $redis->subscribe($channels, function ($redis, $channel, $message) {
            if ($message === 'quit') {
                $redis->close();
            }
            echo "$channel => $message\n";
            return [$channel => $message];
        });
        dump($response);
    }

    public function pubSub(): bool
    {
        return false;
    }
}
