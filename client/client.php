<?php

//同步阻塞客户端

$host = '127.0.0.1';
$port = 9901;
$timeout = 30;

$client = new Swoole\Client(SWOOLE_SOCK_TCP);
if (!$client->connect($host, $port, $timeout)) {
    exit("connect failed. Error: {$client->errCode}\n");
}
$client->send("hello world\n");
echo $client->recv();
$client->close();