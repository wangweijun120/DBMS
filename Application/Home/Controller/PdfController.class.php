<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;

use mageekguy\atoum\report\fields\runner\result;
use mageekguy\atoum\tests\units\writer\decorators\trim;
use Think\Controller;

class PdfController extends Controller
{
    protected $fields;
    protected $vq;
    protected $db;

    function _initialize()
    {
        //引用Vquery类，不同的框架或源生写法可能引入有差异。根据实际情况选择对应的引入方法。
        $arr = array(
            "url" => 'https://plants.usda.gov/java/charProfile?symbol=ABGR',
            //"url" => 'http://localhost/in.html',
            'method' => 'get',
        );
        // $arr=file_get_contents("http://www.jd.com/allSort.aspx");
        $this->vq = new  \Org\Util\Vquery($arr);
        $this->db = D('pdf');
        $this->fields = $this->db->getDbFields();
    }
    public function getProfile(){
        $symbol = $this->db->field('Symbol')->select();
        foreach ($symbol as $value) {
            $plant_arr = array();
            $url = "https://plants.usda.gov/core/profile?symbol=ACNE2";
                //. $value['Symbol'];
            $arr = array(
                "url" => $url,
                //"url" => 'http://localhost/in.html',
                'method' => 'get',
            );
            $vy = new  \Org\Util\Vquery($arr);
            $data = $vy->find("table");
            $r = $data->result;
            $r=$r[0][4];
            file_put_contents('table.txt',$r);
            preg_match_all('/<tr>.*?<\/tr>/ism', $r, $trs);
            var_dump($trs);
            die();
        }
    }

    public function test()
    {
        $symbol = $this->db->field('Symbol,Post_Product')->select();
        foreach ($symbol as $value) {
            if($value['Post_Product']!=='')
                continue;
            $plant_arr = array();
            $url = "https://plants.usda.gov/java/charProfile?symbol=" . $value['Symbol'];
            $arr = array(
                "url" => $url,
                //"url" => 'http://localhost/in.html',
                'method' => 'get',
            );
            $vy = new  \Org\Util\Vquery($arr);
            $data = $vy->find("table");
            $r = $data->result;
            $r = $r[0][3];
            if (substr_count($r, '</tr>') < 20)
                continue;
            $r = preg_replace("#<!--[^\!\[]*?-->#", '', $r);
            preg_match_all('/<tr>.*?<\/tr>/ism', $r, $trs);
            $trs = $trs[0];
            foreach ($trs as $tr) {
                preg_match_all('/<td.*?>.*?<\/td>/ism', $tr, $tds);
                $tds = $tds[0];
                $tds[0] = trim(preg_replace("/\<.*?\>|\<.*?\>/", '', $tds[0]));
                if ($tds[0] === '&nbsp;' || $tds[0] === '')
                    break;
                $tds[0] = str_replace(' ', '_', $tds[0]);
                $tds[1] = trim(preg_replace("/\<.*?\>|\<.*?\>/", '', $tds[1]));
                $this->addField($tds[0], 'varchar(255)');
                $plant_arr[$tds[0]] = $tds[1];
            }

            $where['Symbol'] = $value['Symbol'];
            $this->db->where($where)->save($plant_arr);
            unset($plant_arr);
        }

        echo "successful!";


        // var_dump($trs);

    }

    public function test1()
    {
        foreach ($this->fields as $v) {
            $data = $this->db->where('1=1')->field($v)->select();
            if ($this->is_empty_array($data)) {
                $this->dropField('pdf', $v);
            }
        }

    }

//    function dropField($field){
//        $sql="alter table 'pdf' drop COLUMN $field";
//       var_dump( M()->execute($sql));
//    }
    function dropField($table, $f)
    {
        $sql = "alter table `$table` drop column $f";
        var_dump(M()->execute($sql));
    }

    private function is_empty_array($arr)
    {
        foreach ($arr as $item) {
            foreach ($item as $k) {
                if ($k !== '')
                    return false;
            }
        }
        return true;
    }

    public function factSheet()
    {
        $data = $this->db->where('1=1')->field('Scientific_Name')->select();
        $i = 1;
        foreach ($data as $item) {
            $url = 'D:/xampp/htdocs/spider/text/pg/pg_' . $item['Scientific_Name'] . '.txt';
            //$url = 'D:/xampp/htdocs/spider/text/pg/pg_Acacia angustissima var. hirta.txt';
            if (file_exists($url)) {
                $arr = $this->parse($url);
                $info = $this->insertData($arr, 'pg_');
                $where['Scientific_Name'] = $item['Scientific_Name'];
                $this->db->where($where)->save($info);
                echo '写入第' . $i . "条记录<br/>";
                unset($arr);
                unset($info);
                $i++;
            }
        }
    }

