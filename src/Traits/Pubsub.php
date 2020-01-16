<?php

namespace Webdcg\Redis\Traits;

trait Pubsub
{
    public function pSubscribe(): bool
    {
        return false;
    }

    public function publish(): bool
    {
        return false;
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
