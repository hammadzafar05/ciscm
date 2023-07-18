<?php

use App\User;
use Illuminate\Support\Facades\Auth;

function __lang($key, $params=[]){
	$key = str_ireplace(' ','-',$key);
	$key = strtolower($key);
	
	return __('default.'.$key,$params);
}

function getSetting($key){
	$setting = \App\Setting::where('key',trim($key))->first();
	if($setting){
		return $setting->value;
	}
	else{
		return null;
	}
}

function setting($key,$default=null){
	
	$setting = \App\Setting::where('key',trim(strtolower($key)))->first();
	
	if($setting && !empty($setting->value)){
		return trim($setting->value);
	}
	elseif(!empty($default)){
		return $default;
	}
	else{
		return false;
	}
	
}


/**
 * @param string $message
 * @param array $map
 */
function setPlaceHolders($message,$map){
	
	
	foreach ($map as $key=>$value){
		
		$key = '['.$key.']';
		
		$message = str_replace($key,$value,$message);
		
	}
	
	return $message;
	
}

function sessionType($type){
	$stype = '';
	switch ($type){
		case 'b':
			$stype =  __('default.training-online') ;
			break;
		case 'c':
			$stype =  __('default.online-course');
			break;
		case 's':
			$stype = __('default.training-session');
			break;
	}
	return $stype;
}


function removeTags($data){
	foreach($data as $key=>$value){
		if(is_string($value)){
			$data[$key] = strip_tags($value);
		}
		
	}
	return $data;
}

function forceSSL()
{
	
	if($_SERVER['SERVER_PORT'] != '443') {
		//	header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		exit();
	}
	
	
}

function noSSL()
{
	
	$env = getenv('APP_MODE');
	if($env=='live'  && $_SERVER['SERVER_PORT'] == '443') {
		//	header("HTTP/1.1 301 Moved Permanently");
		$url =$_SERVER['REQUEST_URI'];
		$append = (substr_count($url,'?')>0)? 'nossl=1':'?nossl=1';
		header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$append);
		exit();
	}
	
	
}


function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return strtolower($randomString);
}

function boolToString($val){
	if($val==0){
		return __('default.no');
	}
	else{
		return __('default.yes');
	}
}

function sanitize($string) {
	$replace="_";
	$pattern="/([[:alnum:]_.-]*)/";
	$fname=str_replace(str_split(preg_replace($pattern,$replace,$string)),$replace,$string);
	return $fname;
}

function isImage($file){
	if(empty($file)){
		return false;
	}
	$extensions = ['jpg','gif','png','jpeg'];
	$parts = pathinfo($file);
	
	if(!isset($parts['extension'])){
		return false;
	}
	$ext = strtolower($parts['extension']);
	if(in_array($ext,$extensions)){
		
		return true;
	}
	else{
		
		return false;
	}
	
}


function isUrl($string){
	if(preg_match('#http://#',$string) || preg_match('#https://#',$string)){
		return true;
	}
	else{
		return false;
	}
}

function sanitizeFile($cmd, $result, $args, $elfinder) {
	// do something here
	$files = $result['added'];
	foreach ($files as $file) {
		$filename = sanitize($file['name']);
		$arg = array('target' => $file['hash'], 'name' => $filename);
		// $elfinder->exec('rename', $arg);
		$filepath = (isset($file['realpath']) ? $file['realpath'] : $elfinder->realpath($file['hash']));
		$path_parts = pathinfo($filepath);
		
		rename($filepath,$path_parts['dirname'].'/'.$filename);
		
	}
	
	return true;
}

function fullUrl($url){
	if(!preg_match('#://#',$url)){
		$url = 'http://'.$url;
	}
	return $url;
}

function safeUrl($url) {
	
	$url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
	
	$url = trim($url, "-");
	
	// $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
	$url = strtolower($url);
	//$url = urlencode($url);
	
	//$url = preg_replace('~[^-a-z0-9_]+~', '', $url);
	
	return $url;
}
function getFirstDigit($string){
	$pos = strpos($string,'_');
	$id = substr($string,0,$pos);
	return $id;
}
function getNumbersOnly($text)
{
	
	//remove any whitespace
	$text = str_replace(' ','',$text);
	$text = trim($text);
	$text = str_replace(',','',$text);
	
	$array = str_split($text);
	$amount = array();
	$counter = 0;
	foreach ($array as $value)
	{
		if (is_numeric($value))
		{
			@$amount[$counter] .= $value;
		}
		else
		{
			//$counter++;
		}
		
		
	}
	
	$price = @$amount[0];
	return $price;
}
function cleanTel($text){
	$text = str_ireplace('o','0',$text);
	// $text = str_ireplace('+234','0',$text);
	$text = getNumbersOnly($text);
	return $text;
	
}

function getPhoneNumber($text){
	
	//remove any whitespace
	$text = str_replace(' ','',$text);
	$text = trim($text);
	$text = str_replace(',','',$text);
	
	$array = str_split($text);
	$amount = array();
	$counter = 0;
	foreach ($array as $value)
	{
		if (is_numeric($value) || $value=='+')
		{
			@$amount[$counter] .= $value;
		}
		else
		{
			//$counter++;
		}
		
		
	}
	
	$price = @$amount[0];
	return $price;
}

function convert_number_to_words($number) {
	
	$hyphen      = '-';
	$conjunction = ' and ';
	$separator   = ', ';
	$negative    = 'negative ';
	$decimal     = ' point ';
	$dictionary  = array(
		0                   => 'zero',
		1                   => 'one',
		2                   => 'two',
		3                   => 'three',
		4                   => 'four',
		5                   => 'five',
		6                   => 'six',
		7                   => 'seven',
		8                   => 'eight',
		9                   => 'nine',
		10                  => 'ten',
		11                  => 'eleven',
		12                  => 'twelve',
		13                  => 'thirteen',
		14                  => 'fourteen',
		15                  => 'fifteen',
		16                  => 'sixteen',
		17                  => 'seventeen',
		18                  => 'eighteen',
		19                  => 'nineteen',
		20                  => 'twenty',
		30                  => 'thirty',
		40                  => 'fourty',
		50                  => 'fifty',
		60                  => 'sixty',
		70                  => 'seventy',
		80                  => 'eighty',
		90                  => 'ninety',
		100                 => 'hundred',
		1000                => 'thousand',
		1000000             => 'million',
		1000000000          => 'billion',
		1000000000000       => 'trillion',
		1000000000000000    => 'quadrillion',
		1000000000000000000 => 'quintillion'
	);
	
	if (!is_numeric($number)) {
		return false;
	}
	
	if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
		// overflow
		trigger_error(
			'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
			E_USER_WARNING
		);
		return false;
	}
	
	if ($number < 0) {
		return $negative . convert_number_to_words(abs($number));
	}
	
	$string = $fraction = null;
	
	if (strpos($number, '.') !== false) {
		list($number, $fraction) = explode('.', $number);
	}
	
	switch (true) {
		case $number < 21:
			$string = $dictionary[$number];
			break;
		case $number < 100:
			$tens   = ((int) ($number / 10)) * 10;
			$units  = $number % 10;
			$string = $dictionary[$tens];
			if ($units) {
				$string .= $hyphen . $dictionary[$units];
			}
			break;
		case $number < 1000:
			$hundreds  = $number / 100;
			$remainder = $number % 100;
			$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
			if ($remainder) {
				$string .= $conjunction . convert_number_to_words($remainder);
			}
			break;
		default:
			$baseUnit = pow(1000, floor(log($number, 1000)));
			$numBaseUnits = (int) ($number / $baseUnit);
			$remainder = $number % $baseUnit;
			$string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
			if ($remainder) {
				$string .= $remainder < 100 ? $conjunction : $separator;
				$string .= convert_number_to_words($remainder);
			}
			break;
	}
	
	if (null !== $fraction && is_numeric($fraction)) {
		$string .= $decimal;
		$words = array();
		foreach (str_split((string) $fraction) as $number) {
			$words[] = $dictionary[$number];
		}
		$string .= implode(' ', $words);
	}
	
	return $string;
}

