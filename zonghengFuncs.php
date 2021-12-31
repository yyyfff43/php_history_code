<?php
class Local_Funcs
{
    private static $gp = array(
        'token' => '',
        'p1' => 'channelType', // 渠道类型
        'p2' => 'channelId', // 渠道ID
        'p3' => 'installId', // 客户端生成的installId，标识设备的
        'p4' => 'modelName', // 厂商modelName
        'p5' => 'brand', // 基带brand
        'p6' => 'model', // 机型model
        'p7' => 'system', // 系统平台os 如android
        'p8' => 'sysVer', // 系统平台版本 如19
        'p9' => 'accessPoint', // 接入点 如wlan
        'p10' => 'clientVer', // 客户端版本号 如1.1.0
        'p11' => 'screenWidth', // 屏幕分辨率 宽 如1080
        'p12' => 'screenHeight', // 屏幕分辨率 高 如1920
        'p13' => 'uid', // 用户ID
        'p14' => 'verCode', // versionCode 如1100
        'p15' => 'appid',
        'p16' => 'token',
        'p17' => 'imei', // android传值：imei。IOS传值：idfa
        'p18' => 'first_channel', //第一次安装的渠道号
        'p19' => 'is_today_look',   //是否首次访问
        'xdebug' => 'xdebug',
        'appid' => 'appid'
    );

    public static function getAppKey()
    {
        $apps = array(
			'10000' => '6a4ddae25cce20f5e0ff3fe0702c9d4b',//H5书城
			'10001' => 'fb77fabe1bece8293f8f845d11ddbc49', //百度书城 android
			'10002' => 'af60cb16c4a315cd0755c97464107807',//百度书城 ios
			'10003' => '9086511d4a8d5e04873ec2a1bbb62668',//wap
			'10004' => '1086511d4a8d5e04873ec2a1bbb62667',//安卓书城搜索版
			'10005' => '2386511d4a8d5e04873ec2a1bbb62666',//IOS书城搜索版
			'10006' => 'e9dfc8060b6611e58cd4005056b243f1',//ANDROID书城单本书
			'10007' => '7926f2fe246d11e58830005056b243f1',//熊猫看书android版
			'10008' => 'acde371a246d11e58830005056b243f1',//熊猫看书ios版
			'10009' => 'bfb47dd6246d11e58830005056b243f1',//熊猫看书和阅读android版
			//'10010' => '70973a5c2e9711e58830005056b243f1',//app_ios_zongheng 和统计SDK统一
			//'10011' => '79fbbfb42e9711e58830005056b243f1',//app_andriod_zongheng
			'10012' => 'fbfa3fc5e4d3e5bee3899e54d9bd8125',//手助安卓版
            '10013' => '8045de3bae8163423ff0478dcc0897bb',// 读书巴士
			'10014' => '472822652d7a2089f09c05c13368228b',//淘小说
			'10015' => 'af3acbb4f16a11e5910c00163e01101f',//书城SDK测试
			'10016' => 'e85ce22607a99b7c56fcab0f19f6d7f3',//书城SDK 游讯
			'10017' => '51315d15975865cfc01082db98a8ef57',//书城SDK 乐读
			'30000' => '5d15de7ad3dc11e6830b5cf3fc0955f0',//熊猫看书IOS 小号APP [30000，4000]
			'10018' => 'f4ae4434d7c811e6a462005056b2643f',//熊猫看书小程序
            '30000' => '5d15de7ad3dc11e6830b5cf3fc0955f0',//熊猫看书IOS 小号APP [30000，4000)
			'50000' => 'e11d798df4e911e6ab0100163e01101f',// 新版安卓SDK
        );

        return $apps;
    }


    /**
     * 全局参数配置
     *
     * @return multitype:number
     */
    public static function getParams()
    {
        return self::$gp;
    }

    /**
     * 优化一下链接上的参数 对于支持cookie的手机把usid隐藏
     */
    public static function reBuildGp()
    {
        $arr = self::$gp;
        $newArr = array();
        foreach ($arr as $k => $v) {
            if (! (isset($_COOKIE[$v]) && $_COOKIE[$v] !== '')) {
                $newArr[$k] = $v;
            }
        }
        self::$gp = $newArr;
    }

    /**
     * 跟踪全局参数
     *
     * @param unknown $key
     * @return string
     */
    public static function getGpValue($key)
    {
        $value = '';
        $info = array();
        switch ($key) {
            case 'usid':
                $info = array(
                    'key' => 'usid',
                    'ckey' => USID_COOKIE_NAME,
                    'date' => '15 year',
                    'canOverload' => true
                ); // 是否可从URL上覆盖
                break;
            case 'uid':
                $info = array(
                    'key' => 'uid',
                    'ckey' => "WENXUEID",
                    'date' => '99 year',
                    'canOverload' => false
                );
                break;
            case 'v':
                $info = array(
                    'key' => 'v',
                    'ckey' => 'v',
                    'date' => '5 year',
                    'canOverload' => true
                );
                break;
        }
        
        // 先取URL上的值
        if (isset($_GET[$info['key']]) && trim($_GET[$info['key']])) {
            if ($info['key'] == 'uid' && isset($_GET['lfr'])) {
                // 从百度登录跳回来的
            } else {
                $currV = urldecode(trim($_GET[$info['key']]));
            }
        }
        
        // 取cookie中的值
        // if(isset($_COOKIE[$info['key']]) && $_COOKIE[$info['key']]){
        if (isset($_COOKIE[$info['ckey']])) {
            $oldV = $_COOKIE[$info['ckey']];
            if (isset($currV) && $currV) {
                if ($currV != $oldV) {
                    // 是否能覆盖
                    if ($info['canOverload']) {
                        // 修改
                        self::$gp[$info['key']] = $currV;
                        self::wx_setcookie($info['ckey'], $currV, $info['date']);
                    } else {
                        // 不处理
                        $currV = $oldV;
                    }
                }
            } else {
                $currV = $oldV;
            }
        } else {
            // echo '['.$info['key'] . ':' . $currV . ']';
            if (! isset($currV)) {
                switch ($key) {
                    case 'v':
                        $currV = self::initGPv();
                        break;
                    case 'uid':
                        $currV = self::initGPuid();
                        break;
                }
            }
            if (isset($currV)) {
                self::wx_setcookie($info['ckey'], $currV, $info['date']);
            }
        }
        $value = isset($currV) ? $currV : '0';
        return $value;
    }

    /**
     * 新用户初始化V
     *
     * @return Ambigous <string, number>
     */
    public static function initGPv()
    {
        $v = '';
        $uaInfo = self::checkUA();
        if ($uaInfo['isSmartyPhone']) {
            $v = 4;
        }
        if (! isset($v) && isset($_GET['bd_page_type'])) {
            $v = "0" == $_GET['bd_page_type'] ? 1 : 2;
        }
        if (! isset($v)) {
            $McpApi = Yaf_Registry::get('McpApi');
            $res = $McpApi->get_phone_info($_SERVER["HTTP_USER_AGENT"]);
            if (isset($res["phone_info"][0][7])) {
                $arr = explode("x", $res["phone_info"][0][7]);
                if (count($arr) == 2) {
                    $x = intval($arr[0]);
                    $v = $x > 0 && $x <= 176 ? 1 : 2;
                }
            }
        }
        // 默认到彩版
        $v = $v ? $v : 2;
        return $v;
    }

    /**
     * 获取访客ID
     *
     * @return string
     */
    public static function initGPuid()
    {
        $McpApi = Yaf_Registry::get('McpApi');
        $uids = $_SERVER['REMOTE_ADDR'];
        $uidarray = $McpApi->get_gen_baiduid($uids);
        $uid = $uidarray['baiduid'];
        Bingo_Log::pushNotice('newuid', 1);
        return $uid;
    }

    public static function formatUrl($url)
    {
        $preg = '%^(^[^#?]*)(\?[^#]*)?(#.*)?$%';
        preg_match($preg, $url, $m);
        return $m;
    }

    /**
     * 为手写的简易redirect补充全局参数
     */
    public static function formatRedirect($url, $p = array())
    {
        if ($url) {
            $arr = self::formatUrl($url);
            if (isset($arr[2])) {
                $arr[2] = str_replace('?', '', $arr[2]);
                parse_str($arr[2], $param);
                if ($param) {
                    foreach ($param as &$v) {
                        $v = urlencode($v);
                    }
                    $param = array_merge($param, $p);
                }
                $url = self::url($arr[1], $param);
                if (isset($arr[3]) && $arr[3]) {
                    $url .= $arr[3];
                }
            }
        }
        return $url;
    }

    /**
     * 添加全局参数
     *
     * @param unknown $url
     * url url地址
     * addParam 要传递的get参数
     * urlbase64 如果对外链url进行base64编码，传true
     * @return string
     */
    public static function url($url, $addParam = array(), $urlbase64 = false)
    {
        /* ------带有http参数的地址判断---这样就支持外连接了---------- */
        $G = Yaf_Registry::get('G');
        $outer_link = (substr($url, 0, 7) == 'http://') || (substr($url, 0, 7) == 'https:/');
        if ($outer_link) {

            if ($urlbase64) {
                $addParam['base64url'] = base64_encode($url);
            } else {
                if ($G['pp']['p7'] == 'ios') {
                    $url = urlencode($url);
                }
                $addParam["url"] = urlencode($url);
            }
            return self::url("/sys/log", $addParam);
        }
        /* ------带有http参数的地址判断------这样就支持外连接了------- */
        
        $currUrl = $_SERVER['REQUEST_URI'];
        
        $paramM = self::formatUrl($url);
        $currM = self::formatUrl($currUrl);
        $paramM[3] = isset($paramM[3]) ? $paramM[3] : ''; // #锚点
                                                          // 过滤一遍
        $currArr = $_currArr = $paramArr = array();
        if (isset($currM[2]) && $currM[2]) {
            $_currArr = self::getParamArr($currM[2]);
            foreach (self::getParams() as $k => $v) {
                if (isset($_currArr[$k])) {
                    $currArr[$k] = $_currArr[$k];
                } else {
                    if (isset($G[$k]) && $G[$k]) {
                        $currArr[$k] = $G[$k];
                    }
                }
            }
        }
        if (isset($paramM[2]) && $paramM[2]) {
            $paramArr = self::getParamArr($paramM[2]);
        }
        
        if ($addParam) {
            $currArr = array_merge($currArr, $addParam);
        }
        
        // 合并
        // $p = array_merge($paramArr, $currArr);
        $p = array_merge($currArr, $paramArr);
        $p = array_map("htmlspecialchars", $p);
        $s = self::paramToString($p);
        $s = $p ? '?' . $s : $s;
        $u = $paramM[1] . $s . $paramM[3];
        
        return $u;
    }

    /**
     * 数组变字符串
     *
     * @param unknown $p
     * @return string
     */
    public static function paramToString($p)
    {
        $s = '';
        if ($p) {
            foreach ($p as $k => $v) {
                $s .= $k . '=' . $v . '&';
            }
            $s = substr($s, 0, - 1);
        }
        return $s;
    }

    /**
     * 格式化URL里的参数成数组
     *
     * @param unknown $string
     * @return multitype:multitype:
     */
    public static function getParamArr($string)
    {
        $return = array();
        if ($string) {
            $string = substr($string, 0, 1) == '?' ? substr($string, 1) : $string;
            // $string = str_replace('&amp;', '&', $string);
            $arr = explode('&', $string);
            foreach ($arr as $k => $v) {
                // list($pk, $pv) = explode('=', $v);
                $arr = explode('=', $v);
                if (isset($arr[0]) && $arr[0]) {
                    $arr[1] = isset($arr[1]) && $arr[1] ? $arr[1] : '';
                    $return[$arr[0]] = $arr[1];
                }
                // $return[$pk] = $pv;
            }
        }
        return $return;
    }

    /**
     * 用户输入
     */
    public static function is_utf8($str)
    {
        $c = 0;
        $b = 0;
        $bits = 0;
        $len = strlen($str);
        for ($i = 0; $i < $len; $i ++) {
            $c = ord($str[$i]);
            if ($c > 128) {
                if (($c >= 254)) {
                    return false;
                } elseif ($c >= 252) {
                    $bits = 6;
                } elseif ($c >= 248) {
                    $bits = 5;
                } elseif ($c >= 240) {
                    $bits = 4;
                } elseif ($c >= 224) {
                    $bits = 3;
                } elseif ($c >= 192) {
                    $bits = 2;
                } else {
                    return false;
                }

                if (($i + $bits) > $len) {
                    return false;
                }
                while ($bits > 1) {
                    $i ++;
                    $b = ord($str[$i]);
                    if ($b < 128 || $b > 191) {
                        return false;
                    }

                    $bits --;
                }
            }
        }
        return true;
    }

