<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: database.functions.php

	// Some preperations!
	$donut['db_query_count'] = 0;

	// Getting ALL the tables
	if($donut['db_prefix'] !== ''){
		$donut['tables_replace'] = array();
		$tables = $donut['db']->query("SHOW TABLES");
		while($table = $tables->fetch()){
			$donut['db_search'][] = pStr($table[0])->replacePrefix($donut['db_prefix'], '').".";
			$donut['db_search_from'][] = "FROM ".pStr($table[0])->replacePrefix($donut['db_prefix'], '');
			$donut['db_search_update'][] = "UPDATE ".pStr($table[0])->replacePrefix($donut['db_prefix'], '');
			$donut['db_search_into'][] = "INTO ".pStr($table[0])->replacePrefix($donut['db_prefix'], '');
			$donut['db_search_join'][] = "JOIN ".pStr($table[0])->replacePrefix($donut['db_prefix'], '');
			$donut['db_replace'][] = $donut['db_prefix'].pStr($table[0])->replacePrefix($donut['db_prefix'], '').".";
			$donut['db_replace_from'][] = "FROM ".$donut['db_prefix'].pStr($table[0])->replacePrefix($donut['db_prefix'], '');
			$donut['db_replace_update'][] = "UPDATE ".$donut['db_prefix'].pStr($table[0])->replacePrefix($donut['db_prefix'], '');
			$donut['db_replace_into'][] = "INTO ".$donut['db_prefix'].pStr($table[0])->replacePrefix($donut['db_prefix'], '');
			$donut['db_replace_join'][] = "JOIN ".$donut['db_prefix'].pStr($table[0])->replacePrefix($donut['db_prefix'], '');
		}

	}



function pQuery($sql, $force_no_cache = false, $force_no_count = false){

	global $donut;

	//echo ("<div class='notice'>".$sql."</div>");

	$dbg = debug_backtrace();

	$donut['queries'][] = $sql." file: ".$dbg[0]['file'].'/'.$dbg[0]['line'];

	// We like counting
	if(!$force_no_count)
		$donut['db_query_count']++;

	// Replacing the table names with the prefixed ones.
	$sql = pReplaceDbPrefix($sql);

	// We need the queries hash
	$table_name = preg_match_all("/\b(FROM|INTO|UPDATE)\b\s*(\w+)/", $sql, $matches);
	

	// If query caching is disabled, we just need to execute the query
	if($force_no_cache or CONFIG_ENABLE_QUERY_CACHING == 0){
		// Only select queries can be cached
		if(!pStartsWith($sql, "SELECT"))
			return $donut["db"]->query($sql);		
		$execute = $donut["db"]->query($sql);
		$objects = array();
		while($object = $execute->fetchObject()){
			$objects[] = $object;
		}
		return new pCachedQuery($objects, $execute->rowCount(), $sql);
	} 

	// If this is not a select query, it will change something, therefore the cache needs to be cleaned
	if(!pStartsWith($sql, "SELECT")){
		pCleanCache('queries', $matches[2][0].'_');
	}

	// Only select queries can be cached
	if(!pStartsWith($sql, "SELECT"))
		return $donut["db"]->query($sql);

	// File infomation
	$hash = $matches[2][0]."_".md5($sql);
	$cache_file = pFromRoot('cache/queries/'.$hash.'.cache');
	$cache_folder = pFromRoot('cache/queries');
	$cache_time = CONFIG_QC_TIME;

	// If we are not writable, we have to run the query without cache
	if(!is_writable($cache_folder))
		return $donut["db"]->query($sql);

	// We need to see if there are any queries like these done within the query_cache_time
	if(file_exists($cache_file)){
		
		// Our file exists... let's get its creation time
		$cache_file_time = filemtime($cache_file);

		// Is the file still valid?
		 if (time() - $cache_file_time < $cache_time and $donut['db_query_count']--)
		 	return unserialize(file_get_contents($cache_file));

		// If not we need to delete it and be a little recursive...
		elseif(unlink($cache_file))
			return pQuery($sql, true, true);

	}

	// The query isn't cached... or it is unvalid and therefore deleted
	else{

	//We need to execute the query, cache it and return the cached object
		

		$execute = $donut['db']->query($sql);

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
			return pQuery($sql, true);

		return $cache_query;

	}

}



// This will just delete all cache files in a certain section
function pCleanCache($section = 'queries', $prefix = ''){

	global $donut;

	foreach(glob($donut['root_path'] . '//cache/' . $section . '/'.$prefix.'*.cache') as $filename)
		unlink($filename);

	return true;
	
}


function pReplaceDbPrefix($sql) 
{

	global $donut;

	if($donut['db_prefix'] == '')
		return $sql;

    $array = array();
    if($number = preg_match_all( '#((?<![\\\])[\'"])((?:.(?!(?<![\\\])\1))*.?)\1#i', $sql, $matches))
    {
        for ($i = 0; $i < $number; $i++)
        {
            if (!empty($matches[0][$i]))
            {
                $array[$i] = trim($matches[0][$i]);
                $sql = str_replace($matches[0][$i], '<#encode:'.$i.':encode#>', $sql);
            }
        }
    }

    
	$sql = str_replace($donut['db_search'], $donut['db_replace'], $sql);
	$sql = str_replace($donut['db_search_from'], $donut['db_replace_from'], $sql);
	$sql = str_replace($donut['db_search_update'], $donut['db_replace_update'], $sql);
	$sql = str_replace($donut['db_search_into'], $donut['db_replace_into'], $sql);
	$sql = str_replace($donut['db_search_join'], $donut['db_replace_join'], $sql);


    foreach ($array as $key => $js)
    {
        $sql = str_replace('<#encode:'.$key.':encode#>', $js, $sql);
    }

    return $sql;
}




function pEscape($value){
	
	global $donut;

	// Return a proper escaped version of our value
	return trim($donut['db']->quote($value), "'");

}

function pQuote($value){
	global $donut;
	return $donut['db']->quote(pEscape($value));
}

