<?php
$numrow= 150;
$psize = 10;        //单页笔数,预设全部
$pnos  =0;              //分页笔数
$pinx  =1;              //目前分页索引,预设1
$sinx  =0;              //值域起始值
$einx  =0;              //值域终止值
if(isset($_GET['psize'])){
    $psize=(int)$_GET['psize'];
    if($psize===0){
        $psize=10;
    }
}
if(isset($_GET['pinx'])){
    $pinx=(int)$_GET['pinx'];
    if($pinx===0){
        $pinx=1;
    }
}

$pnos  =ceil($numrow/$psize);
$pinx  =($pinx>$pnos)?$pnos:$pinx;
$sinx  =(($pinx-1)*$psize)+1;
$einx  =(($pinx)*$psize);
$einx  =($einx>$numrow)?$numrow:$einx;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script>
    window.onload=function(){
    //套表格列奇偶色
    //分页列
        //分页列
        var cid         ="page";                        //容器id
        var numrow      =<?php echo (int)$numrow;?>;    //资料总笔数
        var psize       =<?php echo (int)$psize ;?>;    //单页笔数,预设10笔
        var pnos        =<?php echo (int)$pnos  ;?>;    //分页笔数
        var pinx        =<?php echo (int)$pinx  ;?>;    //目前分页索引,预设1
        var sinx        =<?php echo (int)$sinx  ;?>;    //值域起始值
        var einx        =<?php echo (int)$einx  ;?>;    //值域终止值
        var list_size   =5;                             //分页列显示笔数,5
        var url_args    ={};                            //连结资讯
    url_args={
    'pinx_name' :'pinx',
    'psize_name':'psize',
    'page_name' :'index.php',
    'page_args' :{}
    }
    var opage=pages(cid,numrow,psize,pnos,pinx,sinx,einx,list_size,url_args);

    }

    </script>
