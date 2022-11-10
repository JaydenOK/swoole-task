<?php

//协程客户端
//Coroutine\Client 的所有涉及网络请求的方法，Swoole 都会进行协程调度

Swoole\Coroutine\run(function () {
    $host = 'api.amazon.com';
    $port = 80;
    $client = new Swoole\Coroutine\Http\Client($host, $port);
    $client->setHeaders([
        'Host' => $host,
        'Accept' => 'application/json',
    ]);
    $client->set(['timeout' => 1]);
    $client->get('/index.php');

    //post
    //$client->post('/post.php', ['a' => '123', 'b' => '456']);

    echo $client->body;
    $client->close();
});