<?php
namespace app\index\controller;
use app\common\lib\Util;
use app\common\lib\redis\Predis;
class Chart
{
    public function index()
    {
        //丢给task异步处理
        $redisInstance = Predis::getInstance();
        $fdArr = $redisInstance->sMembers(config('redis.live_game_key'));
        foreach ($fdArr as $fd) {
            $_POST['http_server']->push($fd, 'content : '.$_POST['content']);
        }
        return '';
        // 登录
        if(empty($_POST['game_id'])) {
            return Util::show(config('code.error'), 'error');
        }
        if(empty($_POST['content'])) {
            return Util::show(config('code.error'), 'error');
        }

        $data = [
            'user' => "用户".rand(0, 2000),
            'content' => $_POST['content'],
        ];
        //  todo
        foreach($_POST['http_server']->ports[1]->connections as $fd) {
            $_POST['http_server']->push($fd, json_encode($data));
        }

        return Util::show(config('code.success'), 'ok', $data);
    }


}
