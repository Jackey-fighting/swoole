<?php
$process = new swoole_process('callBack', false);

function callBack(swoole_process $pro){
	//exce 与 父进程进行管道通信
	$pro->exec('/usr/bin/php',[dirname(__FILE__).'/../task.php']);
}
$pid = $process->start();
echo $pid.PHP_EOL;
//echo 'form exec: '.$process->read().PHP_EOL;
