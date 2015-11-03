<?php
/**
 * 验证码类
 * 注:需要GD库支持
 */
Session_start();
Class Coder{
    Private $config;
    Private $im;
    Private $str;

    Function __construct(){
        $this->config['width']=50;
        $this->config['height']=20;
        $this->config['boxline']=False;
        $this->config['codname']="coder";
        $this->config['type']="int";
        $this->config['length']=4;
        $this->config['color']=array(246,246,246);
        $this->config['interfere']=3;

        $this->str['default']="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $this->str['string']="abcdefghijklmnopqrstuvwxyz";
        $this->str['int']="0123456789";
    }

    //配置
    Public Function init($config=array()){
        IF(!empty($config) && is_array($config)){
            Foreach($config as $key=>$value){
                $this->config[$key]=$value;
            }
        }
    }

    //输出验证码
    Public Function create(){
        IF(!Function_exists("Imagecreate")){
            Return False;
        }
        $this->create_do();
    }

    //创建
    Private Function create_do(){
        $this->im=Imagecreate($this->config['width'],$this->config['height']);
        //设置背景为白色
        Imagecolorallocate($this->im,$this->config['color'][0],$this->config['color'][1],$this->config['color'][2]);

        //为此背景加个边框
        IF($this->config['boxline']==True){
            $bordercolor=Imagecolorallocate($this->im,37,37,37);
            Imagerectangle($this->im,0,0,$this->config['width']-1,$this->config['height']-1,$bordercolor);
        }

        //生成验证码
        $this->create_str();
        $coder=$_SESSION[$this->config['codname']];

        //输入文字
        $fontcolor=Imagecolorallocate($this->im,46,46,46);
        For($i=0;$i<$this->config['length'];$i++){
            Imagestring($this->im,5,$i*10+6,rand(2,5),$coder[$i],$fontcolor);
        }

        //加入干扰线
        $interfere=$this->config['interfere'];
        $interfere=$interfere>30?"30":$interfere;
        IF(!empty($interfere) && $interfere>1){
            For($i=1;$i<$interfere;$i++){
                $linecolor=Imagecolorallocate($this->im,rand(0,255),rand(0,255),rand(0,255));
                $x=rand(1,$this->config['width']);
                $y=rand(1,$this->config['height']);
                $x2=rand($x-10,$x+10);
                $y2=rand($y-10,$y+10);
                Imageline($this->im,$x,$y,$x2,$y2,$linecolor);
            }
        }

        Header("Pragma:no-cache\r\n");
        Header("Cache-Control:no-cache\r\n");
        Header("Expires:0\r\n");
        Header("Content-type:Image/jpeg\r\n");
        Imagejpeg($this->im);
        Imagedestroy($this->im);
        Exit;
    }

    //得到验证码
    Private Function create_str(){
        IF($this->config['type']=="int"){
            For($i=1;$i<=$this->config['length'];$i++){
                $coder.=Rand(0,9);
            }
            $_SESSION[$this->config['codname']]=$coder;
            Return True;
        }
        $len=strlen($this->str[$this->config['type']]);
        IF(!$len){
            $this->config['type']="default";
            $this->create_str();
        }
        For($i=1;$i<=$this->config['length'];$i++){
            $offset=rand(0,$len-1);
            $coder.=substr($this->str[$this->config['type']],$offset,1);
        }
        $_SESSION[$this->config['codname']]=$coder;
        Return True;
    }
}

/**
 * 默认验证码SESSION为:$_SESSION['coder'];
 * 注意在给变量符值时不要把变量的名子和SESSION冲突
 * 注:在验证时不分大小写
 */
$v = New Coder;
//$config['width'] = 50;				//验证码宽
//$config['height'] = 20;				//验证码高
//$config['boxline'] = True;			//是否有边框线
//$config['codname'] = "coder";			//检查验证码时用的SESSION
//$config['type'] =	"default";			//验证码展示的类型default:大写字母,string:小写字母,int:数字
//$config['length'] = 4;				//验证码长度
//$config['color']=array(246,246,246);	//背景色,RGB颜色值
//$config['interfere']= 0;				//干扰线强度,范围为1-30,0或空为不起用干扰线
//$v->init($config);					//将配置信息写入
$v->create();
?>