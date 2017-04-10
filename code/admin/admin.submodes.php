<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under MIT
	File: code/admin/admin.submodes.php
*/


	// WE ABSOLUTLY NEED TO BE LOGGED IN!!!
	if(!pLogged())
		pUrl('', true);

	// Calling the manage element code!

	pManageInflectionElement('submodes', 'submode', 'submodes', 'Inflection rows');