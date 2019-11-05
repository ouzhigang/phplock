<?php

require_once(dirname(__FILE__) . "/RedisLock.php");

use \phplock\RedisLock;

$k = "aaa";

//lock方法执行后只有一个请求在执行下面的逻辑，其余请求均在排队
$v = RedisLock::lock($k);
var_dump($v);


//处理逻辑
sleep(10);


var_dump(RedisLock::unlock($k, $v));
