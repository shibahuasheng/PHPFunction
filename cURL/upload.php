<?php
/**
 * Created by PhpStorm.
 * Date: 2015/7/27
 * Time: 17:36
 */
$url = "http://www.360weboy.me/upload.php";
$post_data = array (
    "attachment" => "@E:/jackblog/boy.jpg"
);

//初始化cURL会话
$ch = curl_init();

//设置请求的url
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//设置为post请求类型
curl_setopt($ch, CURLOPT_POST, 1);

//设置具体的post数据
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

$response = curl_exec($ch);
curl_close($ch);

print_r($response);