function limitLength($string,$length=100)
{
	if (strlen($string) > $length) {
		$string = substr($string, 0,$length).'...';
	}
	return $string;
}

function showDateWithoutTimezone($format,$date){
	if(!empty($date)){
		
		$timestamp_server_time = \Illuminate\Support\Carbon::parse($date)->format($format);
		
		/*$timestamp_server_time = Carbon::createFromFormat('Y-m-d H:i:s', $date, 'Europe/Stockholm');
		$date->setTimezone('UTC');*/
		
		return \Illuminate\Support\Carbon::parse($date)->format($format);
	}
	else{
		return 'N/A';
	}
}
function showDate($format,$date){
	if(!empty($date)){
		
		$timestamp_server_time = \Illuminate\Support\Carbon::parse($date)->format($format);
		
		/*$timestamp_server_time = Carbon::createFromFormat('Y-m-d H:i:s', $date, 'Europe/Stockholm');
		$date->setTimezone('UTC');*/
		
		return \Illuminate\Support\Carbon::parse($date)->format($format).' GMT';
	}
	else{
		return 'N/A';
	}
}
function showDateTime($format,$date){
	if(!empty($date)){
		
		$timestamp_server_time = \Illuminate\Support\Carbon::parse($date)->format($format);
		
		/*$timestamp_server_time = Carbon::createFromFormat('Y-m-d H:i:s', $date, 'Europe/Stockholm');
		$date->setTimezone('UTC');*/
		
		return \Illuminate\Support\Carbon::parse($date)->format($format).' GMT';
	}
	else{
		return 'N/A';
	}
}

function showTime($format,$time){
	if(!empty($time)){
		if ($format == '12'){
			return date("h:i A", strtotime($time));
		}else{
			return date("H:i", strtotime($time));
		}
		return \Illuminate\Support\Carbon::parse($date)->format($format).' GMT';
	}
	else{
		return '';
	}
}

function resizeImage($filename, $width, $height,$basePath) {
	
	$filename = urldecode($filename);
	$dirImage = 'tmp/';
	$baseDir = '';
	if (!file_exists($baseDir . $filename) || !is_file($baseDir . $filename)) {
		
		return;
	}
	
	$info = pathinfo($filename);
	
	$extension = $info['extension'];
	
	$old_image = $filename;
	$new_image = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;
	
	if (!file_exists($dirImage . $new_image) || (filemtime($baseDir . $old_image) > filemtime($dirImage . $new_image))) {
		$path = '';
		
		$directories = explode('/', dirname(str_replace('../', '', $new_image)));
		
		foreach ($directories as $directory) {
			$path = $path . '/' . $directory;
			
			if (!file_exists($dirImage . $path)) {
				@mkdir($dirImage . $path, 0777);
			}
		}
		
		$image = new \App\Lib\Image($baseDir . $old_image);
		
		$image->resize($width, $height);
		$image->save($dirImage . $new_image);
	}
	
	
	return $basePath.'/tmp/'. $new_image;
}

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir")
					rrmdir($dir."/".$object);
				else unlink   ($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}
function convertJsonBody() {
	$methodsWithDataInBody = array(
		'POST',
		'PUT',
	);
	
	if (
		isset($_SERVER['CONTENT_TYPE'])
		&& (strpos(strtolower($_SERVER['CONTENT_TYPE']), 'application/json') !== FALSE)
		&& isset($_SERVER['REQUEST_METHOD'])
		&& in_array($_SERVER['REQUEST_METHOD'], $methodsWithDataInBody)
	) {
		$_POST = json_decode(file_get_contents('php://input'), TRUE);
		foreach($_POST as $key => $value) {
			$_REQUEST[$key] = $value;
		}
	}
}

function getApiStudent($request){
	$authToken = $request->getHeaderLine('Authorization');
	if(!empty($authToken)){
		$student = \App\Student::where('api_token',$authToken)->first();
	}
	else{
		$student = false;
	}
	
}



/**
 * Gets the complete url of
 * the current script
 * @return string
 */
function selfURL() {
	$s = empty($_SERVER["HTTPS"]) ? '' : (($_SERVER["HTTPS"] == "on") ? "s" : "");
	$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}

function isValidUpload($file){
	$allowed_types = array ('application/zip', 'application/pdf', 'image/jpeg', 'image/png','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.presentationml.presentation');
	$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
	$detected_type = finfo_file( $fileInfo, $file );
	finfo_close( $fileInfo );
	if ( !in_array($detected_type, $allowed_types) ) {
		
		return false;
	}
	else{
		return true;
	}
}

function getExtensionForMime($file){
	$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
	$detected_type = finfo_file( $fileInfo, $file );
	finfo_close( $fileInfo );
	
	$extensions = array('image/jpeg' => 'jpg',
		'application/pdf' => 'pdf',
		'image/png'=>'png',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document'=>'docx',
		'application/msword'=>'doc',
		'application/vnd.ms-excel'=>'xls',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'=>'xlsx',
		'application/vnd.openxmlformats-officedocument.presentationml.presentation'=>'pptx',
		'application/zip'=>'zip'
	);
	
	return $extensions[$detected_type];
}

function crop_img($imgSrc){
	//getting the image dimensions
	list($width, $height) = getimagesize($imgSrc);
	
	//saving the image into memory (for manipulation with GD Library)
	$myImage = imagecreatefromjpeg($imgSrc);
	
	// calculating the part of the image to use for thumbnail
	if ($width > $height) {
		$y = 0;
		$x = ($width - $height) / 2;
		$smallestSide = $height;
	} else {
		$x = 0;
		$y = ($height - $width) / 2;
		$smallestSide = $width;
	}
	
	// copying the part into thumbnail
	$thumbSize = min($width,$height);
	$thumb = imagecreatetruecolor($thumbSize, $thumbSize);
	imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, $thumbSize, $thumbSize, $smallestSide, $smallestSide);
	
	unlink($imgSrc);
	imagejpeg($thumb,$imgSrc);
	@imagedestroy($myImage);
	@imagedestroy($thumb);
}

