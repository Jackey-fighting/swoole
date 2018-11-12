<?php
namespace app\admin\controller;
use app\common\lib\Util;
use app\common\lib\redis\Predis;
class Live
{

    public function push() {
        //redis的有序集合操作推送到detail页面
       /*$clientIds = \app\common\lib\redis\Predis::getInstance()->sMembers(config('redis.live_game_key'));
        foreach ($clientIds as $fd) {
            $arr = ['time'=>date('Y-m-d H:i:s'), 'rand'=>mt_rand(10,1000)];
            $_POST['http_server']->push($fd, json_encode($arr));
        }
        exit('111');*/
        if(empty($_GET)) {
            return Util::show(config('code.error'), 'error');
        }  // admin
        // token    md5(content)
        // => mysql
        $teams = [
            1 => [
                'name' => '马刺',
                'logo' => '/live/imgs/team1.png',
            ],
            4 => [
                'name' => '火箭',
                'logo' => '/live/imgs/team2.png',
            ],
        ];

        $data = [
            'type' => intval($_GET['type']),
            'title' => !empty($teams[$_GET['team_id']]['name']) ?$teams[$_GET['team_id']]['name'] : '直播员',
            'logo' => !empty($teams[$_GET['team_id']]['logo']) ?$teams[$_GET['team_id']]['logo'] : '',
            'content' => !empty($_GET['content']) ? $_GET['content'] : '',
            'image' => !empty($_GET['image']) ? $_GET['image'] : '',
        ];
        //print_r($_GET);
        // 获取连接的用户
        // 赛况的基本信息入库   2、数据组织好 push到直播页面
        $taskData = [
            'method' => 'pushLive',
            'data' => $data
        ];
        $_POST['http_server']->task($taskData);
        return Util::show(config('code.success'), 'ok');
    }

}
