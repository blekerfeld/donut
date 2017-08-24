<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      config.php

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

if (version_compare(phpversion(), CONFIG_REQUIRED_PHP_VERSION, '<'))
	die('Donut requires PHP version '.CONFIG_REQUIRED_PHP_VERSION." in order to work.");

// We might be dealing with the most crazy symbols evâh, so UTF-8 is needed, like a lot
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");

// Passing on the responsibilty to the main class

require CONFIG_ROOT_PATH . '/code/classes/Main.class.php';