function isMobileApp(){
	
	$client = session('client');
	if($client=='mobile'){
		return true;
	}
	else{
		return false;
	}
}

function isTrainEasySubdomain(){
	$url = selfURL();
	if(substr_count($url,'traineasy.net')>0){
		return true;
	}
	else{
		return false;
	}
}

function formatSizeUnits($bytes) {
	if ($bytes >= 1073741824) {
		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
	} elseif ($bytes >= 1048576) {
		$bytes = number_format($bytes / 1048576, 2) . ' MB';
	} elseif ($bytes >= 1024) {
		$bytes = number_format($bytes / 1024, 2) . ' KB';
	} elseif ($bytes > 1) {
		$bytes = $bytes . ' bytes';
	} elseif ($bytes == 1) {
		$bytes = $bytes . ' byte';
	} else {
		$bytes = '0 bytes';
	}
	return $bytes;
}


function time_elapsed_string($datetime, $full = false) {
	$now = new DateTime;
	$ago = new DateTime($datetime);
	$diff = $now->diff($ago);
	
	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;
	
	$string = array(
		'y' => __lang('year'),
		'm' => __lang('month'),
		'w' => __lang('week'),
		'd' => __lang('day'),
		'h' => __lang('hour'),
		'i' => __lang('minute'),
		's' => __lang('second'),
	);
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}
	
	if (!$full) $string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) . ' '.__lang('ago') : __lang('just now');
}

function strleft($s1, $s2) {
	return substr($s1, 0, strpos($s1, $s2));
}

function getClientIp() {
	$ipaddress = '';
	if (isset($_SERVER['HTTP_CLIENT_IP']))
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_X_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if(isset($_SERVER['REMOTE_ADDR']))
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}

function booleanValue($val){
	if($val=='true'){
		return true;
	}
	elseif($val=='false'){
		return false;
	}
	else{
		return $val;
	}
}

function removeScriptTags($html){
	return preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $html);
}


