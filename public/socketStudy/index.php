<?php
use Workerman\Worker;
use Workerman\Lib\Timer;
require_once __DIR__ . '/Workerman/Autoloader.php';

// 心跳间隔25秒
define('HEARTBEAT_TIME', 25);
$ws_worker = new Worker("websocket://0.0.0.0:1234");

// Worker::$daemonize = true; // 守护进程
// Worker::$stdoutFile = '/tmp/stdout.log';  // 配合上面的守护进程使用,所有的echo var_dump 会输出到日志中
// 启动4个进程对外提供服务
$ws_worker->count = 4;
$ws_worker->name = "ws_workerName";
$ws_worker->reusePort = false;
// 启动时候，立马执行
$ws_worker->onWorkerStart = function($worker){
    echo "work start...". PHP_EOL;
    echo "id:".$worker->id . PHP_EOL;
    echo "name:".$worker->name . PHP_EOL;
    echo "count:".$worker->count . PHP_EOL;
    echo "reusePort:".$worker->reusePort . PHP_EOL;
    echo "connections:".json_encode($worker->connections) . PHP_EOL;
//    var_dump($worker);

    // 定时，每10秒一次
    Timer::add(10, function()use($worker)
    {
        // 遍历当前进程所有的客户端连接，发送当前服务器的时间
        foreach($worker->connections as $connection)
        {
            $connection->send(time());
        }
    });
    // 心跳
    Timer::add(1, function()use($worker){
        $time_now = time();
        foreach($worker->connections as $connection) {
            // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
            if (empty($connection->lastMessageTime)) {
                $connection->lastMessageTime = $time_now;
                continue;
            }
            // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
            if ($time_now - $connection->lastMessageTime > HEARTBEAT_TIME) {
                $connection->close();
            }
        }
    });
};
// 这里只能拿到ip,在握手之前是无法获取数据的
$ws_worker->onConnect = function($connection){
    echo "new connection from ip " . $connection->getRemoteIp() . "\n";
};
$ws_worker->onWorkerReload = function($worker){
    echo "Reload...\n";
};
$ws_worker->onWorkerStop = function($worker){
    echo "Worker stopping...\n";
};
$ws_worker->onClose = function($connection)
{
    echo "connection closed...\n";
};
$ws_worker->onMessage = function($connection, $data)
{
    // 保持心跳
    $connection->lastMessageTime = time();

    $connection->send("你刚刚发了 " .$data);
};

// 运行worker
Worker::runAll();