    public function index()
    {

        $data = $this->db->where('1=1')->field('Scientific_Name')->select();
        $i = 1;
        foreach ($data as $item) {
            $url = 'D:/xampp/htdocs/spider/text/fs/fs_' . $item['Scientific_Name'] . '.txt';
            if (file_exists($url)) {
                $arr = $this->parse($url);
                $info = $this->insertData($arr, 'fs_');
                $where['Scientific_Name'] = $item['Scientific_Name'];
                $this->db->where($where)->save($info);
                echo '写入第' . $i . "条记录<br/>";
                unset($arr);
                unset($info);
                $i++;
            }
        }
    }

    public function insertData($arr, $prefix)
    {
        $str = '';
        $data = array();
        $i = 0;
        $count = count($arr);
        while ($i < $count) {
            if ($arr[$i] === ' ') {
                $filed = $prefix . str_replace(' ', '_', trim($arr[$i + 1]));
                $this->addField($filed, 'text');
                $index = $i + 2;
                while (true) {
                    if ($index == $count)
                        break;
                    if ($arr[$index] === ' ' && strlen($arr[$index + 1]) < 30 && trim($arr[$index + 1]) !== '') {
                        break;
                    } else {
                        $str .= $arr[$index];
                    }
                    $index++;
                }//end while
                $data[$filed] = $str;
                $i = $index;
                unset($index);
                unset($str);
                $str = '';
            }
        }
        return $data;
    }


    private function addField($name, $type)
    {
        $info['name'] = $name;
        $info['type'] = $type;
        $table = 'pdf';
        if (!in_array($info['name'], $this->fields)) {
            $sql = "alter table `$table` add column ";
            $sql .= $this->filterFieldInfo($info);
            M()->execute($sql);
            $this->fields = $this->db->getDbFields();
        }
    }

    private function filterFieldInfo($info)
    {
        if (!is_array($info))
            return
                $newInfo = array();
        $newInfo['name'] = $info['name'];
        $newInfo['type'] = $info['type'];
        switch ($info['type']) {
            case 'text':
                $newInfo['length'] = '';
                $newInfo['isNull'] = $info['isNull'] == 1 ? 'NULL' : 'NOT NULL';
                $newInfo['default'] = '';
                $newInfo['comment'] = empty($info['comment']) ? '' : 'COMMENT ' . $info['comment'];
                break;
            case 'varchar(255)':
                $newInfo['length'] = '';
                $newInfo['isNull'] = $info['isNull'] == 1 ? 'NULL' : 'NOT NULL';
                $newInfo['default'] = '';
                $newInfo['comment'] = empty($info['comment']) ? '' : 'COMMENT ' . $info['comment'];
        }
        $sql = $newInfo['name'] . " " . $newInfo['type'];
        $sql .= (!empty($newInfo['length'])) ? ($newInfo['length']) . ' ' : ' ';
        $sql .= $newInfo['isNull'] . ' ';
        $sql .= $newInfo['default'];
        $sql .= $newInfo['comment'];
        return $sql;
    }


    public function catchContent()
    {
        $data = $this->vq->find("class=\"rowon\"");
        //$data=$this->vq->find("tr");
        $r = $data->result;
        $r = $r[0];
        $i = 0;
        foreach ($r as $v) {
            $data = $this->change($v);
            var_dump($data);
            $this->db->add($data);
            $i++;
            echo '<br/>' . $i . '<br/>';
        }
    }

    public function parse($path)
    {

        $str = file_get_contents($path);
        $arr = explode("\n", $str);

        $offset = 0;
        foreach ($arr as $k => $v) {
            if (trim($v) === 'Uses') {
                $offset = $k - 1;
                break;
            }
        }

        $arr = array_slice($arr, $offset);
        $arr[0] = ' ';

        foreach ($arr as $k => $item) {
            if (!(strpos($item, 'jsp') === false)) {
                $length = $k;
                break;
            }
        }
        $arr = array_slice($arr, 0, $length);
//        var_dump($arr);
//        die();
        return $arr;
    }

    private function change($arr)
    {
        file_put_contents('1.txt', $arr);
        preg_match_all('/<th.*?>.*?<\/th>/ism', $arr, $th);
        preg_match_all('/<td.*?>.*?<\/td>/is', $arr, $tds);
        $data['Symbol'] = preg_replace("/\<.*?\>|\<.*?\>/", '', $th[0][0]);
        $data['Scientific_Name'] = preg_replace("/\<.*?\>|\<.*?\>/", '', $tds[0][0]);
        $data['Common_name'] = preg_replace("/\<.*?\>|\<.*?\>/", '', $tds[0][1]);
        $data['fs'] = $this->getUrl($tds[0][2]);
        $data['pg'] = $this->getUrl($tds[0][3]);
        return $data;
    }

    private function getUrl($str)
    {
        preg_match_all('/<a.*?>.*?<\/a>/ism', $str, $a);
        $a = $a[0][1];
        preg_match("/href=\"(.*)\"/", $a, $href);
        return $href[1];
    }
}