function getFileMimeType($file){
	if(!function_exists('mime_content_type')) {
		
		function mime_content_type($filename) {
			
			$mime_types = array(
				
				'txt' => 'text/plain',
				'htm' => 'text/html',
				'html' => 'text/html',
				'php' => 'text/html',
				'css' => 'text/css',
				'js' => 'application/javascript',
				'json' => 'application/json',
				'xml' => 'application/xml',
				'swf' => 'application/x-shockwave-flash',
				'flv' => 'video/x-flv',
				
				// images
				'png' => 'image/png',
				'jpe' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg' => 'image/jpeg',
				'gif' => 'image/gif',
				'bmp' => 'image/bmp',
				'ico' => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif' => 'image/tiff',
				'svg' => 'image/svg+xml',
				'svgz' => 'image/svg+xml',
				
				// archives
				'zip' => 'application/zip',
				'rar' => 'application/x-rar-compressed',
				'exe' => 'application/x-msdownload',
				'msi' => 'application/x-msdownload',
				'cab' => 'application/vnd.ms-cab-compressed',
				
				// audio/video
				'mp3' => 'audio/mpeg',
				'qt' => 'video/quicktime',
				'mov' => 'video/quicktime',
				
				// adobe
				'pdf' => 'application/pdf',
				'psd' => 'image/vnd.adobe.photoshop',
				'ai' => 'application/postscript',
				'eps' => 'application/postscript',
				'ps' => 'application/postscript',
				
				// ms office
				'doc' => 'application/msword',
				'rtf' => 'application/rtf',
				'xls' => 'application/vnd.ms-excel',
				'ppt' => 'application/vnd.ms-powerpoint',
				
				// open office
				'odt' => 'application/vnd.oasis.opendocument.text',
				'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			);
			
			$ext = strtolower(array_pop(explode('.',$filename)));
			if (array_key_exists($ext, $mime_types)) {
				return $mime_types[$ext];
			}
			elseif (function_exists('finfo_open')) {
				$finfo = finfo_open(FILEINFO_MIME);
				$mimetype = finfo_file($finfo, $filename);
				finfo_close($finfo);
				return $mimetype;
			}
			else {
				return 'application/octet-stream';
			}
		}
	}
	return mime_content_type($file);
}



function profilePictureUrl($picture,$basePath=null){
	if (empty($basePath)){
		$basePath = url('/');
	}
	$blank = $basePath.'/client/img/user.png';
	if(empty($picture)){
		return $blank;
	}
	
	if(isUrl($picture)){
		return $picture;
	}
	
	if(file_exists($picture) && isImage($picture)){
		return resizeImage($picture,300,300,$basePath);
	}
	else{
		return $blank;
	}
	
}




function is_decimal( $val )
{
	return is_numeric( $val ) && floor( $val ) != $val;
}

function formatCurrency($amount,$currencyCode){
	$lang = setting('config_language');
	$fmt = new NumberFormatter($lang, NumberFormatter::CURRENCY );
	
	return  $fmt->formatCurrency( floatval($amount) ,strtoupper($currencyCode));
}

function sitePrice($amount){
	if (empty($amount)){
		return __lang('free');
	}
	else{
		return price($amount);
	}
}

function price($amount,$forcedCurrencyId=null,$raw=false){
	
	//get currency in use
	
	$currencyId= session('currency');
	
	
	if($forcedCurrencyId){
		$currencyId= $forcedCurrencyId;
	}
	
	$currency = \App\Currency::find($currencyId);
	
	if (!isset($currency)){
		$currency = currentCurrency();
	}
	
	if(setting('country_id') != $currency->country_id){
		$amount = $amount * $currency->exchange_rate;
		$amount = round($amount,2);
	}
	
	if($raw){
		return $amount;
	}
	
	
	return formatCurrency($amount,$currency->country->currency_code);
	
}

function priceRaw($amount){
	return price($amount,null,true);
}

function currencies(){
	$currencies = \App\Currency::orderBy('currency_id','desc')->get();
	return $currencies;
}
function getMonthStr($offset)
{
	return date("M", strtotime("$offset months"));
}

function currentCurrency(){
	
	$countryId = setting('country_id');
	if (empty($countryId)){
		$countryId =235;
	}
	$country = \App\Country::find($countryId);
	
	//check for installed currency
	$defaultCurrency = \App\Currency::where('country_id',$country->id)->first();
	if(!$defaultCurrency){
		$defaultCurrency = \App\Currency::create(['country_id'=>$country->id,'exchange_rate'=>1]);
	}
	
	$currencyId= session('currency');
	
	if(isset($currencyId)){
		$currency = \App\Currency::find($currencyId);
		if($currency){
			return $currency;
		}
		else{
			return $defaultCurrency;
		}
	}
	else{
		return $defaultCurrency;
	}
	
}

/**
 * @return \App\Lib\Cart|mixed
 */
function getCart(){
	$cart = session('cart');
	if(!isset($cart)){
		$cart = new \App\Lib\Cart();
		$cart->store();
	}
	else{
		$cart = unserialize($cart);
		
	}
	return $cart;
}

function getCountry(){
	$ip_address = getClientIp();
	
	
	$ip_address = trim($ip_address);
	
	$countryId = setting('country_id');
	$country =\App\Country::find($countryId);
	$defaultCountry = strtolower($country->iso_code_2);
	
	if(!filter_var($ip_address, FILTER_VALIDATE_IP)){
		
		return strtolower($country->iso_code_2);
	}
	
	if(env('APP_ENV')=='production'){
		
		
		
		if(\App\Ip::where('ip',$ip_address)->count()==0){
			//create ip record in db
			$country = file_get_contents("http://ipinfo.io/$ip_address/country");
			
			$country = trim(strtolower($country));
			
			//   notifyAdmin('country fetched',$ip_address.' . line 31: '.$country);
			
			if(empty($country) || strlen($country)!=2){
				$country = $defaultCountry;
			}
			
			
			\App\Ip::create(['ip'=>$ip_address,'country'=>$country]);
			return $country;
		}
		else{
			
			$ipModel = \App\Ip::where('ip',$ip_address)->first();
			return $ipModel->country;
		}
		
		
	}
	else{
		
		return $defaultCountry;
	}
	
}

function jsonResponse($data){
	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization');
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
	exit(json_encode($data));
}

function langMeta(){
	$lang = getSetting('config_language');
	if($lang=='ar'){
		return ' lang="ar" ';
	}
	else{
		return ' lang="'.$lang.'" ';
	}
}

function validateFolder($folder){
	$path = UPLOAD_PATH.'/'.$folder;
	if(!file_exists($path)){
		rmkdir($path);
	}
}

function saas(){
	$mode = env('APP_MODE');
	if($mode=='saas'){
		return true;
	}
	else{
		return false;
	}
}

function formElement($element){
	$type = $element->getAttribute('type');
	switch ($type){
		case 'text':
			echo Form::text($element->getName(),old($element->getName(),$element->getValue()),$element->getAttributes());
			break;
		case 'number':
			echo Form::number($element->getName(),old($element->getName(),$element->getValue()),$element->getAttributes());
			break;
		case 'email':
			echo Form::email($element->getName(),old($element->getName(),$element->getValue()),$element->getAttributes());
			break;
		case 'textarea':
			echo Form::textarea($element->getName(),old($element->getName(),$element->getValue()),$element->getAttributes());
			break;
		case 'hidden':
			echo Form::hidden($element->getName(),old($element->getName(),$element->getValue()),$element->getAttributes());
			break;
		case 'select':
			$options = $element->getValueOptions();
			
			if (empty($options)){
				$options = [];
			}
			$attributes = $element->getAttributes();
			
			$emptyOption = $element->getEmptyOption();
			if (!empty($emptyOption)){
				$attributes['placeholder'] = $emptyOption;
			}
			
			if (isset($attributes['multiple'])){
				unset($attributes['placeholder']);
			}
			
			
			echo Form::select($element->getName(),$options,old($element->getName(),$element->getValue()),$attributes);
			break;
		case 'checkbox':
			
			echo Form::checkbox($element->getName(),$element->getCheckedValue(),old($element->getName(),$element->getValue())==$element->getCheckedValue(),$element->getAttributes());
			break;
		case 'radio':
			
			foreach ($element->getValueOptions() as $key=>$value){
				echo Form::label($element->getName(), $value);
				echo Form::radio($element->getName(),$key,old($element->getName(),$element->getValue())==$key);
				echo '&nbsp;';
			}
			break;
		case 'password':
			echo Form::password($element->getName(),$element->getAttributes());
			break;
		case 'file':
			echo Form::file($element->getName(),old($element->getName()),$element->getAttributes());
			break;
		
	}
}

function formElementErrors($element){
	$messages = $element->getMessages();
	echo '<ul>';
	foreach($messages as $message){
		echo "<li>{$message}</li>";
	}
	echo '</ul>';
}

function formLabel($element){
	echo Form::label($element->getName(), $element->getLabel());
}

function basePath(){
	return url('/');
}

function paginationControl( \Laminas\Paginator\Paginator $paginator = null,$scrollingStyle ='sliding',$partial = null,$route){
	$pages = get_object_vars($paginator->getPages($scrollingStyle));
	//check route for old version
	if (is_array($route) && isset($route['controller'])){
		if (isset($route['route'])){
			$module = substr($route['route'],0,strpos($route['route'],'/'));
			unset($route['route']);
		}
		else{
			$module = 'admin';
		}
		$params = $route;
		$controller= $params['controller'];
		$action = $params['action'];
		unset($params['controller'],$params['action']);
		
		$route= route("{$module}.{$controller}.{$action}",$params);
		
	}
	$pages['route'] = $route;
	if ($partial==null){
		$partial = 'partials.paginator';
	}
	
	echo view($partial,$pages)->toHtml();
}

function adminName($adminID){
	if (\App\Admin::find($adminID)){
		return \App\Admin::find($adminID)->user->name;
	}
	else{
		return 'N/A';
	}
}

function adminUrl($params){
	$controller= $params['controller'];
	$action = $params['action'];
	unset($params['controller'],$params['action']);
	
	
	return route("admin.{$controller}.{$action}",$params);
}

function lessonType($type){
	switch($type){
		case 'c':
			return __lang('online-course');
		case 's':
			return __lang('physical-location');
	}
}

function courseType($type){
	switch($type){
		case 'b':
			return __lang('training-online');
		case 's':
			return __lang('training-session');
		case 'c':
			return __lang('online-course');
	}
}

function viewModel($module,$class,$method,$params){
	
	//get class name
	$pos = strrpos($class,'\\');
	
	$class= substr($class,$pos+1);
	$class = str_replace('Controller','',$class);
	
	$class = strtolower($class);
	
	$method = strtolower($method);
	//dd("{$module}.{$class}.{$method}");
	return view("{$module}.{$class}.{$method}",$params);
}

function adminRedirect($params){
	return redirect()->to(adminUrl($params));
}

function  getObjectProperties($object)
{
	if($object instanceof ArrayObject){
		$vars = $object->getArrayCopy();
		return $vars;
	}
	if(is_object($object)){
		$vars = get_object_vars($object);
		return $vars;
	}
	else{
		return false;
	}
}

function getDateString($date){
	if (empty($date)){
		return null;
	}
	$date = \Illuminate\Support\Carbon::parse($date)->toDateString();
	return $date;
}

function getDateNewString($date,$time){
	if (empty($date)){
		return null;
	}
	if (strpos($date, 'GMT') !== false) {
		$date = explode('GMT',$date);
		$date = @$date[0];
	}
	$date = $date.' '.$time;
	//dd($date);
	//$date = \Illuminate\Support\Carbon::parse($date)->toDateString();
	$date = date('Y-m-d H:i:s',strtotime($date));
	//dd($date);
	return $date;
}
function getTimeStringTo24($time){
	if (empty($time)){
		return null;
	}
	return date("H:i", strtotime($time));
}
function getTimeStringTo12($time){
	if (empty($time)){
		return null;
	}
	return date("h:i A", strtotime($time));
}

function flashMessage($message){
	session()->flash('flash_message',$message);
}

function adminLayout(){
	if (request()->ajax()){
		return 'layouts.blank';
	}
	else{
		return 'layouts.admin';
	}
}

function studentLayout(){
	if (request()->ajax()){
		return 'layouts.blank';
	}
	else{
		return 'layouts.student';
	}
}

function fileName($path){
	$parts = pathinfo($path);
	return $parts['filename'];
}

function videoImage($fileName)
{
	return fileName($fileName) . '.jpg';
}

function filesize_r($path){
	if(!file_exists($path)) return 0;
	if(is_file($path)) return filesize($path);
	$ret = 0;
	foreach(glob($path."/*") as $fn)
		$ret += filesize_r($fn);
	return $ret;
}

function headScript($script){
	
	echo '<script type="text/javascript" src="'.asset($script).'"></script>';
	
}

function removeNull($data){
	foreach ($data as $key=>$value){
		if (is_null($value)){
			unset($data[$key]);
		}
	}
	return $data;
}


function saveInlineImages($html){
	$savePath = UPLOAD_PATH.'/'.EDITOR_IMAGES.'/'.date('m_Y');
	$saveUrl = url('/').'/'.$savePath;
	if(!file_exists($savePath)){
		mkdir($savePath,0777, true);
	}
	$dom = new \DOMDocument();
	
	//  @$dom->loadHTML($html);
	@$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
	foreach($dom->getElementsByTagName('img') as $element){
		//This selects all elements
		$data = $element->getAttribute('src');
		
		
		
		if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
			$data = substr($data, strpos($data, ',') + 1);
			$type = strtolower($type[1]); // jpg, png, gif
			
			if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
				throw new \Exception('invalid image type');
			}
			
			$data = base64_decode($data);
			
			if ($data === false) {
				continue;
			}
			
			$fileName = time().rand(100,10000);
			file_put_contents($savePath."/{$fileName}.{$type}", $data);
			$element->setAttribute('src',$saveUrl.'/'.$fileName.'.'.$type);
			
		} else {
			continue;
		}
		
		
		
	}
	
	$body = "";
	if ($dom->getElementsByTagName("body")->item(0)){
		foreach($dom->getElementsByTagName("body")->item(0)->childNodes as $child) {
			$body .= $dom->saveHTML($child);
		}
	}
	
	
	return $body;
	
	
}


