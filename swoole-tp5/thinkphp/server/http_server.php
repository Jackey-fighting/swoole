<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 18/2/28
 * Time: 上午1:39
 */
$http = new swoole_http_server("0.0.0.0", 8811);

$http->set(
    [
        'enable_static_handler' => true,
        'document_root' => "/home/wwwroot/default/share/swoole/thinkphp/public/static",
        'worker_num' => 5,
    ]
);
$http->on('WorkerStart', function(swoole_server $server, $worker_id){
    // 定义应用目录
    define('APP_PATH', __DIR__ . '/../application/');
    //加载框架里面的文件
    require __DIR__ . '/../thinkphp/base.php';
});
//如果存在了document_root路径文件，那就直接返回静态文件，不调用onRequest
$http->on('request', function($request, $response) use($http){

    //print_r($request->header);
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

    ob_start();
    // 执行应用并响应
    try{
        \think\Container::get('app', [defined('APP_PATH') ? APP_PATH : ''])
        ->run()
        ->send();
    }catch(\Exception $e){

    }
    //echo '--action-- '.request()->action().PHP_EOL;
    $content = ob_get_contents();
    ob_end_clean();
    $response->end($content);
    //$http->close();
});
$http->start();