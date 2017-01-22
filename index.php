<?php

	// ❉ Donut
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: index.php

	// This is the only file that can (and should) be accessed directly. Let's make sure of that.
	if(!defined('donut'))
		define('donut', 1);

	// We may be dealing with the most crazy symbols evâh, so UTF-8 is needed
	header("content-type: text/html; charset=UTF-8");  
	
	//	Sessions are being started...
	session_start(); 

	// 	Include config.php, an important file
	require 'config.php';

	//	Let's load ALL the functions we got
	polLoadFunctions();

	//	Rewelcome our previous-session logged in guest
	pLogonRestore();

	//	We need to get the language loaded
	pLoadLanguage('English');

	// Gettin' ALL the apps
	$apps = pAppsArray();
	$app_set = false;

	// Defining the menu, intial state
	$donut['page']['menu'] = pMenu();

	// Going through the apps, looking for answers...
	foreach($apps as $app){
		if(isset($_REQUEST[$app[0]]) and !$setapp)
		{

			if($app_file = $donut['root_path'] . '/code/' . $app[1] . '.index.php' && file_exists($app_file))
				require_once $app_file;
			else
				die("<div class='header'>Fatal error: Donut failed loading the ".$app[1]."-section</div>");

			$app_set = true;

		}

		//	We can escape this check early
		if($app_set)
			break;
	}

	//	No app means back to home
	if($app_set == false)
		pUrl("?home", true);
		
	//	Starting with content output
	$donut['page']['header_final'] = "";
	$donut['page']['content_final'] = "";

	//	Putting the header sections into the donut.
	foreach($donut['page']['header'] as $output_section)
		$donut['page']['header_final'] .= "$output_section \n";

	//	Putting the page sections into the donut.
	foreach($donut['page']['content'] as $outputsection)
		$donut['page']['content_final'] .= "$outputsection \n";
	
	//	We need some extra structure if we are dealing with AJAX loaded pages
	if(isset($_REQUEST['ajax']) OR isset($_REQUEST['ajax_pOut']))
		pAjaxStructure();


	//	The template is loaded, that's the begining of the end.
	require pFromRoot("templates/main_template.php");

	//	† Rest in peace and blahblahblah.
		die();