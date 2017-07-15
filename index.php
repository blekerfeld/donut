<?php
// 	Donut
//	Dictionary Toolkit
// 		Version a.1
//		Written by Thomas de Roo
//		Licensed under MIT

//	++	File: index.php

// We need our configuration file
require 'config.php';

// Initialize by calling p, the big helper
p::initialize();

// Calling dispatch via the p
p::dispatch(CONFIG_FORCE_HOME);

// Rest in peace †
die();