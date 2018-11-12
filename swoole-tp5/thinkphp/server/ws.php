<?php
class Ws{
	CONST HOST = '0.0.0.0';
	CONST PORT = 8811;
	CONST CHART_PORT = 8812;

	public $ws = null;
	public function __construct(){
		$this->ws = new swoole_websocket_server(self::HOST, self::PORT);
		$port2 = $this->ws->listen(self::HOST, self::CHART_PORT, SWOOLE_SOCK_TCP);
		$port2->set([
			    'open_websocket_protocol' => true,    // 设置使得这个端口支持 webSocket 协议
			]);
		$this->ws->set([
				'enable_static_handler' => true,
        		'document_root' => "/home/wwwroot/default/share/swoole/thinkphp/public/static",
        		'worker_num' => 4,
        		'task_worker_num' => 4,
			]);

		$this->ws->on('start', [$this, 'onStart']);
		$this->ws->on('workerstart', [$this, 'onWorkerStart']);
		$this->ws->on('open', [$this, 'onOpen']);
		$this->ws->on('message', [$this, 'onMessage']);
		$this->ws->on('request', [$this, 'onRequest']);
		$this->ws->on('task', [$this, 'onTask']);
		$this->ws->on('finish', [$this, 'onFinish']);
		$this->ws->on('close', [$this, 'onClose']);

		$this->ws->start();
	}
	public function onStart(){
		swoole_set_process_name('live_game');
	}

	public function onWorkerStart(swoole_server $server, $worker_id){
	    // 定义应用目录
	    define('APP_PATH', __DIR__ . '/../application/');
	    //加载框架里面的文件
	    //require __DIR__ . '/../thinkphp/base.php';
	    require __DIR__ . '/../thinkphp/start.php';
	}

	/**
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws, $request) {
    	$ws->push($request->fd, 'clientId : '.$request->fd);
    	$redisInstance = app\common\lib\redis\Predis::getInstance();
    	$redisInstance->sadd(config('redis.live_game_key'), $request->fd);
    	$fdArr = $redisInstance->sMembers(config('redis.live_game_key'));
    	$ws->push($request->fd, '在线人数 ：'.count($fdArr));
    	echo '-----------------端口--------------------'.PHP_EOL;
        print_r($ws->connection_info($request->fd));
    	echo '-----------------端口--------------------'.PHP_EOL;
    	echo '-----------------ws--------------------'.PHP_EOL;
        print_r($this->ws);
    	echo '-----------------ws--------------------'.PHP_EOL;
    }

    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws, $frame) {
    	$_POST['http_fd'] = $frame->fd;
        echo "ser-push-message:{$frame->data}\n";
        $ws->push($frame->fd, "server-push:".date("Y-m-d H:i:s").' fd : '.$frame->fd);
    }

	public function onRequest($request, $response){
		unset($_GET,$_POST,$_SERVER);
	    if (isset($request->server)) {
	        foreach ($request->server as $key => $value) {
	            $_SERVER[strtoupper($key)] = $value;
	        }
	    }
	    if (isset($request->header)) {
	        foreach ($request->header as $key => $value) {
	            $_SERVER[strtoupper($key)] = $value;
	        }
	    }
	    if (isset($request->get)) {
	        foreach ($request->get as $key => $value) {
	            $_GET[$key] = $value;
	        }
	    }
	    if (isset($request->post)) {
	        foreach ($request->post as $key => $value) {
	            $_POST[$key] = $value;
	        }
	    }

	    $_POST['http_server'] = $this->ws;
    	$_POST['fd'] = $request->fd;
	    
	    ob_start();
	    // 执行应用并响应
	    try{
	        \think\Container::get('app', [defined('APP_PATH') ? APP_PATH : ''])
	        ->run()
	        ->send();
	    }catch(\Exception $e){

	    }
	    //echo '--action-- '.request()->action().PHP_EOL;
	    var_dump($request->get);
	    $content = ob_get_contents();
	    ob_end_clean();
	    $response->end($content);
	}

	public function onTask($serv, $taskId, $workerId, $data){
		$obj = new app\common\lib\task\Task;
		$obj->pushLive($data, $serv);
		$method = $data['method'];
		$flag = $obj->$method($data['data']);

		return $flag;
	}

	public function onFinish($serv, $taskId, $data){
		echo 'taskId: '.$taskId.PHP_EOL;
		echo 'finish-data-success: '.$data.PHP_EOL;
	}

	public function onClose($ws, $fd){
    	app\common\lib\redis\Predis::getInstance()->srem(config('redis.live_game_key'), $fd);
		echo 'clientId: '.$fd.PHP_EOL;
	}
}

new Ws();