<?php
//登录等其他验证，例如下面的获取$uid
//print_r($_FILES);
/*if(move_uploaded_file($_FILES['file']['tmp_name'],'./user/up.xls'))//上传文件，成功返回true
{echo '上传成功';
}
else
{echo '上传失败';}*/
$result = array(
    "error" => "",
    "message" => "",
    "responsefile" => ""
);
$fileElementName = 'fileToUpload';//html中上传文件的input的name
$allowType = array(".xls",".xlsx",".csv"); //允许上传的文件类型
$num      = strrpos($_FILES[$fileElementName]['name'] ,'.');
$fileSuffixName    = strtolower(substr($_FILES[$fileElementName]['name'],$num,8));//此数可变
$upFilePath             = 'd:/'; //最终存放路径
if(!empty($_FILES[$fileElementName]['error'])) {
    switch ($_FILES[$fileElementName]['error']) {
        case '1':
            $error = '传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值';
            break;
        case '2':
            $error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
            break;
        case '3':
            $error = '文件只有部分被上传';
            break;
        case '4':
            $error = '没有文件被上传';
            break;

        case '6':
            $error = '找不到临时文件夹';
            break;
        case '7':
            $error = '文件写入失败';
            break;
        default:
            $error = '未知错误';
    }
    $result['error'] = $error;
    exit(json_encode($result));
}
if(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none') {
    $result['error']  = '没有上传文件.';
    exit(json_encode($result));
}
if(!in_array($fileSuffixName,$allowType)) {
    $result['error']  = '不允许上传的文件类型';
    exit(json_encode($result));
}
/*
if(@move_uploaded_file($_FILES[$fileElementName]['tmp_name'],$upFilePath) === FALSE){
    $error = '上传失败';
    exit(json_encode($result));
}*/


require_once 'excel_reader2.php';//引用文件
$data = new Spreadsheet_Excel_Reader();
//设置文本输出编码
$data->setOutputEncoding('UTF-8');
$data->read($_FILES[$fileElementName]['tmp_name']);//读取excel

$uid = $_SESSION['uid'];
$path = "../user/".$uid."/code/";
createDir($path);
rmdirs($path);

for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
    //$data->sheets[0]['numCols']为Excel列数
    if($data->sheets[0]['numCols']!=1 ){
        $result['error']  = "请确保excel只有第一列为条形码";
        exit(json_encode($result));
    }
    //for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
        //显示每个单元格内容
        $code =  $data->sheets[0]['cells'][$i][1];
    // }

    if($code){
        /*if(isset($_REQUEST['codebar']) && ($_REQUEST['text'])){*/
        /* $codebar = $_REQUEST['codebar']; *///条形码将要数据的内容
        $codebar = "BCGcode39";
       /* $filename = $path.$code.".jpg";*/
      $filename = $path.$i."--".$code.".jpg";
       // filecode($codebar, $code, $filename);
        /*todo 将失败和成功的都显示出来*/
      if($codeError = filecode($codebar, $code, $filename)){
          $error.= "第".$i."行 ".$code . " 生成错误 ：" .$codeError."\n";
      }
    }else{
        //todo 完善
       $error.= "第".$i."行 没有输入数据\n";
    }
}
/*$result['error'] = "test2";
exit(json_encode($result));*/
if($error){
    //写入错误
    $result['responsefile'] = "../user/".$uid."/error.log";
    file_put_contents($result['responsefile'], $error);
    exit(json_encode($result));
}

$filename = "../user/".$uid."/code.zip"; //最终生成的文件名（含路径）
if(file_exists($filename)){
    unlink($filename);
}
//重新生成文件
$zip=new ZipArchive();
if($zip->open($filename,ZIPARCHIVE::CREATE)!==TRUE){
    $result['error'] = '无法打开文件，或者文件创建失败';
    exit(json_encode($result));
}
$datalist=list_dir($path);
foreach( $datalist as $key=>$val){
    if(file_exists($val)){
        $zip->addFile( $val, basename($val));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
    }
}
/*var_dump($datalist);*/
$zip->close();//关闭
if(!file_exists($filename)) {
   //即使创建，仍有可能失败。。。。
   $result['error'] = "无法找到文件";
    exit(json_encode($result));
}

$result['responsefile']  = $filename;
exit(json_encode($result));
//Header("Location: ".$filename);

/*
header("Cache-Control: public");
header("Content-Description: File Transfer");
header('Content-disposition: attachment; filename='.basename($filename)); //文件名
header("Content-Type: application/zip"); //zip格式的
header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
header('Content-Length: '. filesize($filename)); //告诉浏览器，文件大小
@readfile($filename);*/


/**
 * @param $codebar
 * @param $text
 * @param string $filename
 * @param string $error
 * @return string  有错误就返回
 * @throws BCGDrawException
 */
function filecode($codebar, $text, $filename = '',$error="")
{
    require_once('../class/BCGFontFile.php');
    require_once('../class/BCGColor.php');
    require_once('../class/BCGDrawing.php');


// Including the barcode technology
    require_once('../class/' . $codebar . '.barcode.php');

// Loading Font
    $font = new BCGFontFile('../font/Arial.ttf', 12);

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
        $error = $drawException->getMessage();
        return $error;
       $drawing->drawException($drawException);
    } else {
        $drawing->setBarcode($code);
        $drawing->draw();
    }

    /* var_dump($drawing);*/
// Header that says it is an image (remove it if you save the barcode to a file))
    //header('Content-Type: image/png');

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

function createDir($aimUrl) {
    $aimUrl = str_replace('', '/', $aimUrl);
    $aimDir = '';
    $arr = explode('/', $aimUrl);
    $result = true;
    foreach ($arr as $str) {
        $aimDir .= $str . '/';
        if (!file_exists($aimDir)) {
            $result = mkdir($aimDir);
        }
    }
    return $result;
}
