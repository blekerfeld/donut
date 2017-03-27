<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: structure.cset.php

class pAdminStructure{

	private $_name, $_meta, $_type, $_structure, $_app, $_menu, $_menu_content, $_default_section, $_page_title;

	public function __construct($name, $type, $app, $default_section, $page_title){
		$this->_name = $name;
		$this->_type = $type;
		$this->_app = $app;
		$this->_default_section = $default_section;
		$this->_page_title = $page_title;
	}

	public function load(){
		try {
			$this->_structure = require_once pFromRoot("code/structures/".$this->_name.".".$this->_type.".struct.php");
			$this->_meta = $this->_structure['MAGIC_META'];
			$this->_menu = $this->_structure['MAGIC_MENU'];
			unset($this->_structure['MAGIC_META']);
			unset($this->_structure['MAGIC_MENU']);
		} catch (Exception $e) {
			die();
		}
		
	}

	public function compile(){

		global $donut;

		if(isset($_REQUEST['section']) and array_key_exists($_REQUEST['section'], $this->_structure))
			$this->_section = $_REQUEST['section'];
		else
			$this->_section = $this->_default_section;

		$this->_adminParser = new pAdminParser($this->_structure, $this->_structure[$this->_section]);
		;

		$this->_adminParser->compile();

		$donut['page']['title'] = $this->_page_title;
	}


	public function prepareMenu(){
		// We don't accept double items
		foreach($this->_structure as $item)
			if(isset($item['menu']))
				$this->_menu[$item['menu']]['items'][] = $item;
	}

	private function checkActiveMain($name){
		if(isset($this->_menu[$name]['items']))
			foreach($this->_menu[$name]['items'] as $item)
				if(isset($_REQUEST['section']) && $_REQUEST['section'] == $item['section_key'])
					return true;
	}

	private function checkActiveSub($name){

		if(isset($_REQUEST['section']) && $_REQUEST['section'] == $name)
			return true;

	}

	public function renderMenu(){

		// Starting the menu
		pOut("<div class='d_admin_menu'>
				<div class='stack'>");

		foreach($this->_menu as $key => $main){

			pOut("<a href='".(isset($main['section']) ? pUrl("?".$this->_app."&section=".$main['section']) : '')."' class='".(($this->checkActiveMain($key) OR (isset($_REQUEST['section'], $main['section']) AND $_REQUEST['section'] == $main['section'])) ? 'active' : '')." ttip' title='
				<strong>".htmlspecialchars($main['surface'])."</strong>");

			if(isset($main['items']))
				foreach($main['items'] as $item)
					pOut("<a href=\"".pUrl("?".$this->_app."&section=".$item['section_key'])."\" class=\"ttip-sub ".($this->checkActiveSub($item['section_key']) ? 'active' : '')."\"><i class=\"".$item['icon']." fa fa-12\"></i> ". htmlspecialchars($item['surface'])."</a>");

			pOut("'><i class='fa ".$main['icon']."'></i></a>");

		}

		pOut("</div></div>");
	

	}

	public function render(){

		if(isset($_REQUEST['ajax']))
			goto ajax;

		// Preparing the menu
		$this->prepareMenu();

		// Starting with the wrapper
		pOut("<div class='d_admin_wrap'><div class='d_admin'>");

		pOut("<div class='header dictionary home wiki'><div class='title_header'><div class='header-icon'><i class='fa ".$this->_meta['icon']."'></i></div> ".$this->_meta['title']."</div></div>");

		// If there is an offset, we need to define that
		if(isset($_REQUEST['offset']))
			$this->_adminParser->setOffset($_REQUEST['offset']);

		ajax:
		if(isset($_REQUEST['action'])){

			if(isset($_REQUEST['id']) AND !in_array($_REQUEST['action'], array('link-table')))
				$this->_adminParser->runData($_REQUEST['id']);

			$this->_adminParser->action($_REQUEST['action'], (boolean)isset($_REQUEST['ajax']), ((isset($_REQUEST['linked']) ? $_REQUEST['linked'] : null)));
			if(isset($_REQUEST['ajax']))
				return true;
		}
		else{

			$this->_adminParser->runData();
			$this->_adminParser->render();
			if(isset($_REQUEST['ajax']))
				return true;
		}

		// Ending content
		pOut("</div>");

		// Time for the menu
		$this->renderMenu();


		// Tooltipster time!
		pOut("<script>$('.ttip').tooltipster({theme: 'tooltipster-noir', animation: 'grow', distance: 0, contentAsHTML: true, interactive: true});
			$('.ttip_actions').tooltipster({theme: 'tooltipster-noir', animation: 'grow', distance: 0, contentAsHTML: true, interactive: true, side: 'bottom'});</script>");

		// Ending overall 
		pOut("</div>");

	}

}