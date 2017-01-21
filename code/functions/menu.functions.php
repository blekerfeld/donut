<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: menu.functions.php
*/


	function polMenu()
	{
  			return '<a href="'.pUrl('?home').'" '.polActiveMenu('home').' '.polActiveMenu('search').' '.polActiveMenu('word').' '.polActiveMenu('alphabet').' '.polActiveMenu('generate').'><i class="fa fa-book"></i> '.MMENU_DICTIONARY.'</a> 
  			'.((logged()) ? '<a href="'.pUrl('?admin').'" '.polActiveMenu('admin').' ><i class="fa fa-tasks"></i> '.MMENU_MANAGE.'</a>' : '');
	}

	function polActiveMenu($request)
	{
		global $pol; 
		if(isset($_REQUEST[$request]))
		{
			return 'class="active bga1"';
			break;
		}
		elseif(((int)$pol['usingdapp'] == 1) and ($request == "home"))
			return 'class="active bga1"';
		else
			return '';
	}


 ?>	