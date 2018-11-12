<?php
require './vendor/autoload.php';

use Pheanstalk\Pheanstalk;
//安装pheanstalk
//beanstalkd -l 127.0.0.1 -p 11300 -c & 后台运行beanstalkd命令
//composer require pda/pheanstalk  php操作beanstalkd的类
// Create using autodetection of socket implementation
$pheanstalk = new Pheanstalk('127.0.0.1',11300);
return $pheanstalk;
