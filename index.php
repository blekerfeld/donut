<?php

	$time_start = microtime(true); 
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: index.php

	// This is the only file that can (and should) be accessed directly. Let's make sure of that.
	if(!defined('donut'))
		define('donut', 1);

	// We may be dealing with the most crazy symbols evâh, so UTF-8 is needed
	header("content-type: text/html; charset=UTF-8");  
	
	//	Sessions are being started...
	session_start(); 

	ob_start();

	// 	Include config.php, an important file
	require 'config.php';

	//	Let's load ALL the global code (functions and classes)
	pLoadGlobalCode();


	//	We need to get the language loaded
	pLoadLanguage('English');

	//	Rewelcome our previous-session logged in guest
	pUser::restore();

	// Logging in is not working right now... strange, let's just do this

	// Let's pack some superglobals inside pAdress
	pAdress::session($_SESSION);
	pAdress::post($_POST);

	/*
	
		Welcome to the new dispatcher! :D

	*/ 

	if($_SERVER['QUERY_STRING'] == '')
		pUrl('?home', true);

	//	Calling dispatch!
	$dispatcher = new pDispatcher($_SERVER['QUERY_STRING']);
	
	if($dispatcher->compile())
		$dispatcher->structureObject->render();

	template:

	// Defining the menu, intial state
	$donut['page']['menu'] = pMenu();

	//	Starting with content output
	$donut['page']['header_final'] = "";
	$donut['page']['content_final'] = "";

	//	Putting the header sections into the donut.
	foreach($donut['page']['header'] as $output_section)
		$donut['page']['header_final'] .= "$output_section \n";

	//	Putting the page sections into the donut.
	foreach($donut['page']['content'] as $outputsection)
		$donut['page']['content_final'] .= "$outputsection \n";


	//	The template is loaded, that's the begining of the end.
	if(!isset(pAdress::arg()['ajax']))
		require pFromRoot("library/templates/main_template.php");
	else
		echo $donut['page']['content_final'];


//	† Rest in peace and blahblahblah.
		die();
