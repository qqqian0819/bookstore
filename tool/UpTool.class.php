<?php
/*文件上传类：
上传文件。配置允许的后缀。配置允许的大小。良好的报错支持。随机生成文件名。
*/

defined('ACC')||exit('ACC Denied');

class UpTool{
    protected $allowExt='jpg,jpeg,bmg,png';
    protected $maxSize=1;// M为单位
    /*protected $file=NULL;//储存上传文件的信息

    // 获取文件上传的信息
    protected getFile($key){
        return $this->file=$_FILES[$key];
    }*/

    protected $errno = 0; // 错误代码
    protected $error = array(
        0=>'无错',
        1=>'上传文件超出系统限制',
        2=>'上传文件大小超出网页表单页面',
        3=>'文件只有部分被上传',
        4=>'没有文件被上传',
        6=>'找不到临时文件夹',
        7=>'文件写入失败',
        8=>'不允许的文件后缀',
        9=>'文件大小超出的类的允许范围',
        10=>'创建目录失败',
        11=>'移动失败'
            
    );

    // 上传文件
    public function up($key){
        
        // 防止传入错误的key
        if(!isset($_FILES[$key])){
            return false;
        }
        $f=$_FILES[$key];
        if($f['error']){
            $this->error=$f['error'];
            return false;
        }
        // 获取后缀
        $ext=$this->getExt($f['name']);
        // 检查后缀
        if(!$this->isAllowExt($ext)){
            $this->errno = 8;
            return false;
        }
        // 检查大小
        if(!$this->isAllowSize($f['size'])){
            $this->error=9;
            return false;
        }
        // 创建目录
        if(!$dir=$this->mk_dir()){
            $this->error = 10;
            return false;
        }
        //生成随机名称
        $movname=$this->randName().'.'.$ext;
        if(!$movname){
            return false;
        }
        // 移动目录
        $path=$dir.'/'.$movname;
        if(!move_uploaded_file($f['tmp_name'], $path)){
            $this->errno = 11;
            return false;
        }
        //str_replace(root,'').将D/www/**换成空字符串。绝对路径-->相对路径
        return str_replace(ROOT,'',$dir.'/'.$movname);
}


    // 获取错误
    public function getError(){
        return $this->error[$this->errno];
    }

    // 开放protected的后缀和最大大小。动态配置。
    public function setExt($exts){
        $this->allowExt=$exts;
    }
    public function setSzie($num){
        $this->maxSize=$num;
    }


    // 读取后缀
    protected function getExt($file){
        $tmp=explode('.', $file);
        // 文件一般最后都是.类型名
        return end($tmp);
    }
    // 判断后缀 .返回bool
    protected function isAllowExt($ext){
        // strtolower 全转化为小写
        $arr=explode(',',strtolower($this->allowExt));
        return  in_array(strtolower($ext), $arr);
    }
    // 判断大小。返回bool
    protected function isAllowSize($size){
        // 传过来的size是以Byte为单位
        return $size<=$this->maxSize*1024*1024;// return bool
        
    }
    // 创建目录--年月/日/
    protected function mk_dir(){
        $dir=ROOT.'data/images/'.date('Ymd/hi',time());
        // ||的短路特性。一旦前面面的为否，则不执行或者后面的
        // mkdir(path,mode(必需。规定权限。默认是 0777),recursive(必需。规定是否设置递归模式))
        if (is_dir($dir)||mkdir($dir,0777,true)) {//如果已经是目录，则返回。如果不是，则创建。
            return $dir;
        }else{
            return false;
        }
    }
    // 创建随机6位文件名称
    protected function randName($length=6){
        $str='abcdefghigkmnpqrstuvwxwy23456789';
        // str_shuffle  随机打乱字符串
        return substr(str_shuffle($str), 0,$length);
    }

}