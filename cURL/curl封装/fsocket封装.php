<?php
/**
 * Date: 2015/8/2
 * Time: 19:30
 */
//Fsockopen
/**
 *使用方法同CURL
 */
function sw_http_open($url, $conf = array()) {
    $return = '';
    if(!is_array($conf))
    {
        return $return;
    }
    $matches = parse_url($url);
    !isset($matches['host']) && $matches['host'] = '';
    !isset($matches['path']) && $matches['path'] = '';
    !isset($matches['query']) && $matches['query'] = '';
    !isset($matches['port']) && $matches['port'] = '';
    $host = $matches['host'];
    $path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
    $port = !emptyempty($matches['port']) ? $matches['port'] : 80;

    $conf_arr = array(
        'limit'=>0,
        'post'=>'',
        'cookie'=>'',
        'bysocket'=>FALSE,
        'ip'=>'',
        'timeout'=>15,
        'block'=>TRUE,
    );

    foreach (array_merge($conf_arr, $conf) as $k=>$v) ${$k} = $v;

    if($post) {
        if(is_array($post))
        {
            $post = http_build_query($post);
        }
        $out = "POST $path HTTP/1.0/r/n";
        $out .= "Accept: */*/r/n";
        //$out .= "Referer: $boardurl/r/n";
        $out .= "Accept-Language: zh-cn/r/n";
        $out .= "Content-Type: application/x-www-form-urlencoded/r/n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]/r/n";
        $out .= "Host: $host/r/n";
        $out .= 'Content-Length: '.strlen($post)."/r/n";
        $out .= "Connection: Close/r/n";
        $out .= "Cache-Control: no-cache/r/n";
        $out .= "Cookie: $cookie/r/n/r/n";
        $out .= $post;
    } else {
        $out = "GET $path HTTP/1.0/r/n";
        $out .= "Accept: */*/r/n";
        //$out .= "Referer: $boardurl/r/n";
        $out .= "Accept-Language: zh-cn/r/n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]/r/n";
        $out .= "Host: $host/r/n";
        $out .= "Connection: Close/r/n";
        $out .= "Cookie: $cookie/r/n/r/n";
    }
    $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
    if(!$fp) {
        return '';
    } else {
        stream_set_blocking($fp, $block);
        stream_set_timeout($fp, $timeout);
        @fwrite($fp, $out);
        $status = stream_get_meta_data($fp);
        if(!$status['timed_out']) {
            while (!feof($fp)) {
                if(($header = @fgets($fp)) && ($header == "/r/n" ||  $header == "/n")) {
                    break;
                }
            }

            $stop = false;
            while(!feof($fp) && !$stop) {
                $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                $return .= $data;
                if($limit) {
                    $limit -= strlen($data);
                    $stop = $limit <= 0;
                }
            }
        }
        @fclose($fp);
        return $return;
    }
}
