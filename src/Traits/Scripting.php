<?php

namespace Webdcg\Redis\Traits;

trait Scripting
{
    public function eval(): bool
    {
        return false;
    }

    public function evalSha(): bool
    {
        return false;
    }

    public function script(): bool
    {
        return false;
    }

    public function getLastError(): bool
    {
        return false;
    }

    public function clearLastError(): bool
    {
        return false;
    }

    public function prefix(): bool
    {
        return false;
    }

    public function unserialize(): bool
    {
        return false;
    }

    public function serialize(): bool
    {
        return false;
    }
}