    public static function safeEncoding($string, $outEncoding = 'UTF-8')
    {
        if (self::is_utf8($string)) {
            return $string;
        } else {
            $guessCharSet = mb_detect_encoding(urldecode($string), array(
                "GB2312",
                "GBK"
            ), true);
            if ("EUC-CN" == $guessCharSet) {
                return iconv("GB2312", $outEncoding, $string);
            } else {
                if ("CP936" == $guessCharSet) {
                    return iconv("GBK", $outEncoding, $string);
                } else {
                    return $string;
                }
            }
        }
    }

    public static function get_cookie_domain()
    {
        static $domain = false;
        if ($domain === false) {
            $domain = '';
            $candidates = array(
                '.duoku.com',
                'shucheng.baidu.com'
            );
            $host = $_SERVER['SERVER_NAME'];
            foreach ($candidates as $candidate) {
                if (stripos($host, $candidate) !== false) {
                    $domain = $candidate;
                    break;
                }
            }
        }
        return $domain;
    }

    public static function wx_setcookie($name, $value = null, $expire = 0)
    {
        if (is_string($expire)) {
            $expire = $expire ? strtotime($expire) : 0;
        }
        if ($value === null) {
            self::wx_delcookie($name);
        } else {
            setcookie($name, $value, $expire, '/', self::get_cookie_domain());
        }
    }

    public static function wx_delcookie($name)
    {
        setcookie($name, '', strtotime('-10 year'), '/', self::get_cookie_domain());
    }

