<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: database.functions.php




function pQuery($original_sql, $force_no_cache = false, $force_no_count = false){

	global $aulis, $setting;

	// We like counting
	if(!$force_no_count)
		$aulis['db_query_count']++;

	// Make sure we have the right database prefix.
	$search = array("FROM ", "INTO ", "UPDATE ", "JOIN ");
	$replace = array("FROM ".$aulis['db_prefix'], "INTO ".$aulis["db_prefix"], "UPDATE ".$aulis["db_prefix"],  "JOIN ".$aulis["db_prefix"]);
	$sql = str_replace($search, $replace, $original_sql);

	// Are we in debug mode? ONLY ALPHA :: NOTE: THIS WILL SEND THE HEADERS AWAY
	if(DEBUG_SHOW_QUERIES)
		echo "<div class='notice bg1 cwhite'>".$sql."</div>";

	// If query caching is disabled, we just need to execute the query
	if($force_no_cache or @$setting['enable_query_caching'] == 0)
		return $aulis["db"]->query($sql);

	// If this is not a select query, it will change something, therefore the cache needs to be cleaned
	if(!au_string_starts_with($sql, "SELECT"))
		au_force_clean_cache();


	// Only select queries can be cached
	if(!au_string_starts_with($sql, "SELECT"))
		return $aulis["db"]->query($sql);

	// We need the queries hash
	$hash = md5($sql);
	$cache_file = au_get_path_from_root('cache/queries/'.$hash.'.cache');
	$cache_folder = au_get_path_from_root('cache/queries');
	$cache_time = $setting['query_caching_time'];

	// If we are not writable, we have to run the query without cache
	if(!is_writable($cache_folder))
		return $aulis["db"]->query($sql);

	// We need to see if there are any queries like these done within the query_cache_time
	if(file_exists($cache_file)){
		
		// Our file exists... let's get its creation time
		$cache_file_time = filemtime($cache_file);

		// Is the file still valid?
		 if (time() - $cache_file_time < $cache_time and $aulis['db_query_count']--)
		 	return unserialize(file_get_contents($cache_file));

		// If not we need to delete it and be a little recursive...
		else if(unlink($cache_file))
			return au_query($original_sql, false, true);

	}

	// The query isn't cached... or it is unvalid and therefore deleted
	else{

		// We need to execute the query, cache it and return the cached object
		$execute = $aulis['db']->query($sql);

		// If the rowCount is 0, we can just create an empty cached query
		if($execute->rowCount() == 0)
			$cache_query = new au_class_cached_query(array(), 0, $sql);

		// Otherwise we need to do a little more...
		else{

			// Fetching the objects in order to cache them
			$objects  = array();
			
			while($object = $execute->fetchObject()){
				$objects[] = $object;
			}

			// Create the cached query
			$cache_query = new au_class_cached_query($objects, $execute->rowCount(), $sql);


		}

		// Cache the whole thing, if we cannot do that, we need to fallback
		if(!file_put_contents($cache_file, serialize($cache_query)))
			return au_query($original_sql, true);

		return $cache_query;

	}

}


function au_db_escape($value){
	
	global $aulis;

	// Return a proper escaped version of our value
	return trim($aulis['db']->quote($value), "'");

}


// For some change, this is not a function, but a simple class that contains information about cached queries
class au_class_cached_query implements Iterator {


	// The few variables this class needs
	private $_row_count = 0;
	private $_array_count = 0;
	private $_db_objects = null;
	private $position = -1;
	private $_query = null;


   function __construct($db_objects = array(), $row_count = 0, $query = '') {
       $this->_row_count = $row_count;
       $this->_db_objects = $db_objects;
       $this->_db_objects = new ArrayObject($db_objects);
       $this->_query = $query;
   }

   // Returns the fixed row count of the original query
   function rowCount(){
   		return $this->_row_count;
   }

   // Return the next object, as the original query would
   function fetchObject(){
   		return $this->current(true);
   }

   // Rewind up to -1, so that the object can be used again.
    function rewind() {
        $this->position = -1;
    }

    function current($object = false) {
    	$this->next();
    	if($object)
        	return @$this->_db_objects[$this->position];
        else
        	return @get_object_vars($this->_db_objects[$this->position]);
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->_db_objects[$this->position]);
    }

}