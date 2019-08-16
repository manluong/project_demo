<?php
function v2e_special($value){
	$value = str_replace("â", "a", $value);	
	$value = str_replace("ấ", "a", $value);
	$value = str_replace("ầ", "a", $value);
	$value = str_replace("ẩ", "a", $value);
	$value = str_replace("ẫ", "a", $value);
	$value = str_replace("ậ", "a", $value);
	#---------------------------------A^

	$value = str_replace("Â", "a", $value);	
	$value = str_replace("Ấ", "a", $value);
	$value = str_replace("Ầ", "a", $value);
	$value = str_replace("Ẩ", "a", $value);
	$value = str_replace("Ẫ", "a", $value);
	$value = str_replace("Ậ", "a", $value);
	#---------------------------------a

	$value = str_replace("á", "a", $value);
	$value = str_replace("à", "a", $value);
	$value = str_replace("ả", "a", $value);
	$value = str_replace("ã", "a", $value);
	$value = str_replace("ạ", "a", $value);
	#---------------------------------A

	$value = str_replace("Á", "a", $value);
	$value = str_replace("À", "a", $value);
	$value = str_replace("Ả", "a", $value);
	$value = str_replace("Ã", "a", $value);
	$value = str_replace("Ạ", "a", $value);
	#---------------------------------a(

	$value = str_replace("ă", "a", $value);	
	$value = str_replace("ắ", "a", $value);
	$value = str_replace("ằ","a", $value);
	$value = str_replace("ẳ", "a", $value);
	$value = str_replace("ẵ","a", $value);
	$value = str_replace("ặ", "a", $value);
	#---------------------------------A(

	$value = str_replace("Ă", "a", $value);
	$value = str_replace("Ắ", "a", $value);
	$value = str_replace("Ằ", "a", $value);
	$value = str_replace("Ẳ", "a", $value);
	$value = str_replace("Ẵ", "a", $value);
	$value = str_replace("Ặ", "a", $value);
	#---------------------------------e^

	$value = str_replace("ê", "e", $value);	
	$value = str_replace("ế", "e", $value);
	$value = str_replace("ề", "e", $value);
	$value = str_replace("ể", "e", $value);
	$value = str_replace("ễ", "e", $value);
	$value = str_replace("ệ", "e", $value);
	#---------------------------------E^

	$value = str_replace("Ê", "e", $value);	
	$value = str_replace("Ế", "e", $value);
	$value = str_replace("Ề", "e", $value);
	$value = str_replace("Ể", "e", $value);
	$value = str_replace("Ễ", "e", $value);
	$value = str_replace("Ệ", "e", $value);
	#---------------------------------e

	$value = str_replace("é","e", $value);
	$value = str_replace("è", "e", $value);
	$value = str_replace("ẻ", "e", $value);
	$value = str_replace("ẽ", "e", $value);
	$value = str_replace("ẹ", "e", $value);
	#---------------------------------E

	$value = str_replace("É", "e", $value);
	$value = str_replace("È", "e", $value);
	$value = str_replace("Ẻ", "e", $value);
	$value = str_replace("Ẽ", "e", $value);
	$value = str_replace("Ẹ", "e", $value);
	#---------------------------------i

	$value = str_replace("í", "i", $value);
	$value = str_replace("ì", "i", $value);
	$value = str_replace("ỉ", "i", $value);
	$value = str_replace("ĩ", "i", $value);
	$value = str_replace("ị", "i", $value);
	#---------------------------------I

	$value = str_replace("Í", "i", $value);
	$value = str_replace("Ì", "i", $value);
	$value = str_replace("Ỉ", "i", $value);
	$value = str_replace("Ĩ", "i", $value);
	$value = str_replace("Ị", "i", $value);
	#---------------------------------o^

	$value = str_replace("ô", "o", $value);	
	$value = str_replace("ố", "o", $value);
	$value = str_replace("ồ", "o", $value);
	$value = str_replace("ổ", "o", $value);
	$value = str_replace("ỗ", "o", $value);
	$value = str_replace("ộ", "o", $value);
	#---------------------------------O^

	$value = str_replace("Ô", "o", $value);	
	$value = str_replace("Ố", "o", $value);
	$value = str_replace("Ồ", "o", $value);
	$value = str_replace("Ổ", "o", $value);
	$value = str_replace("Ỗ", "o", $value);
	$value = str_replace("Ộ", "o", $value);
	#---------------------------------o*

	$value = str_replace("ơ", "o", $value);	
	$value = str_replace("ớ", "o", $value);
	$value = str_replace("ờ", "o", $value);
	$value = str_replace("ở", "o", $value);
	$value = str_replace("ỡ", "o", $value);
	$value = str_replace("ợ", "o", $value);
	#---------------------------------O*

	$value = str_replace("Ơ", "o", $value);	
	$value = str_replace("Ớ", "o", $value);
	$value = str_replace("Ờ", "o", $value);
	$value = str_replace("Ở", "o", $value);
	$value = str_replace("Ỡ", "o", $value);
	$value = str_replace("Ợ", "o", $value);
	#---------------------------------u*

	$value = str_replace("ư", "u", $value);	
	$value = str_replace("ứ", "u", $value);
	$value = str_replace("ừ", "u", $value);
	$value = str_replace("ử", "u", $value);
	$value = str_replace("ữ", "u", $value);
	$value = str_replace("ự", "u", $value);
	#---------------------------------U*

	$value = str_replace("Ư", "u", $value);	
	$value = str_replace("Ứ", "u", $value);
	$value = str_replace("Ừ", "u", $value);
	$value = str_replace("Ử", "u", $value);
	$value = str_replace("Ữ", "u", $value);
	$value = str_replace("Ự", "u", $value);
	#---------------------------------y

	$value = str_replace("ý", "y", $value);
	$value = str_replace("ỳ", "y", $value);
	$value = str_replace("ỷ", "y", $value);
	$value = str_replace("ỹ", "y", $value);
	$value = str_replace("ỵ", "y", $value);
	#---------------------------------Y

	$value = str_replace("Ý", "y", $value);
	$value = str_replace("Ỳ", "y", $value);
	$value = str_replace("Ỷ", "y", $value);
	$value = str_replace("Ỹ", "y", $value);
	$value = str_replace("Ỵ", "y", $value);
	#---------------------------------DD

	$value = str_replace("Đ", "d", $value);		
	$value = str_replace("đ", "d", $value);
	#---------------------------------o

	$value = str_replace("ó", "o", $value);
	$value = str_replace("ò", "o", $value);
	$value = str_replace("ỏ", "o", $value);
	$value = str_replace("õ", "o", $value);
	$value = str_replace("ọ", "o", $value);
	#---------------------------------O

	$value = str_replace("Ó", "o", $value);
	$value = str_replace("Ò", "o", $value);
	$value = str_replace("Ỏ", "o", $value);
	$value = str_replace("Õ", "o", $value);
	$value = str_replace("Ọ", "o", $value);
	#---------------------------------u

	$value = str_replace("ú", "u", $value);
	$value = str_replace("ù", "u", $value);
	$value = str_replace("ủ", "u", $value);
	$value = str_replace("ũ", "u", $value);
	$value = str_replace("ụ", "u", $value);
	#---------------------------------U

	$value = str_replace("Ú", "u", $value);
	$value = str_replace("Ù", "u", $value);
	$value = str_replace("Ủ", "u", $value);
	$value = str_replace("Ũ", "u", $value);
	$value = str_replace("Ụ", "u", $value);
	#---------------------------------

	return $value;
}

