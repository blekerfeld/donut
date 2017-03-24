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
  			return '<a onClick="" href="'.pUrl('?home').'" '.pActiveMenu('home').' '.pActiveMenu('search').' '.pActiveMenu('lemma').' '.pActiveMenu('discuss-lemma').'  '.pActiveMenu('edit-lemma').' '.pActiveMenu('alphabet').' '.pActiveMenu('generate').'><i class="fa fa-book fa-8 fa-ccc"></i> 
				'.MMENU_DICTIONARY.'</a> 
 

				<a href="'.pUrl('?wiki').'" '.pActiveMenu('wiki').'><i class="fa fa-map-signs fa-ccc fa-8"></i> 
				'.MMENU_WIKI.'</a> 

				<a href="'.pUrl('?phonology').'" '.pActiveMenu('phonology').'><i class="fa fa-headphones fa-8"></i> 
				'.MMENU_PHONOLOGY.'</a> 


  			'.((pLogged()) ? '<a id="admin" href="'.pUrl('?admin').'" '.pActiveMenu('admin').' ><i class="fa fa-wrench fa-8"></i> '.MMENU_SETTINGS.'</a>' : '').((pLogged()) ? '<a id="editor" href="'.pUrl('?dashboard').'" '.pActiveMenu('dashboard').' ><i class="fa fa-lightbulb-o  fa-8"></i> Dashboard</a>' : '');


	}

	function pActiveMenu($request)
	{
		global $donut; 

		if(isset($_REQUEST[$request]))
			return 'class="active bga1"';

		return '';
	}

	function pAbsHeader(){
		global $donut; 
		if(pLogged())
			return "<span style='float:right'>".MMENU_LOGGEDIN.($donut['user']->longname != '' ? $donut['user']->longname : $donut['user']->username)." (<a href='".pUrl('?logout')."'>".MMENU_LOGOUT."</a>) – <em class='small'>".MMENU_EDITORLANG."<span class='editorlangname'>".pLanguageName(pEditorLanguage($_SESSION['pUser']))."</span> (<a href='".pUrl('?editorlanguage')."'>".MMENU_EDITORLANGCHANGE."</a>)</em></span><br id='cl' />";
	}