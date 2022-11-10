<?php

//协程客户端
//Coroutine\Client 的所有涉及网络请求的方法，Swoole 都会进行协程调度

Swoole\Coroutine\run(function () {
    $host = '127.0.0.1';
    $port = 9501;
    $client = new Swoole\Coroutine\Http\Client($host, $port);
    //升级为WebSocket连接
    $ret = $client->upgrade('/');
    if ($ret) {
        $client->push('hello');
        var_dump($client->recv());
    } else {
        //错误状态码。当 connect/send/recv/close 失败或者超时时，会自动设置 Swoole\Coroutine\Http\Client->errCode 的值
        var_dump($client->errCode);
    }
    $client->close();
});