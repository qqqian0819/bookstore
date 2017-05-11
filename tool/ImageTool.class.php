<?php
/*
先获得图片的大小类型等信息

水印：把指定的水印复制到目标上，并添加透明效果

缩略图：把大图片复制到小尺寸画布上

*/


class ImageTool{
	// 分析图片的信息。返回数组
    static function imageInfo($image){
		// 判断图片是否存在
		if(!file_exists($image)){
			return false;
		}
		//返回array。但是如果不能访问 $img 指定的图像或者其不是有效的图像，将返回 FALSE.
		$info=getimagesize($image);
		// var_dump(getimagesize('./01.php'))-----bool(false)
		if($info==false){
			return false;
		}
		// info[]
		$img['width']=$info[0];
		$img['height']=$info[1];
		// $img['ext']=image_type_to_mime_type($info[2]);//image/png 

	    /*int strripos ( $haystack, $needle)以不区分大小写的方式查找指定字符串在目标字符串中最后一次出现的位置。
		string substr ( string $string , int $start [, int $length ] ):
		如果没有提供 length，返回的子字符串将从 start 位置开始直到字符串结尾。
	    */
		$img['ext']=substr($info['mime'],strripos($info['mime'], '/')+1);//png
		return $img;
	}
	
	/*加水印
	$dst,等操作的图片
	$water,水印的小图片
	$save=NULLL,加水印后的图片保存位置  不填默认替换原图
	$alpha=50,
	$pos=2 水印位置
	*/
	public static function water($dst,$water,$save=NULL,$alpha=50,$pos=4){
		// 验证图片存在
		if(!file_exists($dst)||!file_exists($water)){
			return false;
		}
		// 获取信息
		$dinfo=self::imageInfo($dst);
		$winfo=self::imageInfo($water);
		// 验证水印图片比待操作图片小
		if($winfo['width']>$dinfo['width']||$winfo['height']>$dinfo['height']){
			return false;
		}
		// 两张图(类型可能不同)
		$dfunc='imagecreatefrom'.$dinfo['ext'];
		$wfunc='imagecreatefrom'.$winfo['ext'];
		if(!function_exists($dfunc)||!function_exists($wfunc)){
			return false;
		}
		// 动态加载函数来创建画布
		$dim=$dfunc($dst);//待操作画布
		$wim=$wfunc($water);//水印画布

		//根据水印的位置计算粘贴的坐标
		switch ($pos) {
			// 左上角
		 	case 0:
		 		$pos_x=0;
		 		$pos_y=0;
		 		break;
		 	//右上角 
		 	case 1:
		 		$pos_x=$dinfo['width']-$winfo['width'];
		 		$pos_y=0;
		 		break;
		 	//左下角 
		 	case 2:
		 		$pos_x=0;
		 		$pos_y=$dinfo['height']-$winfo['height'];
		 		break;
		 	// 正下方
		 	case 3:
		 		$pos_x=($dinfo['width']-$winfo['width'])/2;
		 		$pos_y=$dinfo['height']-$winfo['height'];
		 		break;
		 	//右下角 
		 	default:
		 		$pos_x=$dinfo['width']-$winfo['width'];
		 		$pos_y=$dinfo['height']-$winfo['height'];
		 		break;
		 } 

		// 加水印
		imagecopymerge($dim,$wim, $pos_x, $pos_y, 0, 0, $winfo['width'], $winfo['height'], $alpha);

		// 保存加完水印后的图片
		if(!$save){//若为传$save则默认替换原图保存
			$save=$dst;
			unlink($dst);//删掉原图
		}
		$createfunc='image'.$dinfo['ext'];
		$createfunc($dim,$save);

		// 销毁画布
		imagedestroy($dim);
		imagedestroy($wim);

		return true;
	}


	// 按比例生成缩略图两边留白
	public static function thumb($dst,$save=NULL,$width=200,$height=200){
		// 判断待处理的图片是否存在
		$dinfo=self::imageInfo($dst);
		if($dinfo==false){
			return false;
		}
		
		// 读原图当画布
		$dfunc='imagecreatefrom'.$dinfo['ext'];
		$dim=$dfunc($dst);
		// 创建缩略画布
		$tim=imagecreatetruecolor($width, $height);
		// 创建白色颜料以备填充缩略画布
		$white=imagecolorallocate($tim, 225, 225, 225);
		// 填充缩略画布
		imagefill($tim, 0, 0, $white);
		// 缩略
		$cale=min($width/$dinfo['width'],$height/$dinfo['height']);//计算缩放比例

		$dwidth=(int)$dinfo['width']*$cale;//缩略后的宽
		$dheight=(int)$dinfo['height']*$cale;//缩略后的高

		$paddingx=(int)($width-$dwidth)/2;//缩略图在画布上开始x
		$paddingy=(int)($height-$dheight)/2;//缩略图在画布上开始y
		// 复制缩略
		imagecopyresampled($tim,$dim,$paddingx,$paddingy, 0,0,$dwidth,$dheight, $dinfo['width'], $dinfo['height']);
		// 保存缩略后的
		if(!$save){//若未传$save则默认替换原图保存
			$save=$dst;
			unlink($dst);//删掉原图
		}

		$createfunc='image'.$dinfo['ext'];
		$createfunc($tim,$save);

		// 销毁画布
		imagedestroy($dim);
		imagedestroy($tim);

		return true;
	}
	//写验证码
    public static function captcha($width=50,$height=25) {
        //造画布
        $image = imagecreatetruecolor($width,$height) ;       
        //造背颜色
        $bgcolor= imagecolorallocate($image, 220, 200, 200);           
        //填充背景
        imagefill($image, 0, 0, $bgcolor);           
        //造随机字体颜色
        $randcolor = imagecolorallocate($image, mt_rand(0, 150), mt_rand(0, 150), mt_rand(0, 150)) ;
        //造随机线条颜色
        $linecolor1 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
        $linecolor2 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
        $linecolor3=imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
           
        //在画布上画线
        imageline($image, mt_rand(0, 50), mt_rand(0, 25), mt_rand(0, 50), mt_rand(0, 25), $linecolor1) ;
        imageline($image, mt_rand(0, 50), mt_rand(0, 20), mt_rand(0, 50), mt_rand(0, 20), $linecolor2) ;
        imageline($image, mt_rand(0, 50), mt_rand(0, 20), mt_rand(0, 50), mt_rand(0, 20), $linecolor3) ;
           
        //在画布上写字
        $text = substr(str_shuffle('ABCDEFGHIJKMNPRSTUVWXYZabcdefghijkmnprstuvwxyz23456789'),0,4) ;

        $_SESSION['captcha']=strtolower($text);
        // imagestring ( $image , $font , $x , $y , $string ,  $color )
        imagestring($image, 5, 7, 5, $text, $randcolor) ;
           
        //显示、销毁
        header('content-type: image/jpeg');
        imagejpeg($image);
        imagedestroy($image);
    }

}
