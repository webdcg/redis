<?php

namespace Webdcg\Redis\Traits;

trait Sets
{
    public function sAdd(): bool
    {
        return false;
    }
    
    public function sCard(): bool
    {
        return false;
    }
    
    public function sSize(): bool
    {
        return false;
    }
    
    public function sDiff(): bool
    {
        return false;
    }
    
    public function sDiffStore(): bool
    {
        return false;
    }
    
    public function sInter(): bool
    {
        return false;
    }
    
    public function sInterStore(): bool
    {
        return false;
    }
    
    public function sIsMember(): bool
    {
        return false;
    }
    
    public function sContains(): bool
    {
        return false;
    }
    
    public function sMembers(): bool
    {
        return false;
    }
    
    public function sGetMembers(): bool
    {
        return false;
    }
    
    public function sMove(): bool
    {
        return false;
    }
    
    public function sPop(): bool
    {
        return false;
    }
    
    public function sRandMember(): bool
    {
        return false;
    }
    
    public function sRem(): bool
    {
        return false;
    }
    
    public function sRemove(): bool
    {
        return false;
    }
    
    public function sUnion(): bool
    {
        return false;
    }
    
    public function sUnionStore(): bool
    {
        return false;
    }
    
    public function sScan(): bool
    {
        return false;
    }
}
