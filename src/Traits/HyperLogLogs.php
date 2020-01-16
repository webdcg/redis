<?php

namespace Webdcg\Redis\Traits;

trait HyperLogLogs
{
    public function pfAdd():bool
    {
        return false;
    }
    public function pfCount():bool
    {
        return false;
    }
    public function pfMerge():bool
    {
        return false;
    }
}