function getDirectoryContents($path){
	$list = scandir($path);
	$newList = [];
	foreach($list as $item){
		if($item!='.' && $item != '..'){
			$newList[] = $item;
		}
	}
	
	return $newList;
}

function tlang($dir,$key){
	return 'temp_'.$dir.'.'.$key;
}

function __t($key){
	return __(tlang(currentTemplate()->directory,$key));
}

function templateInfo($dir){
	$info = include "templates/{$dir}/info.php";
	
	return $info;
}

function currentTemplate(){
	$currentTemplate = \App\Template::where('enabled',1)->first();
	return $currentTemplate;
}

function headerMenu(){
	
	$items= \App\HeaderMenu::where('parent_id',0)->orderBy('sort_order')->get();
	$menu = [];
	$counter = 0;
	foreach($items as $item){
		$menu[$counter] = [
			'label'=> $item->label,
			'url'=> url($item->url)
		];
		//check for children
		$children = \App\HeaderMenu::where('parent_id',$item->id)->orderBy('sort_order')->get();
		if($children->count()>0){
			foreach($children as $child){
				$menu[$counter]['children'][] = [
					'label'=> $child->label,
					'url'=> url($child->url)
				];
			}
			
		}
		else{
			$menu[$counter]['children'] = false;
		}
		
		$counter++;
	}
	
	return $menu;
	
}
/*--MARUF START--*/
function customWebsiteMenu(){
	
	$items= \App\HeaderMenu::where([
		['parent_id', '=', 0],
		['name', '=', 'custom'],
		['url', 'not like', '%verify-certificate%'],
	])->whereNotIn('id', [14,31,30])->orderBy('sort_order')->get();
	$menu = [];
	$counter = 0;
	foreach($items as $item){
		$menu[$counter] = [
			'label'=> $item->label,
			'url'=> url($item->url)
		];
		//check for children
		$children = \App\HeaderMenu::where([
			['parent_id', '=', $item->id],
			['name', '=', 'custom'],
			['url', '!=', 'verify-certificate'],
		])->whereNotIn('id', [15,7])->orderBy('sort_order')->get();
		if($children->count()>0){
			foreach($children as $child){
				$menu[$counter]['children'][] = [
					'label'=> $child->label,
					'url'=> ($child->url)
				];
			}
			
		}
		else{
			$menu[$counter]['children'] = false;
		}
		
		$counter++;
	}
	
	return $menu;
	
}
/*--MARUF END--*/
function footerMenu(){
	
	$items= \App\FooterMenu::where('parent_id',0)->orderBy('sort_order')->get();
	$menu = [];
	$counter = 0;
	foreach($items as $item){
		$menu[$counter] = [
			'label'=> $item->label,
			'url'=> url($item->url)
		];
		//check for children
		$children = \App\FooterMenu::where('parent_id',$item->id)->orderBy('sort_order')->get();
		if($children->count()>0){
			foreach($children as $child){
				$menu[$counter]['children'][] = [
					'label'=> $child->label,
					'url'=> url($child->url)
				];
			}
			
		}
		else{
			$menu[$counter]['children'] = false;
		}
		
		$counter++;
	}
	
	return $menu;
}

function tview($view,$data =[],$merge=[]){
	$currentTemplate = \App\Template::where('enabled',1)->first();
	$path=$currentTemplate->directory.'.views.'.$view;
	
	if(!view()->exists($path)){
		$path = $view;
	}
	//dd($path);
	return view($path,$data,$merge);
}

function tasset($path,$secure=null){
	$currentTemplate = \App\Template::where('enabled',1)->first();
	$path= TEMPLATE_PATH.'/'.$currentTemplate->directory.'/assets/'.$path;
	return asset($path,$secure);
}

function toption($option,$field){
	$currentTemplate = \App\Template::where('enabled',1)->first();
	
	$optionRow = $currentTemplate->templateOptions()->where('name',$option)->first();
	if(!$optionRow){
		return '';
	}
	
	$value = $optionRow->value;
	
	$values = sunserialize($value);
	if(isset($values[$field])){
		return $values[$field];
	}
	else{
		return '';
	}
	
}

function sunserialize($value){
	$value = preg_replace_callback(
		'!s:(\d+):"(.*?)";!',
		function($m) {
			return 's:'.strlen($m[2]).':"'.$m[2].'";';
		},
		$value);
	return unserialize($value);
}

