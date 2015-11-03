<?php
header("content-type:text/html;charset=utf-8");
$smtp = array(
    "url"      => "邮箱SMTP服务器地址",
    "port"     => "邮箱SMTP服务器端口", // 一般为25
    "username" => "用户名",
    "password" => "密码",
    "from"     => "发件地址",
    "to"       => "收件地址",
    "subject"  => "测试一下标题",
    "body"     => "测试一下内容"
);

$CRLF = "\r\n";
$test = "";
$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $smtp['url']);
curl_setopt($curl, CURLOPT_PORT, $smtp['port']);
curl_setopt($curl, CURLOPT_TIMEOUT,10);

function inlineCode($str){
    $str = trim($str);
    return $str?'=?UTF-8?B?'.base64_encode($str).'?= ':'';
}

function buildHeader($headers){
    $ret = '';
    foreach($headers as $k=>$v){
        $ret.=$k.': '.$v."\n";
    }
    return $ret;
}

// 
$header = array(
    'Return-path'=>'<'.$smtp['from'].'>',
    'Date'=>date('r'),
    'From'=> '<'.$smtp['from'].'>',
    'MIME-Version'=>'1.0',
    'Subject'=>inlineCode($smtp['subject']),
    'To'=>$smtp['to'],
    'Content-Type'=>'text/html; charset=UTF-8; format=flowed',
    'Content-Transfer-Encoding'=>'base64'
);
$data = buildHeader($header).$CRLF.chunk_split(base64_encode($smtp['body']));


$content  = "EHLO ".$smtp["url"].$CRLF; // 先得hello一下
$content .= "AUTH LOGIN".$CRLF.base64_encode($smtp["username"]).$CRLF.base64_encode($smtp["password"]).$CRLF; // 验证登陆
$content .= "MAIL FROM:".$smtp["from"].$CRLF; // 发件地址
$content .= "RCPT TO:".$smtp["to"].$CRLF;  // 收件地址
$content .= "DATA".$CRLF.$data.$CRLF.".".$CRLF; // 发送内容
$content .= "QUIT".$CRLF; // 退出

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);    // curl接收返回数据
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $content);
$test = curl_exec($curl);
var_dump($test);
echo "<br/>\r\n";
var_dump($content);

// 结束
curl_close($curl);