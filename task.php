<?php
class WebSocket{
	public function __construct(){
		$serv = new swoole_server('127.0.0.1', 9501, SWOOLE_BASE);

		$serv->set([
			'worker_num' => 2,
			'task_worker_num' => 4,
		]);

		$serv->on('Receive', function(swoole_server $serv, $fd, $from_id, $data){
			echo '接受数据 '. $data."\n";
			$data = trim($data);
			$task_id = $serv->task($data, 0);
			$serv->send($fd, "分发任务， 任务id为$task_id\n");
		});

		$serv->on('Task', function(swoole_server $serv, $task_id, $from_id, $data){
			file_put_contents('./task_write.txt', $data.PHP_EOL, FILE_APPEND);
			echo 'Tasker进程接受到数据';
			echo "#{$serv->worker_id}\tonTask: [PID={$serv->worker_pid}]: task_id=$task_id, data_len=".strlen($data).PHP_EOL;
			return;
			//$serv->finish($data);
		});

		$serv->on('Finish', function(swoole_server $serv, $task_id, $data){
			//echo "Task#$task_id finished, data_len=".strlen($data).PHP_EOL;
		});

		$serv->on('workerStart', function($serv, $worker_id){
			global $argv;
			if ($worker_id >= $serv->setting['worker_num']) {
				swoole_set_process_name("php {$argv[0]}: task_worker");
			}else{
				swoole_set_process_name("php {$argv[0]}: worker");
			}
		});

		$serv->start();
	}
}

new WebSocket();