function v2e($value){
	#---------------------------------SPECIAL	
	$value = str_replace("&quot;","", $value);	
	$value = str_replace(".","", $value);
	$value = str_replace("=","", $value);
	$value = str_replace("+","", $value);
	$value = str_replace("!","", $value);
	$value = str_replace("@","", $value);
	$value = str_replace("#","", $value);
	$value = str_replace("$","", $value);
	$value = str_replace("%","", $value);	
	$value = str_replace("^","", $value);	
	$value = str_replace("&","", $value);	
	$value = str_replace("*","", $value);	
	$value = str_replace("(","", $value);	
	$value = str_replace(")","", $value);	
	$value = str_replace("`","", $value);	
	$value = str_replace("~","", $value);	
	$value = str_replace(",","", $value);
	$value = str_replace("/","", $value);	
	$value = str_replace("\\","", $value);	
	$value = str_replace('"',"", $value);	
	$value = str_replace("'","", $value);	
	$value = str_replace(":","", $value);	
	$value = str_replace(";","", $value);	
	$value = str_replace("|","", $value);	
	$value = str_replace("[","", $value);	
	$value = str_replace("]","", $value);	
	$value = str_replace("{","", $value);	
	$value = str_replace("}","", $value);	
	$value = str_replace("(","", $value);	
	$value = str_replace(")","", $value);		
	$value = str_replace("?","", $value);
	#---------------------------------a^

	$value = str_replace("â", "a", $value);	
	$value = str_replace("ấ", "a", $value);
	$value = str_replace("ầ", "a", $value);
	$value = str_replace("ẩ", "a", $value);
	$value = str_replace("ẫ", "a", $value);
	$value = str_replace("ậ", "a", $value);
	#---------------------------------A^

	$value = str_replace("Â", "a", $value);	
	$value = str_replace("Ấ", "a", $value);
	$value = str_replace("Ầ", "a", $value);
	$value = str_replace("Ẩ", "a", $value);
	$value = str_replace("Ẫ", "a", $value);
	$value = str_replace("Ậ", "a", $value);
	#---------------------------------a

	$value = str_replace("á", "a", $value);
	$value = str_replace("à", "a", $value);
	$value = str_replace("ả", "a", $value);
	$value = str_replace("ã", "a", $value);
	$value = str_replace("ạ", "a", $value);
	#---------------------------------A

	$value = str_replace("Á", "a", $value);
	$value = str_replace("À", "a", $value);
	$value = str_replace("Ả", "a", $value);
	$value = str_replace("Ã", "a", $value);
	$value = str_replace("Ạ", "a", $value);
	#---------------------------------a(

	$value = str_replace("ă", "a", $value);	
	$value = str_replace("ắ", "a", $value);
	$value = str_replace("ằ","a", $value);
	$value = str_replace("ẳ", "a", $value);
	$value = str_replace("ẵ","a", $value);
	$value = str_replace("ặ", "a", $value);
	#---------------------------------A(

	$value = str_replace("Ă", "a", $value);
	$value = str_replace("Ắ", "a", $value);
	$value = str_replace("Ằ", "a", $value);
	$value = str_replace("Ẳ", "a", $value);
	$value = str_replace("Ẵ", "a", $value);
	$value = str_replace("Ặ", "a", $value);
	$value = str_replace("Ă", "a", $value);
	#---------------------------------e^

	$value = str_replace("ê", "e", $value);	
	$value = str_replace("ế", "e", $value);
	$value = str_replace("ề", "e", $value);
	$value = str_replace("ể", "e", $value);
	$value = str_replace("ễ", "e", $value);
	$value = str_replace("ệ", "e", $value);
	#---------------------------------E^

	$value = str_replace("Ê", "e", $value);	
	$value = str_replace("Ế", "e", $value);
	$value = str_replace("Ề", "e", $value);
	$value = str_replace("Ể", "e", $value);
	$value = str_replace("Ễ", "e", $value);
	$value = str_replace("Ệ", "e", $value);
	#---------------------------------e

	$value = str_replace("é","e", $value);
	$value = str_replace("è", "e", $value);
	$value = str_replace("ẻ", "e", $value);
	$value = str_replace("ẽ", "e", $value);
	$value = str_replace("ẹ", "e", $value);
	#---------------------------------E

	$value = str_replace("É", "e", $value);
	$value = str_replace("È", "e", $value);
	$value = str_replace("Ẻ", "e", $value);
	$value = str_replace("Ẽ", "e", $value);
	$value = str_replace("Ẹ", "e", $value);
	#---------------------------------i

	$value = str_replace("í", "i", $value);
	$value = str_replace("ì", "i", $value);
	$value = str_replace("ỉ", "i", $value);
	$value = str_replace("ĩ", "i", $value);
	$value = str_replace("ị", "i", $value);
	#---------------------------------I

	$value = str_replace("Í", "i", $value);
	$value = str_replace("Í", "i", $value);
	$value = str_replace("Ỉ", "i", $value);
	$value = str_replace("Ĩ", "i", $value);
	$value = str_replace("Ị", "i", $value);
	#---------------------------------o^

	$value = str_replace("ô", "o", $value);	
	$value = str_replace("ố", "o", $value);
	$value = str_replace("ồ", "o", $value);
	$value = str_replace("ổ", "o", $value);
	$value = str_replace("ỗ", "o", $value);
	$value = str_replace("ộ", "o", $value);
	#---------------------------------O^

	$value = str_replace("Ô", "o", $value);	
	$value = str_replace("Ố", "o", $value);
	$value = str_replace("Ồ", "o", $value);
	$value = str_replace("Ổ", "o", $value);
	$value = str_replace("Ỗ", "o", $value);
	$value = str_replace("Ộ", "o", $value);
	#---------------------------------o*

	$value = str_replace("ơ", "o", $value);	
	$value = str_replace("ớ", "o", $value);
	$value = str_replace("ờ", "o", $value);
	$value = str_replace("ở", "o", $value);
	$value = str_replace("ỡ", "o", $value);
	$value = str_replace("ợ", "o", $value);
	#---------------------------------O*

	$value = str_replace("Ơ", "o", $value);	
	$value = str_replace("Ớ", "o", $value);
	$value = str_replace("Ờ", "o", $value);
	$value = str_replace("Ở", "o", $value);
	$value = str_replace("Ỡ", "o", $value);
	$value = str_replace("Ợ", "o", $value);
	#---------------------------------u*

	$value = str_replace("ư", "u", $value);	
	$value = str_replace("ứ", "u", $value);
	$value = str_replace("ừ", "u", $value);
	$value = str_replace("ử", "u", $value);
	$value = str_replace("ữ", "u", $value);
	$value = str_replace("ự", "u", $value);
	#---------------------------------U*

	$value = str_replace("Ư", "u", $value);	
	$value = str_replace("Ứ", "u", $value);
	$value = str_replace("Ừ", "u", $value);
	$value = str_replace("Ử", "u", $value);
	$value = str_replace("Ữ", "u", $value);
	$value = str_replace("Ự", "u", $value);
	#---------------------------------y

	$value = str_replace("ý", "y", $value);
	$value = str_replace("ỳ", "y", $value);
	$value = str_replace("ỷ", "y", $value);
	$value = str_replace("ỹ", "y", $value);
	$value = str_replace("ỵ", "y", $value);
	#---------------------------------Y

	$value = str_replace("Ý", "y", $value);
	$value = str_replace("Ỳ", "y", $value);
	$value = str_replace("Ỷ", "y", $value);
	$value = str_replace("Ỹ", "y", $value);
	$value = str_replace("Ỵ", "y", $value);
	#---------------------------------DD

	$value = str_replace("Đ", "d", $value);		
	$value = str_replace("đ", "d", $value);
	#---------------------------------o

	$value = str_replace("ó", "o", $value);
	$value = str_replace("ò", "o", $value);
	$value = str_replace("ỏ", "o", $value);
	$value = str_replace("õ", "o", $value);
	$value = str_replace("ọ", "o", $value);
	#---------------------------------O

	$value = str_replace("Ó", "o", $value);
	$value = str_replace("Ò", "o", $value);
	$value = str_replace("Ỏ", "o", $value);
	$value = str_replace("Õ", "o", $value);
	$value = str_replace("Ọ", "o", $value);
	#---------------------------------u

	$value = str_replace("ú", "u", $value);
	$value = str_replace("ù", "u", $value);
	$value = str_replace("ủ", "u", $value);
	$value = str_replace("ũ", "u", $value);
	$value = str_replace("ụ", "u", $value);
	#---------------------------------U

	$value = str_replace("Ú", "u", $value);
	$value = str_replace("Ù", "u", $value);
	$value = str_replace("Ủ", "u", $value);
	$value = str_replace("Ũ", "u", $value);
	$value = str_replace("Ụ", "u", $value);
	#---------------------------------

	return $value;
}

