<?php
if (!defined('IN_CB')) { die('You are not allowed to access to this page.'); }
?>

            <div class="output">
                <section class="output">
                    <h3>Output</h3>
                    <?php
                        $finalRequest = '';
                        foreach (getImageKeys() as $key => $value) {
                            $finalRequest .= '&' . $key . '=' . urlencode($value);
                        }
                        if (strlen($finalRequest) > 0) {
                            $finalRequest[0] = '?';
                        }
                    ?>
                    <div id="imageOutput">
                        <?php if ($imageKeys['text'] !== '') { ?><img src="image.php<?php echo $finalRequest; ?>" alt="Barcode Image" /><?php }
                        else { ?>Fill the form to generate a barcode.<?php } ?>
                    </div>
                </section>
            </div>
        </form>
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
                url: '../excel/excelTo.php',//处理脚本
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
                              window.location.href = data.responsefile;
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

        <div class="footer">
            <footer>
            <h1>批量excel导入:</h1>
                <!--<form action="../excel/excelTo.php" method="post" enctype="multipart/form-data">
                    <input type="file" id="file" name="fileToUpload" style="display:none">
                    <input type="button" onclick="file.click()" value="选择附件">
                    <input type="submit" value="提交" />
                </form>
                <br />-->
                <h2>请在excel第一列输入数据</h2>
               <input id="fileToUpload" type="file" size="20" name="fileToUpload" class="input">
                <button id="buttonUpload">上传</button>
            </footer>
        </div>
    </body>
</html>