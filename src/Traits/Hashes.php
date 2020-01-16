<?php

namespace Webdcg\Redis\Traits;

trait Hashes
{
    public function hDel(): bool
    {
        return false;
    }
    
    public function hExists(): bool
    {
        return false;
    }
    
    public function hGet(): bool
    {
        return false;
    }
    
    public function hGetAll(): bool
    {
        return false;
    }
    
    public function hIncrBy(): bool
    {
        return false;
    }
    
    public function hIncrByFloat(): bool
    {
        return false;
    }
    
    public function hKeys(): bool
    {
        return false;
    }
    
    public function hLen(): bool
    {
        return false;
    }
    
    public function hMGet(): bool
    {
        return false;
    }
    
    public function hMSet(): bool
    {
        return false;
    }
    
    public function hSet(): bool
    {
        return false;
    }
    
    public function hSetNx(): bool
    {
        return false;
    }
    
    public function hVals(): bool
    {
        return false;
    }
    
    public function hScan(): bool
    {
        return false;
    }
    
    public function hStrLen(): bool
    {
        return false;
    }
}
