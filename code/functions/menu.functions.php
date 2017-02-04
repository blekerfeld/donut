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
  			return '<a href="'.pUrl('?home').'" '.pActiveMenu('home').' '.pActiveMenu('search').' '.pActiveMenu('lemma').' '.pActiveMenu('discuss-lemma').' '.pActiveMenu('alphabet').' '.pActiveMenu('generate').'>
				'.MMENU_DICTIONARY.'</a> 
  			'.((pLogged()) ? '<a href="'.pUrl('?admin').'" '.pActiveMenu('admin').' >'.MMENU_MANAGE.'</a>' : '');
	}

	function pActiveMenu($request)
	{
		global $donut; 

		if(isset($_REQUEST[$request]))
			return 'class="active bga1"';

		return '';
	}


 ?>	