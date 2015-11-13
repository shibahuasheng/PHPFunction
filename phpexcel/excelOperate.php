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


//开始处理excel
//excel验证（标识符）todo
//读取excel
//require_once('Classes/PHPExcel/Reader/Excel2007.php');
//$objReader = new PHPExcel_Reader_Excel2007;
//$objPHPExcel = $objReader->load("$_FILES[$fileElementName]['tmp_name']");
//将include设置为./Classses/ 路径
set_include_path(get_include_path() . PATH_SEPARATOR . './Classes/');
include 'PHPExcel/IOFactory.php';

$exceldata = excelRead(($_FILES[$fileElementName]['tmp_name']));
excelWrite('data',$a = array(), $exceldata, false, 5);
//或者$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel); 非2007格式
//$objWriter->save("a.xlsx");  存储excel

/*$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
header("Pragma: public");
header("Expires: 0");
header("Cache-Control:must-revalidate, post-check=0, pre-check=0;");
header("Content-Type:application/force-download");
header("Content-Type:application/vnd.ms-execl");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");;
header('Content-Disposition:attachment;filename="resume.xls"');
header("Content-Transfer-Encoding:binary");
$objWriter->save('php://output');*/


function excelRead($filename){
    $objReader = PHPExcel_IOFactory::createReader('Excel5');
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($filename);
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $highestRow = $objWorksheet->getHighestRow();
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
    $excelData = array();
    for ($row = 1; $row <= $highestRow; $row++) {
        for ($col = 0; $col < $highestColumnIndex; $col++) {
            $excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
        }
        /*可筛选固定的列
       $excelData[$row]['id'] = $objPHPExcel->getActiveSheet()->getCell("A".$row)->getValue();//ID
       $excelData[$row]['name'] = $objPHPExcel->getActiveSheet()->getCell("D".$row)->getValue();//姓名
        */
    }
    return $excelData;
}


/**
 * @param array $data  一个二维数组,结构如同从数据库查出来的数组
 * @param array $title excel的第一行标题,一个数组,如果为空则没有标题
 * @param string $filename  下载的文件名
 *
 * exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
 */
function excelWrite1($data=array(),$title=array(),$filename='report'){
    header("Content-type:application/octet-stream");
    header("Accept-Ranges:bytes");
    header("Content-type:application/vnd.ms-excel");
    header("Content-Disposition:attachment;filename=".$filename.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    //导出xls 开始
    if (!empty($title)){
        foreach ($title as $k => $v) {
            $title[$k]=iconv("UTF-8", "GB2312",$v);
        }
        $title= implode("\t", $title);
        echo "$title\n";
    }
    if (!empty($data)){
        foreach($data as $key=>$val){
            foreach ($val as $ck => $cv) {
                $data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
            }
            $data[$key]=implode("\t", $data[$key]);

        }
        echo implode("\n",$data);
    }
}
/*列索引*/
function getExcelColumnValue($index){
    $array = range('A', 'Z');
    $columnValue = '';
    if ($index >= 26) {
        $columnValue = getExcelColumnValue(intval($index / 26) - 1) . $array[$index % 26];
    } else {
        $columnValue = $array[$index] . $columnValue;
    }
    return $columnValue;
}

/**
 * @param $fileName
 * @param $headArr
 * @param $data    excel数据
 * @param bool|false $colNum  默认不限制列数，防止有些很末尾的列数隐藏数值而中间都是空的
 * @param bool|false $local  默认浏览器下载excel
 * @throws PHPExcel_Exception
 */
    function excelWrite($fileName, $headArr, $data,$local = false, $colNum = false)
    {
        if (empty($data) || !is_array($data)) {
            die("data must be a array");
        }
        if (empty($fileName)) {
            exit;
        }
        if(is_numeric($colNum) && is_int($colNum+0) && ((int)$colNum > 0) ) {
            $colNumCount = true;
        }
        $date = date("Y_m_d", time());
       /* if($local) {
       //03兼容格式
            $fileName .= "_{$date}.xls";
        }else{
            $fileName .= "_{$date}.xlsx";
        }*/
        $fileName .= "_{$date}.xls";

        //创建新的PHPExcel对象
        $objPHPExcel = new PHPExcel();
        $objProps = $objPHPExcel->getProperties();

        if(is_array($headArr) && !empty($headArr)){
            //设置表头
            $columindex = 0;
                foreach ($headArr as $key => $v) {
                    if($colNumCount ) {
                        if(((int)$columindex < (int)$colNum)) {
                            $colum = getExcelColumnValue($columindex);
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
                            $columindex++;
                        }
                    }else{
                        $colum = getExcelColumnValue($columindex);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
                        $columindex++;
                    }
                }

            $row = 2;
        }else{
            $row = 1;
        }

        $objActSheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->getActiveSheet()->getStyle( 'A1:E1')->getFill()->getStartColor()->setARGB('FF0094FF');
        //$objPHPExcel->getActiveSheet()->getStyle( 'A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        /*$objPHPExcel->getActiveSheet()->getStyle( 'A3:A10')->applyFromArray(
            array(
                'font'    => array (
                    'bold'      => true
                ),
                'alignment' => array (
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT ,
                ),
                'borders' => array (
                    'top'     => array (
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'fill' => array (
                    'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR ,
                    'rotation'   => 90,
                    'startcolor' => array (
                        'argb' => 'FFA0A0A0'
                    ),
                    'endcolor'   => array (
                        'argb' => 'FF0094FF'
                    )
                )
            )
        );*/

        foreach ($data as $key => $rows) { //行写入
            $columindex = 0;
            foreach ($rows as $keyName => $value) {// 列写入
                if($colNumCount){
                    if(((int)$columindex < (int)$colNum)) {
                        $colum = getExcelColumnValue($columindex);
                        //宽度
                        //$objActSheet->getColumnDimension($colum)->setAutoSize(true);
                        //颜色
                        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('FF808080');
                        $objActSheet->setCellValue($colum . $row, $value);
                        $columindex++;
                    }
                } else {
                    $colum = getExcelColumnValue($columindex);
                    //宽度
                    //$objActSheet->getColumnDimension($colum)->setAutoSize(true);
                    //颜色
                //$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('FF808080');
                    $objActSheet->setCellValue($colum . $row, $value);
                    $columindex++;
                }
            }
            $row++;
        }

        $fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        //将输出重定向到一个客户端web浏览器(Excel2007)

        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

        if($local) {
            $objWriter->save($fileName); //脚本方式运行，保存在当前目录
        }else{
            //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');这个中可以输出xlsx
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
        }
        exit;

}
