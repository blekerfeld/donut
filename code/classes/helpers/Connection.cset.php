<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: connection.cset.php

class pConnection extends PDO{

	public function __construct(){
		call_user_func_array('parent::__construct', func_get_args());
		// UTF-8, bardzo proszę
		$this->exec("SET NAMES UTF8");
		$this->query("SET CHARACTER_SET_RESULTS='UTF8'");
		$this->initConfig();
	}

	protected function initConfig(){
		$settings = $this->query("SELECT * FROM config");
		while($setting = $settings->fetchObject())
			define("CONFIG_".$setting->SETTING_NAME, $setting->SETTING_VALUE);
	}

	public function cacheQuery($sql, $force_no_cache = false, $force_no_count = false){
	
		// We need the queries hash
		$table_name = preg_match_all("/\b(FROM|INTO|UPDATE)\b\s*(\w+)/", $sql, $matches);

		// If query caching is disabled, we just need to execute the query
		if($force_no_cache or CONFIG_ENABLE_QUERY_CACHING == 0){
			// Only select queries can be cached
			if(!p::StartsWith($sql, "SELECT"))
				return $this->query($sql);		
			$execute = $this->query($sql);
			$objects = array();
			while($object = $execute->fetchObject()){
				$objects[] = $object;
			}
			return new pCachedQuery($objects, $execute->rowCount(), $sql);
		} 

		// If this is not a select query, it will change something, therefore the cache needs to be cleaned
		if(!p::StartsWith($sql, "SELECT")){
			$this->CleanCache('queries', $matches[2][0].'_');
		}

		// Only select queries can be cached
		if(!p::StartsWith($sql, "SELECT"))
			return $this->query($sql);

		// File infomation
		$hash = $matches[2][0]."_".md5($sql);
		$cache_file = p::FromRoot('cache/queries/'.$hash.'.cache');
		$cache_folder = p::FromRoot('cache/queries');
		$cache_time = CONFIG_QC_TIME;

		// If we are not writable, we have to run the query without cache
		if(!is_writable($cache_folder))
			return $this->query($sql);

		// We need to see if there are any queries like these done within the query_cache_time
		if(file_exists($cache_file)){
			
			// Our file exists... let's get its creation time
			$cache_file_time = filemtime($cache_file);

			// Is the file still valid?
			 if (time() - $cache_file_time < $cache_time)
			 	return unserialize(file_get_contents($cache_file));

			// If not we need to delete it and be a little recursive...
			elseif(unlink($cache_file))
				return $this->cacheQuery($sql, true, true);

		}

		// The query isn't cached... or it is unvalid and therefore deleted
		else{

		//We need to execute the query, cache it and return the cached object
			

			$execute = $this->query($sql);

			// If the rowCount is 0, we just don't cache then!
			if($execute->rowCount() == 0)
				return $execute;

			// Otherwise we need to do a little more...
			else{

				// Fetching the objects in order to cache them
				$objects  = array();
				
				while($object = $execute->fetchObject()){
					$objects[] = $object;
				}



				// Create the cached query
				$cache_query = new pCachedQuery($objects, $execute->rowCount(), $sql);

			}

			// Cache the whole thing, if we cannot do that, we need to fallback
			if(!file_put_contents($cache_file, serialize($cache_query)))
				return $this->cacheQuery($sql, true);

			return $cache_query;

		}

	}

	public function CleanCache($section = 'queries', $prefix = ''){
		foreach(glob(CONFIG_ROOT_PATH . '/cache/' . $section . '/'.$prefix.'*.cache') as $filename)
			unlink($filename);
		return true;
	}
	
}