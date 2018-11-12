<?php
echo 'start-time: '.date('Y-m-d H:i:s').PHP_EOL;
$rules = [
	'http://www.baidu.com',
	'http://www.baidu.com?search=Java',
	'http://www.baidu.com?search=php',
	'http://www.baidu.com?search=go',
	'http://www.baidu.com?search=c++',
	'http://www.sina.com.cn',
];

$count = count($rules);
for ($i=0; $i < $count; $i++) { 
	$process[] = new swoole_process(function(swoole_process $pro) use($rules,$i){
		$content = curlHandel($rules[$i], $i);
		echo $content.PHP_EOL;
	}, true);
	$pid = $process[$i]->start();
}
//管道读取数据
for ($i=0; $i < $count; $i++) { 
	echo $process[$i]->read();
}
function curlHandel($url,$i){
	$content = file_get_contents($url);
	file_put_contents('./curls/'.$i.'.txt', $content);
	return $url.' sucess';
}
echo 'end-time: '.date('Y-m-d H:i:s').PHP_EOL;
