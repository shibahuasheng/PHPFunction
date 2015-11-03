<?php
/**
 * Date: 2015/8/2
 * Time: 19:29
 *///CURL
/**
 * 使用：
 * echo cevin_http_open('http://www.baidu.com');
 *
 * POST数据
 * $post = array('aa'=>'ddd','ee'=>'d')
 * 或
 * $post = 'aa=ddd&ee=d';
 * echo cevin_http_open('http://www.baidu.com',array('post'=>$post));
 */
function cevin_http_open($url, $conf = array())
{
    if(!function_exists('curl_init') or !is_array($conf))  return FALSE;

    $post = '';
    $purl = parse_url($url);

    $arr = array(
        'post' => FALSE,
        'return' => TRUE,
        'cookie' => 'C:/cookie.txt',);
    $arr = array_merge($arr, $conf);
    $ch = curl_init();

    if($purl['scheme'] == 'https')
    {
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, $arr['return']);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $arr['cookie']);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $arr['cookie']);

    if($arr['post'] != FALSE)
    {
        curl_setopt($ch, CURL_POST, TRUE);
        if(is_array($arr['post']))
        {
            $post = http_build_query($arr['post']);
        } else {
            $post = $arr['post'];
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}


