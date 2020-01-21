<?php

namespace Webdcg\Redis\Traits;

trait Lists
{
    public function blPop(): bool
    {
        return false;
    }

    public function brPop(): bool
    {
        return false;
    }

    public function bRPopLPush(): bool
    {
        return false;
    }

    public function lIndex(): bool
    {
        return false;
    }

    public function lGet(): bool
    {
        return false;
    }

    public function lInsert(): bool
    {
        return false;
    }

    public function lLen(): bool
    {
        return false;
    }

    public function lSize(): bool
    {
        return false;
    }

    public function lPop(): bool
    {
        return false;
    }

    public function lPush(): bool
    {
        return false;
    }

    public function lPushx(): bool
    {
        return false;
    }

    public function lRange(): bool
    {
        return false;
    }

    public function lGetRange(): bool
    {
        return false;
    }

    public function lRem(): bool
    {
        return false;
    }

    public function lRemove(): bool
    {
        return false;
    }

    public function lSet(): bool
    {
        return false;
    }

    public function lTrim(): bool
    {
        return false;
    }

    public function listTrim(): bool
    {
        return false;
    }

    public function rPop(): bool
    {
        return false;
    }

    public function rPopLPush(): bool
    {
        return false;
    }

    public function rPush(): bool
    {
        return false;
    }

    public function rPushX(): bool
    {
        return false;
    }
}
