<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: Configuration.php

define('CONFIG_DB_HOST', 'localhost');
define('CONFIG_DB_USER', 'root');
define('CONFIG_DB_PASSWORD', '');
define('CONFIG_DB_DATABASE', 'donut');
define('CONFIG_REWRITE', true);
define('CONFIG_FILE', 'index.php');
define('CONFIG_HASHID_SALT', "ThereIsYeastInDonutsDidYouKnowThat?");
define('CONFIG_ISBETA', true);
define('CONFIG_ROOT_PATH', dirname(__FILE__));
define('CONFIG_FOLDER', 'donut');
define('CONFIG_ABSOLUTE_PATH', "http://".$_SERVER['SERVER_NAME']."/".CONFIG_FOLDER);
define('CONFIG_REQUIRED_PHP_VERSION', '5.6.0');

// Check whether we actually meet that version requirment.
if (version_compare(phpversion(), CONFIG_REQUIRED_PHP_VERSION, '<'))
	die('We are sorry, but Donut requires PHP version '.CONFIG_REQUIRED_PHP_VERSION." in order to work.");

// ...add a bit of utf-8 to the mix, doesn't hurt, especially when we're in a Windows enviroment
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");

// The Main class is very important
require CONFIG_ROOT_PATH . '/src/main/Main.class.php';

// Now let's initialize the Donut, and give it back to index.php
return pMain::initialize();