<?php

defined('ACC')||exit('Acc Deined');
class OIModel extends Model {
    protected $table = 'tb_orderinfo';
    protected $pk = 'order_id';
    protected $fields = array('order_id','order_sn','user_id','username','address','reciver','email','tel','add_time');

    protected $_valid = array(
                            array('reciver',1,'收货人不能为空','require'),
                            array('email',1,'email非法','email'),
                            array('pay',1,'必须选支付方式','in','1') //代表4在线支付与5到付.
    );

    protected $_auto = array(
                            array('add_time','function','time')
                            );
    // 创建订单号
    public function orderSn(){
    	$sn='OI'.date('Ymd').mt_rand(10000,99999);
    	$sql='select count (*) from '. $this->table.' where order_sn = '." '$sn'";
    	// 订单号存在递归
    	return $this->db->getOne($sql)?$this->orderSn():$sn;
    }
    // 撤销订单
    public function invoke($order_id){
    	// 先删掉订单
    	$this->delete($order_id);
    	// 再删订单对应的商品
    	$sql='delete from ordergoods where order_id = '. $order_id;
    	return $this->db->query($sql);
    }    
    // 查一个用户的历史订单
    public function userOrder($id){
        $sql='SELECT order_sn,order_id,order_amount,reciver,add_time from ' . $this->table .' where user_id = ' . $id;
        return $this->db->getAll($sql);
    }
    //内联查询所有订单
    public function selectAll($sort='add_time desc',$offset=0,$limit=5){
        $sql="select order_sn,username,order_amount,add_time,order_id,tb_user.user_id from tb_orderinfo inner join tb_user on tb_orderinfo.user_id=tb_user.user_id order by $sort limit ". $offset . ',' . $limit;
        return $this->db->getAll($sql);
    }
    // 内联查询订单详情
    public function findDet($id){
        $sql="select order_sn,address,reciver,tb_orderinfo.email,tel,username,order_amount,add_time,order_id,tb_user.user_id from tb_orderinfo inner join tb_user on tb_orderinfo.user_id=tb_user.user_id where tb_orderinfo.order_id=$id";
        return $this->db->getRow($sql);
    }
    //关键字搜索订单
    public function keyOrder($str,$offset=0,$limit=5){
        $sql="select order_sn,username,order_amount,add_time,order_id,tb_user.user_id from tb_orderinfo inner join tb_user on tb_orderinfo.user_id=tb_user.user_id where order_sn like'% " .trim($str). " %' or username like'%" . trim ($str) ."%' limit ". $offset . ',' . $limit;
        return $this->db->getAll($sql);
    }
    // 关键字订单条数
    public function number($str){
        $sql="SELECT count(*) from tb_orderinfo inner join tb_user on tb_orderinfo.user_id=tb_user.user_id where order_sn like'% " .trim($str). " %' or username like'%" . trim ($str) ."%' ";
        return $this->db->getOne($sql);
    }
}