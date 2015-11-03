<?php
/**
 * Created by PhpStorm.
 * Date: 2015/7/22
 * Time: 18:19
 */
$url = "http://localhost/PHP/cURL/curlAction1.php";
$post_data = array (
    "blog_name" => "360weboy",
    "blog_url" => "http://www.360weboy.com",
    "action" => "Submit"
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// 设置请求为post类型
curl_setopt($ch, CURLOPT_POST, 1);
// 添加post数据到请求中
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

// 执行post请求，获得回复
$response= curl_exec($ch);
curl_close($ch);

echo $response;