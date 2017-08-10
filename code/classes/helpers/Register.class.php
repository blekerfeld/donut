<?php

// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: Register.class.php

// For jQuery style fetching of the url information! :)
// like this: pRegister::arg()['id'];
class pRegister{

	static $queryString, $arguments, $post, $session, $objectcache;

	public static function arg($set = null){
		if($set != null)
			return self::$arguments = $set;
		return self::$arguments;
	}

	public static function cacheAway($section, $id, $object){
		self::$objectcache[$section][$id] = $object;
	}

	public static function cache($section){
		return self::$objectcache[$section];
	}

	public function cacheCallBack($section, $id, $function, $arg = array()){
		if(!isset(self::$objectcache[$section][$id]))
			self::$objectcache[$section][$id] = call_user_func_array($function, $arg);
		return self::$objectcache[$section][$id];
	}

	public static function post($set = null){
		if($set != null)
			return self::$post = $set;
		return self::$post;
	}

	public static function changePost($key, $value){
		return self::$post[$key] = $value;
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