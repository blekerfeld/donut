<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: version.functions.php
*/

#	Information about this version, and the team

	// This will make intern version 1.0.1.0.0 codename: Mammal (1.0 alpha 1)
	$donut['version'] = array(
		'major' => 1,
		'minor' => 0,
		'alpha' => true,
		'alphanumber' => 1,
		'beta' => false,
		'betanumber' => 0,
		'rc' => false,
		'rcnumber' => 0,
		'codename' => 'Mammal'
	);
	$donut['team'] = array(
		'managers' => array('Thomas de Roo'),
		'developers' => array('Thomas de Roo'),
	);

	function pshowVersion()
 	{
 		global $donut;
 		$version = $donut['version']['major'].'.'.$donut['version']['minor'];
 		if($donut['version']['alpha'])
 			$version .= ' alpha '.$donut['version']['alphanumber'];
 		if($donut['version']['beta'])
 			$version .= ' beta '.$donut['version']['betanumber'];
 		if($donut['version']['rc'])
 			$version .= ' R.C. '.$donut['version']['rcnumber'];
 		$version .= ' <i>('.$donut['version']['codename'].')</i>';
 		return $version;
 	}	
 	function pMySQL_version()
 	{
 		global $donut;
 		$r = pQuery("SELECT version() as ver;");
 		$v = $r->fetchObject();
 		return $v->ver;
 	}

 ?>