if ( ! function_exists('SEO')){	
	function SEO($name='') {
		$name = v2e($name);
		$name = preg_replace("/[^a-z,A-Z,0-9,_,-]/", "-", $name);
		$name = str_replace("---", "-", $name);
		$name = str_replace("--", "-", $name);		
		return strtolower($name);
	}
}

if ( ! function_exists('random_string')){
	function random_string($length = 4)
	{
		$sWord = '';
		$sChars = 'abcdefghjklmnprtwyzABCDEFGHJKLMNPRTWXYZ1234567890';		
		for ($i = 1; $i <= $length; $i++)
		{
			$nNumber = rand(1, strlen($sChars));
			$sWord .= substr($sChars, $nNumber - 1, 1);
	 	}
	 	return $sWord;
	}
}

if ( ! function_exists('last_query'))
{
	function last_query($exit=false)
	{
		$CI =& get_instance();
		echo $CI ->db->last_query();
		if($exit){
			exit();
		}
	}
}

if ( ! function_exists('pr')){
	function pr($data,$type=0)
	{
		print '<pre>';
		print_r($data);
		print '</pre>';
		if($type!=0){
			exit();
		}
	}
}

if ( ! function_exists('CutText')){
	function CutText($text, $n=80) 
	{ 
		// string is shorter than n, return as is
		if (strlen($text) <= $n) {
			return $text;}
		$text= substr($text, 0, $n);
		if ($text[$n-1] == ' ') {
			return trim($text)."...";
		}
		$x  = explode(" ", $text);
		$sz = sizeof($x);
		if ($sz <= 1)   {
			return $text."...";}
		$x[$sz-1] = '';
		return trim(implode(" ", $x))."...";
	}
}

