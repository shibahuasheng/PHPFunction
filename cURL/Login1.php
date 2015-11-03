<?php
$cookie_file = tempnam('temp','cookie');
$login_url  = 'http://bbs.php100.com/login.php';
$post_fields = 'cktime=31536000&step=2&pwuser=dmodai&pwpwd=waxd32123';
$ch = curl_init($login_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_exec($ch);
curl_close($ch);

$url='http://bbs.php100.com/userpay.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
$contents = curl_exec($ch);
preg_match("/<li>金钱：(.*)<\/li>/",$contents,$arr);
/*echo $arr[1];*/

print_r($arr);

curl_close($ch);