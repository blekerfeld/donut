<?php
// 	Donut 				🍩 
//	Dictionary Toolkit
// 		Version a.1
//		Written by Thomas de Roo
//		Licensed under MIT

//	++	File: index.php

// We might be dealing with the most crazy symbols evâh, so UTF-8 is needed, like a lot
header("content-type: text/html; charset=UTF-8");  

// We need our configuarion file
require 'config.php';

// Initialize the helper class
p::init();

// The dispactcher handles the remaining tasks 
pDispatcher::compile()->dispatch()->render();

// Rest in peace †  
die();