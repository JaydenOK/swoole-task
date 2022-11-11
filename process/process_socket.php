<?php

//实现了一个简单的父子进程通讯：
//同一台主机上两个进程间通信 (简称 IPC) 的方式有很多种，在 Swoole 下使用了 2 种方式 Unix Socket 和 sysvmsg(基本不用)
//exportSocket() 将 unixSocket 导出为 Coroutine\Socket 对象，然后利用 Coroutine\socket 对象的方法进程间通讯，

use Swoole\Process;
use function Swoole\Coroutine\run;

//由于导出是 Coroutine\Socket 对象，必须在协程容器中使用，所以 Swoole\Process 构造函数 $enable_coroutine 参数必须为 true。
$proc1 = new Process(function (Process $proc) {
    //导出创建的子进程为Coroutine\Socket对象使用
    //进程重命名：name 方法应当在 start 之后的子进程回调函数中使用
    $proc->name('child-process-1');
    $socket = $proc->exportSocket();
    echo $socket->recv();
    $socket->send("hello master\n");
    echo "proc1 stop\n";
    //子进程执行完，回调wait(true)，结束父进程阻塞
    //退出子进程
    $proc->exit();
}, false, 1, true);
$proc1->start();

$proc2 = new Process(function (Process $proc2) {
    $proc2->name('child-process-2');
    $socket = $proc2->exportSocket();
    echo $socket->recv();
    $socket->send("hello master-2\n");
    echo "proc2 stop\n";
    $proc2->exit();
}, false, 1, true);
$proc2->start();

//同样的父进程想用 Coroutine\Socket 对象，需要手动 Coroutine\run() 以创建协程容器
run(function () use ($proc1, $proc2) {
    $socket = $proc1->exportSocket();
    $socket->send("hello pro1\n");
    var_dump($socket->recv());

    $socket2 = $proc2->exportSocket();
    $socket2->send("hello pro2\n");
    var_dump($socket2->recv());
});


//每个子进程结束后，父进程必须都要执行一次 wait() 进行回收，否则子进程会变成僵尸进程，会浪费操作系统的进程资源。
//如果父进程有其他任务要做，没法阻塞 wait 在那里，父进程必须注册信号 SIGCHLD 对退出的进程执行 wait。
//SIGCHILD 信号发生时可能同时有多个子进程退出；必须将 wait() 设置为非阻塞，循环执行 wait 直到返回 false。

//回收结束运行的子进程，指定是否阻塞等待【默认为阻塞】
//Process::wait(true);
//异步不阻塞方式
Swoole\Process::signal(SIGCHLD, function ($sig) {
    //必须为false，非阻塞模式
    while ($ret = Swoole\Process::wait(false)) {
        echo "PID={$ret['pid']}\n";
    }
});

//使当前进程蜕变为一个守护进程。
//蜕变为守护进程时，该进程的 PID 将发生变化，可以使用 getmypid() 来获取当前的 PID
Swoole\Process::daemon(true);