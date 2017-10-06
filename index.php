<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      index.php

// We need this file...
require 'Configuration.php';

// Doing our thing...
Donut::initialize()->dispatch()->render();

// And... Bye! 
die();
