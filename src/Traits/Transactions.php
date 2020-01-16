<?php

namespace Webdcg\Redis\Traits;

trait Transactions
{
    public function multi(): bool
    {
        return false;
    }

    public function exec(): bool
    {
        return false;
    }

    public function discard(): bool
    {
        return false;
    }

    public function watch(): bool
    {
        return false;
    }

    public function unwatch(): bool
    {
        return false;
    }
}
