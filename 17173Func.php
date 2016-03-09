<?php
/**
 * 常用函数类
 * @author 吴昭宏(john.wu) <zhaohong.wu@sohu.com>
 * 
 */


class FrontHelper
{
    
    public static function hashCode($s)
    {
		// based on JDK1.5 String.hashCode()
		//$domain = 'ue' + (((hashCode($3) & 0x7fffffff) % 3) + 1) + '.17173.itc.cn';
		$h = 0;
		for ($i=0, $n=strlen($s); $i<$n; $i++) {
			$h = $h*31 + ord($s{$i});
			$h = $h & $h; // convert to 32bit integer
		}
		
		return $h;
	}

	/**
     * 图片地址转换
     * @param string $year 年份
     * @param string $s 图片服务器存储路径（相对于项目根路径）
     */
	public static function imageUrlConvert($year, $s, $sign=null)
	{
		if (!$sign)
		{
			$sign = Yii::app()->params['upload_server_sign'];
		}
		$pos = strpos($s, '/', 1);
		$domain='i'.(((self::hashCode($s) & 0x7fffffff) % 3) + 1).'.17173.itc.cn';
		$s = $year.'/uploads/'.$sign.'/vlog'.substr($s, $pos);
		$url = 'http://'.$domain.'/'.$s;
		if($sign == 'old'){
			$url = str_replace('images/video/', '', $url);
		}else{
			$key_str_num = substr_count($url, 'images/video/'); 
			if($key_str_num==0){
			  $url = str_replace('/vlog/', '/vlog/images/video/', $url);
			}			
		}
		//后台视频广场上传图片
		if(strpos($url,'square') !== false){
		      $url = str_replace('/vlog/images/video/','/vlog/' , $url);
		 }
		 
		 //前台个人空间认证的图片地址
		 if(strpos($url,'album') !== false){
		      $url = str_replace('/vlog/images/video/','/vlog/' , $url);
		 } 
		 
		return $url;
	}
	
