<?php


defined('ACC')||exit('Acc Denied');
class PageTool{
	protected $total=0;
	protected $perpage=10;
	protected $page=1;
	public function __construct($total,$page=false,$perpage=false){
		$this->total=$total;
		if($perpage){
			$this->perpage=$perpage;
		}
		if($page){
			$this->page=$page;
		}
	}
	// 创建分页导航
	public function show(){
		// 总页数
		$cnt=ceil($this->total/$this->perpage);
		$uri=$_SERVER['REQUEST_URI'];
		$parse=parse_url($uri);
		$param=array();
		// 如果传参了
		if(isset($parse['query'])){
			// parse_str(string规定要解析的字符串。,array规定存储变量的数组的名称)
			parse_str($parse['query'],$param);
		}
		// print_r($param);
		// 去掉地址串中的page
		unset($param['page']);//去掉数组中的page

 		$url=$parse['path'].'?';
 		// 不止传参page
 		if(!empty($param)){
			//http_build_query — 生成 URL-encode 之后的请求字符串 数组-》字符串
			$param=http_build_query($param);
			// 去掉page的地址
			$url=$url.$param.'&';
	    }
	    // echo $url;
		// 计算页码导航
		$nav=array();
		$nav[0]= '<span>' . $this->page . '</span>';
		// 循环当(最左边页码大于等于1或者右边页码小于等于最大)并且已有5个显示页码
		for($left=$this->page-1,$right=$this->page+1;($left>=1||$right<=$cnt)&&count($nav)<=5;){
			if($left>=1){
				//在数组开头插入一个或多个单元
				array_unshift($nav,'<a href="' . $url . 'page=' . $left . '">[' . $left . ']</a>');
				$left-=1;
			}
			if($right<=$cnt){
				//将一个或多个单元压入数组的末尾（入栈）
				array_push($nav,'<a href="' . $url . 'page=' . $right . '">[' . $right . ']</a>');		
				$right+=1;
			}
		}
		return implode('',$nav);
	}
}

/*$page = $_GET['page']?$_GET['page']:1;
// new pagetool(总条数,当前页,每页条数);
$p=new PageTool(20,$page,4);
echo $p->show();*/