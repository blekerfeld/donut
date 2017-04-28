<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under MIT
	File: version.functions.php
*/

#	Information about this version, and the team

	// This will make intern version 1.0.1.0.0 codename: Mammal (1.0 alpha 1)
	$donut['version'] = array(
		'major' => 0,
		'minor' => 1,
		'alpha' => true,
		'alphanumber' => 1,
		'beta' => false,
		'betanumber' => 0,
		'rc' => false,
		'rcnumber' => 0,
		'codename' => 'yeast'
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
 			$version .= ' ɑ'.$donut['version']['alphanumber'];
 		if($donut['version']['beta'])
 			$version .= ' beta '.$donut['version']['betanumber'];
 		if($donut['version']['rc'])
 			$version .= ' RC '.$donut['version']['rcnumber'];
 		$version .= ' <i class="small">('.$donut['version']['codename'].')</i>';
 		return $version;
 	}	
 	