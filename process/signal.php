<?php

//设置异步信号监听。
//此方法基于 signalfd 和 EventLoop 是异步 IO，不能用于阻塞的程序中，会导致注册的监听回调函数得不到调度；
//同步阻塞的程序可以使用 pcntl 扩展提供的 pcntl_signal；
//如果已设置了此信号的回调函数，重新设置时会覆盖历史设置。

Swoole\Process::signal(SIGTERM, function ($signo) {
    echo "shutdown.";
});