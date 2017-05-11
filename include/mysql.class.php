<?php


defined('ACC')||exit('ACC Denied');
class mysql extends db {
    private static $ins = NULL;
    private $conn = NULL;
    private $conf = array();
    protected function __construct() {
        $this->conf = conf::getIns();
        
        $this->connect($this->conf->host,$this->conf->user,$this->conf->pwd,$this->conf->db);
        $this->setChar($this->conf->char);
    }

    // 析构函数关闭结果集释放系统资源
   /* public function __destruct() {
        mysql_free_result($rs);
        mysql_close($this->conn);
    }*/

    public static function getIns() {
        if(!(self::$ins instanceof self)) {
            self::$ins = new self();
        }

        return self::$ins;
    }

    public function connect($h,$u,$p,$db) {
        $this->conn = mysqli_connect($h,$u,$p,$db);
        if(!$this->conn) {
            $err = new Exception('连接失败');
            throw $err;
        }
    }

    protected function setChar($char) {
        $sql = 'set names ' . $char;
        return $this->query($sql);
    }

    public function query($sql) {

        $rs = mysqli_query($this->conn,$sql);

        log::write($sql);

        return $rs;
    }

    public function autoExecute($table,$arr,$mode='insert',$where = ' where 1 limit 1') {
        /*    insert into tbname (username,passwd,email) values ('',)
        /// 把所有的键名用','接起来
        // implode(',',array_keys($arr));
        // implode("','",array_values($arr));
        */
        
        if(!is_array($arr)) {
            return false;
        }

        if($mode == 'update') {
            $sql = 'update ' . $table .' set ';
            foreach($arr as $k=>$v) {
                $sql .= $k . "='" . $v ."',";
            }
            $sql = rtrim($sql,',');
            $sql .= $where;
            
            return $this->query($sql);
        }

        $sql = 'insert into ' . $table . ' (' . implode(',',array_keys($arr)) . ')';
        $sql .= ' values (\'';
        $sql .= implode("','",array_values($arr));
        $sql .= '\')';

        return $this->query($sql);
    
    }

    public function getAll($sql) {
        $rs = $this->query($sql);
        
        $list = array();
        while($row = mysqli_fetch_assoc($rs)) {
            $list[] = $row;
        }

        return $list;
    }

    public function getRow($sql) {
        $rs = $this->query($sql);
        
        return mysqli_fetch_assoc($rs);
    }

    public function getOne($sql) {
        $rs = $this->query($sql);
        $row = mysqli_fetch_row($rs);

        return $row[0];
    }

    // 返回影响行数的函数
    public function affected_rows() {
        return mysqli_affected_rows($this->conn);
    }

    // 返回最新的auto_increment列的自增长的值
    public function insert_id() {
        return mysqli_insert_id($this->conn);
    }


}
