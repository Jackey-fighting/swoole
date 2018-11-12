一般使用到异步的时候，比如登录后要筛选些信息准备给客户的，或者队列，因为有优先级等等，可以采用beanstalk来实现效果
队列的使用场景：异步处理、系统解耦、定时处理。

Beanstalk的特性
优先级（priority）
延迟（delay）
持久化（persistent data）
预留（buried）
任务超时重发（time-to-run）


1、定时/延时 `DELAY`-->`READY`-->`RESERVE`-->`DELETE`
2、重复任务  `DELAY`-->`READY`-->`RESERVE`-->`DELAY` 重复N池
定时任务利用put的延时 + release的延时来搞

Beanstalk的任务状态
ready 任务就绪，随时可以被消费
delayed 任务延迟，延迟时间到时进入ready状态
buried 任务预留，此状态会重新进入ready
delete 删除任务
reserved 任务正在消费中，此状态可以进入delayed、ready、buried、delete

安装
1.百度安装pheanstalk
2.beanstalkd -l 127.0.0.1 -p 11300 -c & #后台运行beanstalkd命令
3.composer require pda/pheanstalk  #php操作beanstalkd的类,或者你直接将这个vendor搞下来
4.详细的代码操作可以参考 task.php 和 demo.php 等