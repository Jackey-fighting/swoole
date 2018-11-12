swoole的websocket js可以看detail.html ;服务端可以看 server/ws.php

task:用于处理耗时，需要速度的，异步处理的任务
1.直接把业务丢到onTask()里面，可以在函数里面用循环，因为它会自动调用任务池(task_worker_num)去处理
2.一定要是websocket的，才可以调用$ws->task()任务，不过onrequest()里也可以调用task()，只是服务器会报错('Swoole\WebSocket\Server::push(): the connected client of connection[42] is not a websocket client or')，但会继续执行，推送数据出来

push:服务端发送消息给客户端
1.有时可以考虑把swoole_websocket_server放到 $_POST全局变量下，方便后面调用和结合push使用
2.$ws->push($fd, $string);
3.$fd可以存放到redis的sadd()有序集合里面；
4.websocket连接的时候，onOpen()里就开始把$fd放到sadd()里面，onClose()的时候，就srem($key,$value)删除对应值


监控服务:(具体可以参看 script/bin/monitor/server.php)
1.利用Linux命令netstat -anp|grep $port... ;
2.使用shell_exec()来执行1这条命令;
3.可以利用Linux的crontab的定时器来定时执行监控(只能定时到每分钟);
4.也可以利用swoole的timer定时器来执行，或者beanstalk也可以具体到秒;


服务器平滑重启：
//设置进程名称
public function onStart($server){
	swoole_set_process_name('live_master');
}
然后再利用shell脚本平滑重启
编写sh文件，reload.sh
然后服务器文件有更改的话，直接在运行下 sh reload.sh即可，
其他用户的访问不会断


lnmp下的nginx访问静态文件：
1.find / -name nginx.conf

2.location /live_game/ {
    alias /home/wwwroot/default/share/swoole/thinkphp/public/static/;
    index index.html index.html;

    if (!-e $request_filename) {
            proxy_pass http://192.168.0.107:8811;
    }
}
3.后台文件则访问swoole的websocket服务的端口的，结合tp5的，则利用s=index/index/hello来访问，
http://192.168.0.107/live_game/tp?s=index/index/hello（这个tp是随便哪个都行，但一定要有，不然一直报403）