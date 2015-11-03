<html>
<body>
<form action="">
    <input type="text" name="text">
    <input type="submit" value="生成">
</form>
</body>
</html>
<?php
// Including all required classes

$path = "./code/";
if($_REQUEST['text']){
    /*if(isset($_REQUEST['codebar']) && ($_REQUEST['text'])){*/
    /* $codebar = $_REQUEST['codebar']; *///条形码将要数据的内容
    $codebar = "BCGcode128";
    rmdirs($path);
    $text = $_REQUEST['text']; //条形码将要数据的内容
    $filename = "./code/".$text.".jpg";

    filecode($codebar, $text, $filename);

    $filename = "./code.zip"; //最终生成的文件名（含路径）
    if(file_exists($filename)){
        unlink($filename);
    }
//重新生成文件
    $zip=new ZipArchive();
    if($zip->open($filename,ZIPARCHIVE::CREATE)!==TRUE){
        exit('无法打开文件，或者文件创建失败');
    }
    $datalist=list_dir($path);
    foreach( $datalist as $val){
        if(file_exists($val)){
            $zip->addFile( $val, basename($val));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
        }
    }
    $zip->close();//关闭
    if(!file_exists($filename)) {
        exit("无法找到文件"); //即使创建，仍有可能失败。。。。
    }

    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header('Content-disposition: attachment; filename='.basename($filename)); //文件名
    header("Content-Type: application/zip"); //zip格式的
    header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
    header('Content-Length: '. filesize($filename)); //告诉浏览器，文件大小


    @readfile($filename);

}else{
    $text = "testcode";
    $filename = "./code/testcode.jpg";
}



function filecode($codebar, $text, $filename = '')
{
    require_once('class/BCGFontFile.php');
    require_once('class/BCGColor.php');
    require_once('class/BCGDrawing.php');


// Including the barcode technology
    require_once('class/' . $codebar . '.barcode.php');

// Loading Font
    $font = new BCGFontFile('./font/Arial.ttf', 12);

// The arguments are R, G, B for color.
    $color_black = new BCGColor(0, 0, 0);
    $color_white = new BCGColor(255, 255, 255);

    $drawException = null;
    try {
        $code = new $codebar();//实例化对应的编码格式
        $code->setScale(2); // Resolution
        $code->setThickness(23); // Thickness
        $code->setForegroundColor($color_black); // Color of bars
        $code->setBackgroundColor($color_white); // Color of spaces
        $code->setFont($font); // Font (or 0)
        /* $text = $_REQUEST['text']; //条形码将要数据的内容*/
        $code->parse($text);
    } catch (Exception $exception) {
        $drawException = $exception;
    }

    /* Here is the list of the arguments
    - Filename (empty : display on screen)
    - Background color */
    $drawing = new BCGDrawing($filename, $color_white);

    if ($drawException) {
        $drawing->drawException($drawException);
    } else {
        $drawing->setBarcode($code);
        $drawing->draw();
    }

    /* var_dump($drawing);*/
// Header that says it is an image (remove it if you save the barcode to a file)
    header('Content-Type: image/png');

// Draw (or save) the image into PNG format.
    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
    /*imagepng($drawing->finish(BCGDrawing::IMG_FORMAT_PNG) , 'aacode.png');*/
    /*   GrabImage("/Test/PHP/barcodegen.1d-php5.v5.2.1/", 'acode.jpg');*/
}


//获取文件列表
function list_dir($dir){
    $result = array();
    if (is_dir($dir)){
        $file_dir = scandir($dir);
        foreach($file_dir as $file){
            if ($file == '.' || $file == '..'){
                continue;
            }
            elseif (is_dir($dir.$file)){
                $result = array_merge($result, list_dir($dir.$file.'/'));
            }
            else{
                array_push($result, $dir.$file);
            }
        }
    }
    return $result;
}

function rmdirs($dir)
{
    //error_reporting(0);    函数会返回一个状态,我用error_reporting(0)屏蔽掉输出
    //rmdir函数会返回一个状态,我用@屏蔽掉输出
    $dir_arr = scandir($dir);
    foreach ($dir_arr as $key => $val) {
        if ($val == '.' || $val == '..') {
        } else {
            if (is_dir($dir . '/' . $val)) {
                if (@rmdir($dir . '/' . $val) == 'true') {
                }    //去掉@您看看
                else
                    rmdirs($dir . '/' . $val);
            } else
                unlink($dir . '/' . $val);
        }
    }
}
?>