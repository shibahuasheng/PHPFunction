<?php
session_start ();
header ( "Content-type: text/html; charset=utf-8" );
class checkCode {
	private $codes = '';
	function __construct() {
		$code = '0-1-2-3-4-5-6-7-8-9-A-B-C-D-E-F-G-H-I-J-K-L-M-N-O-P-Q-R-S-T-U-V-W-X-Y-Z-a-b-c-d-e-f-g
		-h-i-j-k-l-m-o-p-s-t-u-v-w-x-y-z';
		// $code='我-们-都-素-阿-红-的-建-安-大-断-奶-多-久-啊-打-交-道-假-按-揭-看-代-码-的-卡-等-级-哦-老-大-看-懂-啊-打-开-大-马-来-打-开-大-怒-江-开-发-骂-客-服-法-减-肥-啊-风-科-技-啊-打-开-门-哪-来-的-啊-奋-斗-打-的-大-门-口-大-家-看';
		$codeArray = explode ( '-', $code );
		shuffle ( $codeArray ); // 将数组随机排列
		$this->codes = implode ( '', array_slice ( $codeArray, 0, 4 ) ); // 从数组中取出4位。0号开始，取4个
	}
	public function createImg() {
		$_SESSION ['check_pic'] = $this->codes;
		$img = imagecreate ( 70, 25 );
		imagecolorallocate ( $img, 222, 222, 222 );
		$testcolor1 = imagecolorallocate ( $img, 255, 0, 0 );
		$testcolor2 = imagecolorallocate ( $img, 51, 51, 51 );
		$testcolor3 = imagecolorallocate ( $img, 0, 0, 255 );
		$testcolor4 = imagecolorallocate ( $img, 255, 0, 255 );
		for($i = 0; $i < 4; $i ++) {
			imagestring ( $img, rand ( 5, 6 ), 8 + $i * 15, rand ( 2, 8 ), $this->codes [$i], rand ( 1, 4 ) );
		}
		Header ( "Content-type: image/gif" );
		imagegif ( $img );
	}
}
$code = new checkCode ();
$code->createImg ();
$code = NULL;
?> 