<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: code/admin/admin.numbers.php
*/


	// WE ABSOLUTLY NEED TO BE LOGGED IN!!!
	if(!pLogged())
		pUrl('', true);

	// Calling the manage element code!

	pManageInflectionElement('numbers', 'number', 'numbers', 'Headings');