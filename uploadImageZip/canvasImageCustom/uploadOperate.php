<?php
/**
 * Date: 2015/11/17
 * Time: 17:25
 */

$imgname = $_POST['imgName'];
/*if(move_uploaded_file($imgdata , "./a.jpg")){
    echo "upload success!";
}else{
    echo "fail! try again!";
}*/
$img = str_replace(' ', '+', $_POST['imgData']);
$img = base64_decode($img);
$f = fopen('canvas.png', 'w+');


if(fwrite($f, $img)){
    $message = "success";
}else{
    $message = 'fail';
}
fclose($f);
exit($message);

