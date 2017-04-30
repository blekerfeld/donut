<?php

  //  Donut         🍩 
  //  Dictionary Toolkit
  //    Version a.1
  //    Written by Thomas de Roo
  //    Licensed under MIT

  //  ++  File: jsMover.js.php

header('Content-Type: text/javascript');
require '../../../config.php';
session_start();

if(!isset($_GET['key']))
  die();

elseif(isset($_SESSION[$_GET['key']]))
  die(($_SESSION[$_GET['key']]));