function resizeImage($src,$w=0,$h=0){
	if($w!=0 && $h!=0){
		return PATH_URL.'imgresize.php?w='.$w.'&h='.$h.'&src='.$src;
	}elseif($w!=0){
		return PATH_URL.'imgresize.php?w='.$w.'&src='.$src;
	}elseif($h!=0){
		return PATH_URL.'imgresize.php?h='.$h.'&src='.$src;
	}
}

function check_dir_upload($targetDir){
	if(!is_dir($targetDir.date('Y'))){
		@mkdir($targetDir.date('Y'),0777);
	}
	if(!is_dir($targetDir.date('Y').'/'.date('m'))){
		@mkdir($targetDir.date('Y').'/'.date('m'),0777);
	}
	/*if(!is_dir($targetDir.date('Y').'/'.date('m').'/'.date('d'))){
		@mkdir($targetDir.date('Y').'/'.date('m').'/'.date('d'),0777);
	}*/
}

function getIP(){
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if($ip=='::1'){
		$ip = '127.0.0.1';
	}
	return $ip;
}

function parserEditorHTML($str){
	$str = str_replace('../../../assets/uploads/editor/', PATH_URL.'assets/uploads/editor/', $str);
	$str = preg_replace('/http:\/\/www.youtube.com\/watch\?v=([A-Za-z0-9\-\_]+)&amp;feature=([A-Za-z0-9]+)/is', '<iframe width="500" height="289" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>', $str);
	$str = preg_replace('/http:\/\/www.youtube.com\/watch\?v=([A-Za-z0-9\-\_]+)/is', '<iframe width="500" height="289" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>', $str);
	return $str;
}

