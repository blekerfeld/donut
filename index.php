<?php
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

	// 	Include config.php, an important file
	require 'config.php';

	//	Let's load ALL the global code (functions and classes)
	pLoadGlobalCode();

	//	We need to get the language loaded
	pLoadLanguage('English');

	//	Rewelcome our previous-session logged in guest
	pUser::restore();

	// Let's pack some superglobals inside pAdress
	pAdress::session($_SESSION);
	pAdress::post($_POST);


	// Temporary markdown wrapper
	if(isset($_GET['md'])){
		$md = pMarkdown(file_get_contents(pFromRoot($_GET['md'].".md")), true);
		pOut("<br /><div class='home-margin'>".$md."</div>");
		goto template;
	}

	// l&Ezen [-en;&E=a]

	$rules = array("CON.VOW_CON_+.VOW=>%%", "CON_[aeou]_CON.+.&T,D=>%%", "CON_&A,E,O,U_CON.+.&T,D=>%%", "[x,k,f,s,c,h,p].+_&D_=>t", "_z_+=>s", "CON.VOW.CON_[b]_+.&T=>0", "_z_:>=>s");

	$rules2 = array(
		"<:_o_CON.VOW=>oʊ", "CON_o_CON.VOW=>oʊ", "_o.o_=>oʊ",
		"<:_o_CON.CON=>ɔ", "CON_o_CON.CON=>ɔ", "CON_o_CON.:>=>ɔ",
		"<:_a_CON.VOW=>a:", "CON_a_CON.VOW=>a:", "_a.a_=>a:",
		"<:_a_CON.CON=>ɑ", "CON_a_CON.CON=>ɑ", "CON_a_CON.:>=>ɑ",
		"_s.c.h_=>sχ",
		"_n.g_=>ŋ",
		);

	$twolc = new pTwolc($rules);
	$twolc2 = new pTwolc($rules2);

	$twolc->compile();
	$twolc2->compile();

	echo $twolc2->feed("school schol kat koning")->toSurface()."<br />";

	echo $twolc->feed('kat+en')->toSurface()."<br />";

	$voltooidDeelwoord = new pInflection("ge-!^ver-!^be[-en]&D");

	// Rule variables: E -> e that doesn't need to be corrected, D -> becomes d or t by phonological rules

	echo $twolc->feed((new pInflection("[-en]&T"))->inflect("l&Ezen"))->toSurface()."<br />";
	echo $twolc->feed((new pInflection("[-en;&E=>a]"))->inflect("l&Ezen"))->toSurface()."<br />";
	echo $twolc->feed((new pInflection("[-en;]&T"))->inflect("hebben"))->toSurface()."<br />";
	echo $twolc->feed((new pInflection("[-en]&De"))->inflect("werken"))->toSurface()."<br />";
	echo $twolc->feed((new pInflection("[-en]&De"))->inflect("delen"))->toSurface()."<br />";
	echo $twolc->feed((new pInflection("[-en]&De"))->inflect("fotograferen"))->toSurface()."<br />";
	echo $twolc->feed((new pInflection("[-en]&De"))->inflect("schildEren"))->toSurface()."<br />";
	echo $twolc->feed((new pInflection("[-a]ą-\$a"))->inflect("kobieta"))->toSurface()."<br />";
	echo $twolc->feed((new pInflection("[]mi-\$a"))->inflect("kobieta"))->toSurface()."<br />";
	echo $twolc->feed($voltooidDeelwoord->inflect("verhuizen"))->toSurface()."<br />";
	echo $twolc->feed($voltooidDeelwoord->inflect("duwen"))->toSurface()."<br />";
	echo $twolc->feed($voltooidDeelwoord->inflect("maken"))->toSurface()."<br />";
	echo $twolc->feed($voltooidDeelwoord->inflect("bewonen"))->toSurface()."<br />";
	echo $twolc->feed($voltooidDeelwoord->inflect("gooien"))->toSurface()."<br />";


	//!$x; remove x from end
	//!^x; remove x from start
	//?$x; add x to end
	//?^x; add x to start

	// maken -> gemaakt
	// maken!$en;?^ge;?$t; 
	// <ge>[-en]<t>

	if(isset($_REQUEST['old']))
		goto Old;

	/*
	
		Welcome to the new dispatcher! :D

	*/ 

	if($_SERVER['QUERY_STRING'] == '')
		pUrl('?home', true);

	//	Calling dispatch!
	$dispatcher = new pDispatch($_SERVER['QUERY_STRING']);
	$dispatcher->compile();
	
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

	//	If we're logged in, we need to have the user to our disposal
	if(pLogged())
		$donut['user'] = pGetUser();
	else
		$donut['user'] = null;

	//	The template is loaded, that's the begining of the end.
	if(!isset(pAdress::arg()['ajax']))
		require pFromRoot("library/templates/main_template.php");
	else
		echo $donut['page']['content_final'];

	Old:

	if(!isset($_REQUEST['old']))
		die();

	// We are in beta!
	$donut['is_beta'] = true;

	// Gettin' ALL the apps
	$apps = pAppsArray();
	$app_set = false;

	// Defining the menu, intial state
	$donut['page']['menu'] = pMenu();

		//	If we're logged in, we need to have the user to our disposal
	if(pLogged())
		$donut['user'] = pGetUser();
	else
		$donut['user'] = null;


	// Going through the apps, looking for answers...
	while($app = $apps->fetchObject()){


		if(isset($_REQUEST[$app->getter]) and !$app_set)
		{

			if(($app_file = $donut['root_path'] . '/code/' . $app->app . '.index.php') && file_exists($app_file))
				require_once $app_file;
			else
				die("<div class='header'>Fatal error: Donut failed loading the ".$app->app."-section</div><br />");

			$app_set = true;

		}

		//	We can escape this check early
		if($app_set)
			break;
	}

	//	No app means back to home
	if($app_set == false)
		pUrl("?".CONFIG_HOMEPAGE, true);
		
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
	if(isset($_REQUEST['ajax']) OR isset($_REQUEST['ajax_pOut'])){
		pAjaxStructure();
	}

	//	The template is loaded, that's the begining of the end.
	require pFromRoot("library/templates/main_template.php");

	//	† Rest in peace and blahblahblah.
		die();
