<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: dictionary.functions.php



function pEL_Basics($id, $native, $type, $class, $subclass){

	// The donut is EVERY WHERE
	global $donut;

	return pQuery("UPDATE words SET native = ".$donut['db']->quote(pEscape($native)).", type_id = ".$donut['db']->quote(pEscape($type)).", classification_id = ".$donut['db']->quote(pEscape($class)).", subclassification_id = ".$donut['db']->quote(pEscape($subclass))." WHERE id = '$id';");

}