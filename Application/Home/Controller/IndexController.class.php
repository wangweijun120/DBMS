<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    protected $db;
    protected $pg_fields;
    protected $fs_fields;

    function _initialize()
    {
        $this->db = D('pdf');
        $fields = $this->db->getDbFields();
        foreach ($fields as $k =>$v)
       if(strpos($v, 'pg_') !== false){
           $this->pg_fields[$k]=$v;
       }else if(strpos($v, 'fs_') !== false){
           $this->fs_fields[$k]=$v;
       }

    }

    public function index()
    {
        $data = $this->db->field('Symbol,Scientific_Name,Common_Name,fs,pg')->select();
        foreach ($data as $k => $value) {
            if ($value['fs'] !== null) {
                $value['fs_pdf'] = 'pdf';
            }
            if ($value['pg'] !== null) {
                $value['pg_pdf'] = 'pdf';
            }
            $data[$k] = $value;
        }
        $this->assign('list', $data);
        $this->display();
    }

    public function fact()
    {
        $name = I('science');
        $name = str_replace('+', ' ', $name);
        $id = I('id');
        $where['Scientific_Name']=$name;
        if ($id === 'fs') {
            $data = $this->db->field($this->fs_fields)->where($where)->select();
        } else {
            $data = $this->db->field($this->pg_fields)->where($where)->select();
        }
        $data=$data[0];
        $data=array_filter($data);
        $flip=array_flip($data);
        foreach ($flip as $k=>$value) {
            $value=substr($value,3);
            $value=str_replace('_',' ',$value);
           $flip[$k]=$value;
        }
        $data=array_flip($flip);
        $this->assign('title',$name);
        $this->assign('list',$data);
        $this->display();
    }

}