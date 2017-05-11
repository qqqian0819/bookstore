<?php

/*稍复杂验证码*/
header('content-type:image/png');
// 画布
$im=imagecreatetruecolor(60,25);
// 颜料
$red=imagecolorallocate($im,225,0,0);
$gr=imagecolorallocate($im,220,200,200);
// 随机字体颜色
$randcolor=imagecolorallocate($im,mt_rand(0,150), mt_rand(0,150), mt_rand(0,150));
// 随机斜线yanse
$linecolor1=imagecolorallocate($im,mt_rand(100,150), mt_rand(100,150), mt_rand(100,150));

// 背景填充
imagefill($im,0,0,$gr);
// 写字--水平地画一行字符串
$str='ABCDEFGHJKMNPQRSTabcdefghijkmnpqrstuvwxyz23456789';
// imagestring ( $image , $font , $x , $y , $string ,  $color )
$code=substr(str_shuffle($str),0,4);
session_start();
$_SESSION["var_code"] = strtolower($code); 

imagestring($im,7,4,2,$code,$randcolor);
// 划线
imageline($im, 0,mt_rand(0,25), 60,mt_rand(0,25) ,$linecolor1);
// 输出
imagepng($im);
// 销毁
imagedestroy($im);