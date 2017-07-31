<?php

  //  Donut         🍩 
  //  Dictionary Toolkit
  //    Version a.1
  //    Written by Thomas de Roo
  //    Licensed under MIT

  //  ++  File: jsMover.js.php

header('Content-Type: text/javascript');
session_start();

if(!isset($_GET['key']))
  die();

if(isset($_SESSION[$_GET['key']]))
  die(($_SESSION[$_GET['key']]));
else
	die("void(0);");
