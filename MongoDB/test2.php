<?php
/**
 * Date: 2015/9/24
 * Time: 12:36
 */
$connection = new Mongo(mongodb://192.168.1.5:27017); //链接到 192.168.1.5:27017//27017端口是默认的。
$connection = new Mongo( "example.com" ); //链接到远程主机(默认端口)
$connection = new Mongo( "example.com:65432" ); //链接到远程主机的自定义的端口
print_r($connection->listDBs());//能打印出数据库数组，看看有几个数据库。