function optionActive($option){
	$currentTemplate = \App\Template::where('enabled',1)->first();
	$option =$currentTemplate->templateOptions()->where('name',$option)->first();
	if(!$option){
		return false;
	}
	$enabled = $option->enabled;
	return $enabled==1;
}

function paymentInfo($dir){
	$info = include PAYMENT_PATH."/{$dir}/info.php";
	
	return $info;
}

function smsInfo($dir){
	$info = include MESSAGING_PATH."/{$dir}/info.php";
	return $info;
}

function getRole(){
	if (\Illuminate\Support\Facades\Auth::check()){
		return \Illuminate\Support\Facades\Auth::user()->role;
	}
}

function fullstop($text){
	if(empty($text) || substr($text, -1)=='.'){
		return $text;
	}
	else{
		return $text.'.';
	}
	
}

function credits(){
	
	return '';
	
	
}


function setRedirectLink($url){
	session()->put('redirect',$url);
}

function getRedirectLink(){
	$url = session()->get('redirect');
	session()->forget('redirect');
	return $url;
}

function canEnroll($courseId){
	$course = \App\Course::findOrFail($courseId);
	if(empty($course->enabled)){
		flashMessage(__lang('disabled-course'));
		return false;
	}
	
	if($course->enrollment_closes > 0 && \Illuminate\Support\Carbon::parse($course->enrollment_closes) < \Illuminate\Support\Carbon::now()){
		flashMessage('enrolment-closed');
		return false;
	}
	
	if($course->end_date > 0 && \Illuminate\Support\Carbon::parse($course->end_date) < \Illuminate\Support\Carbon::now()){
		flashMessage('course-ended');
		return false;
	}
	
	if($course->enforce_capacity==1 && $course->studentCourses()->count() >= $course->capacity){
		flashMessage(__lang('capacity-exceeded'));
		return false;
	}
	
	return true;
}

function paymentOption($directory,$option){
	$gateway = \App\PaymentMethod::where('directory',$directory)->first();
	if(!$gateway){
		return false;
	}
	$data = unserialize($gateway->settings);
	if (isset($data[$option])){
		return $data[$option];
	}
	else{
		return false;
	}
}

function messagingOption($directory,$option){
	$gateway = \App\SmsGateway::where('directory',$directory)->first();
	if(!$gateway){
		return false;
	}
	$data = unserialize($gateway->settings);
	if (isset($data[$option])){
		return $data[$option];
	}
	else{
		return false;
	}
}


function sendEmail($recipientEmail,$subject,$message,$from=null,$cc=null,$attachments=null){
	
	$cc = extract_emails($cc);
	try{
		
		if(!empty($cc)){
			
			//generate array from cc
			$ccArray = explode(',',$cc);
			$allCC = [];
			foreach($ccArray as $key=>$value){
				$value = trim($value);
				$validator = \Illuminate\Support\Facades\Validator::make(['email'=>$value],['email'=>'email']);
				
				if(!$validator->fails()){
					$allCC[] = $value;
				}
				
			}
			
			\Illuminate\Support\Facades\Mail::to($recipientEmail)->cc($allCC)->send(New \App\Mail\Generic($subject,$message,$from,$attachments));
		}
		else{
			
			\Illuminate\Support\Facades\Mail::to($recipientEmail)->send(New \App\Mail\Generic($subject,$message,$from,$attachments));
		}
		return true;
		
		
		
	}
	catch(\Exception $ex){
		
		flashMessage(__('default.send-failed').': '.$ex->getMessage());
		return false;
	}
	
}


function extract_emails($str){
	// This regular expression extracts all emails from a string:
	$regexp = '/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+/i';
	preg_match_all($regexp, $str, $m);
	
	$emails= isset($m[0]) ? $m[0] : array();
	$newEmails = [];
	foreach($emails as $key=>$value){
		$newEmails[$value] = $value;
	}
	
	if(count($newEmails)>0){
		$addresses = implode(' , ',$newEmails);
		return $addresses;
	}
	else{
		return null;
	}
	
	
	
}

function setBillingDefaults($user){
	if (empty($user->billing_firstname))
	{
		$user->billing_firstname = $user->name;
	}
	
	if (empty($user->billing_lastname)){
		$user->billing_lastname = $user->last_name;
	}
	
	if (empty($user->billing_country_id) && getCart()->hasInvoice()){
		$user->billing_country_id = getCart()->getInvoiceObject()->currency->country_id;
	}
	$user->save();
	
}

