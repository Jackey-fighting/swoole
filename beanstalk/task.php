<?php
$pheanstalk = require 'pheanstalk.php';

//延时管道
$pheanstalk->pauseTube('Jackey', 10);
$job = $pheanstalk->watch('Jackey')->reserve();
print_r($job);
print_r($pheanstalk->statsTube('Jackey'));
exit;

//获取延时的任务
$tube = $pheanstalk->useTube('Jackey');
//插入值到Jackey管道
$tube->put('delay_01',1,50);
$tube->put('delay_01',2,220);
$resutl = $pheanstalk->peekDelayed('Jackey');
print_r($resutl);
exit;


//批量将小于999 的bury状态的转成ready状态
$pheanstalk->useTube('Jackey')->kick(999);
print_r($pheanstalk->statsTube('Jackey'));
exit;

//kickJob()将bury的任务弄到ready任务
$job = $pheanstalk->peekBuried('Jackey');
print_r($pheanstalk->statsJob($job));
$pheanstalk->kickJob($job);
print_r($pheanstalk->statsTube('Jackey'));
exit;

//保留任务操作
$tube = $pheanstalk->useTube('Jackey');
$tube->put('hey',0);
//查看任务状态
$job = $pheanstalk->watch('Jackey')->reserve();
//$stats = $pheanstalk->statsJob($job);//任务详细信息，用于管理的
//$pheanstalk->delete($job);exit;

//释放
/*$flag = false;
if (!$flag) {
	sleep(30);
	//release就是把任务放到ready状态下，供消费者reserve
	$pheanstalk->release($job);
}else{
	$pheanstalk->delete($job);
}*/

sleep(10);
//保留起来
$pheanstalk->bury($job);

//查看管道信息
$statsTube = $pheanstalk->statsTube('Jackey');
print_r($statsTube);