<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: apps.functions.php
*/

	function pAppsArray()
	{
		global $donut;
		$getapps = $donut['db']->query("SELECT * FROM apps");
		$apps = array();
		foreach($getapps as $app)
			$apps[] = array($app['getter'], $app['app']);
		return $apps;
	}

	function pGetApp($appname)
	{
		global $donut;
		$getapps = $donut['db']->query("SELECT * FROM apps WHERE getter = '$appname' LIMIT 1;");
		foreach($getapps as $app)
			$app = array($app['getter'], $app['app']);
		return $app;
	}

 ?>