function url_login_openid($type='google'){
	if($type=='google'){
		$params = array(
			'response_type'            => 'token',
			'client_id'                => G_CLIENT_ID,
			'redirect_uri'             => REDIRECT_URL,
			'state'                    => 'profile',
			'scope'                    => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile https://www.google.com/m8/feeds'
		);
		
		$url = 'https://accounts.google.com/o/oauth2/auth?';
		foreach($params as $key=>$param){
			$url .= $key.'='.urlencode($param).'&';
		}
	}
	if($type=='yahoo'){
		$params = array(
			'openid.claimed_id'        => 'http://specs.openid.net/auth/2.0/identifier_select',
			'openid.identity'          => 'http://specs.openid.net/auth/2.0/identifier_select',
			'openid.mode'              => 'checkid_setup',
			'openid.ns'                => 'http://specs.openid.net/auth/2.0',
			'openid.realm'             => PATH_URL,
			'openid.return_to'         => REDIRECT_URL.'/login/yahoo',
			'openid.ns.oauth'          => 'http://specs.openid.net/extensions/oauth/1.0',
			'openid.oauth.consumer'    => YH_CLIENT_KEY,
			'openid.ns.ax'             => 'http://openid.net/srv/ax/1.0',
			'openid.ax.mode'           => 'fetch_request',
			'openid.ax.required'       => 'email,fullname,nickname,gender,language,timezone,image',
			'openid.ax.type.email'     => 'http://axschema.org/contact/email',
			'openid.ax.type.fullname'  => 'http://axschema.org/namePerson',
			'openid.ax.type.nickname'  => 'http://axschema.org/namePerson/friendly',
			'openid.ax.type.gender'    => 'http://axschema.org/person/gender',
			'openid.ax.type.image'     => 'http://axschema.org/media/image/default',
		);
		
		$url = 'https://open.login.yahooapis.com/openid/op/auth?';
		foreach($params as $key=>$param){
			$url .= $key.'='.urlencode($param).'&';
		}
	}
	if($type=='fb'){
		$url = 'https://www.facebook.com/dialog/oauth/?client_id='.FB_CLIENT_ID.'&redirect_uri='.REDIRECT_URL.'&state='.FB_CLIENT_SECRET.'&display=popup&scope=email,publish_stream';
	}
	return $url;
}

