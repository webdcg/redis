<?php

$redis = new \Redis();
$redis->connect('localhost', 6379);

$response = $redis->subscribe(['test'], function ($redis, $channel, $message) {
    if ($message === 'quit') {
        $redis->close();
    }
    echo "$channel => $message\n";
    return [$channel => $message];
});

var_dump($response);
