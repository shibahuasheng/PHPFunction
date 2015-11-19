<?php
/**
 * Date: 2015/11/16
 * Time: 18:20
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
//将include设置为./Classses/ 路径
set_include_path(get_include_path() . PATH_SEPARATOR . './Classes/');
include 'PHPExcel/IOFactory.php';
include('PHPExcel.php');



$excelData = excelRead(($_FILES[$fileElementName]['tmp_name']));
$excelHeader = array('a1','a2','a3','a4','a5','a6','a7','a8','a9','a10');
excelWrite('aaa', $excelData,$excelHeader, false);

function excelRead($excelfile){

    if(is_null($excelfile)){
       exit('excel文件不存在');
   }

    $exceldata = array();

    $reader         =  PHPExcel_IOFactory::createReaderForFile($excelfile); #Excel2007
    $reader->setReadDataOnly(true);
    $excel= $reader->load($excelfile);
    $sheet=$excel->getActiveSheet();
    $highestRow = $sheet->getHighestRow(); // 取得总行数，从一开始
    $highestColumm = $sheet->getHighestColumn(); // 取得总列数，从0开始

    //检查excel存在于AZ位置上的密码是否为20021514
    /*$key = $sheet -> getCellByColumnAndRow( 51 , 1 ) -> getValue();//按照索引取
    if( $key != '20021514' ){}else{}*/

    //不包含excel头行
    for($row = 2 ;$row <= $highestRow ;$row++){
        //直接选择列
        $i = $row - 2;
        $exceldata[$i][] = trim($sheet->getCellByColumnAndRow(0 , $row)->getValue());
        $exceldata[$i][] = trim($sheet->getCellByColumnAndRow(1 , $row)->getValue());
        $exceldata[$i][] = trim($sheet->getCellByColumnAndRow(2 , $row)->getValue());
        $exceldata[$i][] = trim($sheet->getCellByColumnAndRow(3 , $row)->getValue());
        $exceldata[$i][] = trim($sheet->getCellByColumnAndRow(4 , $row)->getValue());
        $exceldata[$i][] = trim($sheet->getCellByColumnAndRow(5 , $row)->getValue());
        $exceldata[$i][] = trim($sheet->getCellByColumnAndRow(6 , $row)->getValue());
        $exceldata[$i][] = trim($sheet->getCellByColumnAndRow(7 , $row)->getValue());
        $exceldata[$i][] = trim($sheet->getCellByColumnAndRow(8 , $row)->getValue());
        $exceldata[$i][] = trim($sheet->getCellByColumnAndRow(9 , $row)->getValue());
    }

    //对数据做相应处理

    return $exceldata;

}

function excelWrite($filename, $exceldata, $excelHeader, $local =false, $uploadPath = './upload/41'){
    $writer =  new PHPExcel();
    $writer -> setActiveSheetIndex(0);

    $writer_sheet = $writer-> getActiveSheet();
    $writerow = 1;
    if(is_array($excelHeader) && !empty($excelHeader)){
      foreach($excelHeader as $k => $v){
          $writer_sheet ->setCellValueByColumnAndRow($k, $writerow, $v);
      }
        $writerow++;
    }

    foreach($exceldata as $data){
        foreach($data as $k =>$v){
            $writer_sheet -> setCellValueByColumnAndRow($k, $writerow, $v);
        }
        $writerow++;
    }

    //假设,之后加个默认上传路径
    $filename = $filename.".xls" ;
    $filename = iconv("utf-8", "gb2312", $filename);
    if($local){

        $output = $uploadPath."/".$filename ;
        $PHPExcelWriter = PHPExcel_IOFactory::createWriter($writer, 'Excel5');
        $PHPExcelWriter -> save($output);
       /* header("Content-type: application/force-download");
        header("Content-Disposition: attachment; Filename=\"$filename\"");
        header("Content-Length: ".Filesize($output));
        @readFile($output);*/
    }else{
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $PHPExcelWriter = PHPExcel_IOFactory::createWriter($writer, 'Excel5');
        $PHPExcelWriter->save('php://output');
    }

}

//每一行写入excel
/**
 * @param null $writer
 * $writer =  new PHPExcel();
$writer -> setActiveSheetIndex(0);
 * @param array $data  一维数组
 * @param int $writer_row_count 写入的行数
 */
function  prepare_excel($writer = null , $data = array() , $writer_row_count = 0){
    if($writer == null){
        echo "ERROR : no start_excel ";
        exit();
    }

    $writer_sheet       = $writer -> getActiveSheet();

    if($writer_row_count == 0){
        //写表头，如不需要表头则传入参数从一开始
        # = = = = = = = = = = # writer # = = = = = = = = = =

        $writer_row_count   = 1;
        $writer_sheet -> setCellValueByColumnAndRow(0 , $writer_row_count, "年纪");
        $writer_sheet -> setCellValueByColumnAndRow(1 , $writer_row_count, "班级");
        $writer_sheet -> setCellValueByColumnAndRow(2 , $writer_row_count, "座位号");
        $writer_sheet -> setCellValueByColumnAndRow(3 , $writer_row_count, "姓名");
        $writer_sheet -> setCellValueByColumnAndRow(4 , $writer_row_count, "性別(男1,女2)");
        $writer_sheet -> setCellValueByColumnAndRow(5 , $writer_row_count, "学号");
        $writer_sheet -> setCellValueByColumnAndRow(6 , $writer_row_count, "图书馆借阅号");
        $writer_sheet -> setCellValueByColumnAndRow(7 , $writer_row_count, "账号");
        $writer_sheet -> setCellValueByColumnAndRow(8 , $writer_row_count, "密码");
        $writer_sheet -> setCellValueByColumnAndRow(9 , $writer_row_count, "身份证号");
        $writer_sheet -> setCellValueByColumnAndRow(10 , $writer_row_count, "讯息");
        $writer_sheet -> setCellValueByColumnAndRow(51 , $writer_row_count, "20021514"); //key 可以自定义唯一的excel（官方隐藏标志，可供上传检验）
        $writer_row_count ++;
    }else{
        $writer_row_count += 2 ;
    }

    
    $writer_sheet -> setCellValueByColumnAndRow(0 , $writer_row_count, $data["grade"]);
    $writer_sheet -> setCellValueByColumnAndRow(1 , $writer_row_count, $data["class"]);
    $writer_sheet -> setCellValueByColumnAndRow(2 , $writer_row_count, $data["number"]);
    $writer_sheet -> setCellValueByColumnAndRow(3 , $writer_row_count, $data["name"]);
    $writer_sheet -> setCellValueByColumnAndRow(4 , $writer_row_count, $data["sex"]);
    $writer_sheet -> setCellValueByColumnAndRow(5 , $writer_row_count, $data['school_number']);
    $writer_sheet -> setCellValueByColumnAndRow(6 , $writer_row_count, $data['library_card']);
    $writer_sheet -> setCellValueByColumnAndRow(7 , $writer_row_count, $data['account']);
    $writer_sheet -> setCellValueByColumnAndRow(8 , $writer_row_count, $data["password"]);
    $writer_sheet -> setCellValueByColumnAndRow(9 , $writer_row_count, $data["member_id_numbers"]);
    $writer_sheet -> setCellValueByColumnAndRow(10 , $writer_row_count, $data["message"]);
}

