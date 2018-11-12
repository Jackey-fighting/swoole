<?php
//消费者
$pheanstalk = require 'pheanstalk.php';

while (true) {
	//取出任务,server()的时候是阻塞的，阻塞的时候，服务器性能等于没消耗
	$job = $pheanstalk->watch('Jackey')->reserve();
	$data = $job->getData().date('Y-m-d H:i:s').PHP_EOL;
	print_r($pheanstalk->statsJob($job));
	//获取这个数据
	//echo($job->getData());
	//处理具体业务
	file_put_contents('log.txt', $data,FILE_APPEND);

	//释放任务回到ready
	$pheanstalk->release($job,0,10);
	//$pheanstalk->delete($job);//删除任务
}