function getCountryDialCode($countryCode){
	$countryArray = array(
		'AD'=>array('name'=>'ANDORRA','code'=>'376'),
		'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
		'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
		'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
		'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
		'AL'=>array('name'=>'ALBANIA','code'=>'355'),
		'AM'=>array('name'=>'ARMENIA','code'=>'374'),
		'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
		'AO'=>array('name'=>'ANGOLA','code'=>'244'),
		'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
		'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
		'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
		'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
		'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
		'AW'=>array('name'=>'ARUBA','code'=>'297'),
		'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
		'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
		'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
		'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
		'BE'=>array('name'=>'BELGIUM','code'=>'32'),
		'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
		'BG'=>array('name'=>'BULGARIA','code'=>'359'),
		'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
		'BI'=>array('name'=>'BURUNDI','code'=>'257'),
		'BJ'=>array('name'=>'BENIN','code'=>'229'),
		'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
		'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
		'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
		'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
		'BR'=>array('name'=>'BRAZIL','code'=>'55'),
		'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
		'BT'=>array('name'=>'BHUTAN','code'=>'975'),
		'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
		'BY'=>array('name'=>'BELARUS','code'=>'375'),
		'BZ'=>array('name'=>'BELIZE','code'=>'501'),
		'CA'=>array('name'=>'CANADA','code'=>'1'),
		'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
		'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
		'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
		'CG'=>array('name'=>'CONGO','code'=>'242'),
		'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
		'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
		'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
		'CL'=>array('name'=>'CHILE','code'=>'56'),
		'CM'=>array('name'=>'CAMEROON','code'=>'237'),
		'CN'=>array('name'=>'CHINA','code'=>'86'),
		'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
		'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
		'CU'=>array('name'=>'CUBA','code'=>'53'),
		'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
		'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
		'CY'=>array('name'=>'CYPRUS','code'=>'357'),
		'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
		'DE'=>array('name'=>'GERMANY','code'=>'49'),
		'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
		'DK'=>array('name'=>'DENMARK','code'=>'45'),
		'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
		'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
		'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
		'EC'=>array('name'=>'ECUADOR','code'=>'593'),
		'EE'=>array('name'=>'ESTONIA','code'=>'372'),
		'EG'=>array('name'=>'EGYPT','code'=>'20'),
		'ER'=>array('name'=>'ERITREA','code'=>'291'),
		'ES'=>array('name'=>'SPAIN','code'=>'34'),
		'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
		'FI'=>array('name'=>'FINLAND','code'=>'358'),
		'FJ'=>array('name'=>'FIJI','code'=>'679'),
		'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
		'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
		'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
		'FR'=>array('name'=>'FRANCE','code'=>'33'),
		'GA'=>array('name'=>'GABON','code'=>'241'),
		'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
		'GD'=>array('name'=>'GRENADA','code'=>'1473'),
		'GE'=>array('name'=>'GEORGIA','code'=>'995'),
		'GH'=>array('name'=>'GHANA','code'=>'233'),
		'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
		'GL'=>array('name'=>'GREENLAND','code'=>'299'),
		'GM'=>array('name'=>'GAMBIA','code'=>'220'),
		'GN'=>array('name'=>'GUINEA','code'=>'224'),
		'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
		'GR'=>array('name'=>'GREECE','code'=>'30'),
		'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
		'GU'=>array('name'=>'GUAM','code'=>'1671'),
		'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
		'GY'=>array('name'=>'GUYANA','code'=>'592'),
		'HK'=>array('name'=>'HONG KONG','code'=>'852'),
		'HN'=>array('name'=>'HONDURAS','code'=>'504'),
		'HR'=>array('name'=>'CROATIA','code'=>'385'),
		'HT'=>array('name'=>'HAITI','code'=>'509'),
		'HU'=>array('name'=>'HUNGARY','code'=>'36'),
		'ID'=>array('name'=>'INDONESIA','code'=>'62'),
		'IE'=>array('name'=>'IRELAND','code'=>'353'),
		'IL'=>array('name'=>'ISRAEL','code'=>'972'),
		'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
		'IN'=>array('name'=>'INDIA','code'=>'91'),
		'IQ'=>array('name'=>'IRAQ','code'=>'964'),
		'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
		'IS'=>array('name'=>'ICELAND','code'=>'354'),
		'IT'=>array('name'=>'ITALY','code'=>'39'),
		'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
		'JO'=>array('name'=>'JORDAN','code'=>'962'),
		'JP'=>array('name'=>'JAPAN','code'=>'81'),
		'KE'=>array('name'=>'KENYA','code'=>'254'),
		'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
		'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
		'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
		'KM'=>array('name'=>'COMOROS','code'=>'269'),
		'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
		'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
		'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
		'KW'=>array('name'=>'KUWAIT','code'=>'965'),
		'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
		'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
		'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
		'LB'=>array('name'=>'LEBANON','code'=>'961'),
		'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
		'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
		'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
		'LR'=>array('name'=>'LIBERIA','code'=>'231'),
		'LS'=>array('name'=>'LESOTHO','code'=>'266'),
		'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
		'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
		'LV'=>array('name'=>'LATVIA','code'=>'371'),
		'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
		'MA'=>array('name'=>'MOROCCO','code'=>'212'),
		'MC'=>array('name'=>'MONACO','code'=>'377'),
		'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
		'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
		'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
		'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
		'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
		'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
		'ML'=>array('name'=>'MALI','code'=>'223'),
		'MM'=>array('name'=>'MYANMAR','code'=>'95'),
		'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
		'MO'=>array('name'=>'MACAU','code'=>'853'),
		'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
		'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
		'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
		'MT'=>array('name'=>'MALTA','code'=>'356'),
		'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
		'MV'=>array('name'=>'MALDIVES','code'=>'960'),
		'MW'=>array('name'=>'MALAWI','code'=>'265'),
		'MX'=>array('name'=>'MEXICO','code'=>'52'),
		'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
		'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
		'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
		'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
		'NE'=>array('name'=>'NIGER','code'=>'227'),
		'NG'=>array('name'=>'NIGERIA','code'=>'234'),
		'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
		'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
		'NO'=>array('name'=>'NORWAY','code'=>'47'),
		'NP'=>array('name'=>'NEPAL','code'=>'977'),
		'NR'=>array('name'=>'NAURU','code'=>'674'),
		'NU'=>array('name'=>'NIUE','code'=>'683'),
		'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
		'OM'=>array('name'=>'OMAN','code'=>'968'),
		'PA'=>array('name'=>'PANAMA','code'=>'507'),
		'PE'=>array('name'=>'PERU','code'=>'51'),
		'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
		'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
		'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
		'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
		'PL'=>array('name'=>'POLAND','code'=>'48'),
		'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
		'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
		'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
		'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
		'PW'=>array('name'=>'PALAU','code'=>'680'),
		'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
		'QA'=>array('name'=>'QATAR','code'=>'974'),
		'RO'=>array('name'=>'ROMANIA','code'=>'40'),
		'RS'=>array('name'=>'SERBIA','code'=>'381'),
		'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
		'RW'=>array('name'=>'RWANDA','code'=>'250'),
		'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
		'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
		'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
		'SD'=>array('name'=>'SUDAN','code'=>'249'),
		'SE'=>array('name'=>'SWEDEN','code'=>'46'),
		'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
		'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
		'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
		'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
		'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
		'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
		'SN'=>array('name'=>'SENEGAL','code'=>'221'),
		'SO'=>array('name'=>'SOMALIA','code'=>'252'),
		'SR'=>array('name'=>'SURINAME','code'=>'597'),
		'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
		'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
		'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
		'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
		'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
		'TD'=>array('name'=>'CHAD','code'=>'235'),
		'TG'=>array('name'=>'TOGO','code'=>'228'),
		'TH'=>array('name'=>'THAILAND','code'=>'66'),
		'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
		'TK'=>array('name'=>'TOKELAU','code'=>'690'),
		'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
		'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
		'TN'=>array('name'=>'TUNISIA','code'=>'216'),
		'TO'=>array('name'=>'TONGA','code'=>'676'),
		'TR'=>array('name'=>'TURKEY','code'=>'90'),
		'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
		'TV'=>array('name'=>'TUVALU','code'=>'688'),
		'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
		'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
		'UA'=>array('name'=>'UKRAINE','code'=>'380'),
		'UG'=>array('name'=>'UGANDA','code'=>'256'),
		'US'=>array('name'=>'UNITED STATES','code'=>'1'),
		'UY'=>array('name'=>'URUGUAY','code'=>'598'),
		'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
		'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
		'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
		'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
		'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
		'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
		'VN'=>array('name'=>'VIET NAM','code'=>'84'),
		'VU'=>array('name'=>'VANUATU','code'=>'678'),
		'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
		'WS'=>array('name'=>'SAMOA','code'=>'685'),
		'XK'=>array('name'=>'KOSOVO','code'=>'381'),
		'YE'=>array('name'=>'YEMEN','code'=>'967'),
		'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
		'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
		'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
		'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
	);
	
	$countryCode = strtoupper($countryCode);
	return $countryArray[$countryCode]['code'];
}

function traineasy_parseXMLToArray($xml)
{
	if($xml->count() <= 0)
		return false;
	
	$data = array();
	foreach ($xml as $element) {
		if($element->children()) {
			foreach ($element as $child) {
				if($child->attributes()) {
					foreach ($child->attributes() as $key => $value) {
						$data[$element->getName()][$child->getName()][$key] = $value->__toString();
					}
				} else {
					$data[$element->getName()][$child->getName()] = $child->__toString();
				}
			}
		} else {
			$data[$element->getName()] = $element->__toString();
		}
	}
	return $data;
}

