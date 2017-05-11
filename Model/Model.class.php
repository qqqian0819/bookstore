<?php

defined('ACC')||exit('ACC Denied');
class Model {
    protected $table = NULL; // 是model所控制的表
    protected $db = NULL; // 是引入的mysql对象
    protected $pk='';//每个表有自己的primary key。
    protected $fileds=array();
    protected $auto=array();
    protected $error=array();
    public function __construct() {
        $this->db = mysql::getIns();
    }

    public function table($table) {
        $this->table = $table;
    }
    // 清除掉不用的单元-----自动过滤
    public function _facade($array=array()) {
        $data = array();
        // 遍历
        foreach($array as $k=>$v) {
            if(in_array($k,$this->fields)) {  // 判断$k是否已是表的字段，该字段是否已赋值
                $data[$k] = $v;
            }
        }

        return $data;
    }

    // 自动填充------$post未传表却需要
    public function _autoFill($data) {
        foreach($this->_auto as $k=>$v) {
            if(!array_key_exists($v[0],$data)) {
                switch($v[1]) {//array('is_hot','value','0')
                    case 'value':
                    $data[$v[0]] = $v[2];
                    break;

                    case 'function':
                    // 回调函数(time();)
                    $data[$v[0]] = call_user_func($v[2]);
                    break;
                }
            }
        }

        return $data;
    }
        /*
        格式 $this->_valid = array(
                    array('验证的字段名',0/1/2(验证场景),'报错提示','require/in(某几种情况)/between(范围)/length(某个范围)','参数')
        );

        array('goods_name',1,'必须有商品名','requird'),
        array('cat_id',1,'栏目id必须是整型值','number'),
        array('is_new',0,'in_new只能是0或1','in','0,1')
        array('goods_breif',2,'商品简介就在10到100字符','length','10,100')

    */
        public function _validate($data) {
        if(empty($this->_valid)) {
            return true;
        }

        $this->error = array();

        foreach($this->_valid as $k=>$v) {
            switch($v[1]) {
                case 1:
                    if(!isset($data[$v[0]])) {
                        $this->error[] = $v[2];
                        return false;
                    }
                    
                    if(!isset($v[4])) {
                        $v[4] = '';
                    }

                    if(!$this->check($data[$v[0]],$v[3],$v[4])) {
                        $this->error[] = $v[2];
                        return false;
                    }
                    break;
                case 0:
                    if(isset($data[$v[0]])) {
                        if(!$this->check($data[$v[0]],$v[3],$v[4])) {
                            $this->error[] = $v[2];
                            return false;
                        }
                    }
                    break;
                case 2:
                    if(isset($data[$v[0]]) && !empty($data[$v[0]])) {
                        if(!$this->check($data[$v[0]],$v[3],$v[4])) {
                            $this->error[] = $v[2];
                            return false;
                        }
                    }
            }
        }

        return true;

    }

    public function getErr(){
        return $this->error;
    }

    protected function check($value,$rule='',$parm='') {
        switch($rule) {
            case 'require':
                return !empty($value);

            case 'number':
                return is_numeric($value);

            case 'in':
                $tmp = explode(',',$parm);
                return in_array($value,$tmp);
            case 'between':
                list($min,$max) = explode(',',$parm);
                return $value >= $min && $value <= $max;
            case 'length':
                list($min,$max) = explode(',',$parm);
                return strlen($value) >= $min && strlen($value) <= $max;
            case 'email':
                // 判断$value是否是email,可以用正则表达式,但现在没学.
                // 因此,此处用系统函数来判断
                return (filter_var($value,FILTER_VALIDATE_EMAIL) !== false);
            default:
                return false;
        }
    }

    // 所有表都会用到最基本的增删改查操作
    public function add($data){
    	return $this->db->autoExecute($this->table,$data);//bool
    }
    // 删
    public function delete($id) {
        $sql = 'delete from ' .$this->table . ' where ' . $this->pk . '=' .$id;
        if($this->db->query($sql)) {
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
    // 改
    public function update($data,$id){
    	$rs = $this->db->autoExecute($this->table,$data,' update ',' where '. $this->pk .'='. $id);
        if($rs) {
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }
    
    // 查所有
    public function select(){
    	$sql='select * from '.$this->table;
    	return $this->db->getAll($sql);
    }
    // 查一行
    public function find($id) {
        $sql = 'select * from ' .  $this->table . ' where ' . $this->pk . '=' . $id;
        return $this->db->getRow($sql);
    }
    public function insert_id(){
        return $this->db->insert_id();
    }
}

    