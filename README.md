# swoole-task

#### swoole 实际项目应用

```text
coroutine/

1，Coroutine协程并发实例
常驻监听进程启动，Http Server + 协程 + channel 实现并发处理，可控制并发数量，分批次执行任务，适用于内部系统要处理大量耗时的任务

2，对外提供api高并发访问接口
使用常驻监听进程，pdo-mysql连接池，多协程处理业务逻辑

3，不启动常驻监听进程server，直接一次性处理的任务



```

```text
worker_task/

worker-task 异步任务 ，分批次处理异步任务
worker进程接收client任务投递，并投递到task进程异步处理任务，worker进程onFinish事件接收处理结果，并投递剩余任务


```


```text
process/


```