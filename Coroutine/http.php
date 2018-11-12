<?php
$http = new swoole_http_server("0.0.0.0", 9501);
$http->set([
	//开启内置协程
	'enable_coroutine' => true,
]);

$http->on("request", function ($request, $response) {
    $swoole_mysql = new Swoole\Coroutine\MySQL();
	$swoole_mysql->connect([
	    'host' => '127.0.0.1',
	    'port' => 3306,
	    'user' => 'root',
	    'password' => '123456',
	    'database' => 'mysql',
	]) or $response->end('<i>MySQL连接失败</i>');
	$res = $swoole_mysql->query('select * from user');
	$response->end(json_encode($res));
	$swoole_mysql->close();
});

$http->start();