<?php
/**
 * Date: 2015/9/24
 * Time: 12:36
 */
$connection = new Mongo(mongodb://192.168.1.5:27017); //���ӵ� 192.168.1.5:27017//27017�˿���Ĭ�ϵġ�
$connection = new Mongo( "example.com" ); //���ӵ�Զ������(Ĭ�϶˿�)
$connection = new Mongo( "example.com:65432" ); //���ӵ�Զ���������Զ���Ķ˿�
print_r($connection->listDBs());//�ܴ�ӡ�����ݿ����飬�����м������ݿ⡣