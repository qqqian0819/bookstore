<?php

defined('ACC')||exit('Acc Deined');
class UserModel extends Model{
	protected $table='tb_user';
	protected $pk='user_id';
	protected $fields=array('user_id','username','email','passwd','regtime');
	// 验证的数组
    protected $_valid = array(
                            array('username',1,'用户名必须存在','require'),
                            array('username',0,'用户名必须在2-16字符内','length','2,16'),
                            array('email',1,'email非法','email'),
                            array('passwd',1,'密码不能为空','require'),
                            array('passwd',0,'密码不能少于4位','length','4,16')
    );
    // 填充时间
    protected $_auto = array(
                            array('regtime','function','time'),
                            array('lastlogin','function','time')
                            );
    // 对密码进行编码
    protected function encPasswd($p){
    	return md5($p);
    }
    // 对传入的密码编码并注册
    public function reg($data){
    	// 如果传入了密码则进行编码
    	if($data['passwd']){
    		$data['passwd']=$this->encPasswd($data['passwd']);
    	}
    	return $this->add($data);
    }
    // 检测用户
    public function checkUser($username,$passwd=''){
        if($passwd==''){
    	$sql = "select user_id,username,email,passwd from " . $this->table . " where username= '" . $username . "'";
    	return $this->db->getOne($sql);

        }else{
            $sql= "select user_id,username,email,passwd from " . $this->table . " where username= '" . $username . "'";
            $row=$this->db->getRow($sql);
            if(empty($row)){
                return false;
            }
            if($row['passwd']!=$this->encPasswd($passwd)){
                return false;
            }
            // 为了session时的安全删掉密码
            // unset($row['passwd']);
            return $row;
        }
    }
    // 根据用户名取出信息
    public function getUser($name){
        $sql = "select user_id,username,email,passwd,regtime from " . $this->table . " where username= '" . $name . "'";
        return $this->db->getRow($sql);
    }

}