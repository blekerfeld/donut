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
	$pol['version'] = array(
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
	$pol['team'] = array(
		'managers' => array('Thomas de Roo'),
		'developers' => array('Thomas de Roo'),
	);

	function pshowVersion()
 	{
 		global $pol;
 		$version = $pol['version']['major'].'.'.$pol['version']['minor'];
 		if($pol['version']['alpha'])
 			$version .= ' alpha '.$pol['version']['alphanumber'];
 		if($pol['version']['beta'])
 			$version .= ' beta '.$pol['version']['betanumber'];
 		if($pol['version']['rc'])
 			$version .= ' R.C. '.$pol['version']['rcnumber'];
 		$version .= ' <i>('.$pol['version']['codename'].')</i>';
 		return $version;
 	}	
 	function pMySQL_version()
 	{
 		global $pol;
 		$r = $pol['db']->query("SELECT version() as ver;");
 		$v = $r->fetchObject();
 		return $v->ver;
 	}

 ?>