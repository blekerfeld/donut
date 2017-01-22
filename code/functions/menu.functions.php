<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: menu.functions.php
*/


	function pMenu()
	{
  			return '<a href="'.pUrl('?home').'" '.pActiveMenu('home').' '.pActiveMenu('search').' '.pActiveMenu('word').' '.pActiveMenu('alphabet').' '.pActiveMenu('generate').'>
				<i class="fa fa-book"></i> '.MMENU_DICTIONARY.'</a> 
  			'.((pLogged()) ? '<a href="'.pUrl('?admin').'" '.pActiveMenu('admin').' ><i class="fa fa-tasks"></i> '.MMENU_MANAGE.'</a>' : '');
	}

	function pActiveMenu($request)
	{
		global $donut; 

		if(isset($_REQUEST[$request]))
			return 'class="active bga1"';

		return '';
	}


 ?>	