function sendSms($gateway=null,$recipients,$message){
	
	if(empty($gateway)){
		$gateway = \App\SmsGateway::where('default',1)->first();
		if(!$gateway){
			return false;
		}
	}else{
		$gateway = \App\SmsGateway::findOrFail($gateway);
	}
	
	if (setting('sms_enabled')!=1){
		return false;
	}
	
	$code = $gateway->directory;
	$file = 'gateways/messaging/'.$code.'/functions.php';
	if (file_exists($file)){
		require_once($file);
	}
	else{
		return false;
	}
	
	if (!function_exists('traineasy_send')){
		flashMessage(__lang('invalid-gateway'));
		return false;
	}
	
	return traineasy_send($message,$recipients);
}

function arrayToStringMsg($array){
	if (!is_array($array)){
		return false;
	}
	$text = '<ul>';
	foreach ($array as $key=>$value){
		if(is_string($value)){
			$text .= "<li><strong>{$key}</strong>: {$value}</li>";
		}
	}
	$text .= '</ul>';
	return $text;
}


function stamp($time){
	if (empty($time)){
		return $time;
	}
	return \Illuminate\Support\Carbon::parse($time)->timestamp;
}

function apiCourse($row){
	$row->session_id = $row->id;
	$row->session_date = stamp($row->start_date);
	$row->session_status = $row->enabled;
	$row->enrollment_closes = stamp($row->enrollment_closes);
	$row->session_name = $row->name;
	$row->session_end_date = stamp($row->end_date);
	$row->session_type = $row->type;
	$row->amount = $row->fee;
	return $row;
}

function apiClass($row){
	if (!$row){
		return false;
	}
	
	if (isset($row->id)){
		$row->session_lesson_id = $row->id;
		$row->lesson_id = $row->id;
	}
	
	
	if (isset($row->course_id)){
		$row->session_id = $row->course_id;
	}
	
	
	if (!empty(@$row->lesson_date)){
		$row->lesson_date = stamp($row->lesson_date);
	}
	if (!empty(@$row->lesson_start)){
		$row->lesson_start = stamp($row->lesson_start);
	}
	if (!empty(@$row->lesson_end)){
		$row->lesson_end = stamp($row->lesson_end);
	}
	
	$row->lesson_name = @$row->name;
	
	$row->lesson_type = @$row->type;
	$row->content = @$row->description;
	return $row;
}

function apiDownload($row){
	
	if(isset($row->name)){
		$row->download_name = $row->name;
	}
	
	if (isset($row->course_id)){
		$row->session_id = $row->course_id;
	}
	
	
	
	$row->download_session_id = $row->id;
	if (!isset($row->download_id)){
		$row->download_id = $row->id;
	}
	
	
	return $row;
}

function apiInstructor($row){
	$row->session_instructor_id = $row->admin_id;
	$row->account_id = $row->admin_id;
	$row->first_name = $row->name;
	$row->account_description =  $row->about;
	$row->picture = $row->user_picture;
	return $row;
}

function apiTest($row){
	$row->session_test_id = $row->id;
	$row->test_id = $row->id;
	if(isset($row->course_id)){
		$row->session_id = $row->course_id;
	}
	
	$row->opening_date = stamp($row->opening_date);
	$row->closing_date = stamp($row->closing_date);
	$row->account_id = $row->admin_id;
	if (isset($row->course_name)){
		$row->session_name = $row->course_name;
	}
	
	return $row;
}

function apiLecture($row){
	$row->lecture_id = $row->id;
	$row->lecture_title = $row->title;
	return $row;
}

function apiRowset($rowset,$function){
	$data = [];
	foreach($rowset as $row){
		$data[] = $function($row);
	}
	return $data;
}

function apiDiscussion($row){
	$row->discussion_id = $row->id;
	$row->session_id = $row->course_id;
	return $row;
}

function redirect_now($url, $code = 302)
{
	try {
		\App::abort($code, '', ['Location' => $url]);
	} catch (\Exception $exception) {
		// the blade compiler catches exceptions and rethrows them
		// as ErrorExceptions :(
		//
		// also the __toString() magic method cannot throw exceptions
		// in that case also we need to manually call the exception
		// handler
		$previousErrorHandler = set_exception_handler(function () {
		});
		restore_error_handler();
		call_user_func($previousErrorHandler, $exception);
		die;
	}
}

function getAdminRole($id){
	$admin = User::where('id',$id)->whereHas('admin')->first();
	return $admin->admin->adminRole->name;
}
function check_route_permissions($route_name){
	
	$has_permission = 1;
	$list = include 'permissions.php';

	if(isset($list[$route_name])){
		$permission = $list[$route_name];
		if(!empty($permission) && !Auth::user()->can('access',$permission)){
			$has_permission = 0;
		}
	}else{
		$has_permission = 0;
	}
	
	return $has_permission;
}


/**
 * get Unread Messages
 *
 *
 * @return mixed
 */
function getUnreadMessages()
{
	$folder = \App\Model\MailboxFolder::where('title', "Inbox")->first();
	
	$messages = \App\Model\Mailbox::join('mailbox_receiver', 'mailbox_receiver.mailbox_id', '=', 'mailbox.id')
		->join('mailbox_user_folder', 'mailbox_user_folder.user_id', '=', 'mailbox_receiver.receiver_id')
		->join('mailbox_flags', 'mailbox_flags.user_id', '=', 'mailbox_user_folder.user_id')
		->where('mailbox_receiver.receiver_id', \Auth::user()->id)
//                          ->where('parent_id', 0)
		->where('mailbox_flags.is_unread', 1)
		->where('mailbox_user_folder.folder_id', $folder->id)
		->where('sender_id', '!=', \Auth::user()->id)
		->whereRaw('mailbox.id=mailbox_receiver.mailbox_id')
		->whereRaw('mailbox.id=mailbox_flags.mailbox_id')
		->whereRaw('mailbox.id=mailbox_user_folder.mailbox_id')
		->select(["*", "mailbox.id as id"])
		->get();
	
	return $messages;
}



/**
 * getContacts
 *
 *
 * @param null $status
 * @return \Illuminate\Database\Eloquent\Collection|static[]
 */
function getContacts($status = null)
{
	if(!$status)
		return \App\Model\Contact::all();
	
	return \App\Model\Contact::join('contact_status', 'contact_status.id', '=', 'contact.status')
		->where('contact_status.name', $status)
		->get();
	
}



/**
 * check directory exists and try to create it
 *
 *
 * @param $directory
 */
function checkDirectory($directory)
{
	try {
		if (!file_exists(public_path('uploads/' . $directory))) {
			mkdir(public_path('uploads/' . $directory));
			
			chmod(public_path('uploads/' . $directory), 0777);
		}
	} catch (\Exception $e) {
		die($e->getMessage());
	}
}