</head>
<body>
<span id="page"></span>
<span style="position:relative;top:0px;" class="fc_brown0">到<input id="page_val" type="text" value="" size="10" maxlength="20" class="form_text" style="width:30px">页<input type="button" value="GO" class="ibtn_gr3020" onclick="page_go();void(0);" onmouseover="this.style.cursor='pointer'" style="cursor: pointer;">
</span>
</body>
<script>
    //-------------------------------------------------------
    //inc
    //-------------------------------------------------------
    //root  根單元
    //
    //-------------------------------------------------------
    //root  根單元
    //-------------------------------------------------------
    //  root/pages()        分頁列
    //  root/qform()        查詢表單列
    //  root/logo()         首頁logo
    //
    //-------------------------------------------------------



    //-------------------------------------------------------
    //root  根單元
    //-------------------------------------------------------

    //---------------------------------------------------
    //分頁列
    //---------------------------------------------------

    function pages(cid,numrow,psize,pnos,pinx,sinx,einx,list_size,url_args){
        //---------------------------------------------------
        //分頁列
        //---------------------------------------------------
        //參數
        //---------------------------------------------------
        //cid           容器id
        //numrow        資料總筆數
        //psize         單頁筆數
        //pnos          分頁筆數
        //pinx          目前所在頁
        //sinx          目前所在頁,值域起始值
        //einx          目前所在頁,值域終止值
        //list_size     分頁列顯示筆數
        //url_args      連結資訊
        //---------------------------------------------------
        //回傳值
        //---------------------------------------------------
        //本函式會傳回容器物件,你可以透過 容器物件.tbl 取得
        //分頁列表格 物件.
        //---------------------------------------------------

        //分頁列區段
        var arry_list=[];   //分頁列資料陣列
        var s_sinx   =0;    //分頁列區段,值域起始值
        var s_einx   =0;    //分頁列區段,值域終止值
        var s_sinx   =(get_seinx()).s_sinx;
        var s_einx   =(get_seinx()).s_einx;

        //連結資訊
        var pinx_name =url_args.pinx_name;
        var psize_name=url_args.psize_name;
        var page_name =url_args.page_name;
        var page_args =parse_page_args(url_args.page_args);

        //容器
        var opage=document.getElementById(cid);
        opage.className="page_container";

        //表格
        var otbl =document.createElement("TABLE");
        otbl.className="page_tbl";

        //列
        var otr  =otbl.insertRow(-1);
        otr.className="page_tr";

        //資訊欄位
        var otd_info=otr.insertCell(-1);
        otd_info.className="page_info";
        //otd_info.innerHTML="第"+sinx+"筆~第"+einx+"筆"+":"+"共"+numrow+"筆";
        otd_info.innerHTML="共"+pnos+"頁";

        //第一頁
        if(s_sinx!=1){
            var otd_first=otr.insertCell(-1);
            otd_first.className="page_first";
            otd_first.innerHTML="第一頁";
            otd_first.cls="page_first";

            var _pinx =1;
            var _psize=psize;
            var _url  ="";

            if(page_args!=""){
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize+"&"
                _url+=page_args
            }else{
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize
            }

            otd_first._pinx =_pinx;
            otd_first._psize=_psize;
            otd_first._url  =_url;

            otd_first.onmouseover=function(){
                this.className="page_hover";
                this.style.cursor="pointer";
            }
            otd_first.onmouseout=function(){
                this.className=this.cls;
                this.style.cursor="";
            }
            otd_first.onclick=function(){
                var _pinx =this._pinx ;
                var _psize=this._psize;
                var _url  =this._url  ;
                self.location.href=_url;
            }
        }
        //上一頁
        if(s_sinx>1){
            var otd_prev=otr.insertCell(-1);
            otd_prev.className="page_prev";
            otd_prev.innerHTML="<<";
            otd_prev.cls="page_prev";

            var _pinx =s_sinx-1;
            var _psize=psize;
            var _url  ="";

            if(page_args!=""){
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize+"&"
                _url+=page_args
            }else{
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize
            }

            otd_prev._pinx =_pinx;
            otd_prev._psize=_psize;
            otd_prev._url  =_url;

            otd_prev.onmouseover=function(){
                this.className="page_hover";
                this.style.cursor="pointer";
            }
            otd_prev.onmouseout=function(){
                this.className=this.cls;
                this.style.cursor="";
            }
            otd_prev.onclick=function(){
                var _pinx =this._pinx ;
                var _psize=this._psize;
                var _url  =this._url  ;
                self.location.href=_url;
            }
        }
        //一般|現在
        for(;s_sinx<=s_einx;s_sinx++){
            if(pinx==s_sinx){
                //現在
                var otd_current=otr.insertCell(-1);
                otd_current.className="page_current";
                otd_current.innerHTML=s_sinx;
                otd_current.cls="page_current";

                var _pinx =s_sinx;
                var _psize=psize;
                var _url  ="";

                if(page_args!=""){
                    _url+=page_name +"?"
                    _url+=pinx_name +"="+_pinx+"&"
                    _url+=psize_name+"="+_psize+"&"
                    _url+=page_args
                }else{
                    _url+=page_name +"?"
                    _url+=pinx_name +"="+_pinx+"&"
                    _url+=psize_name+"="+_psize
                }

                otd_current._pinx =_pinx;
                otd_current._psize=_psize;
                otd_current._url  =_url;

                otd_current.onmouseover=function(){
                    this.style.cursor="pointer";
                }
                otd_current.onmouseout=function(){
                    this.className=this.cls;
                    this.style.cursor="";
                }
                otd_current.onclick=function(){
                    var _pinx =this._pinx ;
                    var _psize=this._psize;
                    var _url  =this._url  ;
                    self.location.href=_url;
                }
            }else{
                //一般
                var otd_normal=otr.insertCell(-1);
                otd_normal.className="page_normal";
                otd_normal.innerHTML=s_sinx;
                otd_normal.cls="page_normal";

                var _pinx =s_sinx;
                var _psize=psize;
                var _url  ="";

                if(page_args!=""){
                    _url+=page_name +"?"
                    _url+=pinx_name +"="+_pinx+"&"
                    _url+=psize_name+"="+_psize+"&"
                    _url+=page_args
                }else{
                    _url+=page_name +"?"
                    _url+=pinx_name +"="+_pinx+"&"
                    _url+=psize_name+"="+_psize
                }

                otd_normal._pinx =_pinx;
                otd_normal._psize=_psize;
                otd_normal._url  =_url;

                otd_normal.onmouseover=function(){
                    this.className="page_hover";
                    this.style.cursor="pointer";
                }
                otd_normal.onmouseout=function(){
                    this.className=this.cls;
                    this.style.cursor="";
                }
                otd_normal.onclick=function(){
                    var _pinx =this._pinx ;
                    var _psize=this._psize;
                    var _url  =this._url  ;
                    self.location.href=_url;
                }
            }
        }
        //下一頁
        if(s_einx<pnos){
            var otd_next=otr.insertCell(-1);
            otd_next.className="page_next";
            otd_next.innerHTML=">>";
            otd_next.cls="page_next";

            var _pinx =s_einx+1;
            var _psize=psize;
            var _url  ="";

            if(page_args!=""){
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize+"&"
                _url+=page_args
            }else{
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize
            }

            otd_next._pinx =_pinx;
            otd_next._psize=_psize;
            otd_next._url  =_url;

            otd_next.onmouseover=function(){
                this.className="page_hover";
                this.style.cursor="pointer";
            }
            otd_next.onmouseout=function(){
                this.className=this.cls;
                this.style.cursor="";
            }
            otd_next.onclick=function(){
                var _pinx =this._pinx ;
                var _psize=this._psize;
                var _url  =this._url  ;
                self.location.href=_url;
            }
        }
        //最末頁
        if(s_einx<pnos){
            var otd_last=otr.insertCell(-1);
            otd_last.className="page_last";
            otd_last.innerHTML="最末頁";
            otd_last.cls="page_last";

            var _pinx =pnos;
            var _psize=psize;
            var _url  ="";

            if(page_args!=""){
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize+"&"
                _url+=page_args
            }else{
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize
            }

            otd_last._pinx =_pinx;
            otd_last._psize=_psize;
            otd_last._url  =_url;

            otd_last.onmouseover=function(){
                this.className="page_hover";
                this.style.cursor="pointer";
            }
            otd_last.onmouseout=function(){
                this.className=this.cls;
                this.style.cursor="";
            }
            otd_last.onclick=function(){
                var _pinx =this._pinx ;
                var _psize=this._psize;
                var _url  =this._url  ;
                self.location.href=_url;
            }
        }

        opage.appendChild(otbl);
        opage.tbl=otbl;

        return opage;

        function get_seinx(){
            //-----------------------------------------------
            //分頁列區段,值域起始值,值域終止值
            //-----------------------------------------------

            arry_list=array_range(1,pnos);
            arry_list=array_chunk(arry_list,list_size);

            for(var i=0;i<arry_list.length;i++){
                if(in_array(pinx,arry_list[i])){
                    var list=arry_list[i];
                    s_sinx=list[0];
                    s_einx=list[list.length-1];
                    break;
                }
            }

            return {
                's_sinx':s_sinx,
                's_einx':s_einx
            };
        }

        function array_range(s,e,step){
            //-----------------------------------------------
            //值域數值陣列
            //-----------------------------------------------
            //s     起始值
            //e     終止值
            //step  遞增數,預設1,可以指定負整數
            //-----------------------------------------------

            if(!step){
                step=1;
            }else{
                step=parseInt(step);
            }

            var arry=[];
            while(s<=e){
                arry.push(s);
                s=s+step;
            }

            return arry;
        }

        function array_chunk(arry,size){
            //-----------------------------------------------
            //依長度分割陣列
            //-----------------------------------------------
            //arry  陣列
            //size  長度,預設1
            //-----------------------------------------------

            //參數檢驗
            if(!arry){
                return [];
            }
            if(!size){
                size=1;
            }else{
                size=parseInt(size);
            }

            //處理
            var len     =arry.length;
            var pnos    =Math.ceil(len/size);
            var results =[];

            var inx=0;
            for(var i=1;i<=pnos;i++){
                var result=[];
                for(var j=0;j<size;j++){
                    var val=arry[inx];
                    if(val){
                        result[j]=val;
                    }
                    inx++;
                }
                //alert(result);
                results.push(result);
            }

            //alert(results.length);

            //回傳
            return results;
        }

        function in_array(val,array){
            //-----------------------------------------------
            //檢驗元素是否在陣列裡
            //-----------------------------------------------
            //val   值
            //array 陣列
            //-----------------------------------------------

            flag=false;
            for(var i=0;i<array.length;i++){
                if(val==array[i]){
                    flag=true;
                    break;
                }
            }

            //回傳
            return flag;
        }

        function parse_page_args(arry){
            //-----------------------------------------------
            //處理額外參數
            //-----------------------------------------------

            var tmp=[];
            for(var key in arry){
                var val=trim(arry[key]);
                tmp.push(key+'='+encodeURI(val));
            }

            return tmp.join('&');

            function trim(str){
                //去除字串前後空白

                str=str.toString();
                str=str.replace(/^\s+/,'');
                str=str.replace(/\s+$/,'');
                return str;
            }
        }
    }

    function page_go(){
        //页数指定跳转

        var opage_val=document.getElementById('page_val');
        var page_val=parseInt(opage_val.value);
        var numrow=<?php echo (int)$numrow;?>;

        if(isNaN(page_val)){
            alert('请输入页数 !');
            return false;
        }

        if((page_val<=0)||(page_val>numrow)){
            alert('页数错误，请重新输入 !');
            opage_val.value='';
            return false;
        }

        var url ='';
        var page='index.php';
        var arg ={
            'psize':<?php echo (int)$psize?>,
            'pinx' :page_val
        };
        var _arg=[];
        for(var key in arg){
            _arg.push(key+"="+encodeURI(arg[key]));
        }
        arg=_arg.join("&");

        if(arg.length!=0){
            url+=page+"?"+arg;
        }else{
            url+=page;
        }
        /*
         $.blockUI({
         message:'<h2 class="fc_white0">处理中，请稍後 !</h2>',
         css: {
         border: 'none',
         padding: '15px',
         backgroundColor: '#000',
         '-webkit-border-radius': '10px',
         '-moz-border-radius': '10px',
         opacity:.8,
         color: '#437C85'
         }
         });
*/
        /* go(url,'self');*/

       window.location.href=url;
    }
   /* function go(url, target) {
        debugger;
        if ((url == undefined) || (url == '')) {
            return false;
        }
        if ((target == undefined) || (target == '')) {
            target = 'self';
        }
        var estr = target + '.location.href="' + url + '"';
        eval(estr);
    }*/
    //---------------------------------------------------

</script>
</html>