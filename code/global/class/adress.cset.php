<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: adress.cset.php

// For jQuery style fetching of the url information! :)
// like this: pAdress::arg()['id'];
class pAdress{

	static $queryString, $arguments, $post, $session;

	public static function arg($set = null){
		if($set != null)
			return self::$arguments = $set;
		return self::$arguments;
	}

	public static function post($set = null){
		if($set != null)
			return self::$post = $set;
		return self::$post;
	}

	public static function session($set = null, $value = null){
		if($value != null){
			$_SESSION[$set] = $value;
			return self::$session[$set] = $value;
		}
		if($set != null)
			return self::$session = $set;
		return self::$session;
	}

	public static function queryString($set = null){
		if($set != null)
			return self::$queryString = $set;
		return self::$queryString;
	}

}