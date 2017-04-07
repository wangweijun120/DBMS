<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;

class PdfController extends Controller
{
    protected $vq;

    function _initialize()
    {
        //引用Vquery类，不同的框架或源生写法可能引入有差异。根据实际情况选择对应的引入方法。
        $arr = array(
            "url" => 'https://plants.usda.gov/java/factSheet',
            "url" => 'http://localhost/in.html',
            'method' => 'get',
        );
        // $arr=file_get_contents("http://www.jd.com/allSort.aspx");
        $this->vq = new  \Org\Util\Vquery($arr);
    }

    public function index()
    {
        $data = $this->vq->find("class=\"rowon\"");
        //$data=$this->vq->find("tr");
        $r = $data->result;
        $r=$r[0];
$i=0;
        foreach ($r as $v) {
          $data= $this->change($v);
            var_dump($data);
           $db= M('pdf');
            //$db->add($data);
            $i++;
            echo '<br/>'.$i.'<br/>';
        }

    }

    private function change($arr)
    {
        file_put_contents('1.txt',$arr);
        preg_match_all('/<th.*?>.*?<\/th>/ism', $arr, $th);
        preg_match_all('/<td.*?>.*?<\/td>/is', $arr, $tds);
        $data['Symbol']=preg_replace("/\<.*?\>|\<.*?\>/", '', $th[0][0]);
        $data['Scientific_Name']=preg_replace("/\<.*?\>|\<.*?\>/", '', $tds[0][0]);
        $data['Common_name']=preg_replace("/\<.*?\>|\<.*?\>/", '', $tds[0][1]);
        $data['fs']=$this->getUrl($tds[0][2]);
        $data['pg']=$this->getUrl($tds[0][3]);
        return $data;
    }
   private function getUrl($str){
       preg_match_all('/<a.*?>.*?<\/a>/ism', $str, $a);
       $a=$a[0][1];
       preg_match("/href=\"(.*)\"/", $a, $href);
       return $href[1];
    }
}








