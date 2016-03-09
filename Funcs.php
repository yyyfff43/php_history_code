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
        );
        
        return $apps;
    }

    public static function auth()
    {
        $appid = isset($_GET['appid']) && $_GET['appid'] ? $_GET['appid'] : '';
        if (! $appid) {
            self::showHeader('403');
        }
        
        $sign = isset($_GET['sign']) && $_GET['sign'] ? $_GET['sign'] : '';
        if (! $sign) {
            self::showHeader('403');
        }
        
        $apps = self::getAppKey();
        $key = isset($apps[$appid]) && $apps[$appid] ? $apps[$appid] : '';
        if (! $key) {
            self::showHeader('403');
        }
        
        $params = $_GET;
        ksort($params);
        $params = array_map("urldecode", $params);
        $string = '';
        foreach ($params as $k => $v) {
            $string .= $k . '=' . $v . '&';
        }
        $string = $string . $key;
        $vSign = md5($string);
        
        if ($vSign != $sign) {
            Bingo_Log::pushNotice('vstring', $string);
            Bingo_Log::pushNotice('vsign', $vSign);
            self::showHeader('400');
        }
        // print_r($params);
    }

    public static function showHeader($code, $msg = '')
    {
        switch ($code) {
            case '400':
                header('HTTP/1.0 400 Bad Request');
                if ($msg) {
                    echo $msg;
                }
                Bingo_Log::pushNotice('rescode', '400');
                Bingo_Log::buildNotice();
                exit();
                break;
            case '403':
                header('HTTP/1.0 403 Forbidden');
                echo 'No authorization';
                Bingo_Log::pushNotice('rescode', '403');
                Bingo_Log::buildNotice();
                exit();
                break;
        }
    }

    public static function uuid($key = null)
    {
        $key = ($key == null) ? uniqid(rand(), true) : $key;
        $chars = md5($key);
        $uuid = substr($chars, 0, 8) . '';
        $uuid .= substr($chars, 8, 4) . '';
        $uuid .= substr($chars, 12, 4) . '';
        $uuid .= substr($chars, 16, 4) . '';
        $uuid .= substr($chars, 20, 12);
        return $uuid;
    }

    public static function formatUrl($url)
    {
        $preg = '%^(^[^#?]*)(\?[^#]*)?(#.*)?$%';
        preg_match($preg, $url, $m);
        return $m;
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
            } else 
                if ("CP936" == $guessCharSet) {
                    return iconv("GBK", $outEncoding, $string);
                } else {
                    return $string;
                }
        }
    }

    public static function subString($String, $Length)
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
                $StringLast .= "...";
            }
            return $StringLast;
        }
    }

    public static function getChapterPrice($words)
    {
        $t_num = floor($words / 1000);
        $s_num = $words % 1000;
        
        $s_num = $s_num > 0 && $s_num < 500 ? $s_num : 1000;
        
        $price = floor(($t_num * 1000 + $s_num) / 1000);
        return $price <= 0 ? 5 : 5 * $price;
    }

    /**
     * 封面地址
     *
     * @param unknown $str            
     * @param unknown $type
     *            1:只取前缀
     * @return string
     */
    public static function fBkImg($str, $type = "")
    {
        $G = Yaf_Registry::get('G');
        $currDev = $G['currDev'];
        // $host = $currDev == 'online' ? 'http://img.m.baidu.com/novel/' : 'http://11.11.0.72:8090/';
        $host = 'http://img.shucheng.platform.zongheng.com/novel/';
        if ($type == 1) {
            return $host;
        }
        return $str ? (substr($str, 0, 7) == 'http://' ? $str : $host . $str) : '';
    }

    public static function getPrice($num)
    {
        $r = 200;
        if ($num > 200 && $num <= 500)
            $r = 500;
        if ($num > 500 && $num <= 1000)
            $r = 1000;
        if ($num > 1000 && $num <= 2000)
            $r = 2000;
        return $r;
    }

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
                if($nodes){
                	//非QA环境就略过
                	if(!in_array($_SERVER['SERVER_ADDR'], array(
                			'10.3.138.32',
                			'10.3.138.49',
                			'10.3.138.60'
                	))){
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
                    if (! isset($temp[$idxArr[$kk]]))
                        $temp[$idxArr[$kk]] = '';
                    
                    $temp[$idxArr[$kk]] = ($vv == '?' ? '' : htmlspecialchars_decode($vv));
                }
                $return[] = $temp;
            }
        }
        return $return;
    }

    public static function getMultiChapterDiscount($num)
    {
        // $discount = array(
        // '20' => 95,
        // '50' => 90,
        // '100' => 85
        // );
        // return isset($discount[$num]) ? $discount[$num] : 100;
        switch ($num) {
            case ($num >= 20 && $num < 50):
                $discount = 95;
                break;
            case ($num >= 50 && $num < 100):
                $discount = 90;
                break;
            case ($num >= 100):
                $discount = 85;
                break;
            default:
                $discount = 100;
                break;
        }
        
        return $discount;
    }

    public static function getfr($fr, $bkid = 0)
    {
        $plugInfo = array(
            "id" => 148,
            "name" => '中间层渠道(FR)书籍是否可读配置'
        );
        $plugInfo["method"] = __CLASS__ . "->" . __FUNCTION__;
        $data = Local_PlugCenter::getData($plugInfo);
        $data_res = Local_Funcs::parsePlugData($data['data'], array(
            'title'
        ));
        $topic_res = Local_Funcs::parsePlugData($data['topic_data'], array(
            'title',
            'topic_id'
        ));
        
        $resvalue = - 1;
        if ($data_res && $resvalue == - 1) {
            foreach ($data_res as $val) {
                if ($val['title'] == $fr) {
                    $resvalue = 0;
                    break;
                }
            }
        }
        if ($bkid && $topic_res && $resvalue == - 1) {
            $topic_id = 0;
            foreach ($topic_res as $val) {
                if ($val['title'] == $fr) {
                    $topic_id = $val['topic_id'];
                    break;
                }
            }
            
            if ($topic_id) {
                $BookRpc = Yaf_Registry::get('BookRpc');
                $res = $BookRpc->wx_judge_book_belong_topic($bkid, $topic_id);
                if (isset($res['result']) && $res['result'] == 1) {
                    $resvalue = 0;
                }
            }
        }
        
        return $resvalue;
    }

    public static function getCovrSize($width=0)
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
     * 获取用户昵称
     *
     * @param string $nickName
     *            昵称
     * @param integer $userId
     *            用户ID
     * @return string 用户昵称
     * @author SC
     */
    public static function getUserNickName($nickName, $userId, $thirdName = false, $bindPhone = false, $pandaUserName = false, $loginType = 'default')
    {
        if($loginType=='panda' || $loginType=='pandabaidu') {
            //在新熊猫后端注册的用户 thridName会用设备号标识，这种情况应该处理一下 应该显示用户ID
            if(preg_match('/[0-9a-z]{32}/i', $thirdName)) $thirdName = '';
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
     * 去除字符中制表符
     */
    public static function deleteHtml($str)
    {
        $str = trim($str); // 清除字符串两边的空格
        $str = strip_tags($str, ""); // 利用php自带的函数清除html格式
        $str = preg_replace("/\t/", "", $str); // 使用正则表达式替换内容，如：空格，换行，并将替换为空。
        $str = preg_replace("/\r\n/", "", $str);
        $str = preg_replace("/\r/", "", $str);
        $str = preg_replace("/\n/", "", $str);
        $str = preg_replace("/ /", "", $str);
        $str = preg_replace("/  /", "", $str); // 匹配html中的空格
        return trim($str); // 返回字符串
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
        // this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
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
        $ra1 = Array(
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
        
        $ra2 = Array(
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
            'SELECT',
            'INSERT',
            'DELETE',
            'UPDATE',
            'CREATE',
            'DROP',
            'VERSION',
            'DATABASES',
            'TRUNCATE',
            'HEX',
            'UNHEX',
            'CAST',
            'DECLARE',
            'EXEC',
            'SHOW',
            'CONCAT',
            'TABLES',
            'CHAR',
            'FILE',
            'SCHEMA',
            'DESCRIBE',
            'UNION',
            'JOIN',
            'ALTER',
            'RENAME',
            'LOAD',
            'FROM',
            'SOURCE',
            'INTO',
            'LIKE',
            'PING',
            'PASSWD'
        );
        
        return str_ireplace($replace, '', $str);
    }

    /**
     * 比较版本大小
     * 1.1.1 > 1.1.0.1
     *
     * @param unknown $s1            
     * @param unknown $s2            
     * @return number 1 : $s1 > $s2
     */
    public static function checkVersion($s1, $s2, $equal = true)
    {
        $a1 = explode('.', $s1);
        $a2 = explode('.', $s2);
        
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
                $v1 = (int) $a1[$k];
                $v2 = (int) $v;
                if ($v1 > $v2) {
                    $isTrue = 1;
                } elseif ($v1 == $v2) {
                    $isTrue = 1;
                    continue;
                } else {
                    $isTrue = - 1;
                }
                break;
            }
        } else {
            foreach ($a2 as $k => $v) {
                $v1 = (int) $a1[$k];
                $v2 = (int) $v;
                if ($v1 == $v2)
                    continue;
                $isTrue = $v1 > $v2 ? 1 : - 1;
                break;
            }
        }
        
        return $isTrue;
    }

    public static function parseDate($date)
    {
        $return = '';
        $time = strtotime($date);
        $now = time();
        $t = (int) $now - (int) $time;
        $year = floor($t / (3600 * 24 * 365));
        if ($year) {
            $return = $year . '年前';
        }
        if (! $return) {
            $month = floor($t / (3600 * 24 * 30));
            if ($month) {
                $return = $month . '个月前';
            }
        }
        if (! $return) {
            $day = floor($t / (3600 * 24));
            if ($day) {
                $return = $day . '天前';
            }
        }
        if (! $return) {
            $hour = floor($t / 3600);
            if ($hour) {
                $return = $hour . '小时前';
            }
        }
        if (! $return) {
            $sec = floor($t / 60);
            if ($sec) {
                $return = $sec . '分钟前';
            }
        }
        if (! $return) {
            $return = $t . '秒前';
        }
        return $return ? $return : $date;
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

    /**
     *
     * @param number $type
     *            2图片 65文字
     * @param unknown $key            
     * @return Ambigous <multitype:, multitype:NULL string >
     */
    public static function getBaiduXmlAd($type = 0, $key = array())
    {
        $return = array();
        $a = array();
        $url = 'http://cpro.baidu.com/cpro/ui/uijs.php?';
        
        // $key = array('游戏', '日用品', '服饰', '手机', '数码', '优惠', '图书', '电商', '折扣', '秒杀', '包邮', '省钱', '食品', '内测', '网游', '小说', '养生');
        // $key = array('美胸','赚钱','减肥','祛斑','致富','祛痘','丰胸','兼职','什么方法让阴茎变大','丰乳','祛斑','瘦脸','游戏','生殖器','丰乳','性保健','瘦身', '电子书','性生活','找工作');
        // $key = array('美胸', '减肥', '丰胸', '瘦脸', '美白', '祛斑', '性保健', '性生活', '人体艺术', '成人电影');
        // $key = array('美胸','早泄','癫痫病','白斑病','前列腺炎','羊羔疯','狐臭','人流','早泄与性生活','不孕不育','快播电影','黄色电影','性交','成人电影');
        
        $enKey = array_map('urlencode', $key);
        
        $a['i'] = ip2long($_SERVER['REMOTE_ADDR']);
        $a['p'] = 'duoku100';
        $a['q'] = '59074049_1_cpr';
        // 条数
        $a['n'] = 1;
        // $a['k'] = implode('+', $enKey);
        $a['c'] = 'news';
        $a['u'] = 'http://' . $_SERVER['SERVER_NAME'];
        $a['url'] = urlencode('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
        $a['at'] = 2;
        $a['t'] = 'baiduXMLBase';
        $a['width'] = '468';
        $a['height'] = '60';
        $a['prt'] = time();
        
        if ($type == 0)
            $a['at'] = 65; // 文本
        
        $param = http_build_query($a);
        $param .= '&k=' . implode('+', $enKey);
        $url .= $param;
        
        $content = $this->getUrl($url);
        // print_r($content);
        if ($content['http_code'] == 200 && $content['content']) {
            if ($type == 1) {
                $content['content'] = iconv('GBK', 'UTF-8//TRANSLIT', $content['content']);
                $xml = simplexml_load_string($content['content'], null, LIBXML_NOCDATA);
            } else {
                $xml = simplexml_load_string($content['content']);
            }
            // print_r($xml);
            if ($xml) {
                $arr = (array) $xml;
                $arr = json_decode(json_encode($arr), true);
                // print_r($arr);
                $return = array(
                    'text' => $arr['ads']['ad']['title'], // iconv("UTF-8","gbk//TRANSLIT",$content);
                    'tarurl' => $arr['ads']['ad']['curl'],
                    'desc' => $arr['ads']['ad']['desc'],
                    'imgurl' => isset($arr['ads']['ad']['material']) ? $arr['ads']['ad']['material'] : ''
                );
            }
        }
        return $return;
    }

    public static function getUrl($url, $cache = false, $postData = array(),$param = array())
    {
        $return = array();
        $needcache = false;
        if ($cache) {
            $key = NOVEL_CACHE_START . md5($url);
            $time = NOVEL_CACHE_TIME;
            $redis = DataProvider::getDataProvider('NOVEL_INTERFACE_CACHE');
            if (isset($_GET['debug']) && $_GET['debug'] == 'delcache') {
                $redis->del($key);
            }
            $data = $redis->get($key);
            if ($data) {
                $return = json_decode($data, true);
            } else {
                $needcache = true;
            }
        }
        if (! $return) {
            $stime = microtime(true);
            $ch = curl_init();
            
            if(isset($param['timeout'])){
            	$timeout = $param['timeout'];
            }else{
            	$timeout = 3;            	
            }

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if ($postData) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            }
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $handles = curl_exec($ch);
            $header = curl_getinfo($ch);
            curl_close($ch);
            $end = microtime(true);
            $time = $end - $stime;
            if ($time > 0.3) {
                Bingo_Log::warning('SLOW_API:' . $header["url"] . "[" . $time . "]", 'TIME_LOG');
            }
            $header['content'] = $handles;
            self::_save($header);
            $return = $header;
            if ($needcache) {
                $redis->setex($key, $time, json_encode($header));
            }
        }
        return $return;
    }
    private static function _save($res, $data = array())
    {
        $key = $res["url"];
        
        // 请求内容
        $log_str = str_replace(array(
            "\n",
            "\r\n",
            " ",
            PHP_EOL
        ), "", substr($res["content"], 0, 300));
        $log_str = json_encode($log_str);

        if ($res['http_code'] != 200) {
            Bingo_Log::warning('API_Error:' . $key . "[" . $log_str . "]", 'LOG_DAL');
        } else {
            $content = json_decode($res["content"], true);
            if (!empty($content)) {
                Bingo_Log::pushNotice($key, $log_str);
            } else {
                Bingo_Log::warning($key . "[" . $log_str . "]", 'LOG_DAL');
            }
        }
    }

    public static function getChapterZip($bkid, $crid, $token)
    {
        $return = array();
        // $url = KERNEL_HOST.'/v1/91down/zip?token='.$token.'&bkid='.$bkid;
        $url = KERNEL_HOST . '/v1/91down/zip?token=' . $token . '&bkid=' . $bkid;
        $res = $this->getUrl($url, false, 'crid=' . $crid);
        $res = json_decode($res['content'], true);
        // var_dump($res);
        if ($res['code'] === 0 && $res['result']) {
            $arr = explode(',', $crid);
            $zips = array();
            foreach ($res['result'] as $v) {
                $zips[$v['chapterid']] = $v;
            }
            foreach ($arr as $id) {
                $return[$id] = $zips[$id];
            }
        }
        // print_r($return);
        return $return;
    }

    public static function getChapterZipUrl($bkid, $crid, $token)
    {
        $return = array();
        $down_url = DOWN_HOST . '/download/file';
        
        // 处理图书、章节信息
        $crids = explode(',', trim($crid, ','));
        if ($bkid && $crids) {
            foreach ($crids as $id) {
                $file_key = $bkid . '-' . $id . '.zip';
                $return[$id] = array(
                    'bookid' => $bkid,
                    'chapterid' => $id,
                    'zipurl' => $down_url . '/' . $file_key . '?token=' . $token, // zip地址
                    'txturl' => ''
                );
                unset($file_key);
            }
        }
        // print_r($return);die();
        return $return;
    }

    public static function getPayChannels()
    {
        return array(
            array(
                'title' => '支付宝',
                'name' => 'alipay',
                'src' => 'http://res.baidukanshu.com/pandares/frame5.0/money/zhifubao_dk.png',
                'href' => VIEW_HOST . '/billing?tb=2',
                'actType' => 0,
                'isLastPay' => 5
            ),
            array(
                'title' => '话费充值',
                'name' => 'sms',
                'href' => VIEW_HOST . '/billing?tb=1',
                'src' => 'http://res.baidukanshu.com/pandares/frame5.0/money/duanxin_dk.png',
                'actType' => 5,
                'isLastPay' => 0
            ),
            array(
                'title' => '储蓄卡',
                'name' => 'bankcard',
                'href' => VIEW_HOST . '/billing?tb=5',
                'src' => 'http://res.baidukanshu.com/pandares/frame5.0/money/chuxuka_dk.png',
                'actType' => 5,
                'isLastPay' => 0
            ),
            array(
                'title' => ' 信用卡',
                'name' => 'creditcard',
                'href' => VIEW_HOST . '/billing?tb=6',
                'src' => 'http://res.baidukanshu.com/pandares/frame5.0/money/xinyongka_dk.png',
                'actType' => 5,
                'isLastPay' => 0
            ),
            array(
                'title' => '手机充值卡',
                'name' => 'smscard',
                'href' => VIEW_HOST . '/billing?tb=3',
                'src' => 'http://res.baidukanshu.com/pandares/frame5.0/money/shouji_dk.png',
                'actType' => 5,
                'isLastPay' => 0
            ),
            array(
                'title' => '更多',
                'href' => VIEW_HOST . '/billing',
                'src' => 'http://res.baidukanshu.com/pandares/frame5.0/money/more_dk.png',
                'actType' => 2,
                'isLastPay' => 0
            )
        );
    }

    public static function getGift($param = array())
    {
        $return = array();
        $url = KERNEL_HOST . '/v1/91gift/get?' . http_build_query($param);
        $res = $this->getUrl($url, false);
        $res = json_decode($res['content'], true);
        if ($res['code'] === 0 && $res['result']) {
            $return = $res['result'];
        }
        
        return $return;
    }
    
    /**
     * 通过书籍粉丝积分获取相应等级信息
     * 
     * @param integer $score 书籍粉丝积分
     * @return array 书籍粉丝等级信息
     * @author SC
     */
    public static function getBookFansLevelByScore($score)
    {
        $bookFansLevelMap = array(
            // 等级 => array(该等级的最低积分（含边界值）, 该等级的最高积分（含边界值）, 头衔, 荣誉标识)
            0 => array(0, 499, '见习', 'http://img.m.shucheng.baidu.com/operateimg/novel/b8/b85711474644195894ccb82e244bc49d.png'),
            1 => array(500, 1999, '学徒', 'http://img.m.shucheng.baidu.com/operateimg/novel/3b/3b6ff5ee41cc9a1d626731d9e32e5b63.png'),
            2 => array(2000, 4999, '弟子', 'http://img.m.shucheng.baidu.com/operateimg/novel/ef/ef6be0c1c76bfcd59df4d0d3e7be19f6.png'),
            3 => array(5000, 9999, '执事', 'http://img.m.shucheng.baidu.com/operateimg/novel/c1/c121984ad75c006864a05295a2ba1dbd.png'),
            4 => array(10000, 19999, '舵主', 'http://img.m.shucheng.baidu.com/operateimg/novel/ca/caf4c622e0363fec1e5f326c611a8e4a.png'),
            5 => array(20000, 29999, '堂主', 'http://img.m.shucheng.baidu.com/operateimg/novel/f6/f6afc145947bbdacb49ea3840f3994e4.png'),
            6 => array(30000, 39999, '护法', 'http://img.m.shucheng.baidu.com/operateimg/novel/1e/1eb3d09e3aed46628e8d768d7c6ef902.png'),
            7 => array(40000, 49999, '长老', 'http://img.m.shucheng.baidu.com/operateimg/novel/be/bea3b611b0192c8eb0c937ae8c5b23c2.png'),
            8 => array(50000, 69999, '掌门', 'http://img.m.shucheng.baidu.com/operateimg/novel/ce/ce5a73fca140837b3e7cf8344cebcb49.png'),
            9 => array(70000, 99999, '宗师', 'http://img.m.shucheng.baidu.com/operateimg/novel/27/273a38bf912e2e652b30792fca5558fd.png'),
            10 => array(100000, 149999, '盟主', 'http://img.m.shucheng.baidu.com/operateimg/novel/aa/aac06d41969fa1f1f5a1ef1d4b9dde13.png'),
            11 => array(150000, 199999, '地仙', 'http://img.m.shucheng.baidu.com/operateimg/novel/13/130be1045f613b86cfb43def44c7f238.png'),
            12 => array(200000, 299999, '天仙', 'http://img.m.shucheng.baidu.com/operateimg/novel/96/96e6728df2316722f7dcf6359d27c8b6.png'),
            13 => array(300000, 399999, '金仙', 'http://img.m.shucheng.baidu.com/operateimg/novel/b3/b3a60a0341e7c900f41090e3640a4d84.png'),
            14 => array(400000, 499999, '仙君', 'http://img.m.shucheng.baidu.com/operateimg/novel/4e/4eb4646fc5d0c568b8d87e3ecb06223d.png'),
            15 => array(500000, 599999, '仙帝', 'http://img.m.shucheng.baidu.com/operateimg/novel/cb/cbd408c1dca18d5b869cbc331d85f494.png'),
            16 => array(600000, 699999, '仙皇', 'http://img.m.shucheng.baidu.com/operateimg/novel/6f/6f13cb294a9c775428535455dd0f85f9.png'),
            17 => array(700000, 799999, '仙尊', 'http://img.m.shucheng.baidu.com/operateimg/novel/73/737b06f47f113250a6a4b2c5ef4e42a0.png'),
            18 => array(800000, 899999, '神帝', 'http://img.m.shucheng.baidu.com/operateimg/novel/8d/8d7f0a8d156994e851fa741ce7992f36.png'),
            19 => array(900000, 999999, '神皇', 'http://img.m.shucheng.baidu.com/operateimg/novel/9e/9e132ac89038a150cfcf8c433ca64e81.png'),
            20 => array(1000000, null, '神尊', 'http://img.m.shucheng.baidu.com/operateimg/novel/da/dac7032798d9be7ffb34531e34e57858.png'),
        );
        
        $returnFlag = false;
        foreach ($bookFansLevelMap as $level => $levelInfo) {
            list($minScore, $maxScore, $title, $icon) = $levelInfo;
            
            if ($minScore !== null && $maxScore !== null) {
                if ($minScore <= $score && $score <= $maxScore) $returnFlag = true;
            } elseif ($maxScore === null) {    // 最高级别无积分上限
                if ($minScore <= $score) $returnFlag = true;
            }
            
            if ($returnFlag) {
                return array(
                    'level' => $level,
                    'title' => $title,
                    'icon' => $icon,
                );
            }
        }
        
        return array();
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
        foreach($b as $ch) {
            $str .= chr($ch);
        }
    
        return md5($str);
    }
    
    /**
     * 按位进行与运算加密
     * 两次异或变原文
     */
    public static function exclusiveor($string, $key = '11001100'){
        $str = "";
        $keylen = strlen($key);
        for($i=0;$i<strlen($string);$i++){
            $k = $i%$keylen;
            $str .= $string{$i} ^ $key{$k}; //按位进行与运算，从而实现加密
        }
        return $str; //返回加密后的值
    }
    
    /**
     * 由giftid获取对应的礼券描述
     * @param int $giftid
     */
    public static function get_gift_name_byid($giftid){
    	
    	$gift_arr = array(
    			'0' => '看小说送礼券',
    			'1' => '充熊猫币送礼券',
    			'2' => '1月份活动礼券',
    			'3' => '2月份活动礼券',
    			'4' => '补偿礼券',
    			'5' => '元旦活动送礼券',
    			'6' => '春节活动送礼券',
    			'7' => '3G书城读书送礼券',
    			'8' => '3月份活动礼券',
    			'9' => '4月份活动礼券',
    			'10' => '5月份活动礼券',
    			'11' => '6月份活动礼券',
    			'12' => '7月份活动礼券',
    			'13' => '8月份活动礼券',
    			'14' => '9月份活动礼券',
    			'15' => '10月份活动礼券',
    			'16' => '11月份活动礼券',
    			'17' => '12月份活动礼券',
    			'18' => '团购返利',
    			'19' => '纵横烟雨新区活动赠送礼券',
    			'20' => '网龙内部员工充值礼券',
    			'21' => '充值活动抽奖礼券',
    			'22' => '3G书城消费返券',
    			'23' => '首次充值返礼券',
    			'24' => '5月消费返券',
    			'25' => '5月份充值返利礼券',
    			'26' => '母亲节活动礼券',
    			'27' => '打赏返还礼券',
    			'28' => '六一登录礼券',
    			'29' => '端午节礼券1',
    			'30' => '端午节礼券2',
    			'31' => '中秋充值返还礼券',
    			'32' => '12月充值返还礼券',
    			'33' => '阅读限免第一期礼券',
    			'34' => '微博分享礼券',
    			'35' => '阅读限免第二期礼券',
    			'36' => '元宵活动礼券',
    			'37' => '天界活动礼券',
    			'38' => '5月份签到礼券[当月有效]',
    			'39' => '本周签到礼券',
    			'40' => '评论打赏礼券',
    			'41' => '安装应用送礼券',
    			'42' => '购书返还礼券',
    			'43' => '注册赠送礼券',
    			'44' => '绑定手机号送礼券',
    			'49' => '幸运大转盘礼券',
    			'50' => '本周分享礼券',
    			'51' => '升级送礼券',
    			'100' => '特殊礼券',
    			'200' => '活动礼券',    			
    	);
    	
    	if(isset($gift_arr[$giftid])&&$gift_arr[$giftid]!=''){
    		return $gift_arr[$giftid];
    	}else{
    		return '';
    	}
    }
    
    // 熊猫看书ios充值屏蔽开关
    public static function ios_appstore_switch()
    {
        // 熊猫看书ios充值屏蔽开关
        $plugInfo = array(
            "id" => 328,
            "name" => '熊猫看书ios充值屏蔽开关'
        );
        $plugInfo["method"] = __CLASS__ . "->" . __FUNCTION__;
        $data = Local_PlugCenter::getData($plugInfo);
        if ($data['p2'] && $data['p7'] && $data['p10']) {
            $G = Yaf_Registry::get('G');
            if($G['pb']['p2'] == $data['p2'] && $G['pb']['p7'] == $data['p7'] && $G['pb']['p10'] == $data['p10']){
                return true;
            }
        }
    
        return false;
    }
    
    /**
     * 添加全局参数
     *
     * @param unknown $url
     * @return string
     */
    public static function url($url, $addParam = array(), $f = false)
    {
    	/* ------带有http参数的地址判断---这样就支持外连接了---------- */
    	$outer_link = (substr($url, 0, 7) == 'http://') || (substr($url, 0, 7) == 'https:/');
    	if ($outer_link) {
    		$addParam["url"] = urlencode($url);
    		return self::url("/sys/log", $addParam);
    	}
    	/* ------带有http参数的地址判断------这样就支持外连接了------- */
    
    	$G = Yaf_Registry::get('G');
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
    				if (isset($G[$k]) && $G[$k])
    					$currArr[$k] = $G[$k];
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
     * 全局参数配置
     *
     * @return multitype:number
     */
    public static function getParams()
    {
    	return self::$gp;
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
     * 风险控制
     * @param unknown $params
     * @return boolean
     */
    public static function RiskControl($params=array())
    {
        if(is_array($params) && $params){
            $redis = Local_DataProvider::getDataProvider('COMMON_MIX_USE');
            foreach($params as $key=>$val){
                $count = (int)$redis->get($key);
                if($count <= (int)$val['count']){
                    if($count < 1){
                        $redis->multi();
                        $redis->incr($key);
                        $redis->expire($key, (int)$val['time']);
                        $redis->exec();
                    }else{
                        $redis->incr($key);
                    }
                }else{
                    $redis->expire($key, 600);//超次数请求锁定10分钟
                    Bingo_Log::warning('Risk_Control:' . $key . "[" . (int)$val['time'] . "S内尝试请求接口超过" . (int)$val['count'] ."次]", 'LOG_DAL');
                    return true;
                }
            }
        }
        
        return false;
    }
}
