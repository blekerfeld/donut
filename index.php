<?php
// 	Donut 				🍩 
//	Dictionary Toolkit
// 		Version a.1
//		Written by Thomas de Roo
//		Licensed under MIT

//	++	File: index.php

// We need our configuarion file
require 'config.php';

// Initialize by calling p, the big helper
p::initialize();

// Calling dispatch via the p
// CONFIG_FORCE_HOME loads the homepage if no query string is given.
p::dispatch(CONFIG_FORCE_HOME);

// Rest in peace †  
die();