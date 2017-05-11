<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under MIT
	File: menu.functions.php
*/


	function pMenu()
	{
  			return '<a onClick="" href="'.pUrl('?home').'" '.pActiveMenu('home').' '.pActiveMenu('search').'  '.pActiveMenu('dictionary-admin').' '.pActiveMenu('lemma').' '.pActiveMenu('discuss-lemma').'  '.pActiveMenu('edit-lemma').' '.pActiveMenu('alphabet').' '.pActiveMenu('generate').'><i class="fa fa-book fa-8 fa-ccc"></i> 
				'.MMENU_DICTIONARY.'</a> 
 

				<a href="'.pUrl('?wiki').'" '.pActiveMenu('wiki').'><i class="fa fa-map-signs fa-ccc fa-8"></i> 
				'.MMENU_WIKI.'</a> 

				<a href="'.pUrl('?phonology').'" '.pActiveMenu('phonology').'><i class="fa fa-headphones fa-8"></i> 
				'.MMENU_PHONOLOGY.'</a>';


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
		if(pUser::noGuest())
			return MMENU_LOGGEDIN.(pUser::read('longname') != '' ? pUser::read('longname') : pUser::read('username'))." (<a href='".pUrl('?auth/logout')."'>".MMENU_LOGOUT."</a>) – <em>".MMENU_EDITORLANG."<span class='editorlangname'>".(new pLanguage(pUser::read('editor_lang')))->read('name')."</span> (<a href='".pUrl('?editorlanguage')."'>".MMENU_EDITORLANGCHANGE."</a>)</em>";
	}