    /**
	 * 转换数组中所有元素的字符集
	 * @param array $array 源数组
	 * @param string $to 目标字符集
	 * @param string $from 源字符集
	 */
	public static function arrayCharsetConvert(& $array, $to, $from = 'GBK'){
		if (is_array($array)) {
			foreach ($array as $key => $item) {
				if (is_array($item)) {
					self::arrayCharsetConvert($array[$key], $to, $from);
				} else {
					$array[$key] = iconv($from, $to, $item);
				}
			}
		}
	}
	/**
     * 自动创建目录
     * @param string $destFolder 服务器路径
     */
    public static function makeDir($destFolder) {
        if (!is_dir($destFolder) && $destFolder!='./' && $destFolder!='../') {
            $dirname = '';
            $folders = explode('/',$destFolder);
            foreach ($folders as $folder) {
                $dirname .= $folder . '/';
                if ($folder!='' && $folder!='.' && $folder!='..' && !is_dir($dirname)) {
                    mkdir($dirname);
                }
            }
            chmod($destFolder,0755);
        }    
    }
	/**
	 * 获取客户端ip
	 */
	public static function getClientIp(/*boolean*/$flag=false) {
		$ip=false;
	    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
	        $ip = $_SERVER["HTTP_CLIENT_IP"];
	 	}
		if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$ips = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
			if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
			for ($i = (count($ips)-1); $i >= 0; $i--) {
				if (!preg_match("/^(10|172\.16|192\.168)\./", trim($ips[$i]))) {
					$ip = $ips[$i];
					break;
		   		}
	  		}
		}
		$ip = ($ip ? $ip : $_SERVER["REMOTE_ADDR"]);
	
			
		if ($flag)
			$ip = sprintf("%u",ip2long($ip));
	
		return $ip;
	}
	/**
	 * 判断是否登录
	 * 
	 */
	public static function validateLogin()
	{
		if(Yii::app()->user->hasState('login_user_id')){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 判断用户登录并返回登录用户id
	 * @return uid/null
	 */
	public static function getLoginUserId()
	{
            $userId = intval(Yii::app()->user->getState('login_user_id'));
            if (is_numeric($userId) && $userId > 0) {
                Yii::log("Plog:GotSession:Uid:".$userId."--Ip:".CoreHelper::getRealIp()."--Uri:".YII::app()->request->getRequestUri()."--".date("Y-m-d H:i:s")."\n",'info','Passport.Log');
            } else {
                #读取cookie
                $pp = new Passport();
                $cookie_uid = intval($pp->getUid());
                if( empty($cookie_uid) ) {
                   Yii::log("Plog:NoCookie:Ip:".CoreHelper::getRealIp()."--Uri:".YII::app()->request->getRequestUri()."--".date("Y-m-d H:i:s")."\n",'info','Passport.Log'); 
                } else {
                    if ($pp->authToken()) {
                        $userId = intval($pp->getUserInfo('uid'));
                        Yii::app()->user->setState('login_user_id', $userId);
                    }
                    Yii::log("Plog:NoSession:Uid:".$userId."--Ip:".CoreHelper::getRealIp()."--Uri:".YII::app()->request->getRequestUri()."--".date("Y-m-d H:i:s")."\n",'info','Passport.Log');
                }
            }
            
            return $userId;
	}
	
	
	/**
     * 获取服务端ip
     */
    public static function getMyIp($dest='64.0.0.0', $port=80)
	{
		$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		socket_connect($socket, $dest, $port);
		socket_getsockname($socket, $addr, $port);
		socket_close($socket);
		return $addr;
	}
	
	/**
	 * 获取文件扩展名
	 */
	public static function getFileExtensionName($name)
	{
		if(($pos=strrpos($name,'.'))!==false)
			return (string)substr($name,$pos+1);
		else
			return '';
	}
	
	/**
	 * 转换字符串中的中文为拼音
	 * @param $string 
	 * @param $charset 字符集 默认utf-8 
	 * @param $format 转换格式 index 索引字母 initial 首字母 normal 全拼
	 */
	public static function stringPinyinConvert($string,$format='index',$charset='utf-8')
	{
		if(empty($string)) return '';
		$pinyin=new Pinyin();
		
		switch($format){
			case 'index':
				$spell=$pinyin->main($string,$charset,true);			
				$spell=substr($spell,0,1);
				break;
			case 'initial':
				$spell=$pinyin->main($string,$charset,true);
				break;
			case 'normal':
				$spell=$pinyin->main($string,$charset,false);
				break;
			default:
				$spell='';			
		}
			
		return $spell;
	}

	
	/**
	 * b转MB，精确到小数点后2位
	 *
	 */
	public static function fileSizeBKM($size)
	{ // B/KB/MB单位转换
		if($size < 1024)
		{
			$size_BKM = (string)$size . " B";
		}
		elseif($size < (1024 * 1024))
		{
			$size_BKM = number_format((double)($size / 1024), 2) . " KB";
		}else
		{
			$size_BKM = number_format((double)($size / (1024*1024)),2)." MB";
		}
		return $size_BKM;
	}
	/**
	 * 分页GET参数xss过滤
	 * @param array $data
	 */
	public static function htmlEncodeArray($data)
	{
		$d=array();
		foreach($data as $key=>$value)
		{
			if(is_string($key))
				$key=htmlspecialchars($key,ENT_QUOTES,Yii::app()->charset);
			if(is_string($value)){
				$value=!mb_check_encoding($value, 'UTF-8')?iconv('gb2312', 'utf-8', $value):$value;
				$value=htmlspecialchars($value,ENT_QUOTES,Yii::app()->charset);
			}
			else if(is_array($value))
				$value=self::htmlEncodeArray($value);
			$d[$key]=$value;
		}
		return $d;
	}
	
	/**
	 * 转为时分秒时间 00：00：00
	 * @param   int  seconds  秒数
	 * @return  string
	 */
	public static function ftime($seconds){
		$seconds = intval($seconds);
		if($seconds>0){
			if ($seconds>3600){
				$time = gmstrftime('%H:%M:%S', $seconds);
			}else{
				$time = gmstrftime('%M:%S', $seconds);
			}
			return $time;
		}else{
			return 0;
		}
	}
	/**
	 * 获取图片域名
	 * @param int $type 类型(0:前台;1:后台,默认前台)
	 * @return string
	 */
	public static function getImgDomain($type = 0){
		if($type == 0){
			return "i" . mt_rand(1,9) . ".17173.itc.cn";
		}else{
			return "images.17173.com";
		}
	}
	public static function format_upload_time($time){
		$d_time = time() - $time;
		$d_day = floor($d_time / 86400);
		$d_hours = floor($d_time / 3600);
		$d_minutes = floor($d_time / 60);
		if($d_day > 0 && $d_day < 30){
			return $d_day . "天前";
		}else if($d_day <= 0 && $d_hours > 0){
			return $d_hours . "小时前";
		}else if($d_hours <= 0 && $d_minutes > 0){
			return $d_minutes . "分钟前";
		}else{
			return date('Y-m-d', $time);
		}
	}

	/*
	 * 格式化数字
	 * @param int $number
	 * @return string
	 */
	public static function format_number($number){
		if($number > 100000000){
			return round($number / 100000000,1).'亿';
		}elseif($number > 10000){
			return round($number/10000,1).'万';
		}else{
			return number_format($number);
		}
	}
	
	/*
	 * 格式化时间  
	* @param int $datetime 时间戳
	* @return string
	A 1个小时内的行为，显示：在xx分钟前，赞了我！/在xx分钟前，赞了TA！
	B 1小时至24小时之间的行为，显示：在xx小时前，攒了我！/ 在xx小时前，赞了TA！
	C 24小时至96小时之间的行为，显示：在xx天前赞了我！/在 xx天前，赞了TA！
	D 96小时之后且在当年度内，显示：在2月3日，赞了我！/在 2月3日，赞了TA！
	E 上个年度之前，显示：在2012年11月4日，攒了我！/在2012年11月4日，攒了TA！* 
	*/
	public static function format_datetime($datetime){
		$result = '';
		$nowtime = time();
		$differ = $nowtime - $datetime;//相差秒数
		if($nowtime - 3600<$datetime){
			$result = floor($differ/60).'分钟前';
		}elseif($nowtime - 3600*24<$datetime){
			$result = floor($differ/3600).'小时前';
		}elseif($nowtime - 3600*96<$datetime){
			$result = floor($differ/(3600*24)).'天前';
		}elseif(date('Y',$nowtime) == date('Y',$datetime)){
			$result = date('n月j日',$datetime);
		}else{
			$result = date('Y年n月j日',$datetime);
		}
		return $result;
	}	
	/*
	 * 格式化数字
	 * @param string $url 网址
	 * @param array $paraArr 参数数组	
	 * @param string $type get 或 post 请求
	 * @return string //请求结果
	 */
	public static function curl($url,$paraArr=array(),$type='get'){
		$ch = curl_init();	
		if($type=='get'){
			if($paraArr){
				$url .='?';
			}
			foreach($paraArr as $key=>$value){
				$url .=$key.'='.$value.'&';
			}
		}elseif($type=='post'){
			curl_setopt($ch, CURLOPT_POST, 1); //设置为POST传输
			curl_setopt($ch, CURLOPT_POSTFIELDS, $paraArr); //post过去数据			
		}	
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //时候将获取数据返回		
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}	
	/*
	* 生成不重复随机数
	* @param int $min 最小值
	* @param int $max 最大值
	* @param int $num 数量
	* @return array 
	*/
	public static function getNoRepeatNum($min=0,$max,$num=1){
		$result = array();
		$num = ($max-$min)<$num ? ($max-$min)+1 : $num;
		$connt = 0;
		while($connt<$num){
			$a[]=rand($min,$max);//产生随机数
			$result=array_unique($a);
			$connt=count($result);
		}
		return $result;
	}
	/*
	 * 二维数组按照指定的键值进行排序
	 * @param arrty 排序数组
	 * @param string 键 
	 * @param string 排序方式 
	 * @return array
	 */	
	public static function sysSortArray($ArrayData,$KeyName1,$SortOrder1 = "SORT_ASC",$SortType1 = "SORT_REGULAR")
	{
	    if(!is_array($ArrayData))
	    {
	        return $ArrayData;
	    }
	    $ArgCount = func_num_args();
	    for($I = 1;$I < $ArgCount;$I ++)
	    {
	        $Arg = func_get_arg($I);
	        if(!preg_match("/SORT/i",$Arg))
	        {
	            $KeyNameList[] = $Arg;
	            $SortRule[]    = '$'.$Arg;
	        }
	        else
	        {
	            $SortRule[]    = $Arg;
	        }
	    }
	    foreach($ArrayData AS $Key => $Info)
	    {
	        foreach($KeyNameList AS $KeyName)
	        {
	            ${$KeyName}[$Key] = $Info[$KeyName];
	        }
	    }
	    $EvalString = 'array_multisort('.join(",",$SortRule).',$ArrayData);';
	    eval ($EvalString);
	    return $ArrayData;
	}
	/*
	 * 生成页面Title,keywords,description
	 */
	public static function get_seosetting($type,$data=array(),$object){
		$searchs = array();
		$replaces = array();
		$seotitle = $seodescription = $seokeywords = '';
		$seotitle = !empty(Yii::app()->params['seo'][$type]['title']) ? Yii::app()->params['seo'][$type]['title'] : '';
		$seodescription = !empty(Yii::app()->params['seo'][$type]['description']) ? Yii::app()->params['seo'][$type]['description'] : '';
		$seokeywords = !empty(Yii::app()->params['seo'][$type]['keywords']) ? Yii::app()->params['seo'][$type]['keywords'] : '';
		
		preg_match_all("/\{(\S+?)\}/", $seotitle.$seodescription.$seokeywords, $pageparams);
		if($pageparams){
			foreach($pageparams[1] as $var) {
				$searchs[] = '{'.$var.'}';
				$replaces[] = !empty($data[$var]) ? strip_tags($data[$var]) : '';
			}
			if($seotitle) {
				$seotitle = self::strreplace_strip_split($searchs, $replaces, $seotitle);
			}
			if($seodescription) {
				$seodescription = self::strreplace_strip_split($searchs, $replaces, $seodescription);
			}
			if($seokeywords) {
				$seokeywords = self::strreplace_strip_split($searchs, $replaces, $seokeywords);
			}						
		}
		$object->pageTitle = $seotitle;
		//Yii::app()->clientScript->registerMetaTag($seokeywords,'keywords');
		//Yii::app()->clientScript->registerMetaTag($seodescription,'description');
		Yii::app()->params['keywords'] = $seokeywords;
		Yii::app()->params['description'] = $seodescription;
		
	}
	public static function strreplace_strip_split($searchs, $replaces, $str) {
		$searchspace = array('((\s*\-\s*)+)', '((\s*\,\s*)+)', '((\s*\|\s*)+)', '((\s*\t\s*)+)', '((\s*_\s*)+)');
		$replacespace = array('-', ',', '|', ' ', '_');
		return trim(preg_replace($searchspace, $replacespace, str_replace($searchs, $replaces, $str)), ' ,-|_');
	}	
	/*
	* 根据出生日期得到年龄
	* @param string $date 出生日期 
	* @return string 
	*/
	public static function getAge($date){
		$year_diff = '';
		$time = strtotime($date);
		if(FALSE === $time){
			return '';
		}
		$date = date('Y-m-d', $time);
		list($year,$month,$day) = explode("-",$date);
		$year_diff = date("Y") - $year;
		$month_diff = date("m") - $month;
		$day_diff = date("d") - $day;
		if ($day_diff < 0 || $month_diff < 0) $year_diff–;
		return $year_diff;
	}
	
	/**
	 * 转换内容中的表情数据
	 * @param string $message
	 * @return string
	 */
	public static function convertFace($message){
		$message = htmlspecialchars($message);
		$pattern = '/\[(\w+)=(\d+)\]/';
		$replacement = "<img alt=\"$2\" src=\"http://images.17173.com/news/comment1/smile/$2.gif\" />";
		$message = preg_replace($pattern, $replacement, $message);
		return $message;
	}
	/**
	 * 计算中英混合字串长度
	 *
	 * @param String $str
	 * @param Int $mode 1-中英文每个字符算1个长度 2-英文数字等符号2个算1个长度
	 * @return Int
	 */
	private static function _strlen($str, $mode=1)
	{
		$len = 0;
	
		switch ($mode) {
			case 1:
				$len = mb_strlen($str, 'UTF-8');
				break;
			case 2:
				$len = floor(mb_strwidth($str, 'UTF-8') / 2);
				break;
			default:
				return false;
		}
		return $len;
	}
	
	public static function utf8Substr($str, $from, $len, $more='…')
	{	
		$return = '';
		$mblen = mb_strlen($str);
		$addlen = 0;
		$facelen = 0;
		for ($i=0; $i<$mblen; $i++){
			$char = mb_substr($str, $i, 1);
			if (strlen($char) == 1)
			{
				$facelen += 0.5;
			}else{
				$facelen += 1;				
			}
			$addlen++; 
			if($facelen>$len){
				$addlen--;		
				break;
			}elseif($facelen==$len){
				break;
			}
		}
		if($addlen >= $mblen){
			$return = $str;
		}else{
			$return = preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
					'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$addlen.'}).*#s',
					'$1',$str);			
			$return = $return.$more;
		}
		return $return;		
	}
	public static function getShort($str,$cutCount){
		$return = '';
		$count  = self::_strlen($str);
			
		if($count < $cutCount){
			$return = $str;
		}else{
			$return = self::utf8Substr($str,0,$cutCount).'...';
		}
			
		return $return;
	}

	
	/*
	 * 得到省信息
	 * @param int $id 省ID
	 * @param string $type 返回值数组类型 (默认array或json)
	 * @return string/array 
	 */
	public static function getProvince($id=0,$type=''){
		$arrayProvince=array(
				1=>"北京市",
				2=>"上海市",
				3=>"天津市",
				4=>"重庆市",
				5=>"黑龙江省",
				6=>"河北省",
				7=>"吉林省",
				8=>"辽宁省",
				9=>"河南省",
				10=>"山东省",
				11=>"山西省",
				12=>"陕西省",
				13=>"甘肃省",
				14=>"宁夏",
				15=>"内蒙古",
				16=>"青海省",
				17=>"新疆",
				18=>"西藏",
				19=>"四川省",
				20=>"湖北省",
				21=>"湖南省",
				22=>"安徽省",
				23=>"江苏省",
				24=>"江西省",
				25=>"浙江省",
				26=>"福建省",
				27=>"贵州省",
				28=>"广西省",
				29=>"广东省",
				30=>"云南省",
				31=>"海南省",
				33=>"台湾",
				34=>"香港",
				35=>"澳门",
				32=>"海外",
				36=>"其他",
		);
		if($id>0){
			return !empty($arrayProvince[$id]) ? $arrayProvince[$id] : '';
		}
		if($type=='json'){
			return !empty($arrayProvince) ? json_encode($arrayProvince) : json_encode(array());
		}else{
			return !empty($arrayProvince) ? $arrayProvince : array();
		}
	}
	/*
	 * 得到城市信息
	* @param int $province 省ID
	* @param int $id 城市ID	 
	* @param string $type 返回值数组类型 (默认array或json)
	* @return string/array
	*/
	public static function getCity($province,$id=0,$type=''){
		$arrayCity[1] = Array("不限");
		$arrayCity[2] = Array("不限");
		$arrayCity[3] = Array("不限");
		$arrayCity[4] = Array("不限");
		$arrayCity[5] = Array("不限","哈尔滨","齐齐哈尔","鸡西","鹤岗","双鸭山","大庆","伊春","佳木斯","七台河","牡丹江","黑河","绥化","大兴安岭");
		$arrayCity[6] = Array("不限","石家庄","唐山","秦皇岛","邯郸","邢台","保定","张家口","承德","沧州","廊坊","衡水");
		$arrayCity[7] = Array("不限","长春","吉林","四平","辽源","通化","白山","松原","白城","延边朝鲜族自治州");
		$arrayCity[8] = Array("不限","沈阳","大连","鞍山","抚顺","本溪","丹东","营口","阜新","辽阳","盘锦","铁岭","朝阳","葫芦岛");
		$arrayCity[9] = Array("不限","郑州","开封","洛阳","平顶山","安阳","鹤壁","新乡","焦作","濮阳","许昌","漯河","三门峡","南阳","商丘","信阳","周口","驻马店","济源");
		$arrayCity[10] = Array("不限","济南","青岛","淄博","枣庄","东营","烟台","潍坊","济宁","泰安","威海","日照","莱芜","临沂","德州","聊城","滨州","菏泽");
		$arrayCity[11] = Array("不限","太原","大同","阳泉","长治","晋城","朔州","晋中","运城","忻州","临汾","吕梁");
		$arrayCity[12] = Array("不限","西安","铜川","宝鸡","咸阳","渭南","延安","汉中","榆林","安康","商洛");
		$arrayCity[13] = Array("不限","兰州","嘉峪关","金昌","白银","天水","武威","张掖","平凉","酒泉","庆阳","定西","陇南","临夏","甘南");
		$arrayCity[14] = Array("不限","银川","石嘴山","吴忠","固原","中卫");
		$arrayCity[15] = Array("不限","呼和浩特","包头","乌海","赤峰","通辽","鄂尔多斯","呼伦贝尔","兴安盟","锡林郭勒盟","乌兰察布盟","巴彦淖尔盟","阿拉善盟");
		$arrayCity[16] = Array("不限","西宁","海东","海北","黄南","海南","果洛","玉树","海西");
		$arrayCity[17] = Array("不限","乌鲁木齐","克拉玛依","吐鲁番","哈密","昌吉","博尔塔拉","巴音郭楞","阿克苏","克孜勒苏","喀什","和田","伊犁","塔城","阿勒泰","石河子");
		$arrayCity[18] = Array("不限","拉萨","昌都","山南","日喀则","那曲","阿里","林芝");
		$arrayCity[19] = Array("不限","成都","自贡","攀枝花","泸州","德阳","绵阳","广元","遂宁","内江","乐山","南充","眉山","宜宾","广安","达州","雅安","巴中","资阳","阿坝","甘孜","凉山");
		$arrayCity[20] = Array("不限","武汉","黄石","十堰","宜昌","襄阳","鄂州","荆门","孝感","荆州","黄冈","咸宁","随州","恩施土家族苗族自治州","仙桃","潜江","天门","神农架");
		$arrayCity[21] = Array("不限","长沙","株洲","湘潭","衡阳","邵阳","岳阳","常德","张家界","益阳","郴州","永州","怀化","娄底","湘西土家族苗族自治州");
		$arrayCity[22] = Array("不限","合肥","芜湖","蚌埠","淮南","马鞍山","淮北","铜陵","安庆","黄山","滁州","阜阳","宿州","巢湖","六安","亳州","池州","宣城");
		$arrayCity[23] = Array("不限","南京","无锡","徐州","常州","苏州","南通","连云港","淮安","盐城","扬州","镇江","泰州","宿迁");
		$arrayCity[24] = Array("不限","南昌","景德镇","萍乡","九江","新余","鹰潭","赣州","吉安","宜春","抚州","上饶");
		$arrayCity[25] = Array("不限","杭州","宁波","温州","嘉兴","湖州","绍兴","金华","衢州","舟山","台州","丽水");
		$arrayCity[26] = Array("不限","福州","厦门","莆田","三明","泉州","漳州","南平","龙岩","宁德");
		$arrayCity[27] = Array("不限","贵阳","六盘水","遵义","安顺","铜仁","黔西南","毕节","黔东南","黔南");
		$arrayCity[28] = Array("不限","南宁","柳州","桂林","梧州","北海","防城港","钦州","贵港","玉林","百色","贺州","河池","来宾","崇左");
		$arrayCity[29] = Array("不限","广州","韶关","深圳","珠海","汕头","佛山","江门","湛江","茂名","肇庆","惠州","梅州","汕尾","河源","阳江","清远","东莞","中山","潮州","揭阳","云浮");
		$arrayCity[30] = Array("不限","昆明","曲靖","玉溪","保山","昭通","楚雄","红河","文山","思茅","西双版纳","大理","德宏","丽江","怒江","迪庆","临沧");
		$arrayCity[31] = Array("不限","海口","三亚","其他");
		$arrayCity[33] = Array("不限");
		$arrayCity[34] = Array("不限");
		$arrayCity[35] = Array("不限");
		$arrayCity[32] = Array("不限","美国","英国","法国","俄罗斯","加拿大","巴西","澳大利亚","印尼","泰国","马来西亚","新加坡","菲律宾","越南","印度","日本","新西兰","韩国","德国","意大利","爱尔兰","荷兰","瑞士","乌克兰","南非","芬兰","瑞典","奥地利","西班牙","比利时","挪威","丹麦","波兰","阿根廷","白俄罗斯","哥伦比亚","古巴","埃及","希腊","匈牙利","伊朗","蒙古","墨西哥","葡萄牙","沙特阿拉伯","土耳其","其他");
		$arrayCity[36] = Array("不限");
		if(empty($arrayCity[$province])){
			return $type=='json' ? json_encode($arrayCity) : $arrayCity;
		}
		if($id>=0){
			return !empty($arrayCity[$province][$id]) ? $arrayCity[$province][$id] : '';
		}
		if($type=='json'){
			return !empty($arrayCity[$province]) ? json_encode($arrayCity[$province]) : json_encode(array());
		}else{
			return !empty($arrayCity[$province]) ? $arrayCity[$province] : array();
		}
	}
}