function get_attr_profile_openid($type){
	if($type=='google'){
		include('application/libraries/eac_curl.class.php');
		$options = array();
		$options['CURLOPT_AUTOREFERER']    = 1;
		$options['CURLOPT_CRLF']           = 1;
		$options['CURLOPT_NOPROGRESS']     = 1;
		$http = new cURL($options);
		$http->setOptions($options);
		$src = $http->get("https://www.googleapis.com/oauth2/v1/userinfo?access_token=".$_GET['access_token']);
		$contact = $http->get("https://www.google.com/m8/feeds/contacts/default/full?max-results=5000&oauth_token=".$_GET['access_token']);
		$xml =  new SimpleXMLElement($contact);
		$xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
		$result = $xml->xpath('//gd:email');

		$profile = json_decode($src);
		$info['type'] = 'google';
		if(isset($profile->id)){
			$info['id'] = $profile->id;
		}else{
			$info['id'] = '';
		}
		if($profile->email){
			$info['email'] = $profile->email;
		}else{
			$info['email'] = '';
		}
		if($profile->verified_email){
			$info['verified_email'] = $profile->verified_email;
		}else{
			$info['verified_email'] = '';
		}
		if($profile->name){
			$info['full_name'] = $profile->name;
		}else{
			$info['full_name'] = '';
		}
		if($profile->given_name){
			$info['f_name'] = $profile->given_name;
		}else{
			$info['f_name'] = '';
		}
		if($profile->family_name){
			$info['l_name'] = $profile->family_name;
		}else{
			$info['l_name'] = '';
		}
		if(isset($profile->picture)){
			$info['avatar'] = $profile->picture;
		}else{
			$info['avatar'] = '';
		}
		if(isset($profile->locale)){
			$info['locale'] = $profile->locale;
		}else{
			$info['locale'] = '';
		}
		if(isset($profile->timezone)){
			$info['timezone'] = $profile->timezone;
		}else{
			$info['timezone'] = '';
		}
		if(isset($profile->gender)){
			$info['gender'] = $profile->gender;
		}else{
			$info['gender'] = '';
		}
		foreach ($result as $title){
			$info['contact'][] = $title->attributes()->address;
		}
	}elseif($type=='yahoo'){
		$info['type'] = 'yahoo';
		if(isset($_REQUEST['openid_ax_value_email'])){
			$info['email'] = $_REQUEST['openid_ax_value_email'];
		}else{
			$info['email'] = '';
		}
		
		if(isset($_REQUEST['openid_ax_value_fullname'])){
			$info['full_name'] = $_REQUEST['openid_ax_value_fullname'];
		}else{
			$info['full_name'] = '';
		}
		
		if(isset($_REQUEST['openid_ax_value_nickname'])){
			$info['nick_name'] = $_REQUEST['openid_ax_value_nickname'];
		}else{
			$info['nick_name'] = '';
		}
		
		if(isset($_REQUEST['openid_ax_value_gender'])){
			$info['gender'] = $_REQUEST['openid_ax_value_gender'];
		}else{
			$info['gender'] = '';
		}
		
		if(isset($_REQUEST['openid_ax_value_image'])){
			$info['avatar'] = $_REQUEST['openid_ax_value_image'];
		}else{
			$info['avatar'] = '';
		}
	}else{
		$facebook = new Facebook(array(
			'appId'  => FB_CLIENT_ID,
			'secret' => FB_CLIENT_SECRET,
		));
		$user_id = $facebook->getUser();
		if($user_id){
			$user_info = $facebook->api('/me');
			$list_firend = $facebook->api('/me/friends');
			$info['type'] = 'facebook';
			$info['uid'] = $user_info['id'];
			$info['email'] = $user_info['email'];
			$info['full_name'] = $user_info['name'];
			$info['list_firend'] = $list_firend;
		}
	}
	return $info;
}


/****************************	BEGIN: DATETIME HELPER ***************************/
/**
 * getVNDateTime()
 *
 * @return
 */
if ( ! function_exists('getVNDateTime'))
{
	function getVNDateTime($datetime,$config=array(),$gio=false)
	{
		$date = '';
		$format = '';
		if(!empty($datetime)){
			// Check valid datetime
			$datetime_array = explode('-',$datetime);
			if(!empty($datetime_array) && count($datetime_array) >= 3){
				$year = (int)$datetime_array[0];
				$month = (int)$datetime_array[1];
				$is_valid_datetime = !empty($year) && !empty($month);
			}
			if(!empty($is_valid_datetime)){
				$show_date = isset($config['show_date']) ? $config['show_date'] : true;
				$show_time = isset($config['show_time']) ? $config['show_time'] : true;
				$show_time_second = isset($config['show_time_second']) ? $config['show_time_second'] : true;
				if($show_date){
					$date_format = 'd-m-Y';
					$format = $date_format;
				}
				if($show_time){
					$time_format = 'H:i';
					if($show_time_second){
						$time_format .= ':s';
					}
					$format = !empty($format) ? $format.' ': $format;
					$format .= $time_format;
				}
				$date = date($format, strtotime($datetime));
			} else {
				// Do nothing
			}
		}
		return $date;
	}
}

