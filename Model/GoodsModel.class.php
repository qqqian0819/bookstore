<?php

defined('ACC')||exit('ACC Denied');

class GoodsModel extends Model{
	protected $table='tb_book';
	// primary key
	protected $pk='book_id';
	// 过滤的数组
	protected $fields = array('book_id','book_sn','cat_id','book_name','author','book_publish','pub_date','shop_price','market_price','book_number','book_brief','book_desc','thumb_img','book_img','ori_img','is_delete','is_best','is_hot','add_time');
	// 过滤的数组
    protected $_auto = array(
                            array('is_hot','value',0),
                            array('is_best','value',0),
                            array('add_time','function','time'),
                            array('pub_date','function','time')
                            );
    // 验证的数组
    protected $_valid = array(
                            // array('book_name',1,'必须有书籍名称','require'),
                            // array('cat_id',1,'栏目id必须是整型值','number'),
                            array('book_brief',2,'书籍简介要在4到20字符','length','4,20')
    );

    /*商品不直接删除,而是标记删除或逻辑删除：
    1:破坏了数据的完整性。"脏数据
    2:影响查询的速度(对于大型网站,尤为严重)。
    */
	// 特殊的删除.trash:回收站
	public function trash($data,$id){
        $this->db->autoExecute($this->table,$data,'update',' where book_id=' . $id);
        return $this->db->affected_rows();
	}

	// 商品恢复
	public function reTrash($data,$id){
		$this->db->autoExecute($this->table,$data,'update',' where book_id=' . $id);
        return $this->db->affected_rows();
	}
	// 还未“删除”的所有商品
	public function getGoods($offset=0,$limit=5){
		$sql='select * from ' . $this->table . ' where is_delete=0 limit '. $offset . ',' . $limit;
		return $this->db->getAll($sql);
	}
	// 已被“删除”的商品
	public function getTrash(){
		$sql="SELECT  * FROM " . $this->table . ' where is_delete=1 ';
		return $this->db->getAll($sql);
	}
    // 生成商品货号
    public function createSn() {
        $sn = 'BK' . date('Ymd') . mt_rand(10000,99999);

        $sql = 'select count(*) from ' . $this->table . " where book_sn='" . $sn . "'";
        // 如果已经存在的商品号则递归创建唯一的商品号
        return $this->db->getOne($sql)?$this->createSn():$sn;
    }
    // 取出指定条数的畅销品默认取4个
    public function getHot($n=4){
        $sql='select book_id,book_name,shop_price,market_price,thumb_img,book_img from '.$this->table.' where is_hot = 1 order by add_time limit '.$n;
        return $this->db->getAll($sql);
    }
    // 取出指定条数的畅销品默认取4个
    public function getBest($n=4){
        $sql='select book_id,book_name,shop_price,market_price,thumb_img,book_img from '.$this->table.' where is_best = 1 order by add_time limit '.$n;
        return $this->db->getAll($sql);
    }
    // 取出指定条数的所有物品默认取12个
    public function getALl($n=12){
        $sql='select book_id,book_name,shop_price,market_price,thumb_img,book_img from '.$this->table.' order by add_time limit '.$n;
        return $this->db->getAll($sql);
    }
    // 取出指定栏目所有的商品
    public function catGoods($cat_id,$offset=0,$limit=5){
        $category=new CatModel();
        //取出所有栏目来
        $cats=$category->select();
        // 取出给定栏目的子孙栏目
        $sons=$category->getCatTree($cats,$cat_id);

        $sub=array($cat_id);
        //有子孙栏目
        if(!empty($sons)){
            foreach($sons as $v){
                $sub[]=$v['cat_id'];
            }
        }
        $in=implode(',',$sub);
        $sql='select * from '.$this->table.' where cat_id in ('. $in .') order by add_time limit '. $offset . ',' . $limit;
        return $this->db->getAll($sql);
    }

    public function catGoodsCount($cat_id) {
        $category = new CatModel();
        $cats = $category->select(); // 取出所有的栏目来
        $sons = $category->getCatTree($cats,$cat_id);  // 取出给定栏目的子孙栏目
        
        $sub = array($cat_id);

        if(!empty($sons)) { // 没有子孙栏目
            foreach($sons as $v) {
                $sub[] = $v['cat_id'];
            }
        }

        $in = implode(',',$sub);

        $sql = 'select count(*) from ' . $this->table . ' where cat_id in (' . $in . ')';
        return $this->db->getOne($sql);
    }
    // 获取购物车中商品详细信息
    public function getCartGoods($items){
        // id 当键
        foreach($items as $k=>$v) {  // 循环购物车中的商品,每循环一个,到数据查一下对应的详细信息

            $sql = 'select book_id,book_name,thumb_img,shop_price,market_price,author from ' . $this->table . ' where book_id =' . $k;

            $row = $this->db->getRow($sql);

            $items[$k]['thumb_img'] = $row['thumb_img'];
            $items[$k]['market_price'] = $row['market_price'];
            $items[$k]['author'] = $row['author'];
        
        }

        return $items;
       
    }
    // 修改信息
    public function update($data,$id) {
        $this->db->autoExecute($this->table,$data,'update',' where book_id=' . $id);
        return $this->db->affected_rows();
    }
    // 取出指定条件下所有书籍
    public function special($str,$offset=0,$limit=5){
        $sql='select book_id,book_name,book_sn,shop_price,market_price, author, pub_date, book_publish,is_best,is_hot,book_number,thumb_img from '.$this->table.' where '. $str .' order by add_time limit '. $offset . ',' . $limit;
        return $this->db->getAll($sql);
    }
    // 作者
    public function auList(){
        $sql="select distinct author from " .$this->table;
        return $this->db->getAll($sql);
    }
    // 按指定书序取出书籍
    public function speSort($str,$offset=0,$limit=5){
        $sql="select book_id,book_name,book_sn,shop_price,market_price, author, pub_date, book_publish,is_best,is_hot,book_number,thumb_img from " .$this->table ." order by $str limit ". $offset . ',' . $limit;
        return $this->db->getAll($sql);
    }
    //获取数量
    public function number($str){
        $sql="SELECT COUNT(*) FROM tb_book WHERE $str";
        return $this->db->getOne($sql);
    }


}