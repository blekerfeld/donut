<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: index.home.php
*/

	$donut['page']['head']['content'] = '<meta name="viewport" content="width=device-width, user-scalable=no">';

    pOut('<style>.</style>');


    pOut("<div class='ajaxload'></div>");

    pDictionaryHeader();

	pOut(pSearchScript());


 ?>	