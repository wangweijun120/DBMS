<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller
{
    protected $db;
    protected $pg_fields;
    protected $fs_fields;
    protected $info_fields = array();
    protected $python_db;
    protected $python_pg_fields;
    protected $python_fs_fields;
    protected $python_info_fields = array();

    function _initialize()
    {
        $this->db = D('pdf');
        $fields = $this->db->getDbFields();
        foreach ($fields as $k => $v)
            if (strpos($v, 'pg_') !== false) {
                $this->pg_fields[$k] = $v;
            } else if (strpos($v, 'fs_') !== false) {
                $this->fs_fields[$k] = $v;
            }
        $temp = array_merge($this->fs_fields, $this->pg_fields);
        array_push($temp, 'pg', 'fs');
        foreach ($fields as $k => $v) {
            if (!in_array($v, $temp)) {
                $this->info_fields[$k] = $v;
            }
        }


        $this->python_db = D('python_usdaplant');
        $fields = $this->python_db->getDbFields();
        foreach ($fields as $k => $v)
            if (strpos($v, 'pg_') !== false) {
                $this->python_pg_fields[$k] = $v;
            } else if (strpos($v, 'fs_') !== false) {
                $this->python_fs_fields[$k] = $v;
            }
        $temp = array_merge($this->python_fs_fields, $this->python_pg_fields);
        array_push($temp, 'pg', 'fs');
        foreach ($fields as $k => $v) {
            if (!in_array($v, $temp)) {
                $this->python_info_fields[$k] = $v;
            }
        }
    }

    public function python()
    {
        $data = $this->python_db->field('Symbol,Scientific_Name,Common_Name,fs,pg')->select();
        foreach ($data as $k => $value) {
            if ($value['fs'] !== '') {
                $value['fs_pdf'] = 'pdf';
            }
            if ($value['pg'] !== '') {
                $value['pg_pdf'] = 'pdf';
            }
            $data[$k] = $value;
        }
        $this->assign('list', $data);
        $this->display();
    }

    public function index()
    {
        $data = $this->db->field('Symbol,Scientific_Name,Common_Name,fs,pg')->select();
        foreach ($data as $k => $value) {
            if ($value['fs'] !== '') {
                $value['fs_pdf'] = 'pdf';
            }
            if ($value['pg'] !== '') {
                $value['pg_pdf'] = 'pdf';
            }
            $data[$k] = $value;
        }
        $this->assign('list', $data);
        $this->display();
    }

    public function showInfo()
    {
        $name = I('science');
        $name = str_replace('+', ' ', $name);
        $where['Scientific_Name'] = $name;
        if(I('type')=='php') {
            $data = $this->db->field($this->info_fields)->where($where)->select();
        }
        else{
            $data = $this->python_db->field($this->python_info_fields)->where($where)->select();
        }
        $data = $data[0];
        $data = array_filter($data);
        $flip = array_flip($data);
        foreach ($flip as $k => $value) {
            $value = str_replace('_', ' ', $value);
            $flip[$k] = $value;
        }
        $data = array_flip($flip);
        $this->assign('title', $name);
        $this->assign('list', $data);
        $this->display('Index/fact');
    }

   public function guide(){
       $name = I('science');
       $name = str_replace('+', ' ', $name);
       $id = I('id');
       $where['Scientific_Name'] = $name;
       if ($id === 'fs') {
           $data = $this->python_db->field($this->python_fs_fields)->where($where)->select();
       } else {
           $data = $this->python_db->field($this->python_pg_fields)->where($where)->select();
       }
       $data = $data[0];
       $data = array_filter($data);
       $flip = array_flip($data);
       foreach ($flip as $k => $value) {
           $value = substr($value, 3);
           $value = str_replace('_', ' ', $value);
           $flip[$k] = $value;
       }
       $data = array_flip($flip);
       $this->assign('title', $name);
       $this->assign('list', $data);
       $this->display('Index/fact');
   }
    
    public function fact()
    {
        $name = I('science');
        $name = str_replace('+', ' ', $name);
        $id = I('id');
        $where['Scientific_Name'] = $name;
        if ($id === 'fs') {
            $data = $this->db->field($this->fs_fields)->where($where)->select();
        } else {
            $data = $this->db->field($this->pg_fields)->where($where)->select();
        }
        $data = $data[0];
        $data = array_filter($data);
        $flip = array_flip($data);
        foreach ($flip as $k => $value) {
            $value = substr($value, 3);
            $value = str_replace('_', ' ', $value);
            $flip[$k] = $value;
        }
        $data = array_flip($flip);
        $this->assign('title', $name);
        $this->assign('list', $data);
        $this->display();
    }

    public function counter(){
        $counter_db=M('counter');
        if(I('type')=='php'){
            $where['type']='php';
            $data=$counter_db->where($where)->field('id')->find();
        }else if (I('type')=='python') {
            $where['type']='python';
            $data=$counter_db->where($where)->field('id')->find();
        }
        $num=$data['id'];$num++;$data['id']=$num;
        $counter_db->where($where)->save($data);
        $this->ajaxReturn($data,'json');
    }
}
