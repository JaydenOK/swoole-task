<?php

$table = new Swoole\Table(1024);
$table->column('fd', Swoole\Table::TYPE_INT);
$table->column('reactor_id', Swoole\Table::TYPE_INT);
$table->column('data', Swoole\Table::TYPE_STRING, 64);
$table->create();

$server = new Swoole\Server('127.0.0.1', 9501);
$server->set(['dispatch_mode' => 1]);
//赋值到server属性使用
$server->table = $table;
$server->on('receive', function (Swoole\Server $server, $fd, $reactor_id, $data) {
    $cmd = explode(" ", trim($data));
    if ($cmd[0] == 'get') {
        if (count($cmd) < 2) {
            $cmd[1] = $fd;
        }
        $get_fd = intval($cmd[1]);
        $info = $server->table->get($get_fd);
        $server->send($fd, var_export($info, true) . "\n");
    } else if ($cmd[0] == 'set') {
        $ret = $server->table->set($fd, array('reactor_id' => $data, 'fd' => $fd, 'data' => $cmd[1]));
        if ($ret === false) {
            $server->send($fd, "ERROR\n");
        } else {
            $server->send($fd, "OK\n");
        }
    } else {
        $server->send($fd, "command error.\n");
    }
});
$server->start();