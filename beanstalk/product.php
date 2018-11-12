<?php
//生产者
$pheanstalk = require 'pheanstalk.php';
$str = 'Hello, now is ';
$pheanstalk->useTube('Jackey')->put($str,10);