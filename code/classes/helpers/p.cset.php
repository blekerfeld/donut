<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: p.cset.php

	// The big p - the helper class!

// Our friends
require CONFIG_ROOT_PATH.'/library/assets/php/vendors/str.php';
require CONFIG_ROOT_PATH.'/library/assets/php/vendors/HashGenerator.php';
require CONFIG_ROOT_PATH.'/library/assets/php/vendors/Hashids.php';

class p{

	public static $Out = array(), $Header = array(), $db, $assets;

	public static function init(){
		self::$assets = require_once p::FromRoot("library/assets.struct.php");
		// Starting sessions
		session_start();
		$filenames = array();

		// Loading third party php assets
		foreach(self::$assets['php'] as $phpfile)
			$filenames[] = p::FromRoot('library/assets/php/'.$phpfile);

		foreach (glob(CONFIG_ROOT_PATH."/code/classes/*.cset.php") as $filename)
			$filenames[] = $filename;
		foreach (glob(CONFIG_ROOT_PATH."/code/classes/*/*.cset.php") as $filename)
			$filenames[] = $filename;
		foreach($filenames as $filename)
			if(file_exists($filename))
				require_once $filename;
		try {
			self::$db = new pConnection('mysql:host='.CONFIG_DB_HOST.';dbname='.CONFIG_DB_DATABASE, CONFIG_DB_USER, CONFIG_DB_PASSWORD);
		} catch (Exception $e) {
			die("COULD NOT CONNECT TO THE DATABASE! ERROR: ".$e->getMessage());
		}
		// Rewelcome our previous-session logged in guest
		pUser::restore();
		// Loading our locale
		p::loadLocale(CONFIG_ACTIVE_LOCALE);
	}

	public static function Query($sql){
		return self::$db->cacheQuery($sql);
	}

	public static function Out($input){
		self::$Out[] = $input;
	}

	public static function Header($input){
		self::$Header[] = $input;
	}

	public function getHeader(){
		return implode("\n\n", self::$Header);
	}

	public function __toString(){
		return implode("\n\n", self::$Out);
	}

	public static function Url($url, $header = false){

		$file = CONFIG_FILE;
		if($file == 'index.php')
			$file = '';

		if(p::StartsWith($url, '?') and CONFIG_REWRITE)
			$url = CONFIG_ABSOLUTE_PATH . '/' . $file .p::Str($url)->replacePrefix('?', '');
		elseif(p::StartsWith($url, '?') and !CONFIG_REWRITE)
			$url = CONFIG_ABSOLUTE_PATH . '/' . $file.$url;
		elseif(p::StartsWith($url, 'pol://') && $exploded = explode('pol://', $url))
			$url = p::Url($exploded[1]);
		elseif(p::StartsWith($url, 'http://') or p::StartsWith($url, 'ftp://') or p::StartsWith($url, 'https://') or p::StartsWith($url, 'mailto://'))
			$url = $url;
		else
			$url = CONFIG_ABSOLUTE_PATH. '/' . $url;
		if(!$header)
			return $url;
		else
			if(isset(pAdress::arg()['ajax']))
				return "<script type='text/javascript'>window.location = '".$url."';</script>";
		return header("Location:".$url);
	}

	public static function Hash($password, $userdata = ''){
		$salt = md5("kjj8f99e9iwj32ikm8391pok389iokn").sha1("pol0.1");
		$hash = md5(sha1(sha1(md5(sha1(md5($salt).$salt.$password).$password).md5(strlen($password)).$salt.md5($password))));
		$hash = hash('ripemd160', $hash);
		$hash = hash('sha256', $hash);
		if($userdata != '')
			$hash = hash('ripemd160', sha1($hash.$salt.$userdata).$userdata.sha1($salt));
		return $hash;
	}
	
	public static function StartsWith($haystack, $needle){
    	return (substr($haystack, 0, strlen($needle)) === $needle);
	}

	public static function EndsWith($haystack, $needle){
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}

	public static function HashId($hash, $decode = false){

		$hashid = new Hashids\Hashids("yeastIsACoreIngredientOfDoughnuts");

		if($decode){
			$return = $hashid->decode($hash);
			if(isset($return[0]))
				$return[0] = $return[0];
			else
				$return[0] = 0;

			return $return;
		}
		return $hashid->encode($hash); 
	}

	public static function FromRoot($path){
		return CONFIG_ROOT_PATH.'/'.$path;
	}

	public static function Str($str, $charset = null){
		return new \Delight\Str\Str($str, $charset);
	}

	public static function loadLocale($language){
		// So did we actually provide ourselves with a language? If the language is empty... it doesn't work, like at all
		if(empty($language))
			return false;

		// Let's create a variable to make things a little more readable
		$language_main_path = p::FromRoot('library/languages/' . $language . '.php');

		// Does this language exist?
		if(!file_exists($language_main_path))
			return die("Language files were not found.");

		// It does exists, let's load the main file
		include_once $language_main_path;

		// Now all other files
		foreach($transfer['lang_sub_files'] as $filename)
			include p::FromRoot('library/languages/' . $language . '.' . $filename . '.php');
			
		return true;
	}

