<?php
defined('ACC')||exit('ACC Denied');
class MessModel extends Model{
    // 表名
    protected $table = 'tb_message';
    protected $pk='mess_id';
    //获取文章下面的评论
    function bookMess($id){
    	$sql="SELECT content,times,username,is_anonymous FROM  tb_message JOIN tb_user ON tb_message.user_id=tb_user.user_id WHERE book_id=$id ORDER BY times desc";
    	return $this->db->getAll($sql);
    }
    // 获取用户的评论
    function userMess($id){
    	$sql="SELECT content,times,book_name,tb_book.book_id,author FROM  tb_message JOIN tb_book ON tb_message.book_id=tb_book.book_id WHERE user_id=$id ORDER BY times desc";
    	return $this->db->getAll($sql);
    }
    // 获取所有评论
    function getMess($sort='desc',$offset=0,$limit=5){
        $sql="SELECT tb_user.username, tb_user.email,tb_user.user_id,times,tb_book.book_name,tb_message.book_id,tb_message.content FROM tb_message 
            JOIN tb_user ON tb_message.user_id=tb_user.user_id
            JOIN tb_book ON tb_message.book_id=tb_book.book_id ORDER BY times $sort limit ". $offset . ',' . $limit;
        return $this->db->getAll($sql);
    }
    // 关键字搜索评论
    function keyWord($str){
        $sql="SELECT tb_user.username, tb_user.email,tb_user.user_id,times,tb_book.book_name,tb_message.book_id,tb_message.content FROM tb_message 
            JOIN tb_user ON tb_message.user_id=tb_user.user_id
            JOIN tb_book ON tb_message.book_id=tb_book.book_id WHERE book_name like '% " .trim($str). " %' or username like'%" . trim ($str) ."%' or content like'%".trim($str)."%'";
        return $this->db->getAll($sql);
    }
}