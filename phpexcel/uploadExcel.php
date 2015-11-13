<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/ajaxfileupload.js"></script>

</head>
<body>
<script>
    jQuery(function(){
        $("#buttonUpload").click(function(){
            //加载图标
            /* $("#loading").ajaxStart(function(){
             $(this).show();
             }).ajaxComplete(function(){
             $(this).hide();
             });*/
            //上传文件
            $.ajaxFileUpload({
                url: 'excelOperate.php',//处理脚本
                secureuri: false,
                fileElementId: 'fileToUpload',//file控件id
                dataType: 'json',
                success: function (data, status) {
                    if (typeof(data.error) != 'undefined') {
                        if (data.error != '') {
                            alert(data.error);
                            if(data.responsefile){
                                window.location.href = data.responsefile;
                            }
                        } else {
                            alert(data.message);
                            //window.location.href = data.responsefile;
                        }
                    }
                },
                error: function (data, status, e) {
                    alert(e);
                }
            });
            return false;
        })
    })
</script>
<!--jqueryUpload 适合上传成功和失败的文件（已经在服务器上的，然后window.location.href 重定向下载）-->
<div id="upload">
    <input id="fileToUpload" type="file" size="20" name="fileToUpload" class="input">
    <button id="buttonUpload">上传</button>
</div>
<!--form 则可以提交给隐藏的iframe，传递数据流下载（也就是不保存excel文件直接向浏览器返回excel数据流）-->
<div>
    <form method="post" action="excelOperate.php" enctype="multipart/form-data">
        <h3>导入Excel表：</h3><input  type="file" name="fileToUpload" />
        <input type="submit"  value="导入" />
    </form>
  <!-- target="excelDownload" <iframe src="" name="excelDownload" frameborder="0" ></iframe>-->
</div>

</body>
</html>