/**
 * convertVNDate()
 *
 * $datetime_str format: d-m-Y
 * @return
 */
if ( ! function_exists('convertVNDate'))
{
	function convertVNDate($date_str,$sep='-'){
		$date = '';
		$date_array = explode($sep,$date_str);
		if(count($date_array)==3){
			$day = (int)$date_array[0];
			$month = (int)$date_array[1];
			$year = (int)$date_array[2];
			if(!empty($day) && !empty($month) && !empty($year)){
				$date_int = mktime(0,0,0,$date_array[1],$date_array[0],$date_array[2]);
				$date = date(DATETIME_FORMAT_DB_NO_TIME,$date_int);
			}
		}
		return $date;
	}
}

/**
 * convertVNTime()
 *
 * $datetime_str format: d-m-Y
 * @return
 */
if ( ! function_exists('convertVNTime'))
{
	function convertVNTime($time_str){
		$time = '';
		$time_array = explode(':',$time_str);
		if(count($time_array)>=2){
			$hour = $time_array[0];
			$minute = $time_array[1];
			$second = !empty($time_array[2]) ? $time_array[2] : 0;
			$time = mktime($hour,$minute,$second);
			$time = date(TIME_FORMAT_DB,$time);
		}
		return $time;
	}
}

/**
 * convertVNDatetime()
 *
 * $datetime_str format: d-m-Y
 * @return
 */
if ( ! function_exists('convertVNDatetime'))
{
	function convertVNDatetime($datetime_str,$sep='-'){
		$datetime = '';
		$datetime_array = explode(' ',$datetime_str);
		if(count($datetime_array)==2){
			$date = convertVNDate($datetime_array[0]);
			if(!empty($date)){
				$time = convertVNTime($datetime_array[1]);
				$datetime = $date.' '.$time;
			}
		}
		return $datetime;
	}
}

/**
 * convertVNDatetime_int()
 *
 * $datetime_str format: d-m-Y
 * @return
 */
if ( ! function_exists('convertVNDatetime_int'))
{
	function convertVNDatetime_int($datetime_str,$sep='-'){
		$datetime_int = 0;
		$datetime = convertVNDatetime($datetime_str,$sep);
		if(!empty($datetime)){
			$datetime_int = strtotime($datetime);
		}
		return $datetime_int;
	}
}

/**
 * getNow()
 *
 * @return
 */
if ( ! function_exists('getNow'))
{
	function getNow()
	{
		return date(DATETIME_FORMAT_DB);
	}
}

if ( ! function_exists('getToday'))
{
	function getToday($day_adding=0)
	{
		$today = date(DATETIME_FORMAT_DB_NO_TIME);  //Lay ngay hien tai
		$today = strtotime($today);   // chuyen chuyen thanh thoi gian
		$result = $today + $day_adding*3600*24; 
		$result = date(DATETIME_FORMAT_DB,$result);
		return $result;
	}
}

if ( ! function_exists('getmicrotime'))
{
	// a function to  get microtime
	function getmicrotime(){
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}
}

if ( ! function_exists('get_year_month_value')){
	function get_year_month_value($date_value,&$year=0,&$month=0){
		$year_month_value = 0;
		$date_int = strtotime($date_value);
		if($date_int){
			$year = date('Y',$date_int);
			$month = date('m',$date_int);
			$year_month_value = $year*1000 + $month;
		}
		return $year_month_value;
	}
}
/****************************	END: DATETIME HELPER ***************************/


function debug_log_reset(){
	$file_path = 'debug_log.txt';
	@unlink($file_path);
}

function debug_log($value){
	$file_path = 'debug_log.txt';
	$content = getNow();
	$content .= "\r\n";
	$content .= $value;
	$content .= "\r\n";
	$content .= "\r\n";
	
	@file_put_contents($file_path,$content, FILE_APPEND | LOCK_EX);
}

function debug_log_from_config($value){
	if (defined('DEBUG_LOG')) {
		if(DEBUG_LOG){
			if(DEBUG_LOG_LAST_COMMAND){
				debug_log_reset();
			}
			if(is_array($value)){
				$value = print_r($value, true);
			}
			debug_log($value);
		}
	}
}

