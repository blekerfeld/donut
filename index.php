<?php
// 	Donut 				🍩 
//	Dictionary Toolkit
// 		Version a.1
//		Written by Thomas de Roo
//		Licensed under MIT

//	++	File: index.php

// We might be dealing with the most crazy symbols evâh, so UTF-8 is needed, like a lot
header("content-type: text/html; charset=UTF-8");  

// Some required initial actions
session_start(); 
require 'config.php';

// Initialize the helper class, connection to the database, loading the settings
p::init();
p::loadLocale(CONFIG_ACTIVE_LOCALE);

// Rewelcome our previous-session logged in guest
pUser::restore();

// Let's pack some superglobals inside pAdress
pAdress::session($_SESSION);
pAdress::post($_POST);

// Mapping to /home if no query string is given
if($_SERVER['QUERY_STRING'] == '')
	$_SERVER['QUERY_STRING'] = 'home';

// Calling dispatch!
(new pDispatcher($_SERVER['QUERY_STRING']))->compile()->dispatch();

// Calling the template
(new pMainTemplate)->render();

// Rest in peace †  
die();