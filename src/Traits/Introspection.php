<?php

namespace Webdcg\Redis\Traits;

trait Introspection
{
    public function isConnected(): bool
    {
        return false;
    }
    
    public function getHost(): bool
    {
        return false;
    }
    
    public function getPort(): bool
    {
        return false;
    }
    
    public function getDbNum(): bool
    {
        return false;
    }
    
    public function getTimeout(): bool
    {
        return false;
    }
    
    public function getReadTimeout(): bool
    {
        return false;
    }
    
    public function getPersistentID(): bool
    {
        return false;
    }
    
    public function getAuth(): bool
    {
        return false;
    }
}
