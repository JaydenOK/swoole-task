<?php

//默认定时器在执行回调函数时会自动创建协程，可单独设置定时器关闭协程。

$startTime = time();   //总共执行时间
$execSecond = 60;   //总共执行时间
$duration = 1;    //执行周期，秒

//设置一个间隔时钟定时器
$timerId = Swoole\Timer::tick($duration * 1000, function (int $timerId, $execSecond, $startTime) {
    echo $timerId . date('Y-m-d H:i:s') . PHP_EOL;
    if (time() - $startTime >= $execSecond) {
        //删除定时器
        echo 'time out' . PHP_EOL;
        Swoole\Timer::clear($timerId);
    }
}, $execSecond, $startTime);

//使用定时器 ID 来删除定时器
//Swoole\Timer::clear($timerId);


//在指定的时间后执行函数。Swoole\Timer::after 函数是一个一次性定时器，执行完成后就会销毁。
//$str = "Swoole";
//Swoole\Timer::after(1000, function () use ($str) {
//    echo "Hello, $str\n";
//});