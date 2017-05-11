<?php
defined('ACC')||exit('ACC Denied');
class CatModel extends Model{
    // 表名
    protected $table = 'tb_category';
    protected $pk='cat_id';

    //键->表中的列,值-->表中的值,add()函数自动插入该行数据
    public function add($data) {
        return $this->db->autoExecute($this->table,$data);
    }

    // 获取本表下面所有的数据
    public function select() {
        $sql = 'select cat_id,cat_name,parent_id,intro from ' .  $this->table;
        return $this->db->getAll($sql);
    }

    // 根据主键 取出一行数据
    public function find($cat_id) {
        $sql = 'select * from ' . $this->table . ' where cat_id=' . $cat_id;
        return $this->db->getRow($sql);
    }
    // 删除栏目
    public function delete($cat_id=0) {
        $sql = 'delete from ' . $this->table . ' where cat_id=' . $cat_id;
        $this->db->query($sql);
        // 返回影响的行数
        return $this->db->affected_rows();
    }

    // 编辑栏目
    public function update($data,$cat_id=0) {
        $this->db->autoExecute($this->table,$data,'update',' where cat_id=' . $cat_id);
        return $this->db->affected_rows();
    }

    // 递归 获得树
    public function getCatTree($arr,$id = 0,$lev=0) {
        $tree = array();

        foreach($arr as $v) {
            if($v['parent_id'] == $id) {
                $v['lev'] = $lev;
                $tree[] = $v;
                $tree = array_merge($tree,$this->getCatTree($arr,$v['cat_id'],$lev+1));
            }
        }

        return $tree;
    }

    // 获得子孙树   防止一下直接删除父级栏目
    public function getSon($id) {
        $sql = 'select cat_id,cat_name,parent_id from ' . $this->table . ' where parent_id=' . $id;
        // 取出所有儿子
        return $this->db->getAll($sql);
    }

    // 迭代法获得家谱树
    public function getTree($id=0) {
        $tree = array();
        // 取出所有栏目
        $cats = $this->select();        
        while($id>0) {
            foreach($cats as $v) {
                if($v['cat_id'] == $id) {
                    $tree[] = $v;                    
                    $id = $v['parent_id'];
                    break;
                }
            }
        }
        // 返回顺序相反的新数组
        return array_reverse($tree);

    }    


}


