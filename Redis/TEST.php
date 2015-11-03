<?php
/**
 * Date: 2015/7/29
 * Time: 14:53
 */
/*phpinfo();*/
$redis = new Redis();
$redis->connect("127.0.0.1","6379");
$redis->set("test","Hello World");
echo $redis->get("test");
