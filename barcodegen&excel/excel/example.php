<?php
error_reporting(E_ALL ^ E_NOTICE);
header("Content-Type:text/html;charset=utf-8");
require_once 'excel_reader2.php';
//创建对象
$data = new Spreadsheet_Excel_Reader();
//设置文本输出编码
$data->setOutputEncoding('UTF-8');
//读取Excel文件
$data->read("example.xls");
//$data->sheets[0]['numRows']为Excel行数
for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
    //$data->sheets[0]['numCols']为Excel列数
    for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
        //显示每个单元格内容
        echo $data->sheets[0]['cells'][$i][$j] . '  ';
    }
    echo '<br>';
}
