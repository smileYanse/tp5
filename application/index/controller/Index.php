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

//    用户截断
    public function userSlice()
    {
        $user = Db::name('user')->where('role_id', 2)->column('id');
        foreach ($user as $key => $item) {
            $user[$key] = 'jsy_' . $item;
        }
        for ($i = 0; $i < count($user); $i = $i + 100) {
            $res[] = array_slice($user, $i, 100);
        }

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
            if ($v['pid'] == $pId) {        //父亲找到儿子 哈哈哈
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

//    jisuyue

//创建昵称库
    public function setusername()
    {
        $username = username();
        foreach ($username as $k => $item) {
            $map = [
                'id' => null,
                'user' => $item
            ];
//            Db::name('tp_user')->insert($map);
        }
    }

//    获取随机昵称
    public function username()
    {
        $tou = array('快乐', '冷静', '醉熏', '潇洒', '糊涂', '积极', '冷酷', '深情', '粗暴', '温柔', '可爱', '愉快', '义气', '认真', '威武', '帅气', '传统', '潇洒', '漂亮', '自然', '专一', '听话', '昏睡', '狂野', '等待', '搞怪', '幽默', '魁梧', '活泼', '开心', '高兴', '超帅', '留胡子', '坦率', '直率', '轻松', '痴情', '完美', '精明', '无聊', '有魅力', '丰富', '繁荣', '饱满', '炙热', '暴躁', '碧蓝', '俊逸', '英勇', '健忘', '故意', '无心', '土豪', '朴实', '兴奋', '幸福', '淡定', '不安', '阔达', '孤独', '独特', '疯狂', '时尚', '落后', '风趣', '忧伤', '大胆', '爱笑', '矮小', '健康', '合适', '玩命', '沉默', '斯文', '香蕉', '苹果', '鲤鱼', '鳗鱼', '任性', '细心', '粗心', '大意', '甜甜', '酷酷', '健壮', '英俊', '霸气', '阳光', '默默', '大力', '孝顺', '忧虑', '着急', '紧张', '善良', '凶狠', '害怕', '重要', '危机', '欢喜', '欣慰', '满意', '跳跃', '诚心', '称心', '如意', '怡然', '娇气', '无奈', '无语', '激动', '愤怒', '美好', '感动', '激情', '激昂', '震动', '虚拟', '超级', '寒冷', '精明', '明理', '犹豫', '忧郁', '寂寞', '奋斗', '勤奋', '现代', '过时', '稳重', '热情', '含蓄', '开放', '无辜', '多情', '纯真', '拉长', '热心', '从容', '体贴', '风中', '曾经', '追寻', '儒雅', '优雅', '开朗', '外向', '内向', '清爽', '文艺', '长情', '平常', '单身', '伶俐', '高大', '懦弱', '柔弱', '爱笑', '乐观', '耍酷', '酷炫', '神勇', '年轻', '唠叨', '瘦瘦', '无情', '包容', '顺心', '畅快', '舒适', '靓丽', '负责', '背后', '简单', '谦让', '彩色', '缥缈', '欢呼', '生动', '复杂', '慈祥', '仁爱', '魔幻', '虚幻', '淡然', '受伤', '雪白', '高高', '糟糕', '顺利', '闪闪', '羞涩', '缓慢', '迅速', '优秀', '聪明', '含糊', '俏皮', '淡淡', '坚强', '平淡', '欣喜', '能干', '灵巧', '友好', '机智', '机灵', '正直', '谨慎', '俭朴', '殷勤', '虚心', '辛勤', '自觉', '无私', '无限', '踏实', '老实', '现实', '可靠', '务实', '拼搏', '个性', '粗犷', '活力', '成就', '勤劳', '单纯', '落寞', '朴素', '悲凉', '忧心', '洁净', '清秀', '自由', '小巧', '单薄', '贪玩', '刻苦', '干净', '壮观', '和谐', '文静', '调皮', '害羞', '安详', '自信', '端庄', '坚定', '美满', '舒心', '温暖', '专注', '勤恳', '美丽', '腼腆', '优美', '甜美', '甜蜜', '整齐', '动人', '典雅', '尊敬', '舒服', '妩媚', '秀丽', '喜悦', '甜美', '彪壮', '强健', '大方', '俊秀', '聪慧', '迷人', '陶醉', '悦耳', '动听', '明亮', '结实', '魁梧', '标致', '清脆', '敏感', '光亮', '大气', '老迟到', '知性', '冷傲', '呆萌', '野性', '隐形', '笑点低', '微笑', '笨笨', '难过', '沉静', '火星上', '失眠', '安静', '纯情', '要减肥', '迷路', '烂漫', '哭泣', '贤惠', '苗条', '温婉', '发嗲', '会撒娇', '贪玩', '执着', '眯眯眼', '花痴', '想人陪', '眼睛大', '高贵', '傲娇', '心灵美', '爱撒娇', '细腻', '天真', '怕黑', '感性', '飘逸', '怕孤独', '忐忑', '高挑', '傻傻', '冷艳', '爱听歌', '还单身', '怕孤单', '懵懂');
        $do = array("的", "爱", "", "与", "给", "扯", "和", "用", "方", "打", "就", "迎", "向", "踢", "笑", "闻", "有", "等于", "保卫", "演变");
        $wei = array('嚓茶', '凉面', '便当', '毛豆', '花生', '可乐', '灯泡', '哈密瓜', '野狼', '背包', '眼神', '缘分', '雪碧', '人生', '牛排', '蚂蚁', '飞鸟', '灰狼', '斑马', '汉堡', '悟空', '巨人', '绿茶', '自行车', '保温杯', '大碗', '墨镜', '魔镜', '煎饼', '月饼', '月亮', '星星', '芝麻', '啤酒', '玫瑰', '大叔', '小伙', '哈密瓜，数据线', '太阳', '树叶', '芹菜', '黄蜂', '蜜粉', '蜜蜂', '信封', '西装', '外套', '裙子', '大象', '猫咪', '母鸡', '路灯', '蓝天', '白云', '星月', '彩虹', '微笑', '摩托', '板栗', '高山', '大地', '大树', '电灯胆', '砖头', '楼房', '水池', '鸡翅', '蜻蜓', '红牛', '咖啡', '机器猫', '枕头', '大船', '诺言', '钢笔', '刺猬', '天空', '飞机', '大炮', '冬天', '洋葱', '春天', '夏天', '秋天', '冬日', '航空', '毛衣', '豌豆', '黑米', '玉米', '眼睛', '老鼠', '白羊', '帅哥', '美女', '季节', '鲜花', '服饰', '裙子', '白开水', '秀发', '大山', '火车', '汽车', '歌曲', '舞蹈', '老师', '导师', '方盒', '大米', '麦片', '水杯', '水壶', '手套', '鞋子', '自行车', '鼠标', '手机', '电脑', '书本', '奇迹', '身影', '香烟', '夕阳', '台灯', '宝贝', '未来', '皮带', '钥匙', '心锁', '故事', '花瓣', '滑板', '画笔', '画板', '学姐', '店员', '电源', '饼干', '宝马', '过客', '大白', '时光', '石头', '钻石', '河马', '犀牛', '西牛', '绿草', '抽屉', '柜子', '往事', '寒风', '路人', '橘子', '耳机', '鸵鸟', '朋友', '苗条', '铅笔', '钢笔', '硬币', '热狗', '大侠', '御姐', '萝莉', '毛巾', '期待', '盼望', '白昼', '黑夜', '大门', '黑裤', '钢铁侠', '哑铃', '板凳', '枫叶', '荷花', '乌龟', '仙人掌', '衬衫', '大神', '草丛', '早晨', '心情', '茉莉', '流沙', '蜗牛', '战斗机', '冥王星', '猎豹', '棒球', '篮球', '乐曲', '电话', '网络', '世界', '中心', '鱼', '鸡', '狗', '老虎', '鸭子', '雨', '羽毛', '翅膀', '外套', '火', '丝袜', '书包', '钢笔', '冷风', '八宝粥', '烤鸡', '大雁', '音响', '招牌', '胡萝卜', '冰棍', '帽子', '菠萝', '蛋挞', '香水', '泥猴桃', '吐司', '溪流', '黄豆', '樱桃', '小鸽子', '小蝴蝶', '爆米花', '花卷', '小鸭子', '小海豚', '日记本', '小熊猫', '小懒猪', '小懒虫', '荔枝', '镜子', '曲奇', '金针菇', '小松鼠', '小虾米', '酒窝', '紫菜', '金鱼', '柚子', '果汁', '百褶裙', '项链', '帆布鞋', '火龙果', '奇异果', '煎蛋', '唇彩', '小土豆', '高跟鞋', '戒指', '雪糕', '睫毛', '铃铛', '手链', '香氛', '红酒', '月光', '酸奶', '银耳汤', '咖啡豆', '小蜜蜂', '小蚂蚁', '蜡烛', '棉花糖', '向日葵', '水蜜桃', '小蝴蝶', '小刺猬', '小丸子', '指甲油', '康乃馨', '糖豆', '薯片', '口红', '超短裙', '乌冬面', '冰淇淋', '棒棒糖', '长颈鹿', '豆芽', '发箍', '发卡', '发夹', '发带', '铃铛', '小馒头', '小笼包', '小甜瓜', '冬瓜', '香菇', '小兔子', '含羞草', '短靴', '睫毛膏', '小蘑菇', '跳跳糖', '小白菜', '草莓', '柠檬', '月饼', '百合', '纸鹤', '小天鹅', '云朵', '芒果', '面包', '海燕', '小猫咪', '龙猫', '唇膏', '鞋垫', '羊', '黑猫', '白猫', '万宝路', '金毛', '山水', '音响', '尊云', '西安');
        $tou_num = rand(0, 331);
        $do_num = rand(0, 19);
        $wei_num = rand(0, 327);
        $type = rand(1, 2);
        $username = Db::name('name')->column('user');
        if ($type == 1) {
            $username[] = $tou[$tou_num] . $do[$do_num] . $wei[$wei_num];
        } else {
            $username[] = $wei[$wei_num] . $tou[$tou_num];
        }
        return $username[rand(0, count($username) - 1)];
    }

//    获取随机手机号
    private static $mobileSegment = [
        '134', '135', '136', '137', '138', '139', '150', '151', '152', '157', '130', '131', '132', '155', '186', '133', '153', '189', '185', '175', '166'
    ];

    public function mobile()
    {
        $prefix = self::$mobileSegment[array_rand(self::$mobileSegment)];
        $middle = mt_rand(1000, 9999);
        $suffix = mt_rand(1000, 9999);
        return $prefix . $middle . $suffix;
    }

    /**
     * @param $from 开始时间
     * @param $to 结束时间
     * @return int  返回时间戳
     */
    public function ctime($from, $to)//2018-01-31 23:59:59
    {
        $starTime = strtotime($from);
        $endTime = strtotime($to);
        return rand($starTime, $endTime);
    }

//    用户信息
    public function defaultInfo()
    {
        $map['id'] = null;
        $map['mobile'] = self::mobile();
        $map['password'] = "1ce56f78fa417d153437656bb72297c5";//795f1ec97ff3302c4182b1a2de3930b4
        $map['username'] = self::username();
        $map['sex'] = rand(1, 2);
        $map['role_id'] = 1;
        $map['province'] = 21;
        $map['city'] = 22;
        $map['area'] = 23;
//        $map['create_time']=self::ctime('2018-01-01 00:00:00','2018-01-31 23:59:59'); //1月份1000条
//        $map['create_time']=self::ctime('2018-02-01 00:00:00','2018-02-28 23:59:59'); //2月份4000条
//        $map['create_time']=self::ctime('2018-03-01 00:00:00','2018-03-31 23:59:59'); //3月份3500条
        $map['create_time'] = self::ctime('2018-04-01 00:00:00', '2018-04-17 23:59:59'); //4月份3100条

        $map['last_time'] = $map['create_time'];
        $map['is_new'] = 2;
        $map['agent'] = 0;
        return $map;
    }

//    新增用户信息+头像 yue_user ,yue_attachment
    public function insertUser()
    {
        for ($i = 11001; $i < 11601; $i++) {
            $info = self::defaultInfo();
            $uid = Db::name('user')->insertGetId($info);

//            头像attachments
            $map['attach_type'] = 'avatar';
            $map['uid'] = $uid;
            $map['type'] = 'image/jpg';
            $map['cdn_url'] = 'http://www.jisuyue.com/app_photos/man' . $i . '.jpg';
            $map['ctime'] = 1523952674;
            $avatar = Db::name('attachment')->insertGetId($map);
            Db::name('user')->where('id', $uid)->setField('avatar', $avatar);

//            余额
            $money['uid'] = $uid;
            $money['rmb'] = 0;
            Db::name('user_money')->insert($money);
        }
        return 1;
    }


//    新增user_pay
    public function payInfo()
    {
        $uid = Db::name('user')->where('is_new', 2)->column('id');
        $subject = ['微信支付充值-￥', '支付宝充值-￥'];
        $total_amount = [30, 50, 30, 50, 100, 200, 500, 1000, 2000, 5000, 10000, 30, 50, 50, 30, 100, 200, 500];
        $time = self::ctime('2018-01-01 00:00:00', '2018-01-31 23:59:59'); //1000
//        $time = self::ctime('2018-02-01 00:00:00', '2018-02-28 23:59:59');  //4000
//        $time = self::ctime('2018-03-01 00:00:00', '2018-03-31 23:59:59'); //3500
//        $time = self::ctime('2018-04-01 00:00:00', '2018-04-17 23:59:59'); //3100
        $amount = $total_amount[rand(0, count($total_amount) - 1)];
        $map['uid'] = $uid[rand(0, count($uid) - 1)];
        $map['type'] = rand(1, 2);
        if ($map['type'] == 1) {  //支付宝
            $map['subject'] = $subject[1] . $amount;
            $map['total_amount'] = $amount;
            $map['out_trade_no'] = 'ALIPAY' . date('Ymd', $time) . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } elseif ($map['type'] == 2) {  //微信
            $map['subject'] = $subject[0] . $amount;
            $map['total_amount'] = $amount;
            $map['out_trade_no'] = 'WXPAY' . date('Ymd', $time) . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
        $map['status'] = 1;
        $map['c_time'] = $time;
        return $map;
    }

    public function insertPay()
    {

        for ($i = 0; $i < 10; $i++) {
            $map = self::payInfo();
            Db::name('user_pay')->insert($map);
        }
    }


//    新增order_list
    public function orderInfo($isVod)
    {
        $order_pay = [400, 500, 600, 700, 800, 1000, 1200, 1500, 2000, 400, 400, 400, 400, 500];
        $vod_duration = [60, 120, 180, 240, 600, 540];
        $jsy_duration = [1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 6];
        $address = [
            '丰收日(光大店),漕宝路91号云悦·华尔兹精品酒店1-3层',
            '椒意重庆美蛙鱼头(柳州路店),柳州路356号之一',
            '维也纳酒店(朱泾店),金龙新街622号金龙商务大厦',
            '汉庭酒店(嘉善外环东路店),魏塘街道外环东路17号',
            '口湘堂(徐汇万体馆店),漕溪路123-1号',
            '上海市徐汇区漕宝路36号靠近漕宝路(地铁站)',
            '白金汉爵大酒店,新华南路229号',
            '美林小厨(柳州店),柳州路427号(近漕宝路)',
            '老李羊羯子涮锅(柳州路店),柳州路356号-2附近',
            '棉花酒吧(安亭路),田林东路411号(近钦州路)',
            'SFC影城(徐汇日月光中心),漕宝路33号徐汇日月光中心',
            '丽宫商务酒店,城西路108号(翡翠花苑门口)近兴平一路',
            '名羊天下碳烤羊腿(柳州路店),柳州路368号(近漕宝路)',
            '梦幻4D动感体验馆,人民路93号诚泰国际商务广场4层',
            '卢米埃影城(金鹰国际购物中心),珠江中路199号金鹰国际购物中心8层',
            '莱茵河,玉山镇横街38号',
            '川湘食府(玉龙路店),玉龙路99-8号',
            '卢米埃影城(金鹰国际购物中心),珠江中路199号金鹰国际购物中心8层',
            '梦幻4D动感体验馆,人民路93号诚泰国际商务广场4层',
            '玉龙广场,同丰西路与珠江北路交叉口北50米',
            '法兰泥美容养生会所(同丰路店),开法区同丰路玉龙路99号青江秀韵17栋101',
            '好乐迪(田林店),宜山路700号上海普天科技大楼4层',
            '7天酒店(揭阳阳美店),环市北路阳美玉都花园C2座',
            '闺蜜DIY,蓝城区阳美恒富花园3栋011号(七天酒店斜对面)',
            '爱尚网咖,阳美路玉都花园二楼',
            '西园影城(人民路西街店),人民路西街2号',
            '新世界影城(超华店),玉山镇白马泾路46号超华城市广场F4层',
            '灏景精品酒店,河堤路128号',
            '唱吧麦颂量贩KTV(北苑易事达店),北苑家园秋实街1号易事达购物休闲广场3层',
            '百丽宫影城(万象城店),吴中路1599号L501',
            '华士达影城(星宝店)(装修中),漕宝路星宝购物中心五楼',
            '时光掌纹餐饮清吧,兴善寺东街海港城A区A-65号',
            'taxx,巨鹿路158号B1层B1号',
            '新音符西餐酒吧,杨高南路蓝村路13号',
            '鼎响娱乐会所KTV,凯旋路2588号兴力达装饰城东区5层',
            '瑞虹新城·铭庭停车场(出入口),天宝路181弄',
            '瑞虹坊2区停车场,天宝路181号附近',
            '瑞虹新城·铭庭,天宝路181弄',
            '上海之夜,漕宝路400号明申商务广场1-4层',
            '瑞虹新城·铭庭停车场(出入口),天宝路181弄',
            '瑞虹坊二期停车场,天宝路181号瑞虹新城·铭庭',
            '上海瑞虹新城停车场,东沙虹港路与天虹路交叉口东南50米',
            '瑞虹坊2区停车场,天宝路181号附近',
            '上海瑞虹新城停车场,东沙虹港路与天虹路交叉口东南50米',
            '上海市徐汇区漕宝路36号靠近漕宝路(地铁站)',
            '瑞虹天地星星堂(西门),天宝路280弄瑞虹天地星星堂F1层',
            '瑞虹坊2区停车场,天宝路181号附近',
            'Tops,海平路19号外滩悦榕庄顶楼露台(近公平路)',
        ];
        $uid = Db::name('user')->where('is_new', 2)->column('id');
        $map['signuid'] = '';
        $map['c_time'] = $time = self::ctime('2018-01-01 00:00:00', '2018-01-31 23:59:59'); //1000
//         $map['c_time'] = self::ctime('2018-02-01 00:00:00', '2018-02-28 23:59:59');  //4000
//         $map['c_time'] = self::ctime('2018-03-01 00:00:00', '2018-03-31 23:59:59'); //3500
//         $map['c_time'] = self::ctime('2018-04-01 00:00:00', '2018-04-17 23:59:59'); //3100
//        视频单
        if ($isVod == 1) {
            $map['is_vod'] = 1;
            $map['type'] = 0;
            $map['order_sn'] = 'VOD' . date('Ymd', $time) . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $map['uid'] = $uid[rand(0, count($uid) - 1)];
            $map['ouid'] = '';
            $map['order_pay'] = rand(8, 100);
            $map['startime'] = $time;
            $map['duration'] = $vod_duration[rand(0, count($vod_duration) - 1)];

        } else { //线下单
            $map['is_vod'] = 0;
            $map['type'] = rand(1, 9);
            $map['order_sn'] = 'JSY' . date('Ymd', $time) . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $map['uid'] = $uid[rand(0, count($uid) - 1)];
            $map['ouid'] = '';
            $map['order_pay'] = $order_pay[rand(0, count($order_pay) - 1)];
            $map['address'] = $address[rand(0, count($address) - 1)];
            $map['location'] = '121.416724,31.175013';
            $map['startime'] = $time;
            $map['duration'] = $jsy_duration[rand(0, count($jsy_duration) - 1)];
        }

        $map['status'] = 3;
        $map['is_one'] = 0;
        return $map;
    }

    public function insertOrder()
    {
        $is_vod = [0, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 0];
        for ($i = 0; $i < 10; $i++) {
            $vod = $is_vod[rand(0, count($is_vod) - 1)];
            $map = self::orderInfo($vod);
            Db::name('order_list')->insert($map);
        }
        return '添加成功';
    }
}
