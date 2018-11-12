<?php
$pheanstalk = require 'pheanstalk.php';
//有ready(就绪)，delay(延迟)，reserve(消费)，bury(预留)，delete(删除)
//print_r($pheanstalk->stats());
//print_r($pheanstalk->listTubes());
/*$pheanstalk->useTube('Jackey')->put(666);
//查看管道具体详细信息
print_r($pheanstalk->statsTube('Jackey'));*/

//监听管道Jackey
/*$job = $pheanstalk->watch('Jackey')->reserve();
//peek是获取管道id，然后进行监控，跟上面的类似
//$job = $pheanstalk->peek(2);
//查看job的详细信息
$stats = $pheanstalk->statsJob($job);
echo 'getData : '.$job->getData();
print_r($stats);
*/

//推入消息
/*$id = $pheanstalk->putInTube('Jackey', 'by-putInTube');
$job = $pheanstalk->peek($id);
print_r($pheanstalk->statsJob($job));
*/

//查看所有的管道
$listTubes = $pheanstalk->listTubes();
//查看单独管道详细信息
$tube = $pheanstalk->statsTube('Jackey');
//添加值
//$pheanstalk->putInTube('Jackey', 'by-putInTube', 0, 50);
//进行消费波，优先级先的任务先被执行
$job = $pheanstalk->watch('Jackey')->reserve();
echo PHP_EOL.' --------------statsTube---------------'.PHP_EOL;
print_r($tube);
if ($job) {//如果管道有任务才处理
	echo PHP_EOL.' --------------statsJob---------------'.PHP_EOL;
	print_r($pheanstalk->statsJob($job));
}

//$pheanstalk->delete($job);

//若有则获取管道，没则创建一个新管道
/*$tube = $pheanstalk->useTube('newUsers');
//推进4个任务，第二个参数是优先级，越小越优先
$tube->put('member_1024');
$tube->put('member_4',4);
$tube->put('member_3',3);
$tube->put('member_1000',1000);
//获取任务,并进行消费
$job = $pheanstalk->watch('newUsers')->reserve();
print_r($job);
//删除对应的每个任务
$pheanstalk->delete($job);*/
