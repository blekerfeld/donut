<?php

	// ❉ Donut
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: config.php


	//  Database information
		$dbHost = 'localhost'; 
		$dbUser = 'root'; 
		$dbPassword = ''; 
		$dbDatabase = 'donut'; 	

	// Setting up the connection, die if it fails

	try{
		$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbDatabase, $dbUser, $dbPassword);
	}
	catch (Exception $e){
		die("COULD NOT CONNECT TO THE DATABASE! ERROR: ".$e->getMessage());
	}
	
	//	We want the connection to be able to take everything in UTF8!
		$db->exec("SET NAMES UTF8");
		$db->query("SET CHARACTER_SET_RESULTS='UTF8'");

	##  Getting all settings and defining them as constants
	$settings = $db->query("SELECT * FROM config");
	while($setting = $settings->fetchObject())
		define("CONFIG_".$setting->SETTING_NAME, $setting->SETTING_VALUE);

	//	Global array
	$donut = array();
	$donut['rewrite'] = true;
	$donut['is_beta'] = true;
	$donut['file'] = 'index.php';
	$donut['settings'] =array();
	$donut['page'] = array();
	$donut['page']['title'] = CONFIG_SITE_TITLE;
	$donut['page']['outofinner'] = null;
	$donut['page']['menu'] = null;
	$donut['page']['head'] = null;
	$donut['page']['head']['final'] = '';
	$donut['page']['header'] = array();
	$donut['page']['menu'] = '';
	$donut['page']['content'] = array();
	$donut['db'] = $db;
	$donut['db_prefix'] = "";
	$donut['session_auth'] = md5("kjj8f99e9iwj32ikm8391pok389iokn");

	mb_internal_encoding("UTF-8");
	mb_regex_encoding("UTF-8");

##	These variables are the paths to the site root.
	$donut['root_path'] = dirname(__FILE__);
	$donut['absolute_path'] = "http://".$_SERVER['SERVER_NAME']."/donut/";

##  This function is used to encrypt passwords

	function pHash($password, $userdata = '')
	{
		global $donut;

		# Set Salt
		$salt = md5("kjj8f99e9iwj32ikm8391pok389iokn").sha1("pol0.1");
		$hash = md5(sha1(sha1(md5(sha1(md5($salt).$salt.$password).$password).md5(strlen($password)).$salt.md5($password))));
		$hash = hash('ripemd160', $hash);
		$hash = hash('sha256', $hash);
		if($userdata != '')
			$hash = hash('ripemd160', sha1($hash.$salt.$userdata).$userdata.sha1($salt));
		return $hash;
	}

##	Function to check if a string starts with a certain piece of string

function pStartsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function pEndsWith($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

function mbStringToArray ($string) {
    $strlen = mb_strlen($string);
    while ($strlen) {
        $array[] = mb_substr($string,0,1,"UTF-8");
        $string = mb_substr($string,1,$strlen,"UTF-8");
        $strlen = mb_strlen($string);
    }
    return $array;
} 
	
##	This function is used to load all the other functions and needed classes
	function pLoadGlobalCode()
	{
		global $donut;

		$filenames = array();

		foreach (glob($donut['root_path']."/code/classes/*.cset.php") as $filename)
			$filenames[] = $filename;

		foreach (glob($donut['root_path']."/code/classes/*/*.cset.php") as $filename)
			$filenames[] = $filename;


		foreach (glob($donut['root_path']."/code/functions/*.functions.php") as $filename)
			$filenames[] = $filename;

		foreach($filenames as $filename)
			require_once $filename;

	}


## This function is used for urls

	function pUrl($url = '', $header = false, $force = false)
	{
		// Needed. :)
		global $donut;

		if($donut['file'] == 'index.php')
			$donut['file'] = '';

		// Just an adition on the index.php?
		if(pStartsWith($url, '?') and $donut['rewrite'])
			$url = $donut['absolute_path'].$donut['file'].pStr($url)->replacePrefix('?', '');
		elseif(pStartsWith($url, '?') and !$donut['rewrite'])
			$url = $donut['absolute_path'].$donut['file'].$url;


		elseif(pStartsWith($url, 'pol://') && $exploded = explode('pol://', $url))
			$url = pUrl($exploded[1]);

		elseif(pStartsWith($url, 'http://') or pStartsWith($url, 'ftp://') or pStartsWith($url, 'https://'))
			$url = $url;

		else
			$url = $donut['absolute_path'].$url;

		if(!$header)
			return $url;

		else
			if(isset($_GET['ajax']) OR isset($_GET['ajax_pOut']) and !$force)
				return "<script>window.location = '".$url."';</script>";
				
			return header("Location:".$url);
	}

	require $donut['root_path'].'/library/assets/php/vendors/str.php';
	require $donut['root_path'].'/library/assets/php/vendors/HashGenerator.php';
	require $donut['root_path'].'/library/assets/php/vendors/Hashids.php';
	
	$donut['hashid'] = new Hashids\Hashids("yeastIsACoreIngredientOfDoughnuts");

	function pHashId($hash, $decode = false){
		global $donut;
		if($decode){
			$return = $donut['hashid']->decode($hash);
			if(isset($return[0]))
				$return[0] = $return[0];
			else
				$return[0] = 0;

			return $return;
		}
		return $donut['hashid']->encode($hash); 
	}

	function pStr($str, $charset = null) {
	    return new \Delight\Str\Str($str, $charset);
	}
	
	function pFromRoot($url)
	{
		global $donut;
		return $donut['root_path'].'/'.$url;
	}

	function pCheckMobile()  {
 		return preg_match("/Mobile|Android|BlackBerry|iPhone|Windows Phone/", $_SERVER['HTTP_USER_AGENT']);
	}

// Unsetting used variables
	unset($db);
	unset($db_prefix);
	unset($db_host);
	unset($db_user);
	unset($db_password);
