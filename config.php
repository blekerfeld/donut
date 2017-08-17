<?php
// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License

//	++	File:  config.php

define('CONFIG_DB_HOST', 'localhost');
define('CONFIG_DB_USER', 'root');
define('CONFIG_DB_PASSWORD', '');
define('CONFIG_DB_DATABASE', 'donut');
define('CONFIG_REWRITE', true);
define('CONFIG_FILE', 'index.php');
define('CONFIG_FORCE_HOME', true);
define('CONFIG_HASHID_SALT', "ThereIsYeastInDonutsDidYouKnowThat?");
define('CONFIG_ISBETA', true);
define('CONFIG_ROOT_PATH', dirname(__FILE__));
define('CONFIG_FOLDER', 'donut');
define('CONFIG_ABSOLUTE_PATH', "http://".$_SERVER['SERVER_NAME']."/".CONFIG_FOLDER);

// We might be dealing with the most crazy symbols evâh, so UTF-8 is needed, like a lot
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");
header("content-type: text/html; charset=UTF-8");  

// Passing on the responsibilty to the big helper class
require CONFIG_ROOT_PATH . '/code/classes/helpers/main.class.php';