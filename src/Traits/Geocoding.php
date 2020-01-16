<?php

namespace Webdcg\Redis\Traits;

trait Geocoding
{
    public function geoAdd():bool
    {
        return false;
    }
    public function geoHash():bool
    {
        return false;
    }
    public function geoPos():bool
    {
        return false;
    }
    public function geoDist():bool
    {
        return false;
    }
    public function geoRadius():bool
    {
        return false;
    }
    public function geoRadiusByMember():bool
    {
        return false;
    }
}
