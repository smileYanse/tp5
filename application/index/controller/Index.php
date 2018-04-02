<?php

namespace app\index\controller;


use think\Controller;
use JPush\Client as JPush;
use think\Db;
use RongCloud\RongCloud;
use think\Loader;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Symfony\Component\HttpFoundation\Response;

class Index extends Controller
{
    public function index()
    {
        /*        $app_key='';
                $master_secret='';
                $client=new JPush($app_key, $master_secret);
                $client->push()
                    ->setPlatform('all')
                    ->addAllAudience()
                    ->setNotificationAlert('Hello, JPush')
                    ->send();
                return $this->fetch();
                for ($i = 0; $i < 30; $i++) {
                    $sql="INSERT INTO yue_user_paylog (`uid`, `type`, `amount`, `distime`, `distype`, `dec`) VALUES ('723', '3', '11.20', '1517471869', NULL, '视频聊天-5分钟');";
                    $res = Db::query($sql);
                }*/
        $cloud = model('RongyunBox');
        $res = $cloud->getToken('95', '静静', 'http://img.jisuyue.com/avatar95-1516341803.png');
        return $res;
    }

    public function test()
    {
        $num = array('1', '2', '3', '4', '5');
        $content = array('zhx', 'zzz', 'lwh', 'fer', 'eee', 'rrr', 'ttt');
        $action = array('apple', 'pear', 'grape', 'orange', 'watermelon', 'strawberry');
        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[] = $num[rand(0, count($num) - 1)] . ' ' . $content[rand(0, count($action) - 1)] . ' ' . $action[rand(0, count($action) - 1)];
        }
        dump($data);
        echo time();
    }

    public function code()
    {
        $qrCode = new QrCode();
        $url = 'https://www.baidu.com';//加http://这样扫码可以直接跳转url
        $qrCode->setText($url)
            ->setSize(300)//大小
            ->setLabelFontPath(VENDOR_PATH . 'endroid\qrcode\assets\noto_sans.otf')
            ->setErrorCorrectionLevel('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabel('推荐码')
            ->setLabelFontSize(16);
        header('Content-Type: ' . $qrCode->getContentType());
        echo $qrCode->writeString();
        exit;

    }

    public function province()
    {
        $data = file_get_contents(EXTEND_PATH . 'province_data.json');
        $data = json_decode($data, true);
        $data = $data['data']['province'];

        $arr = array();
        $res = self::test1($data, $arr);
        foreach ($res as $k => $datum) {
            Db::name('area')->insert($datum);
        }

    }

    public function test1($array, &$temp)
    {

        foreach ($array as $k => $v) {

            if (array_key_exists('city', $v)) {
                $temp[] = array_slice($v, 0, 4);
                foreach ($v['city'] as $key => $item) {
                    if (array_key_exists('area', $item)) {
                        $temp[] = array_slice($item, 0, 4);
                        self::test1($item['area'], $temp);
                    }
                }
            } else {
                array_push($temp, $v);

            }
        }
        return $temp;

    }

    public function test2()
    {
        $res = Db::name('area')->select();
        $tree = self:: getTree($res, 0);
        dump($tree);
    }

    function getTree($data, $pId)
    {
        $tree = '';
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pId) {
                $v['pid'] = $this->getTree($data, $v['area_id']);
                $tree[] = $v;
            }
        }
        return $tree;
    }
    function getTrees($data, $pId)
    {
        $html = '';
        foreach($data as $k => $v)
        {
            if($v['pid'] == $pId)
            {        //父亲找到儿子
                $html .= "<li>".$v['title'];
                $html .=$this-> getTrees($data, $v['area_id']);
                $html = $html."</li>";
            }
        }
        return $html ? '<ul>' . $html . '</ul>' : $html;
    }

    public function proc()
    {
        $res = Db::name('area')->select();
        $tree = self:: getTrees($res, 0);

        echo $tree;
    }


}
