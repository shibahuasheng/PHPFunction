<?php
/**
 * Created by PhpStorm.
 * Date: 2015/7/27
 * Time: 17:46
 */
function GetSources($Url,$User_Agent='',$Referer_Url='') //抓取某个指定的页面
{
//$Url 需要抓取的页面地址
//$User_Agent 需要返回的user_agent信息 如“baiduspider”或“googlebot”
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $Url);
    curl_setopt ($ch, CURLOPT_USERAGENT, $User_Agent);
    curl_setopt ($ch, CURLOPT_REFERER, $Referer_Url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    $MySources = curl_exec ($ch);
    curl_close($ch);
    return $MySources;
}
$Url = "http://www.jb51.net"; //要获取内容的也没
$User_Agent = "baiduspider+(+http://www.baidu.com/search/spider.htm)";
$Referer_Url = 'http://www.jb51.net/';
echo GetSources($Url,$User_Agent,$Referer_Url);
?>
