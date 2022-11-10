<?php

//协程客户端
//Coroutine\Client 的所有涉及网络请求的方法，Swoole 都会进行协程调度，业务层无需感知

Swoole\Coroutine\run(function () {
    $host = '127.0.0.1';
    $port = 9901;
    $timeout = 5;
    $client = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
    if (!$client->connect($host, $port, $timeout)) {
        echo "connect failed. Error: {$client->errCode}\n";
    }
    $client->send("hello world\n");
    echo $client->recv();
    $client->close();
});