    public static function uuid()
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);
        return $uuid;
    }

    public static function parsePlugData($data, $idxArr, $p = '|')
    {
        $return = array();
        $data = trim($data);
        $data = str_replace("\r\n", "\n", $data);
        // 转化列表成数组
        $tpcfgListArray = explode("\n", $data);
        $tpcfgListArray = count($tpcfgListArray) == 1 && ! $tpcfgListArray[0] ? array() : $tpcfgListArray;
        
        if ($tpcfgListArray) {
            foreach ($tpcfgListArray as $k => $v) {
                $temp = array();
                //看看是不是QA环境标识
                $nodes = substr($v, 0, 1) == '#';
                if ($nodes) {
                    //非QA环境就略过
                    if (!in_array($_SERVER['SERVER_ADDR'], array(
                            '10.3.138.32',
                            '10.3.138.49',
                            '10.3.138.51',
                            '10.3.138.60'
                    ))) {
                        continue;
                    }
                    //去除首字母标识
                    $v = substr($v, 1);
                }
                $t = explode($p, $v);
                foreach ($t as $kk => $vv) {
                    // $temp['idx'] = $k;
                    if (! isset($idxArr[$kk])) {
                        continue;
                    }
                    if (!isset($temp[$idxArr[$kk]])) {
                        $temp[$idxArr[$kk]] = '';
                    }
                    
                    $temp[$idxArr[$kk]] = ($vv == '?' ? '' : htmlspecialchars_decode($vv));
                }
                $return[] = $temp;
            }
        }
        return $return;
    }

    /**
     * 截取字符串
     * @param unknown $String
     * @param unknown $Length
     * @return unknown|string
     * @return boolean point true 加三个点（默认） false 不加三个点
     */
    public static function subString($String, $Length, $point = true)
    {
        if (mb_strwidth($String, 'UTF8') <= $Length) {
            return $String;
        } else {
            $I = 0;
            $len_word = 0;
            while ($len_word < $Length) {
                $StringTMP = substr($String, $I, 1);
                if (ord($StringTMP) >= 224) {
                    $StringTMP = substr($String, $I, 3);
                    $I = $I + 3;
                    $len_word = $len_word + 2;
                } elseif (ord($StringTMP) >= 192) {
                    $StringTMP = substr($String, $I, 2);
                    $I = $I + 2;
                    $len_word = $len_word + 2;
                } else {
                    $I = $I + 1;
                    $len_word = $len_word + 1;
                }
                $StringLast[] = $StringTMP;
            }
            /* raywang edit it for dirk for (es/index.php) */
            if (is_array($StringLast) && ! empty($StringLast)) {
                $StringLast = implode("", $StringLast);
                if ($point) {
                    $StringLast .= "...";
                }
            }
            return $StringLast;
        }
    }

    /**
     * 字符串过滤
     *
     * @param array $param
     *            words 需要过滤的字符串
     *            type 类型。1前后过滤，默认1
     * @return string
     */
    public static function filt_words($param = array())
    {
        // 要过滤的内容
        $filter = array(
            " "
        );
        $words = isset($param["words"]) ? $param["words"] : "";
        // 类型 1前后过滤，
        $type = isset($param["type"]) ? $param["type"] : 1;
        if ($type == 1) {
            $words = trim($words, implode("", $filter));
        }
        if ($type == 2) {
            $filter[] = "\r\n";
            $filter[] = "\n";
            $filter[] = "\r";
            $words = str_replace($filter, "", $words);
        }
        return $words;
    }

    public static function checkUA()
    {
        $a = array();
        $currUA = array();
        $ua = @$_SERVER["HTTP_USER_AGENT"];
        $currUA['iphone'] = stripos(strtolower($ua), "iphone") !== false || stripos($ua, "iphone") !== false;
        $currUA['ipad'] = stripos(strtolower($ua), "ipad") !== false || stripos($ua, "ipad") !== false;
        $currUA['ipod'] = stripos(strtolower($ua), "ipod") !== false || stripos($ua, "ipod") !== false;
        $a['isIos'] = ($currUA['iphone'] || $currUA['ipad'] || $currUA['ipod']);
        $a['isAndroid'] = (
            stripos(strtolower($ua), "android") !== false ||
            stripos($ua, "Linux; U; adr") !== false ||
            stripos($ua, "android") !== false
        );
        $a['isSmartyPhone'] = $a['isIos'] || $a['isAndroid'];
        return $a;
    }

    /**
     * 章节价格
     *
     * @param unknown $words
     * @return number 价格
     */
    public static function getChapterPrice($words)
    {
        return ceil($words / 1000) * 5;
    }

    /**
     * 书的价格
     *
     * @param number $book_size
     * @return number
     */
    public static function getBookPrice($book_size = 0)
    {
        $price = 200;
        if (100 <= $book_size && $book_size < 300) {
            $price = 400;
        } elseif (300 <= $book_size && $book_size < 400) {
            $price = 600;
        } elseif (400 <= $book_size) {
            $price = 800;
        }
        return $price;
    }

    /**
     * 封面地址
     *
     * @param unknown $str
     * @return string
     */
    public static function fBkImg($str, $type = '')
    {
        //$G = Yaf_Registry::get('G');
        //$currDev = $G['currDev'];
        // $host = $currDev == 'online' ? 'http://img.m.baidu.com/novel/' : 'http://11.11.0.72:8090/';
        $host = $type == 'cdn' ?
            'https://img.xmkanshu.com/operateimg/novel/' :
            'https://img.xmkanshu.com/novel/';
        return $str ? (in_array(substr($str, 0, 7), ['http://', 'https:/']) ? $str : $host . $str) : '';
    }

    //获取音频封面地址
    public static function audioImg($str)
    {
        $G = Yaf_Registry::get('G');

        $host = $G['currDev'] == 'qa' ?
            'https://img.xmkanshu.com/novel/' :
            'https://img.xmkanshu.com/novel/';
        return $str ? (in_array(substr($str, 0, 7), ['http://', 'https:/']) ? $str : $host . $str) : '';
    }

    //漫画图片地址
    public static function comicImg($str, $c = true)
    {
        $G = Yaf_Registry::get('G');
        
        $host = ( $G['currDev'] == 'qa') ? 'http://qa.static.xmkanshu.cn/upload/' : 'https://static.xmkanshu.com/upload/';
        if ($c) {
            $host .= '/a_img/';
        }
        return $str ? (in_array(substr($str, 0, 7), ['http://', 'https:/']) ? $str : $host . $str) : '';
    }

    public static function getPrice($num)
    {
        $r = 200;
        if ($num > 200 && $num <= 500) {
            $r = 500;
        }
        if ($num > 500 && $num <= 1000) {
            $r = 1000;
        }
        if ($num > 1000 && $num <= 2000) {
            $r = 2000;
        }
        if ($num > 2000 && $num <= 5000) {
            $r = 5000;
        }
        if ($num > 5000 && $num <= 10000) {
            $r = 10000;
        }

        return $r;
    }

    /**
     * 计时阅读充值价格
     *
     * @param unknown $num
     */
    public static function gettimePrice($num)
    {
        $r = 200;
        if ($num >= 100 && $num < 200) {
            $r = 200;
        }
        if ($num >= 200 && $num < 400) {
            $r = 500;
        }
        if ($num >= 400 && $num < 1000) {
            $r = 1000;
        }
        if ($num >= 1000 && $num < 2000) {
            $r = 2000;
        }

        return $r;
    }

    /**
     * VIP包月计算价格 运营商最低充值价格600分
     */
    public static function getBranch($num)
    {
        $r = 600;
        switch ($num) {
            case 6:
                $r = (int) 600;
                break;
            case 7:
                $r = (int) 700;
                break;
            case 8:
                $r = (int) 800;
                break;
            case 9:
                $r = (int) 900;
                break;
            case 10:
                $r = (int) 1000;
                break;
            case 15:
                $r = (int) 1500;
                break;
        }
        return $r;
    }

    /**
     * 用于互相转码
     *
     * @param unknown $to_encoding
     * @param unknown $gbk
     * @param unknown $utf8
     * @return unknown|string
     */
    public static function endecoding($to_encoding, $gbk, $utf8)
    {
        if (is_string($to_encoding)) {
            return mb_convert_encoding($to_encoding, $gbk, $utf8);
        }
        if (is_array($to_encoding)) {
            foreach ($to_encoding as $key => $value) {
                if (is_array($value)) {
                    $to_encoding[$key] = self::endecoding($value, $gbk, $utf8);
                } else {
                    $to_encoding[$key] = mb_convert_encoding($value, $gbk, $utf8);
                }
            }
            return $to_encoding;
        }
        return '';
    }

    /**
     * 处理白名单302及跳回, 返回userinfo
     *
     * @param unknown $param
     */
    public static function wlDomain($param)
    {
        $key = '637eb58f-c7e7-9283-f2c5-d3d0f7cc9d61';
        $appid = 'wx100';
        $time = time();
        $url = 'http://wap.07890.com/wap/?pageid=Ngfem1ha';
        // $url = 'http://otest.duoku.com/wap/?pageid=Ngfem1ha';
        $sign = $mysign = md5($appid . $time . $key);
        $url = $url . '&appid=' . $appid . '&sign=' . $sign .
            '&type=server_back&time=' . $time . '&uid=' . $param['uid'];
        /*
         * $host_port = $_SERVER["HTTP_HOST"];
         * $pos_i = strrpos($host_port,":");
         * $host_port = ($pos_i!== false)?substr($host_port, $pos_i):"";
         * $site_url = 'http://'. $_SERVER['SERVER_NAME'] . $host_port;
         */
        $site_url = self::get_true_site();
        // 测试机-正式后在注释掉
        // $site_url = "http://itest.zhwenxue.com:8080";
        
        $mobile = $param["mobile"];
        unset($param["mobile"]);
        
        $retUrl = $site_url . '/?fromwl=1&' . http_build_query($param);
        $notifyUrl = $site_url . '/sys/wlnofity?is_notify=notify&uid=' . $param['uid'];
        $url .= '&url=' . urlencode($retUrl) . '&notifyUrl=' . urlencode($notifyUrl);
        $requset_url = $_SERVER["REQUEST_URI"];
        $requset_url = explode("?", $requset_url);
        $req_url = $requset_url[0];
        // print_r($req_url);exit;
        $pageUrl = array(
            "",
            "/",
            "/index",
            "/index/index"
        );
        // 百度蛛蛛的UA
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        
        $let_go = (strpos($agent, 'baiduspider') === false) &&
            (strpos($agent, 'baidu+transcoder') === false) &&
            in_array($req_url, $pageUrl);
        // 跳到白名单 取号完成跳回
        if (isset($_GET['fromwl'])) {
            // /跳回处理
            // 移动 代注册 白名单登录
            self::wx_setcookie('wlgot', "1", '6 hour');
            
            if ($mobile) {
                $UserApi = Yaf_Registry::get('UserApi');
                $user_reg = $UserApi->auto_register(array(
                    'Phone' => $mobile,
                    "rType" => 2
                ));
                
                if (isset($user_reg["res"]) && ! empty($user_reg["res"])) {
                    $user_info = $user_reg["res"];
                    return $user_info;
                }
            }
            return array();
        } else {
            // if (true)
            if (! isset($_COOKIE['wlgot']) && empty($mobile) && $let_go) {
                header("Location: $url");
                exit();
            }
            return array();
        }
    }

    public static function saveWLInfo($param)
    {
        if (! isset($param['mobile']) || empty($param['mobile'])) {
            return;
        }

        $redis = DataProvider::getDataProvider('COMMON_MIX_USE');
        switch ($param['type']) {
            case 2: // 联通白名单
                $key = 'WL:info:uni.' . $param['uid'];
                break;
            case 3: // 电信白名单
                $key = 'WL:info:ct.' . $param['uid'];
                break;
            default: // 移动白名单
                $key = 'WL:info:cmcc.' . $param['uid'];
                break;
        }
        $redis->setex($key, 3600 * 12, $param['mobile']);
    }

    public static function getWLInfo($param)
    {
        $mobile = '';
        $redis = DataProvider::getDataProvider('COMMON_MIX_USE');
        $return = array();
        $userWlCarrname = 0;
        switch ($param['type']) {
            case 2: // 联通白名单
                $key = 'WL:info:uni.' . $param['uid'];
                $userWlCarrname = 2;
                break;
            case 3: // 电信白名单
                $key = 'WL:info:ct.' . $param['uid'];
                $userWlCarrname = 3;
                break;
            default: // 移动白名单
                $key = 'WL:info:cmcc.' . $param['uid'];
                $userWlCarrname = 1;
                break;
        }
        $mobile = $redis->get($key);
        if (! empty($mobile)) {
            $return["userWlCarrname"] = $userWlCarrname;
            $return["mobile"] = $mobile;
        }
        return $return;
    }

    /**
     * 根据版本来自动切换url
     *
     * @param unknown $url
     * @return mixed|unknown
     */
    public static function getTrueUrl($url)
    {
        $v = self::getGpValue("v");
        if ($v == "2") {
            // 已经转码了就不再转了
            if (strpos($url, '&amp;') === false) {
                return str_replace("&", "&amp;", $url);
            } else {
                return $url;
            }
        }
        return $url;
    }

    /**
     * 根据联盟id转换cm值
     *
     * @param unknown $url
     * @return unknown|string
     */
    public static function switchUrlFromFrToCm($url)
    {
        // //fr_name=>cm_value
        // $fr_param = array("1"=>"10086","2"=>"10010");
        // require_once APPPATH . 'libraries/PlugCenter.php';
        $fr = isset($_GET['fr']) && trim($_GET['fr']) ? trim($_GET['fr']) : '';
        $G = Yaf_Registry::get('G');
        $fr = isset($G["fr"]) ? $G["fr"] : $fr;
        $fr_param = Yaf_Registry::get('FR_PRARAM_PLUG_69');
        if (empty($fr_param)) {
            $param = array(
                "_plug" => array(
                    "id" => 69,
                    "name" => "根据联盟移动CM替换"
                )
            );
            $plugInfo = $param["_plug"];
            $plugInfo["method"] = __CLASS__ . "->" . __FUNCTION__;
            $data = PlugCenter::getData($plugInfo);
            if (empty($data)) {
                return $url;
            }
            $ad_info = self::parsePlugData($data["fr_data"], array(
                'frid',
                'cm_value'
            ));
            $fr_param = array();
            foreach ($ad_info as $vk => $v) {
                $fr_param[$v["frid"]] = $v["cm_value"];
            }
            Yaf_Registry::set('FR_PRARAM_PLUG_69', $fr_param);
        }
        if (empty($fr)) {
            return $url;
        }
        $cm_value = key_exists($fr, $fr_param) ? $fr_param[$fr] : "";
        
        if (empty($cm_value)) {
            return $url;
        }
        $url_arr = explode("?", $url);
        if (isset($url_arr[1])) {
            $query = $url_arr[1];
            parse_str($query, $parts);
            $uri = "";
            foreach ($parts as $k => $v) {
                if (strpos($k, "?cm") !== false || $k == "cm") {
                    $v = $cm_value;
                }
                $uri .= $k . "=" . $v . "&";
            }
            $uri = trim($uri, "&");
            $uri = $url_arr[0] . "?" . $uri;
            return $uri;
        } else {
            return $url;
        }
    }

    /**
     * 替换搜索结果红色
     *
     * @param unknown $str
     * @return mixed
     */
    public static function hilight_render($str)
    {
        $str = str_replace("\2", "<font color='red'>", $str);
        $str = str_replace("\3", "</font>", $str);
        return $str;
    }

    /**
     * 移动基地的阅读地址
     *
     * @param unknown $bkid
     * @param unknown $crid
     */
    public static function getCmreadReadUrl($bkid, $crid, $fr = 1, $vt = 2)
    {
        $G = Yaf_Registry::get('G');
        $cm = $G['fr'] != 'aladdin_cmread' ? 'M3140037' : 'M3140031';
        $p = $G['v'] == 4 ? '&' : '&amp;';
        $p = '&';
        return $url = 'http://wap.cmread.com/r/' . $bkid . '/' . $crid .
            '/index.htm?cm=' . $cm . $p . 'fr=' . $fr . $p . 'vt=' . $vt;
    }

    /**
     * 书币转换成运营商需要支付的价钱
     *
     * @param string $price
     *            书币
     *
     */
    public static function payCarrMoney($price)
    {
        $carr_price = 200;
        if ($price > 100) {
            $carr_price = ($price * 2);
        }
        
        return $carr_price;
    }

    /**
     * 代替结束log日志
     */
    public static function finishBuildNotice()
    {
        Bingo_Log::pushNotice('uip', Bingo_Http_Ip::ip2long(Bingo_Http_Ip::getConnectIp()));
        Bingo_Log::pushNotice('url', strip_tags(Bingo_Http_Request::getServer('REQUEST_URI')));
        Bingo_Log::pushNotice('refer', strip_tags(Bingo_Http_Request::getServer('HTTP_REFERER')));
        Bingo_Log::pushNotice('agent', urlencode(strip_tags(Bingo_Http_Request::getServer('HTTP_USER_AGENT'))));
        Bingo_Log::pushNotice('IP', $_SERVER["REMOTE_ADDR"]);
        Bingo_Log::pushNotice('SERVER_NAME', $_SERVER['SERVER_NAME']);
        Bingo_Log::pushNotice('IP', Bingo_Http_Ip::getConnectIp());
        if (isset($_GET['bck']) && $_GET['bck']) {
            Bingo_Log::pushNotice('bck', $_GET['bck']);
        }
        if (isset($_GET['pos']) && $_GET['pos']) {
            Bingo_Log::pushNotice('pos', $_GET['pos']);
        }
        if (isset($_GET['urbid']) && $_GET['urbid']) {
            Bingo_Log::pushNotice('urbid', $_GET['urbid']);
        }
        Bingo_Log::pushNotice('finishBingoLog', 'ok');
        Bingo_Log::buildNotice();
    }

    /**
     * 设置全局插件广告类型
     * $type 2:wap 4:H5
     */
    public static function setGlobalAdType()
    {
        $G = Yaf_Registry::get('G');
        if (isset($G['adTypes'])) {
            return false;
        }
        $ad_param = array(
            '_blockId' => 0,
            '_plug' => array(
                'name' => '全局广告类型变量',
                'id' => 69
            )
        );
        $plugInfo = isset($ad_param['_plug']) ? $ad_param['_plug'] : array();
        $plugInfo["method"] = __CLASS__ . "->" . __FUNCTION__;
        $data = PlugCenter::getData($plugInfo);
        $ad_type = array();
        if (! empty($data)) {
            $ad_wap = Funcs::parsePlugData($data['fr_wap'], array(
                'title'
            ));
            foreach ($ad_wap as $key => $val) {
                $ad_type[] = $val['title'];
            }
            $ad_h5_type = Funcs::parsePlugData($data['fr_Plug_h5'], array(
                'type',
                'id'
            ));
            $ad_h5 = array();
            foreach ($ad_h5_type as $key => $val) {
                $ad_h5[$val['type']] = $val['id'];
            }
            $ad_Global = array(
                'fr_ad_wap' => $ad_type,
                'fr_ad_h5' => $ad_h5
            );
            $G['adTypes'] = $ad_Global;
        }
        Yaf_Registry::set('G', $G);
    }

    /**
     * 获取当前正确的域名
     *
     * @return string
     */
    public static function get_true_site()
    {
        $host_port = $_SERVER["HTTP_HOST"];
        $pos_i = strrpos($host_port, ":");
        $host_port = ($pos_i !== false) ? substr($host_port, $pos_i) : "";
        if (self::is_https()) {
            $site_url = 'https://' . $_SERVER['SERVER_NAME'] . $host_port;
        } else {
            $site_url = 'http://' . $_SERVER['SERVER_NAME'] . $host_port;
        }
        return $site_url;
    }

    public static function is_https()
    {
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
            return true;
        } elseif (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') {
            return true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }
        return false;
    }

    /**
     * 检验必需登陆的url
     *
     * @return string
     */
    public static function check_must_login()
    {
        $uri = @$_SERVER['REQUEST_URI'];
        
        if (empty($uri)) { // 没有请求url就不需要判断了
            return true;
        }
        $aurl = parse_url($uri);
        $uri_path = isset($aurl["path"]) && ! empty($aurl["path"]) ? $aurl["path"] : "";
        $uri_path = strtolower(trim($uri_path, "/"));
        // 所有需要检测的地址
        //
        $must_check_page = array(
            "profile",
            "profile/index",
            "profile/notes",
            "profile/consume",
            "profile/phone",
            "profile/change",
            "profile/changepwd",
            "profile/readhelp",
            "profile/binding",
            "profile/solutiontie",
            "profile/checkcode",
            "profile/checkcode",
            "profile/relieve_ajax",
            "profile/wapbind",
            "profile/bindsms",
            "profile/relievewap",
            "billing",
            "billing/index"
        );
        if (! in_array($uri_path, $must_check_page)) { // 没有请求url就不需要判断了
            return true;
        }
        $G = Yaf_Registry::get('G');
        if (! isset($G["userInfo"]) || empty($G["userInfo"])) {
            $redirect = str_replace("&amp;", "&", $uri);
            $redirect = urlencode($redirect);
            $login_url = self::url("/login/logins", array(
                "redirect" => $redirect
            ));
            $login_url = str_replace("&amp;", "&", $login_url);
            header("location:" . $login_url);
            self::finishBuildNotice();
            exit();
        }
        return true;
    }

    /**
     * 获取充值结果
     * 对用户短信验证码提交接口(/usercenter/recharge/submit_verify_code)的返回结果进行处理
     *
     * @param array $result
     *            操作结果
     * @return string 错误信息，为空字符串时表示成功，格式为："code|message"
     */
    public static function getRechargeResult($result)
    {
        if ($result['status'] == 0 && ($result['res']['code'] == '0' || $result['res']['code'] == '200')) { // 成功
            $error = '';
        } elseif ($result['status'] == 0) { // 失败，业务层错误
                                            // 目前wiki上对res.code的说明尚需经历时间的考验
            switch ($result['res']['code']) {
                case '2001': // 超月消费上限额度
                case '2002': // 超日消费上限额度
                    $error = '21|已超出消费限额';
                    break;
                case '1':
                case '2003':
                    $error = '10|验证码错误';
                    break;
                case '2004': // 计费金额错误
                default:
                    $error = '99|失败';
                    break;
            }
        } else { // 失败，平台层错误
            switch ($result['status']) {
                case '20902':
                    $error = '10|验证码错误';
                    break;
                case '20903': // 手机号已被运营商锁定
                case '20915': // 手机号已被运营商列入黑名单
                    $error = '20|手机号已被运营商限制';
                    break;
                case '20905': // 手机号超过日消费限额
                case '20906': // 手机号超过月消费限额
                case '20907': // 用户名超过日消费限额
                case '20908': // 用户名超过月消费限额
                case '20909': // 同一设备为不同账号充值超过上限
                case '20910': // 同一手机号为不同用户充值超过上限
                case '20911': // 同一个手机号为不同用户充值超过上限
                    $error = '21|手机号已超出运营商的消费限额';
                    break;
                case '20912': //
                    $error = '30|手机号余额不足';
                    break;
                default: //
                    $error = '99|失败';
                    break;
            }
        }
        
        return $error;
    }

    /**
     * 获取客户端（真实）IP
     *
     * @return string|NULL 客户端IP，失败则返回NULL
     */
    public static function getClientIp()
    {
        $keys = array(
            //'HTTP_CLIENT_IP', // 待了解，未在PHP官方手册中发现$_SERVER中含有该项
            'HTTP_X_FORWARDED_FOR',
            // 'HTTP_X_FORWARDED', // 待了解，未在PHP官方手册中发现$_SERVER中含有该项
            // 'HTTP_X_CLUSTER_CLIENT_IP', // 待了解，未在PHP官方手册中发现$_SERVER中含有该项
            // 'HTTP_FORWARDED_FOR', // 待了解，未在PHP官方手册中发现$_SERVER中含有该项
            // 'HTTP_FORWARDED', // 待了解，未在PHP官方手册中发现$_SERVER中含有该项
            'REMOTE_ADDR'
        );
        
        foreach ($keys as $key) {
            if (array_key_exists($key, $_SERVER)) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    // 会过滤掉保留地址和私有地址段的IP，例如"127.0.0.1"会被过滤
                    if (filter_var(
                        $ip,
                        FILTER_VALIDATE_IP,
                        FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                    )) {
                        // 记录log，便于上线后观察获取到的IP是否正常
                        Bingo_Log::pushNotice('scClientIp', $ip);
                        return $ip;
                    }
                }
            }
        }
        
        return '';
    }

    /**
     * 获取用户昵称
     *
     * @param string $nickName
     *            昵称
     * @param integer $userId
     *            用户ID
     * @return string 用户昵称
     * @author SC
     */
    public static function getUserNickName(
        $nickName,
        $userId,
        $thirdName = false,
        $bindPhone = false,
        $pandaUserName = false,
        $loginType = 'default'
    ) {
        if ($loginType == 'panda' || $loginType == 'pandabaidu') {
            $userId = $thirdName ? $thirdName : $userId;
        }
        
        if ($nickName == '') {
            return "书友{$userId}"; // 昵称为空
        }
                                    
        // 弃用一些使用特殊前缀的昵称
        $nickPrefixes = array(
            'MR_',
            'Mobile_',
            'MR1_',
            'MR2_',
            'Baidu_',
            'SP_',
            'MCU_'
        );
        $limit_nick = false;
        $limit_third = false;
        foreach ($nickPrefixes as $nickPrefix) {
            if (stripos($nickName, $nickPrefix) === 0) {
                $limit_nick = true;
            }
            if (stripos($thirdName, $nickPrefix) === 0) {
                $limit_third = true;
            }
        }
        // 绑定手机还是要显示手机号的
        $bindPhone = ! empty($bindPhone) ? substr_replace($bindPhone, '****', 3, 4) : "";
        $last_nick = empty($bindPhone) ? "书友{$userId}" : $bindPhone;
        $nickName = $limit_nick ? ($limit_third ? $last_nick : $thirdName) : $nickName;
        return $nickName;
    }
    
    /**
     * 获取用户昵称
     * @param array $user 用户信息
     * @return string 昵称
     * @author SC
     */
    public static function getNickByUser($user)
    {
        $nickName = isset($user['nick']) ? $user['nick'] : '';
        $userId = isset($user['uid']) ? $user['uid'] : '';
        $thirdName = isset($user['third_name']) ? $user['third_name'] : '';
        $bindPhone = isset($user['bind_phone']) ? $user['bind_phone'] : '';
        $loginType = isset($user['login_type']) ? $user['login_type'] : 'default';
        
        if (($loginType == 'panda') || ($loginType=='pandabaidu')) {
            //在新熊猫后端注册的用户 thridName会用设备号标识，这种情况应该处理一下 应该显示用户ID
            if (preg_match('/[0-9a-z]{32}/i', $thirdName)) {
                $thirdName = '';
            }
            $userId = $thirdName ? $thirdName : $userId;
        }
        
        if ($nickName == '') {
            return "书友{$userId}"; // 昵称为空
        }
    
        // 弃用一些使用特殊前缀的昵称
        $nickPrefixes = array(
            'MR_',
            'Mobile_',
            'MR1_',
            'MR2_',
            'Baidu_',
            'SP_',
            'MCU_'
        );
        $limit_nick = false;
        $limit_third = false;
        foreach ($nickPrefixes as $nickPrefix) {
            if (stripos($nickName, $nickPrefix) === 0) {
                $limit_nick = true;
            }
            if (stripos($thirdName, $nickPrefix) === 0) {
                $limit_third = true;
            }
        }
        // 绑定手机还是要显示手机号的
        $bindPhone = ! empty($bindPhone) ? substr_replace($bindPhone, '****', 3, 4) : "";
        $last_nick = empty($bindPhone) ? "书友{$userId}" : $bindPhone;
        $nickName = $limit_nick ? ($limit_third ? $last_nick : $thirdName) : $nickName;
        
        return $nickName;
    }
    
    public static function getCovrSize($width)
    {
        switch ($width) {
            case ($width <= 320):
                return 2;
                break;
            case ($width > 320 && $width < 640):
                return 3;
                break;
            case ($width > 640):
                return 4;
                break;
            default:
                return 3;
                break;
        }
    }

    /**
     * 获取书籍简介截取的长度
     *
     * 主要用于书籍列表页（如分类列表等）
     * 其返回值依据实验得出，可根据情况再进行调整
     *
     * @param integer $type 类型
     * @return integer 书籍简介截取的长度
     * @author SC
     */
    public static function getBookDescLength($type = 1)
    {
        $G = Yaf_Registry::get('G');
        $width = (int)$G['pp']['p11'];  // 当前设备屏幕分辨率（宽度）
        
        $typeLengths = array(
            // 熊猫看书APP
            // 排行首页男频热销榜、男频人气榜
            // 女频热销榜等榜单模块首本书籍推荐语或书籍简介
            1 => array(60, 58, 56, 54, 44, 42, 40, 24, 18),
        );
        
        if ($width >= 1200) {
            $length = $typeLengths[$type][0];
        } elseif ($width >= 1080) {
            $length = $typeLengths[$type][1];
        } elseif ($width >= 800) {
            $length = $typeLengths[$type][2];
        } elseif ($width >= 720) {
            $length = $typeLengths[$type][3];
        } elseif ($width >= 640) {
            $length = $typeLengths[$type][4];
        } elseif ($width >= 480) {
            $length = $typeLengths[$type][5];
        } elseif ($width >= 320) {
            $length = $typeLengths[$type][6];
        } elseif ($width >= 240) {
            $length = $typeLengths[$type][7];
        } else {
            $length = $typeLengths[$type][8];
        }
        
        return $length;
    }

    /**
     * 检测是否为有效的手机号码
     *
     * @param string $phone
     *      待检测手机号码
     * @param bool  $returnType
     *      是否返回号码类型
     *
     * @return int
     *          0--不是手机号格式 或者不在规定号段
     *          1--移动
     *          2--联通
     *          3--电信
     * @author shaohua
     */
    public static function isMobilePhone($phone, $returnType = false)
    {
        if (!preg_match('/^(13[0-9]|14[1|3|5|7]|15[0-9]|17[0|1|3|5|6|7|8]|18[0-9])\d{8}$/', $phone)) {
            return 0;
        }

        //如果不需要判断手机号运营商 则返回1
        if (!$returnType) {
            return 1;
        }

        $yd = [
            '134', '135', '136', '137', '138', '139', '141',
            '143', '147', '150', '151', '152', '154', '157',
            '158', '159', '178', '182', '183', '184', '187',
            '188', '1703', '1704', '1705', '1706',
        ];

        $lt = [
            '130', '131', '132', '145', '155', '156', '171',
            '175', '176', '185', '186', '1707', '1708', '1709',
        ];

        $dx = [
            '133', '153', '173', '177', '180', '181', '189',
            '1700', '1701', '1702',
        ];

        $section = substr($phone, 0, 3);

        //如果是170号段，则取前4位
        if ('170' == $section) {
            $section = substr($phone, 0, 4);
        }

        if (in_array($section, $yd)) {
            return 1;
        }

        if (in_array($section, $lt)) {
            return 2;
        }

        if (in_array($section, $dx)) {
            return 3;
        }

        return 0;
    }

    /**
     * 去除字符中制表符
     */
    public static function deleteHtml($str)
    {
        $str = trim($str); // 清除字符串两边的空格
        $str = strip_tags($str, ""); // 利用php自带的函数清除html格式
        // 使用正则表达式替换内容，如：空格，换行，并将替换为空。
        $str = preg_replace("/\t/", "", $str);
        $str = preg_replace("/\r\n/", "", $str);
        $str = preg_replace("/\r/", "", $str);
        $str = preg_replace("/\n/", "", $str);
        $str = preg_replace("/ /", "", $str);
        $str = preg_replace("/  /", "", $str); // 匹配html中的空格
        return trim($str); // 返回字符串
    }
    
    // Anti_SQL Injection, escape quotes
    public static function filter($content)
    {
        if (! get_magic_quotes_gpc()) {
            return addslashes($content);
        } else {
            return $content;
        }
    }
    
    // 对字符串等进行过滤
    public static function filterStr($arr)
    {
        if (! isset($arr)) {
            return null;
        }
        
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                $arr[$k] = self::filter(self::stripSQLChars(self::stripHTML(trim($v), true)));
            }
        } else {
            $arr = self::filter(self::stripSQLChars(self::stripHTML(trim($arr), true)));
        }
        
        return $arr;
    }

    public static function stripHTML($content, $xss = true)
    {
        $search = array(
            "@<script(.*?)</script>@is",
            "@<iframe(.*?)</iframe>@is",
            "@<style(.*?)</style>@is",
            "@<(.*?)>@is"
        );
        
        $content = preg_replace($search, '', $content);
        
        if ($xss) {
            $ra1 = array(
                'javascript',
                'vbscript',
                'expression',
                'applet',
                'meta',
                'xml',
                'blink',
                'link',
                'style',
                'script',
                'embed',
                'object',
                'iframe',
                'frame',
                'frameset',
                'ilayer',
                'layer',
                'bgsound',
                'title',
                'base'
            );
            
            $ra2 = array(
                'onabort',
                'onactivate',
                'onafterprint',
                'onafterupdate',
                'onbeforeactivate',
                'onbeforecopy',
                'onbeforecut',
                'onbeforedeactivate',
                'onbeforeeditfocus',
                'onbeforepaste',
                'onbeforeprint',
                'onbeforeunload',
                'onbeforeupdate',
                'onblur',
                'onbounce',
                'oncellchange',
                'onchange',
                'onclick',
                'oncontextmenu',
                'oncontrolselect',
                'oncopy',
                'oncut',
                'ondataavailable',
                'ondatasetchanged',
                'ondatasetcomplete',
                'ondblclick',
                'ondeactivate',
                'ondrag',
                'ondragend',
                'ondragenter',
                'ondragleave',
                'ondragover',
                'ondragstart',
                'ondrop',
                'onerror',
                'onerrorupdate',
                'onfilterchange',
                'onfinish',
                'onfocus',
                'onfocusin',
                'onfocusout',
                'onhelp',
                'onkeydown',
                'onkeypress',
                'onkeyup',
                'onlayoutcomplete',
                'onload',
                'onlosecapture',
                'onmousedown',
                'onmouseenter',
                'onmouseleave',
                'onmousemove',
                'onmouseout',
                'onmouseover',
                'onmouseup',
                'onmousewheel',
                'onmove',
                'onmoveend',
                'onmovestart',
                'onpaste',
                'onpropertychange',
                'onreadystatechange',
                'onreset',
                'onresize',
                'onresizeend',
                'onresizestart',
                'onrowenter',
                'onrowexit',
                'onrowsdelete',
                'onrowsinserted',
                'onscroll',
                'onselect',
                'onselectionchange',
                'onselectstart',
                'onstart',
                'onstop',
                'onsubmit',
                'onunload'
            );
            $ra = array_merge($ra1, $ra2);
            
            $content = str_ireplace($ra, '', $content);
        }
        
        return strip_tags($content);
    }

    public static function removeXSS($val)
    {
        // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
        // this prevents some character re-spacing such as <javaΘscript>
        // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
        $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);
        
        // straight replacements, the user should never need these since they're normal characters
        // this prevents like
        // <IMG SRC=
        //      &#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69
        //      &#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74
        //      &#X28&#X27&#X58&#X53&#X53&#X27&#X29>
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';
        for ($i = 0; $i < strlen($search); $i ++) {
            // ;? matches the ;, which is optional
            // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
            
            // &#x0040 @ search for the hex values
            $val = preg_replace('/(&#[x|X]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
            // @ @ 0{0,7} matches '0' zero to seven times
            $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
        }
        
        // now the only remaining whitespace attacks are \t, \n, and \r
        $ra1 = array(
            'javascript',
            'vbscript',
            'expression',
            'applet',
            'meta',
            'xml',
            'blink',
            'link',
            'style',
            'script',
            'embed',
            'object',
            'iframe',
            'frame',
            'frameset',
            'ilayer',
            'layer',
            'bgsound',
            'title',
            'base'
        );
        
        $ra2 = array(
            'onabort',
            'onactivate',
            'onafterprint',
            'onafterupdate',
            'onbeforeactivate',
            'onbeforecopy',
            'onbeforecut',
            'onbeforedeactivate',
            'onbeforeeditfocus',
            'onbeforepaste',
            'onbeforeprint',
            'onbeforeunload',
            'onbeforeupdate',
            'onblur',
            'onbounce',
            'oncellchange',
            'onchange',
            'onclick',
            'oncontextmenu',
            'oncontrolselect',
            'oncopy',
            'oncut',
            'ondataavailable',
            'ondatasetchanged',
            'ondatasetcomplete',
            'ondblclick',
            'ondeactivate',
            'ondrag',
            'ondragend',
            'ondragenter',
            'ondragleave',
            'ondragover',
            'ondragstart',
            'ondrop',
            'onerror',
            'onerrorupdate',
            'onfilterchange',
            'onfinish',
            'onfocus',
            'onfocusin',
            'onfocusout',
            'onhelp',
            'onkeydown',
            'onkeypress',
            'onkeyup',
            'onlayoutcomplete',
            'onload',
            'onlosecapture',
            'onmousedown',
            'onmouseenter',
            'onmouseleave',
            'onmousemove',
            'onmouseout',
            'onmouseover',
            'onmouseup',
            'onmousewheel',
            'onmove',
            'onmoveend',
            'onmovestart',
            'onpaste',
            'onpropertychange',
            'onreadystatechange',
            'onreset',
            'onresize',
            'onresizeend',
            'onresizestart',
            'onrowenter',
            'onrowexit',
            'onrowsdelete',
            'onrowsinserted',
            'onscroll',
            'onselect',
            'onselectionchange',
            'onselectstart',
            'onstart',
            'onstop',
            'onsubmit',
            'onunload'
        );
        $ra = array_merge($ra1, $ra2);
        
        $found = true; // keep replacing as long as the previous round replaced something
        while ($found == true) {
            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i ++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j ++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
                        $pattern .= '|(&#0{0,8}([9][10][13]);?)?';
                        $pattern .= ')?';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern .= '/i';
                $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
                $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
                if ($val_before == $val) {
                    // no replacements were made, so exit the loop
                    $found = false;
                }
            }
        }
        
        return $val;
    }

    /**
     * Strip specail SQL chars
     */
    public static function stripSQLChars($str)
    {
        $replace = array(
            'SELECT','INSERT','DELETE','UPDATE','CREATE','DROP','VERSION','DATABASES',
            'TRUNCATE','HEX','UNHEX','CAST','DECLARE','EXEC','SHOW','CONCAT','TABLES',
            'CHAR','FILE','SCHEMA','DESCRIBE','UNION','JOIN','ALTER','RENAME','LOAD',
            'FROM','SOURCE','INTO','LIKE','PING','PASSWD'
        );
        
        return str_ireplace($replace, '', $str);
    }

    /**
     * 生成时间的文字描述
     * 生成某时间基于当前时间的诸如
     *      1个月前、2天前、3小时前、4分钟前、5秒前之类的文字描述
     * 时间最长显示到一个月前，即，诸如：2个月前、1年前等，也显示一个月前
     * @param string $date 要进行格式化的时间，"Y-m-d H:i:s"格式
     * @return string 格式化后的时间，诸如：1个月前、2天前、3小时前、4分钟前、5秒前
     */
    public static function parseDate($date)
    {
        $otherTime = strtotime($date);
        $currentTime = time();
    
        if ($otherTime >= $currentTime) {
            return $date;  // 时间参数无效，原样返回
        }
    
        $second = $currentTime - $otherTime;   // 间隔的秒数
    
        //if ($year = floor($s / 31536000)) return "{$year}年前";    // 60 * 60 * 24 * 365 = 31536000
        //elseif ($month = floor($s / 2592000)) return "{$month}个月前";  // 60 * 60 * 24 * 30 = 2592000
        if ($month = floor($second / 2592000)) {
            return $date;  // 60 * 60 * 24 * 30 = 2592000
        } elseif ($day = floor($second / 86400)) {
            return "{$day}天前";  // 60 * 60 * 24 = 86400
        } elseif ($hour = floor($second / 3600)) {
            return "{$hour}小时前";  // 60 * 60 = 3600
        } elseif ($minute = floor($second / 60)) {
            return "{$minute}分钟前";  // 60
        } else {
            return "{$second}秒前"; // 1
        }
    }
    
    public static function getMobileCarr($mobile)
    {
        $check = 0;
        $mobileStart[1] = "134,135,136,137,138,139,147,150,151,152,157,158,159,182,183,184,187,188,178";
        $mobileStart[2] = "130,131,132,145,155,156,185,186,176";
        $mobileStart[3] = "133,153,180,181,189,177";
        foreach ($mobileStart as $key => $val) {
            $startArr = explode(",", $val);
            if (strlen($mobile) == 11 && is_numeric($mobile)) {
                if (in_array(substr($mobile, 0, 3), $startArr)) {
                    $check = $key;
                    break;
                }
            }
        }

        return $check;
    }
    
    public static function getUrl($url, $cache = false, $g_timeout = false, $postData = [], $header = [])
    {
        $return = array();
        $needcache = false;
        $key = NOVEL_CACHE_START.md5($url);
        $time = NOVEL_CACHE_TIME;
        if ($cache) {
            $redis = Local_DataProvider::getDataProvider('NOVEL_INTERFACE_CACHE');
            if (isset($_GET['debug']) && $_GET['debug'] == 'delcache') {
                $redis->del($key);
            }
            $data = $redis->get($key);
            //print_r($data);
            if ($data) {
                $return = json_decode($data, true);
            } else {
                $needcache = true;
            }
        }
        if (!$return) {
            $start =microtime(true);
            $ch = curl_init();
            $timeout = 3;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_REFERER, 'http://megatron.platform.zongheng.com');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if (!empty($postData)) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            }
            if ($g_timeout) {
                curl_setopt($ch, CURLOPT_NOSIGNAL, true);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, $g_timeout);
            }
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            if (!empty($header)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            }
            $handles = curl_exec($ch);
            $header = curl_getinfo($ch);
            curl_close($ch);
            $header['content'] = $handles;
            $return = $header;

            $end =microtime(true);
            $_time = $end - $start;
            if ($_time > 0.2) {
                Bingo_Log::warning("SLOW_API:".$header["url"]. "[" .$_time . "]", 'TIME_LOG');
            }

            if ($needcache) {
                $redis->setex($key, $time, json_encode($header));
            }
        }
        return $return;
    }
    
    /**
     * 比较版本大小
     * 1.1.1 > 1.1.0.1
     * @param unknown $s1
     * @param unknown $s2
     * @return number 1 : $s1 > $s2
     */
    public static function checkVersion($s1, $s2, $equal = true)
    {
        $a1 = explode('.', $s1);
        $a2 = explode('.', $s2);
        //print_r($a1);print_r($a2);
        $isTrue = 0;
        $l1 = count($a1);
        $l2 = count($a2);
        $_l = abs($l1 - $l2);
        if ($l1 > $l2) {
            $am = array_fill(count($a2), $_l, '0');
            $a2 += $am;
        } elseif ($l1 < $l2) {
            $am = array_fill(count($a1), $_l, '0');
            $a1 += $am;
        }
    
        if ($equal) {
            foreach ($a2 as $k => $v) {
                $v1 = (int)$a1[$k];
                $v2 = (int)$v;
                if ($v1 > $v2) {
                    $isTrue = 1;
                } elseif ($v1 == $v2) {
                    $isTrue = 1;
                    continue;
                } else {
                    $isTrue = -1;
                }
                break;
            }
        } else {
            foreach ($a2 as $k => $v) {
                $v1 = (int)$a1[$k];
                $v2 = (int)$v;
                if ($v1 == $v2) {
                    continue;
                }
                $isTrue = $v1 > $v2 ? 1 : -1;
                break;
            }
        }
    
        return $isTrue;
    }
    /**
     * 封装用户等级html
     */
    public static function showGradeHtml($grade, $show_word = true)
    {
        $html = '';
        
        if ($grade) {
            $c = intval($grade / 16);
            $y = intval($grade % 16);
            if ($c) {
                for ($i=0; $i<$c; $i++) {
                    $html .= '<span class="images_1"><img src="'.
                        self::fBkImg('14/14f98e7f5a60d32dc90a12ab444b7fe2.png', 'cdn').
                        '"></span>';
                }
            }

            if ($y) {
                $c = intval($y/4);
                $y = intval($y%4);
                if ($c) {
                    for ($i=0; $i<$c; $i++) {
                        $html .= '<span class="images_2"><img src="'.
                            self::fBkImg('ba/ba12f920e85e03affdcf580189c5deaa.png', 'cdn').
                            '"></span>';
                    }
                }
            }

            if ($y) {
                for ($i=0; $i<$y; $i++) {
                    $html .= '<span class="images_3"><img src="'.
                        self::fBkImg('4c/4c8c129711fc7aac146ed5254b6be448.png', 'cdn').
                        '"></span>';
                }
            }
            
            if ($show_word) {
                $html .= '<span class="fon_1">'.$grade.'级</span>';
            }
        }

        return $html;
    }

    /**
     * 封装用户VIP HTML
     *
     * @param integer $grade VIP等级
     * @param integer $version 版本，默认为6
     * @return string
     */
    public static function showVipGradeHtml($grade, $version = 6)
    {
        $grade = intval($grade);

        if ($version >= 7) {
            $imgList = [
                '',
                '17/174b9172396ba2f81136fe4b48c555b1.png',
                'a9/a97d982e929607d11ed4ab0ebb83f4e9.png',
                'f4/f4e5062c63fa17a4a90e21c75dc2dd70.png',
                '40/40b824fdf84e9d65f6e356ee7c888e0e.png',
                '54/547992fbc4f2fc3a9bd85dcbb534fc39.png',
                'f3/f3c4ef6029d600a6fbf99f8ed65269c4.png',
                '6a/6aa42df5ffacdb893749cdcb6713c555.png',
                '1c/1c20b179a1635530dd4d578528a484fc.png',
                '09/098b9ee0f43213499cba15b3a5c2dd7e.png',
            ];
        } else {
            $imgList = [
                '',
                'e0/e0dcb7a45bd4e5f76b27530a56d0d737.png',
                'ad/addd2792eb08e3f4e963aa40c36f2eb5.png',
                '98/98cb7f75b718edde488a963b38a18107.png',
                'f2/f2ed3e66db0953bc4e75391fc2deeb57.png',
                '69/69684c63857bdd5e0a2eacfa18697674.png',
                '6b/6b5230d7f87c8047229642f0902676eb.png',
                '34/3413eabb92c59fee4b8c72e11e7b0270.png',
                'a3/a331b78190a35a0acc663b504c48d3a3.png',
                '09/09517bba6117a718726848cfc4a3c94c.png'
            ];
        }

        if (!isset($imgList[$grade]) || !$imgList[$grade]) {
            return '';
        }

        return '<span class="images_4"><img src="' .
            self::fBkImg($imgList[$grade], 'cdn') .
            '"></span>';
    }
    /**
     * 封装用户英雄级别html
     */
    public static function showFansGradeHtml($grade)
    {
        $grade = intval($grade);
        $html = '';

        $imgList = [
            '',
            'b8/b85711474644195894ccb82e244bc49d.png',
            '3b/3b6ff5ee41cc9a1d626731d9e32e5b63.png',
            'ef/ef6be0c1c76bfcd59df4d0d3e7be19f6.png',
            'c1/c121984ad75c006864a05295a2ba1dbd.png',
            'ca/caf4c622e0363fec1e5f326c611a8e4a.png',

            '1e/1eb3d09e3aed46628e8d768d7c6ef902.png',
            'be/bea3b611b0192c8eb0c937ae8c5b23c2.png',
            'ce/ce5a73fca140837b3e7cf8344cebcb49.png',
            '27/273a38bf912e2e652b30792fca5558fd.png',
            'aa/aac06d41969fa1f1f5a1ef1d4b9dde13.png',

            '13/130be1045f613b86cfb43def44c7f238.png',
            '96/96e6728df2316722f7dcf6359d27c8b6.png',
            'b3/b3a60a0341e7c900f41090e3640a4d84.png',
            '4e/4eb4646fc5d0c568b8d87e3ecb06223d.png',
            'cb/cbd408c1dca18d5b869cbc331d85f494.png',

            '6f/6f13cb294a9c775428535455dd0f85f9.png',
            '73/737b06f47f113250a6a4b2c5ef4e42a0.png',
            '8d/8d7f0a8d156994e851fa741ce7992f36.png',
            '9e/9e132ac89038a150cfcf8c433ca64e81.png',
            'da/dac7032798d9be7ffb34531e34e57858.png',
        ];

        if (!isset($imgList[$grade]) || !$imgList[$grade]) {
            return '';
        }

        return '<span class="images_5"><img src="' .
            self::fBkImg($imgList[$grade], 'cdn') .
            '"></span>';
    }
    
    /**
     * 91密码加密
     */
    public static function md5Password91($password, $appkey = "fdjf,jkgfkl")
    {
        $b = unpack("C*", $password);
        $b = array_merge($b, array('163', '172', '161', '163')); //加一特殊的字符"，。"
        $b = array_merge($b, unpack("C*", $appkey));
    
        $str = '';
        foreach ($b as $ch) {
            $str .= chr($ch);
        }
    
        return md5($str);
    }

    /**
     * 按位进行与运算加密
     * 两次异或变原文
     */
    public static function exclusiveor($string, $key = '11001100')
    {
        $str = "";
        $keylen = strlen($key);
        for ($i=0; $i < strlen($string); $i++) {
            $k = $i%$keylen;
            $str .= $string{$i} ^ $key{$k};
        }

        return $str;
    }
    /**
     * 默认加载封面
     * @return multitype:string
     */
    public static function get_default_img()
    {
        $return = array();
        //书籍封面
        $return['frontcover'] = 'https://img.xmkanshu.com/operateimg/novel/2c/' .
            '2c3d10fe4f2970283e7e6f849f060a61.jpg';
        //banner
        $return['banner'] = 'https://img.xmkanshu.com/operateimg/novel/94/' .
            '948d0d7b68724c25c7f032a865ccde56.jpg';
        //topic
        $return['topic'] = 'https://img.xmkanshu.com/operateimg/novel/a2/' .
            'a2a05c1087fc5230a18b67cb621efc2d.jpg';
        //no data
        $return['none'] = 'https://img.xmkanshu.com/operateimg/novel/57/' .
            '572aded66949137158cba8887dab8469.png';
        return  $return;
    }
    
    /**
     * 判定输入时间是，今天，昨天
     * @param str 格式化时间，例：2015-8-24 16：39：59
     */
    public static function get_format_day($str)
    {
        // 转换为时间戳
        $a_ux = strtotime($str);
        // 转换为 YYYY-MM-DD 格式
        $a_date = date('Y-m-d', $a_ux);
        // 获取今天的 YYYY-MM-DD 格式
        $b_date = date('Y-m-d');
        // 获取昨天的YYYY-MM-DD 格式
        $c_date = date("Y-m-d", strtotime("-1 day"));

        $format_str = '';
 
        if ($a_date == $b_date) {
            $format_str = '今天 '.date('H:i:s', $a_ux);
        } elseif ($a_date == $c_date) {
            $format_str = '昨天 '.date('H:i:s', $a_ux);
        } else {
            $format_str = date('Y-m-d H:i:s', $a_ux);
        }

        return $format_str;
    }
    
    /**
     * 催更券和字数折算
     * @param unknown $value 催更券或者字数值
     * @param number $type 0为催更券折算成字数，1为字数折算成催更券
     */
    public static function exchange_urge($value, $type = 0)
    {
        $font = 3000;//一张券催3000字
        $result = 0;
        //券数换字数
        if ($type == 0) {
            $result = $value * $font;
        } elseif ($type == 1) {
            $result = $value / 3000;
        }

        return $result;
    }
    
    // 熊猫看书ios充值屏蔽开关
    public static function ios_appstore_switch()
    {
        $G = Yaf_Registry::get('G');
        if ($G['appid'] == 30000) {
            //ios小号
            return true;
        }
        // 熊猫看书ios充值屏蔽开关
        $plugInfo = array(
            "id" => 328,
            "name" => '熊猫看书ios充值屏蔽开关'
        );
        $plugInfo["method"] = __CLASS__ . "->" . __FUNCTION__;
        $data = Local_PlugCenter::getData($plugInfo);
        if ($data['p2'] && $data['p7'] && $data['p10']) {
            if ($G['pp']['p2'] == $data['p2'] &&
                $G['pp']['p7'] == $data['p7'] &&
                $G['pp']['p10'] == $data['p10']
            ) {
                return true;
            }
        }

        //海外ip屏蔽(只在线上奏效，否则qa的ip会被当成外国ip)
        if ($data['ip_overseas'] && $G['currDev'] == 'online') {
            $ip = self::getClientIp();
            if (!$ip) {
                return false;
            }
            $summer = Yaf_Registry::get('SummerApi');
            $res = $summer->getIpLocation($ip);
            if (!empty($res['result']['country'])) {
                $country = trim($res['result']['country']);
                if ($country != '中国') {
                    return true;
                }
            }
        }

        return false;
    }

    //获取此渠道和此版本ios充值金额自定义列表
    //shaohua
    public static function getIosBillingMoneyList()
    {
        $pluginfo = [
            'id' => 575,
            'name' => 'ios充值展示指定金额',
            'method' => __CLASS__ . '->' . __FUNCTION__,
        ];
        $data = Local_PlugCenter::getData($pluginfo);
        if (!isset($data['switch']) || !$data['switch']) {
            return [];
        }

        $G = Yaf_Registry::get('G');
        $appid = $G['appid'];
        $version = $G['pp']['p10'];

        $moneyList = Local_Funcs::parsePlugData($data['money'], ['index', 'money']);
        $ruleList = Local_Funcs::parsePlugData($data['rule'], ['appid', 'version', 'index']);

        $l = [];
        foreach ($ruleList as $val) {
            $val['appid'] = trim($val['appid']);
            $val['version'] = trim($val['version']);
            $val['index'] = trim($val['index']);

            if (!$val['appid'] || !$val['version'] || !$val['index']) {
                continue;
            }

            if ($appid != $val['appid']) {
                continue;
            }

            $fw = explode('-', $val['version']);
            $fwc = count($fw);
            if ($fwc == 1) {
                if ($version != $fw[0]) {
                    continue;
                }
            } elseif ($fwc == 2) {
                if ($fw[0] == '' || $fw[0] == '0' || $fw[0] == 0) {
                    if (!$fw[1] || version_compare($version, $fw[1], '>')) {
                        continue;
                    }
                } elseif ($fw[1] == '' || $fw[1] == '0' || $fw[1] == 0) {
                    if (!$fw[0] || version_compare($version, $fw[0], '<')) {
                        continue;
                    }
                } else {
                    if (version_compare($version, $fw[0], '<') || version_compare($version,$fw[1], '>')) {
                        continue;
                    }
                }
            } else {
                continue;
            }

            foreach ($moneyList as $mv) {
                if ($mv['index'] != $val['index']) {
                    continue;
                }

                $restemp = explode(',', $mv['money']);
                foreach ($restemp as $resv) {
                    if (!is_numeric($resv) || $resv <= 0) {
                        continue;
                    }

                    $l[] = $resv;
                }
            }
        }

        return empty($l) ? $l : array_values(array_unique($l));
    }

    //熊猫屏蔽部分用户ios开关
    public static function ios_switch_billing_user()
    {
        // 熊猫看书ios特定用户充值屏蔽开关
        $plugInfo = array(
            "id" => 506,
            "name" => 'ios新用户屏蔽支付方式'
        );
        $plugInfo["method"] = __CLASS__ . "->" . __FUNCTION__;
        $data = Local_PlugCenter::getData($plugInfo);
        if (isset($data['switch']) && $data['switch']) {
            $G = Yaf_Registry::get('G');
            $appid = $G['appid'];
            if ($data['userid'] && $appid == '10008') {
                $temp = Local_Funcs::parsePlugData($data['userid'], ['uid']);
                $userids = array_column($temp, 'uid');

                $uid = (int)$G['uid'];
                if (!$uid) {
                    return false;
                }
                foreach ($userids as $userid) {
                    $ids = explode('-', $userid);
                    if (count($ids) == 1) {
                        if ($uid == $ids[0]) {
                            return true;
                        }
                        continue;
                    }
                    $ids[0] = (int)$ids[0];
                    $ids[1] = (int)$ids[1];

                    if ($ids[1] == 0) {
                        if ($uid >= $ids[0]) {
                            return true;
                        }
                        continue;
                    }
                    if ($ids[1] < $ids[0]) {
                        continue;
                    }
                    if ($uid >= $ids[0] && $uid <= $ids[1]) {
                        return true;
                    }
                }
            }

            //appid模式
            if ($data['appid']) {
                $temp = Local_Funcs::parsePlugData($data['appid'], ['appid']);
                $appids = array_column($temp, 'appid');
                //不存在没有appid的情况，如果出现，屏蔽其他支付方式
                if (!$appid) {
                    return true;
                }
                if (in_array($appid, $appids)) {
                    return true;
                }
            }
        }

        return false;
    }
    
    //充值方式检测屏蔽列表
    public static function check_billing_none_list()
    {
        $plugInfo = [
            'id' => 549,
            'name' => '全线充值方式屏蔽'
        ];

        $plugInfo['method'] = __CLASS__ . '->' . __FUNCTION__;
        $data = Local_PlugCenter::getData($plugInfo);
        if (!isset($data['is_show']) || $data['is_show'] != 1 || !isset($data['content']) || empty($data['content'])) {
            return [];
        }
        $G = Yaf_Registry::get('G');
        $appid = $G['appid'];
        $p2 = isset($G['pp']['p2']) ? $G['pp']['p2'] : '';
        $p10 = isset($G['pp']['p10']) ? $G['pp']['p10'] : '';
        if (!$appid || !$p2 || !$p10) {
            return [];
        }

        $paramArgs = [
            'platform',
            'ntype',
            'version',
            'channel',
            'paytype',
            'txt',
        ];
        $temp = Local_Funcs::parsePlugData($data['content'], $paramArgs);
        $result = [];
        foreach ($temp as $val) {
            if ($val['platform'] != $appid || !$val['paytype']) {
                continue;
            }

            if ($val['ntype'] == 1) {
                if (('0' === $val['version'] || $val['version'] == $p10) &&
                    ('0' === $val['channel'] || $val['channel'] == $p2)
                ) {
                    $result[] = $val['paytype'];
                }
                continue;
            } else {
                if (('0' === $val['version'] || $val['version'] == $p10) &&
                    ('0' === $val['channel'] || $val['channel'] == $p2)
                ) {
                    continue;
                } else {
                    $result[] = $val['paytype'];
                }
                continue;
            }
        }

        if (!empty($result)) {
            return array_unique($result);
        }

        return [];
    }

    //ubb转换html
    public static function bb2html($Text)
    {
        $Text = str_replace("[br]", "<br />", $Text);
        $Text = nl2br($Text);
        $Text = stripslashes($Text);
        $Text = preg_replace("/\\t/is", " ", $Text);
        if (isset($_GET['p10']) && Local_Funcs::checkVersion($_GET['p10'], '7.0') == 1) {
            $Text = preg_replace("/\[url=\/book\?.+?[bkid|bookid]\=([0-9]+).*\](.*)\[\/url\]/is", "<u class='_clickBook' data-bookid='\\1'>\\2</u>", $Text);
        }
        $Text = preg_replace("/\[url\](http:\/\/.+?)\[\/url\]/is", "<a href=\"\\1\"><u>\\1</u></a>", $Text);
        $Text = preg_replace("/\[url\](.+?)\[\/url\]/is", "<a href=\"\\1\"><u>\\1</u></a>", $Text);
        //处理链接
        $Text = preg_replace("/\[url=(http:\/\/.+?)\](.+?)\[\/url\]/is", "<a href=\"\\1\"><u>\\2</u></a>", $Text);
        //如果不是http开头，加上行参数
        if (preg_match("/\[url=(.+?)\](.+?)\[\/url\]/is", $Text, $matches)) {
            $Text = preg_replace(
                "/\[url=(.+?)\](.+?)\[\/url\]/is",
                "<a href=\"" . self::url($matches[1]) . "\"><u>\\2</u></a>",
                $Text
            );
        }

        $Text = preg_replace("/\[color=(.+?)\](.+?)\[\/color\]/is", "<font color=\"\\1\">\\2</font>", $Text);
        $Text = preg_replace("/\[font=(.+?)\](.+?)\[\/font\]/is", "<font face=\"\\1\">\\2</font>", $Text);
        $Text = preg_replace("/\[email=(.+?)\](.+?)\[\/email\]/is", "<a href=\"mailto:\\1\"><u>\\2</u></a>", $Text);
        $Text = preg_replace("/\[email\](.+?)\[\/email\]/is", "<a href=\"mailto:\\1\"><u>\\1</u></a>", $Text);
        $Text = preg_replace("/\[i\](.+?)\[\/i\]/is", "<i>\\1</i>", $Text);
        $Text = preg_replace("/\[u\](.+?)\[\/u\]/is", "<u>\\1</u>", $Text);
        $Text = preg_replace("/\[b\](.+?)\[\/b\]/is", "<b>\\1</b>", $Text);
        $Text = preg_replace(
            "/\[fly\](.+?)\[\/fly\]/is",
            "<marquee width=\"98%\" behavior=\"alternate\" scrollamount=\"3\">\\1</marquee>",
            $Text
        );
        $Text = preg_replace(
            "/\[move\](.+?)\[\/move\]/is",
            "<marquee width=\"98%\" scrollamount=\"3\">\\1</marquee>",
            $Text
        );
        $Text = preg_replace(
            "/\[shadow=([#0-9a-z]{1,10})\,([0-9]{1,3})\,([0-9]{1,2})\](.+?)\[\/shadow\]/is",
            "<table width=\"*\"><tr><td style=\"filter:shadow(color=\\1, direction=\\2 ," .
                "strength=\\3)\">\\4</td></tr></table>",
            $Text
        );
        return $Text;
    }

    /**
     *  @name   删除所有ubb
     *  @desc   剔除左右ubb、html、js等字符
     *
     *  @param  string  $text   要处理的字符串
     *  @param  string  $allow  要保留的html标签
     *  @return string
     *
     *  @author shaohua <shaohua@baidu-wenxue.com>
     */
    public static function clearbb2html($text, $allow = '')
    {
        $text = strip_tags($text, $allow);
        $text = preg_replace("/\[(url|color|font|email)=.+?\]/is", "", $text);
        $text = preg_replace("/\[[\/]?(br|i|u|b|fly|move|shadow|email|url)\]/is", "", $text);

        return $text;
    }

    /**
     *  @name   高亮字
     *  @desc   对于个人消息可点击提示那块进行高亮提示
     *
     *  @param  string  $text   要处理的字符串
     *  @param  string  $color  要高亮的颜色（可16进制 可英文单词 html支持即可）
     *  @return string
     *
     *  @author shaohua <shaohua@baidu-wenxue.com>
     */
    public static function setClickColor($text, $color = '')
    {
        $color = '' == $color ? 'blue' : $color;
        return preg_replace("/(【.+?】)/", "<font color='$color'>\\1</font>", $text);
    }
    
    /**
     * 根据书籍分类ID获取书籍所属（男/女）频
     *
     * @param int $categoryId 分类ID
     * @return int 男女频类型，0-女频、1-男频（默认）
     * @author SC
     */
    public static function getBoyOrGirlByCategory($categoryId)
    {
        //$boyCategories = array(1, 2, 3, 4, 5, 6, 7, 8);
        $girlCategories = array(9, 10, 11, 12, 23, 24);
        
        if (in_array($categoryId, $girlCategories)) {  // 女频
            return 0;
        } else {  // 男频，默认
            return 1;
        }
    }
    
    /**
     *  @title  统一检测数组中属性
     *
     *  @param  array $testArr 需要检测的数组
     *  @param  array $keyArr  需要检测的字段数组
     *  $param  All   $defaultValue 如果不存在默认值
     *  @return  array
     */
    public static function checkArrayValueIsSet($testArr, $keyArr, $defaultValue = array())
    {
        $return = array();
        foreach ($keyArr as $val) {
            $return[$val] = isset($testArr[$val]) ? $testArr[$val] : $defaultValue;
        }
        return $return;
    }
    
    /** 格式化书籍字数
     *
     * 单位由“千字”转换为“万字”，去整法保留一位小数
     *
     * @param number $bookSize 书籍字数，单位“千字”
     * @return number 书籍字数，单位“万字”
     * @author SC
     */
    public static function formatBookSize($bookSize)
    {
        return floor($bookSize) / 10;
    }
    
    /**
     * 获取两个时间点之间的时间间隔
     *
     * @param string $beginTime 开始时间，标准时间格式，如"2016-05-11 12:13:21"
     * @param string $endTime 结束时间，标准时间格式，如"2016-05-11 13:02:01"
     * @param bool $zeroPadding 补零占位，默认开启，另，仅对时、分、秒做此项处理
     * @return array 时间间隔
     * @author SC
     */
    public static function getIntervalTime($beginTime, $endTime, $zeroPadding = true)
    {
        $intervalTime = array();
        
        if ($beginTime > $endTime) {  // 容错，当开始时间大于结束时间是做互换
            list($beginTime, $endTime) = array($endTime, $beginTime);
        }
        
        $intervalTimestamp = strtotime($endTime) - strtotime($beginTime);
        
        if ($intervalTimestamp) {
            $intervalTime['days'] = floor($intervalTimestamp / 86400);
            $remain = $intervalTimestamp % 86400;
            $intervalTime['hours'] = floor($remain / 3600);
            $remain = $remain % 3600;
            $intervalTime['mins'] = floor($remain / 60);
            $intervalTime['secs'] = $remain % 60;
            
            if ($zeroPadding) {  // 补零占位
                if ($intervalTime['hours'] < 10) {
                    $intervalTime['hours'] = '0' . $intervalTime['hours'];
                }
                if ($intervalTime['mins'] < 10) {
                    $intervalTime['mins'] = '0' . $intervalTime['mins'];
                }
                if ($intervalTime['secs'] < 10) {
                    $intervalTime['secs'] = '0' . $intervalTime['secs'];
                }
            }
        }
        
        return $intervalTime;
    }
    
    /**
     * 格式化时间
     *
     * V7版熊猫的评论时间会用到此方法
     *
     * 1分钟之内，显示xx秒之前
     * 在今天内，显示xx小时之前（去一法）
     * 在昨天内，显示“昨天”
     * 在前天内，显示“前天”
     * 在今年内，显示“04月05日”
     * 在今年之前，显示“2011年04月01日”
     *
     * @param integer $time 时间戳形式的时间
     * @return string 格式化后的时间
     */
    public static function formatDateTime($time)
    {
        // 如果发表时间超越了当前服务器时间，认为是服务器之间时间差问题，当作当前时间处理
        if ($time > TIME) {
            $time = TIME;
        }
        
        $timeInfo = '';
        $tc =  TIME - $time;

        if ($tc < 3600) {
            $timeInfo = '刚刚';
        } elseif ($tc >= 3600 && $tc < 86400) {
            $timeInfo = floor($tc / 3600) . '小时之前';
        } elseif ($tc >= 86400 && $tc < (86400 * 3)) {
            $timeInfo = floor($tc / 86400) . '天之前';
        } elseif ($tc >= (86400 * 3) && $tc < (86400 * 7)) {
            $timeInfo = '3天前';
        } elseif ($tc >= (86400 * 7)) {
            $timeInfo = date('Y-m-d', $time);
        } else {
            $timeInfo = date('Y-m-d', $time);
        }

        /*$today = strtotime(date('Y-m-d'));
        $yesterday = $today - 86400;
        $beforeYesterday = $yesterday - 86400;
        $thisYear = strtotime(date('Y-01-01'));
        
        $intervalTime = TIME - $time;

        //小于1秒的不显示0秒之前，显示刚刚
        if ($intervalTime < 1) {
            $timeInfo = '刚刚';
        } elseif ($intervalTime < 60) {
            $timeInfo = $intervalTime . '秒之前';
        } elseif ($intervalTime < 3600) {
            $timeInfo = floor($intervalTime / 60) . '分钟之前';
        } elseif ($time >= $today) {
            $timeInfo = floor($intervalTime / 3600) . '小时之前';
        } elseif ($time >= $yesterday) {
            $timeInfo = '昨天';
        } elseif ($time >= $beforeYesterday) {
            $timeInfo = '前天';
        } elseif ($time >= $thisYear) {
            $timeInfo = date('m月d日', $time);
        } else {
            $timeInfo = date('Y年m月d日', $time);
        }*/
        
        return $timeInfo;
    }
    
    /**
     * 通过键过滤数组
     *
     * 通过第二维数组中的某个键值是否为空来过滤当前二位数组
     * 适用于处理专题接口返回的下架书数据
     *
     * @param array $array 要过滤的数组
     * @param string $filterKey 用于过滤的键名，默认为"bookname"
     * @return array 过滤后的数组
     * @author SC
     */
    public static function filterArrayByKey($array, $filterKey = 'bookname')
    {
        $newArray = array();
        
        foreach ($array as $value) {
            if (isset($value[$filterKey]) && ($value[$filterKey] != '')) {
                $newArray[] = $value;
            }
        }
        
        return $newArray;
    }

    //根据制定的url，获取url中的get参数
    //return get参数组成的数组
    public static function getUrlArgs($url)
    {
        $m = parse_url($url);
        if (!isset($m['query'])) {
            return [];
        }

        $t = explode('&', $m['query']);
        $r = [];
        foreach ($t as $v) {
            $n = explode('=', $v);
            $r[$n[0]] = isset($n[1]) ? $n[1] : '';
        }
        return $r;
    }

    //解析url，判断是否是专题/排行榜/分类/书籍等
    //神策专用
    //shaohua
    public static function getSaUrlType($url)
    {
        if (!$url) {
            return '';
        }

        if (is_numeric($url)) {
            return 'book';
        }

        $pu = parse_url($url);
        if (!$pu || !isset($pu['path'])) {
            $saType = '';
        } else {
            $saType = $pu['path'];
        }

        if (!$saType) {
            return '';
        }

        if ('/v7/' == substr($saType, 0, 4)) {
            $saType = substr($saType, 3);
        }

        $returnType = '';
        switch ($saType) {
            case '/sys/log':
                $returnType = 'url';
                break;
            case '/topic':
            case '/topic/detail':
            case '/topic/rebate':
            case '/topic/specialinfo':
                $returnType = 'topic';
                break;
            case '/top':
            case '/top/detail':
                $returnType = 'rank';
                break;
            case '/category':
            case '/category/detail':
                $returnType = 'category';
                break;
            default:
                $returnType = 'url';
                break;
        }

        return $returnType;
    }

    //epub下载地址
    public static function getEpubDownloadUrl($bkid, $param = [])
    {
        $return = [];
        $downUrl = DOWN_HOST . '/download/book/';

        if (!$bkid || $bkid % 1000 < 800) {
            return '';
        }

        $type = isset($param['type']) ? $param['type'] : 'part';
        $signParam = [
            'token' => isset($param['token']) ? $param['token'] : '',
            'appid' => isset($param['appid']) ? $param['appid'] : '',
            'bookid'=> $bkid,
            'type'  => $type,
            'time'  => time(),
        ];
        $sign = self::getAuthSign($signParam);

        $fileKey = $bkid . '.' . $type . '.epub' . '?sign=' . $sign . '&' . http_build_query($signParam);

        return $downUrl . $fileKey;
    }

    /**
     * 生成验证签名
     */
    public static function getAuthSign($params = [])
    {
        $appid = isset($params['appid']) && $params['appid'] ? $params['appid'] : '';
        if(!$appid) return '';

        $apps = self::getAppKey();
        $key  = isset($apps[$appid]) && $apps[$appid] ? $apps[$appid] : '';
        if(!$key) return '';

        ksort($params);
        if (isset($params['sign'])) {
            unset($params['sign']);
        }
        $params = array_map("urldecode", $params);
        $string = '';
        $final_num = count($params) - 1;
        $i = 0;
        foreach ($params as $k => $v) {
            if ($final_num == $i) {
                $string .= $k . '=' . $v;
            } else {
                $string .= $k . '=' . $v . '&';
            }
            $i++;
        }
        $string = $string . $key;
        $sign = md5($string);

        return $sign;
    }

    //判断当前的版本和平台，是否比规定的大
    //shaohua
    public static function isThanVersion($p7 = '', $p10 = '', $than = '>=')
    {
        if ($p7 == '' || $p10 == '') {
            return false;
        }

        $G = Yaf_Registry::get('G');
        if (isset($G['pp']['p7']) && isset($G['pp']['p10'])) {
            if ($G['pp']['p7'] == $p7 && version_compare($G['pp']['p10'], $p10, $than)) {
                return true;
            }
        }

        return false;
    }

    //是否支持音频
    public static function isSupportAudio()
    {
        return self::isThanVersion('android', '7.9') || self::isThanVersion('ios', '7.8');
    }

    //判断是否是漫画 根据bookid
    //shaohua
    public static function isComic($bookid = 0)
    {
        if (!is_numeric($bookid) || $bookid <= 0) {
            return false;
        }

        if (substr($bookid, -3) == '699') {
            return true;
        }

        return false;
    }

    //判断书籍类型
    //  0--普通网络书
    //  1--epub出版物
    //  2--漫画
    //  3--喜马拉雅有声小说
    //  shaohua
    public static function getBookType($bookid)
    {
        if (!is_numeric($bookid) || $bookid <= 0) {
            return -1;
        }

        $end = substr($bookid, -3);

        if ($end >= 800) {
            return 1;
        } elseif ($end == 699) {
            return 2;
        } elseif ($end == 698) {
            return 3;
        }

        return 0;
    }

    /**
     * 获取登录签名
     * 
     * @param string $uid 用户ID
     * @param string $dr 重定向地址
     * @return string 签名
     */
    public static function getDuibaSign($uid, $dr)
    {
        $uid = (string)$uid;
        $uidl = strlen($uid);
        $drl = strlen($dr);
        $key = '';
        $n = 0;
        for ($i = 0; $i < $uidl; $i++) {
            $n += $uid[$i];
            $sd = $n % $drl;
            $key .= $dr[$sd];
        }
        
        $key = md5($key . $uid);
        
        $randKey = [];
        for ($i = 0; $i < 256; $i++) {
            $randKey[$i] = ord(ord($key[$i % 32]) % $drl);
        }
        
        $res = ''; 
        for ($i = 0; $i < $drl; $i++) {
            $sk = ord($dr[$i]);
            $res .= $sk ^ $randKey[$i] % 256;
        }
        
        return md5($res);
    }

    //格式化时间（新格式)
    //2018-07-12
    //shaohua
    public static function timeFormatValue($time)
    {
        return date('Y.m.d H:i', $time);
    }

    //格式化特殊展示时间（新格式）
    //2018-07-12
    //shaohua
    public static function timeFormatShow($time)
    {
        $v = '';
        $cx = TIME - $time;
        $thisYear = strtotime(date('Y-01-01'));

        //如果当前时间小于指定格式化时间，认为是时间差问题，
        if ($cx < 0) {
            $time = TIME;
        }

        if ($cx <= 60) {
            $v = '刚刚';
        } elseif ($cx <= 3600) {
            $m = $cx / 60;
            $v = (int)$m . '分钟之前';
        } elseif ($cx <= 86400) {
            $h = $cx / 3600;
            $v = (int)$h . '小时之前';
        } elseif ($cx <= (86400 * 7)) {
            $d = $cx / 86400;
            $v = (int)$d . '天之前';
        } elseif ($time >= $thisYear) {
            $v = date('m.d H:i', $time);
        } else {
            $v = date('Y.m.d H:i', $time);
        }

        return $v;
    }

    //推啊活动uid加密
    public static function tuiaUidEncode()
    {
        $G = Yaf_Registry::get('G');
        $uid = $G['uid'];
        $uid = $uid ? (int)$uid : 0;
        $uid = (string)$uid;

        $len = 0;
        for ($i = 1; $i <= 8; $i++) {
                $max = (1 << (8 * $i)) - 1;
                if ($uid > $max) {
                        continue;
                }
                $len = $i;
                break;
        }

        $buff = [];
        $buff[] = $len;
        for ($i = 0; $i < $len; $i++) {
                $b = ($uid >> (8 * $i)) & 0XFF;
                $b = $b ^ $len;
                $b = sprintf('%02s', dechex($b));
                $buff[] = $b;
        }

        return implode('', $buff);
    }
    /**
     * 红包 uid 解密
     *
     * @return uid
     */
    public static function decode($token, $key = '', $exp = 0)
    {
        $skLen = 6;
        $key = $key ? $key : 'DEFAULT_KEY';
        $key2 = substr($token, 0, $skLen);
        $cryptKey = $key . md5($key . $key2);
        $keyLen = strlen($cryptKey);

        $string = substr($token, $skLen);
        $strLen = strlen($string);
        if (!$string || $strLen % 2 != 0 || $strLen < 5) {
            return false;
        }

        $strBuff = [];
        $b16 = str_split($string);
        $rcLen = $strLen / 2;
        for ($i = 0; $i < $rcLen; $i++) {
            $b = array_slice($b16, 0, 2);
            $b = hexdec(implode('',$b));
            $b16 = array_slice($b16, 2);
            $strBuff[] = $b;
        }

        $vx = $vb = range(0, 255);
        for ($i = 0; $i < 256; $i++) {
            $vx[$i] = ord($cryptKey[$i % $keyLen]);
        }

        for ($i = $j = 0; $i < 256; $i++) {
            $j = ($j + $vb[$i] + $vx[$i]) % 256;
            $vb[$i] = $vb[$i] ^ $vb[$j];
            $vb[$j] = $vb[$i] ^ $vb[$i];
            $vb[$i] = $vb[$i] ^ $vb[$j];
        }

        $rc = [];
        $buffLen = count($strBuff);
        for ($i = $j = $x = 0; $i < $buffLen; $i++) {
            $x = ($x + 1) % 256;
            $j = ($j + $vb[$x]) % 256;
            $vb[$x] = $vb[$x] ^ $vb[$j];
            $vb[$j] = $vb[$x] ^ $vb[$j];
            $vb[$x] = $vb[$x] ^ $vb[$j];
            $rc[] = chr($strBuff[$i] ^ ($vb[($vb[$x] + $vb[$j]) % 256]));
        }

        $rcLen = count($rc);
        $chKey = $rcLen - 9;
        if ($rc[$chKey] != '-') {
            return false;
        }

        $uidBuff = array_slice($rc, 0, -9);
        $uidBuffLen = count($uidBuff) / 2;
        $uid = 0;
        for ($i = 0; $i < $uidBuffLen; $i++) {
            $b = array_slice($uidBuff, 0, 2);
            $b = hexdec(implode('',$b));
            $uid |= $b << (8 * ($i));
            $uidBuff = array_slice($uidBuff, 2);
        }

        $timeBuff = array_slice($rc, -8);
        $time = 0;
        for ($i = 0; $i < 4; $i++) {
            $b = array_slice($timeBuff, 0, 2);
            $b = hexdec(implode('', $b));
            $time |= $b << (8 * ($i));
            $timeBuff = array_slice($timeBuff, 2);
        }

//        if ($exp > 0 && (time() - $time > $exp)) {
//            return false;
//        }

        return $uid;
    }


    /**
     * 红包 token加密
     * @uid int 需要加密的uid
     * @key string 加解密秘钥
     *
     * @return string token
     */
    public static function encode($uid, $key = '')
    {
        $skLen = 6;
        $key = $key ? $key : 'DEFAULT_KEY';
        $key2 = substr(md5(uniqid()), 0, $skLen);
        $time = time();
        $cryptKey = $key . md5($key . $key2);
        $keyLen = strlen($cryptKey);

        $len = 0;
        for ($i = 1; $i <= 8; $i++) {
            $max = (1 << (8 * $i)) - 1;
            if ($uid > $max) {
                continue;
            }
            $len = $i;
            break;
        }

        $buff = [];
        for ($i = 0; $i < $len; $i++) {
            $b = ($uid >> (8 * $i)) & 0XFF;
            $b = sprintf('%02s', dechex($b));
            $buff[] = $b;
        }
        $buff[] = '-';

        for ($i = 0; $i < 4; $i++) {
            $b = ($time >> (8 * $i)) & 0XFF;
            $b = sprintf('%02s', dechex($b));
            $buff[] = $b;
        }

        $string = implode('', $buff);

        $vx = $vb = range(0, 255);
        for ($i = 0; $i < 256; $i++) {
            $vx[$i] = ord($cryptKey[$i % $keyLen]);
        }

        for ($i = $j = 0; $i < 256; $i++) {
            $j = ($j + $vb[$i] + $vx[$i]) % 256;
            $vb[$i] = $vb[$i] ^ $vb[$j];
            $vb[$j] = $vb[$i] ^ $vb[$i];
            $vb[$i] = $vb[$i] ^ $vb[$j];
        }

        $rc = [];
        $strLen = strlen($string);
        for ($i = $j = $x = 0; $i < $strLen; $i++) {
            $x = ($x + 1) % 256;
            $j = ($j + $vb[$x]) % 256;
            $vb[$x] = $vb[$x] ^ $vb[$j];
            $vb[$j] = $vb[$x] ^ $vb[$j];
            $vb[$x] = $vb[$x] ^ $vb[$j];
            $rc[] = ord($string[$i]) ^ ($vb[($vb[$x] + $vb[$j]) % 256]);
        }

        $res = '';
        foreach ($rc as $val) {
            $res .= sprintf('%02s', dechex($val));
        }

        return $key2 . $res;
    }

    public static function uidEncode($uid) {
        $len = 0;
        for ($i = 1; $i <= 8; $i++) {
            $max = (1 << (8 * $i)) - 1;
            if ($uid > $max) {
                continue;
            }
            $len = $i;
            break;
        }

        $buff = [];
        $buff[] = $len;
        for ($i = 0; $i < $len; $i++) {
            $b = ($uid >> (8 * $i)) & 0XFF;
            $b = $b ^ $len;
            $b = sprintf('%02s', dechex($b));
            $buff[] = $b;
        }

        return implode('', $buff);
    }

    public static function uidDecode($key) {
        $len = $key[0];
        if (strlen($key) != (2 * $len) + 1) {
            return 0;
        }
        $key = str_split($key);
        $key = array_slice($key, 1);

        $uid = 0;
        for ($i = 0; $i < $len; $i++) {
            $b = array_slice($key, 0, 2);
            $b = hexdec(implode('',$b));
            $b = $b ^ $len;
            $uid |= $b << (8 * ($i));
            $key = array_slice($key, 2);
        }

        return $uid;
    }

    // 熊猫看书游戏入口屏蔽开关
    public static function game_switch()
    {
        $G = Yaf_Registry::get('G');
        $plugInfo = array(
            'id' => 653,
            'name' => '屏蔽游戏中心入口开关'
        );
        $plugInfo["method"] = __CLASS__ . "->" . __FUNCTION__;
        $data = Local_PlugCenter::getData($plugInfo);
        if ($data['p2'] && $data['p7'] && $data['p10']) {
            if ($G['pp']['p2'] == $data['p2'] &&
                $G['pp']['p7'] == $data['p7'] &&
                $G['pp']['p10'] == $data['p10']
            ) {
                return true;
            }
        }

        return false;
    }

    //验证身份证有效性
    //无效返回false
    //有效返回出生年月日unix时间戳
    public static function checkIdcode($id)
    {
        $id = strtoupper($id);
        $preg = '/(^\d{15}$)|(^\d{17}([0-9]|X)$)/';
        if (!preg_match($preg, $id)) {
            return false;
        }
        //15位身份证号码
        if (15 == strlen($id)) {
            $preg = '/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/';
            preg_match($preg, $id, $match);
            if (empty($match)) {
                return false;
            }
            $birth = '19' . $match[2] . '-' . $match[3] . '-' . $match[4];
            $birthUnix = strtotime($birth);
            if (!$birthUnix) {
                return false;
            }
            return $birthUnix;
        }

        //剩下就是18位的身份证号码
        $intCode = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $chCode = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
        $sign = 0;
        for ($i = 0; $i < 17; $i++) {
            $b = (int)$id[$i];
            $w = $intCode[$i];
            $sign += $b * $w;
        }
        $n = $sign % 11;
        $num = $chCode[$n];
        if ($num != $id[17]) {
            return false;
        }

        $birth = substr($id, 6, 4) . '-' . substr($id, 10, 2) . '-' . substr($id, 12, 2);
        $birthUnix = strtotime($birth);
        if (!$birthUnix) {
            return false;
        }
        return $birthUnix;
    }
    
    /**
     * 十六进制转RGB
     * @param string $color 16进制颜色值
     * @return array
     */
    public static function hex2rgb($color) {
        $hexColor = str_replace('#', '', $color);
        $lens = strlen($hexColor);
        if ($lens != 3 && $lens != 6) {
            return false;
        }
        $newcolor = '';
        if ($lens == 3) {
            for ($i = 0; $i < $lens; $i++) {
                $newcolor .= $hexColor[$i] . $hexColor[$i];
            }
        } else {
            $newcolor = $hexColor;
        }
        $hex = str_split($newcolor, 2);
        $rgb = [];
        foreach ($hex as $key => $vls) {
            $rgb[] = hexdec($vls);
        }
        return $rgb;
    }
    
    /**
     * 按概率抽奖
     * @param array $proArr 奖池数组 例：
     * $prize_arr = array(
        '0' => array('id'=>1,'prize'=>'平板电脑','v'=>1),
        '1' => array('id'=>2,'prize'=>'数码相机','v'=>5),
        '2' => array('id'=>3,'prize'=>'音箱设备','v'=>10),
        '3' => array('id'=>4,'prize'=>'4G优盘','v'=>12),
        '4' => array('id'=>5,'prize'=>'10Q币','v'=>22),
        '5' => array('id'=>6,'prize'=>'下次没准就能中哦','v'=>50),
     ); 
     * @return array
     */
    public static function getRandLottery($proArr) {
        $result = '';
     
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
     
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
     
        return $result;
    }
    
    //获取2021中秋国庆活动每天指定的三本书中的一本
    public static function getMoonSpBook()
    {
        $spArr = array(
            '20210927' =>685640121,
            '20210928' =>104748006,
            '20210929' =>887862121,
            '20210930' =>685640121,
            '20211001' =>104748006,
            '20211002' =>887862121,
        );
        if(isset($spArr[date('Ymd')])){
            return $spArr[date('Ymd')];
        }else{
            return 0;
        }
    }
}
