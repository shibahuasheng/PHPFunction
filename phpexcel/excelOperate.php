<?php
/**
 * Date: 11/13/2015
 * Time: 9:57 AM
 */
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
 * 如果需要上传文件到指定指定则指向下面的$upFilePath,如果只是处理数据则 $data->read($_FILES[$fileElementName]['tmp_name']);//读取excel累世这种直接读取缓存文件中的数据是一样的
if(@move_uploaded_file($_FILES[$fileElementName]['tmp_name'],$upFilePath) === FALSE){
    $error = '上传失败';
    exit(json_encode($result));
}*/
$result['message'] = '上传成功';
exit(json_encode($result));
