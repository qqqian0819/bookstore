<?php 

defined('ACC')||exit('Acc Deined');
class OGModel extends Model {
    protected $table = 'tb_og';
    protected $pk = 'og_id';
    // 把订单的商品写入ordergoods表，并减少库存
    public function addOG($data){
        // 如果添加成功减库存
        if($this->add($data)){
        $sql='update tb_book set book_number = book_number - '.$data['book_number'].' where book_id = '.$data['book_id'];
        return $this->db->query($sql);
        }
        return false;

    }
    // 查一个订单下面的所有商品
    public function getOrder($id){
        $sql="select tb_book.book_id,book_name,tb_og.shop_price,author,tb_og.book_number,book_sn,subtotal from tb_og join tb_book on tb_book.book_id=tb_og.book_id where order_id=$id";
        return $this->db->getAll($sql);
    }
}