	public static function initConfig($db){
		$settings = $db->query("SELECT * FROM config");
		while($setting = $settings->fetchObject())
			define("CONFIG_".$setting->SETTING_NAME, $setting->SETTING_VALUE);
	}

	public function Escape($value){
		// Return a proper escaped version of our value
		return trim(self::$db->quote($value), "'");
	}

	function Quote($value){
		return self::$db->quote(p::Escape($value));
	}

	public function MarkDown($text, $block = true, $examples = true, $num = false){
	
		// Parsing (@) to numbred examples:
		if($examples){
			$exampleCount = 0;
			$text = preg_replace_callback('/[\(](@)[\)]/', function($matches){
				global $exampleCount;
				$exampleCount++;
	  			return "(" . $exampleCount . ")";
			}, $text);
		}

		$parse = new ParsedownExtra;

		return "<div class='markdown-body ".($num ? 'num' : '')." ".($block ? 'block' : 'inline-block')."'>".$parse->text($text)."</div>";
	}

	public function CleanCache($section = 'queries', $prefix = ''){
		foreach(glob(CONFIG_ROOT_PATH . '/cache/' . $section . '/'.$prefix.'*.cache') as $filename)
			unlink($filename);
		return true;
	}

	public static function Highlight($needle, $haystack, $before, $after){
		return preg_replace('/' . preg_quote($needle) . '/i', $before . '$0' . $after, $haystack);
	}
	
	public static function Date($time_stamp, $show_time = true, $show_seconds = false, $timezone_when_possible = false) {
		
		// Everthing is added to this one
		$e = "";

		// Is it today? 
		if(date('d/m/Y') == date('d/m/Y', strtotime($time_stamp)))
		{
			$e .= DATE_TODAY;
		}

		// ... yesterday maybe?
		elseif(date("d/m/Y",mktime(0,0,0,date("m") ,date("d")-1,date("Y"))) == date('d/m/Y', strtotime($time_stamp)))
		{
			$e .= DATE_YESTERDAY;
		}

		// Tomorrow then?
		elseif(date("d/m/Y",mktime(0,0,0,date("m") ,date("d")+1,date("Y"))) == date('d/m/Y', strtotime($time_stamp)))
		{
			$e .= DATE_TOMORROW;
		}

		// This week? We just show the name of the day then
		elseif(date("W") == date("W", strtotime($time_stamp)) and date("Y") == date("Y", strtotime($time_stamp)))
		{
			$e .= constant("DATE_DAY_".date("w", strtotime($time_stamp)));
		}

		// If it is this year, we only have to show the month and the day
		elseif(date("Y") == date("Y", strtotime($time_stamp)))
		{
			if(DATE_DAYNUMBERAFTER == false)
				$e .= date("j", strtotime($time_stamp))." ";
			if(DATE_USESHORTMONTHS == true)
				$e .= constant("DATE_MONTH_SHORT_".date("n", strtotime($time_stamp)));
			if(DATE_USESHORTMONTHS == false)
				$e .= constant("DATE_MONTH_".date("n", strtotime($time_stamp)));
			if(DATE_DAYNUMBERAFTER == true)
				$e .= " ".date("j", strtotime($time_stamp));
			if(DATE_ADDSUFFIX == true)
				$e .= date("S", strtotime($time_stamp));
		}

		// This goes way back to another year, that's when we are now.
		else
		{
			
			if(DATE_DAYNUMBERAFTER == false)
				$e .= date("j", strtotime($time_stamp))." ";
			if(DATE_USESHORTMONTHS == true)
				$e .= constant("DATE_MONTH_SHORT_".date("n", strtotime($time_stamp)));
			if(DATE_USESHORTMONTHS == false)
				$e .= constant("DATE_MONTH_".date("n", strtotime($time_stamp)));
			if(DATE_DAYNUMBERAFTER == true)
				$e .= " ".date("j", strtotime($time_stamp));
			if(DATE_ADDSUFFIX == true)
				$e .= date("S", strtotime($time_stamp));
			if(DATE_SEPERATOR != "")
				$e .= DATE_SEPERATOR;
			$e .= date("Y", strtotime($time_stamp));
		}

		// Need to show time too?
		if($show_time == true)
		{
			$e .= DATE_PREPOSITION_TIME;
			if(DATE_USE12HOUR == true)
				$e .= date("h", strtotime($time_stamp)).":".date("i", strtotime($time_stamp)).(($show_seconds == true) ? (":".date("s", strtotime($time_stamp))) : "")." ".date("A", strtotime($time_stamp));
			else
				$e .= date("H", strtotime($time_stamp)).":".date("i", strtotime($time_stamp)).(($show_seconds == true) ? (":".date("s", strtotime($time_stamp))) : "");
			if(DATE_ADDTIMEZONEINDICATOR == true and $timezone_when_possible== true)
				$e .= " ".date("T", strtotime($time_stamp));
		}

		return $e;
	}


	public static function Notice($icon, $message, $type='notice', $id=''){
		return '<div class="'.$type.'" id="'.$id.'"><i class="fa '.$icon.'"></i> '.$message.'</div>';
	}
}