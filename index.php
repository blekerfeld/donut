<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: index.php
*/



# *le UTF8
	header("content-type: text/html; charset=UTF-8");  
#	*le session start
	session_start(); // start ALL the sessions\

# 	Include pol_config.php, an important file
	require 'pol_config.php';
#	Let's load ALL the functions we got
	polLoadFunctions();

#	Do we just need to set a session? That can we do!


if(isset($_GET['ajax_set_session'], $_GET['ajax_set_session_auth'], $_GET['ajax_set_session_value']))
{

	// We need to know if it was the script that called, not someone else!!
	if($pol['session_auth'] == $_GET['ajax_set_session_auth']){

		$_SESSION[$_GET['ajax_set_session']] = $_GET['ajax_set_session_value'];

	}

	die();
}


#	Rewelcome our previous-session logged in guest
	polLogonRestore();

#	check mobile
	checkMobile();

#	We need to get the language loaded
	pLoadLanguage('English');

	//updateDict();
 	
/*

	Now, Fizlounge works with applications.
	Each application has a folder in /applications, like logon.app
	Now we will set the application we are looking for...

*/	
	$dapp = polGetApp("home"); # Default app is the home - dashboard switcher
	$apps = polAppsArray();
	$setapp = false;
	$pol['usingdapp'] = false;
	$pol['page']['menu'] = polMenu();

	$pol['db']->query("UPDATE graphemes SET order = graphemes.id;");

	if(!isset($_GET['search'])){
		foreach($apps as $app)
		{
			if(isset($_REQUEST[$app[0]]) and !$setapp)
			{
				if(file_exists($pol['root_path'] . '/code/' . $app[1] . '.index.php'))
				{
					require $pol['root_path'] . '/code/' . $app[1] . '.index.php';
				}
				else
					die("<div class='header'>Fatal error: Donut failed loading the ".$app[1]."-section</div>");
				$setapp = true;
				break;
			}
		}
	}
	else{
		require $pol['root_path'] . '/code/home.index.php';
		$setapp = true;
	}

	if($setapp == false)
	{
		header("Location: index.php?home");
	}	
		
	

	$pol['page']['header_final'] = "";
	$pol['page']['content_final'] = "";

	foreach($pol['page']['header'] as $outputsection)
	{
		$pol['page']['header_final'] .= "$outputsection \n";
	}
	foreach($pol['page']['content'] as $outputsection)
	{
		$pol['page']['content_final'] .= "$outputsection \n";
	}
	
#	*let's do this by using the template
	if(!isset($_REQUEST['ajax']) AND !isset($_REQUEST['ajax_pOut']))
		require "templates/maintemplate.php";
	if(isset($_REQUEST['ajax']))
		echo pAjaxLinks($pol['page']['title']);
	if(isset($_REQUEST['ajax_word'])){
		echo pAjaxLinks($pol['page']['title']);
		echo $pol['page']['content_final'];
	}
	if(isset($_REQUEST['ajax_pOut'])){
		echo pAjaxLinks($pol['page']['title'])."<div class='nav'>".$pol['page']['menu']."</div>";
		if(!empty($pol['page']['header']))
            echo "<div class='header'>\n".$pol['page']['header_final']."\n </div>" ;
		echo "<div class='page'>".$pol['page']['content_final']."</div>";
	}

#	If logged in, the code'll save the last active time and last called app trigger (I.E. ?discover) in the database

	//if(loggedIn())
		//$result = $fiz['db']->query("UPDATE {$fiz['db_prefix']}users SET la_date = NOW(), la_act = '$trigger' WHERE id = {$_SESSION['fiz_user']}");

	#	*le page's death
		unset($pol);
		die();
?>