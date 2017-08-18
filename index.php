<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      index.php

// We need our configuration file
require 'config.php';

// Initialize by calling p, the big helper
p::initialize();

// Calling dispatch via the p
// CONFIG_FORCE_HOME loads the homepage if no query string is given.
p::dispatch(CONFIG_FORCE_HOME);

// Rest in peace †
die();