function __initFacebook( $getUrl = null, $url = null ){
	require_once (APPPATH.'/libraries/facebook/autoload.php');

	$fb = new Facebook\Facebook([
			'app_id' => FB_APP_ID,
			'app_secret' => FB_SECRET,
			'default_graph_version' => 'v2.5',

	]);
	if( !empty($getUrl) ){
		$urlLogin = 'home/facebooklogin';
			
		//pr($url,1);
		if( !empty( $url ) ){
			$urlLogin = $url;
		}
		$helper = $fb->getRedirectLoginHelper();
		$permissions = ['email']; // Optional permissions
		$loginUrl = $helper->getLoginUrl( PATH_URL.$urlLogin , $permissions);
			
		return $loginUrl;
	}else{
		return $fb;
	}

}


/****************************  BEGIN: CMS HELPER  ***************************/
/*
function cms_check(){
	global $IS_CMS;
	return $IS_CMS;
}

function cms_enter(){
	global $IS_CMS;
	$IS_CMS = true;
}
*/

function cms_login_check(){
	$CI =& get_instance();
	if(!$CI->session->userdata('userInfo')){
		header('Location: '.PATH_URL_ADMIN.'login');
		exit;
	}
}
/****************************  END: CMS HELPER  ***************************/


function move_file_from_url($module_name, $url, $is_thumbnail = FALSE, $custom_path = NULL) {
    $root_path_upload = 'assets/uploads/images';
    //get and check current path by url
    $path_file =  BASEFOLDER . str_replace(PATH_URL, '', $url);
    if ( ! is_file ($path_file) ) {
        return FALSE;
    }
    //check using custom path
    if ( ! empty ($custom_path) ) {
        $path = $custom_path;
    }
    else {
        $dateNow = new DateTime();
        $path = $root_path_upload."/".$dateNow->format('Y')."/".$dateNow->format('m');
    }

    //make new filename
    $image_pathinfo = pathinfo($path_file);
    $_extension = $image_pathinfo ['extension'];
    $_filename = $image_pathinfo ['filename'];
    $_subfix = ( $is_thumbnail == TRUE ) ? '_thumbnail' : '';
    $new_filename = $module_name . '_' . strtotime (date ('Y-m-d H:i:s')) . $_subfix . '.' . $_extension;

    if ( ! file_exists ( $path ) ) {
        mkdir ( $path, 0777, true );
    }

    $path .= "/" . $new_filename;
    if ( ! file_exists ($path) ) {
        rename ($path_file, $path);
        return "/" . $path;
    }
    else {
        return FALSE;
    }
}

function get_url_language($slug) {
	$ci =& get_instance();
	$lang = $ci->lang->default_lang();
	$result = FALSE;
    if ( ! empty($slug)) {
        $result = trim(rtrim(PATH_URL, '/\\') . '/' . $lang . '/' . ltrim($slug, '/\\'));
    }
    return $result;
}

function get_parent_uri() {
	$result = PATH_URL;
	$ci =& get_instance();
	$segments = $ci->uri->segment_array();
	$count_segment = (count($segments) > 1 ) ? count($segments) : 2;
    for($i = 1; $i < $count_segment; $i++) {
        $result .= $segments[$i] . '/';
    }
    return $result;
}

function get_child_uri($slug, $is_same = FALSE) {
	$result = '';
	if ($is_same) {
		$result = get_parent_uri() . $slug;
	} else {
		$result = current_url() . '/' . $slug;
	}
	return $result;
}


/****** BEGIN - PERMISSION HELPER ******/
function permission_check($permission, $module_id, $user_permission) {
	$result = false;
	
	if($user_permission == 'all') // root
	{
		$result = true;
	}
	else
	{
		$permission_prefix = ','.$module_id.'|';
		$pos = strpos($user_permission, $permission_prefix);
		if($pos!=0){
			$pos = $pos+strlen($module_id);
		}else{
			$pos = 0;
		}
		// pr('$permission_prefix='.$permission_prefix);
		// pr('$pos='.$pos);
		// pr('$user_permission='.$user_permission);
		$offset = strlen($permission_prefix) + 4;
		// pr('$offset='.$offset);
		$sub_str = substr($user_permission,$pos,$offset);
		// pr('$sub_str='.$sub_str,1);
		$pos_result = strpos($sub_str,$permission);
		
		$result = ($pos_result !== false);
	}
	
	return $result;
}

function permission_check_user($permission) {
	$result = false;
	$CI =& get_instance();
	$module_id = $CI->session->userdata('ID_Module');
	$user_permission = $CI->admincp_model->admin_user->permission;
	return permission_check($permission, $module_id, $user_permission);
}

function permission_force_check($permission) {
	$CI =& get_instance();
	modules::run('admincp/chk_perm',$CI->session->userdata('ID_Module'),$permission,0);
}
/****** END - PERMISSION HELPER ******/
?>