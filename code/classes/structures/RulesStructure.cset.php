<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.cset.php

class pRulesStructure extends pStructure{
	
	private $_ajax, $_section, $_template, $_ruleSets;

	public function compile(){
		if(isset(pRegister::arg()['section']))
			$this->_section = pRegister::arg()['section'];
		else
			$this->_section = $this->_default_section;

		pMainTemplate::setTitle($this->_page_title);

		$this->_parser = new pParser($this->_structure, $this->_structure[$this->_section], $this->_app, $this->_permission);
		;
		$this->_parser->compile();

	}

	public function render(){

		// If logged, show tabs
		if(!isset(pRegister::arg()['ajax']) AND !isset(pRegister::arg()['ajaxLoad']))
			p::Out("
				<div class='card-tabs-bar titles'>
				<a class='ssignore disabled no-select' href='javascript:void(0);'>".(new pIcon('dna', 14))." Grammar    </a>
				<a class='ssignore ".((!isset(pRegister::arg()['action']) OR pRegister::arg()['action'] == 'view') ? 'active' : '')."' href='".p::Url("?rules")."'>".(new pIcon('folder-multiple', 14))." ".RS_BROWSER."</a>
				<a class='ssignore' href='".p::Url("?rules")."'>".(new pIcon('view-list', 14))." ".RS_BY_TABLE."</a>
			</div><br /><div class='rulesheet-margin'>");
		
		$this->_parser->render();

		if(!isset(pRegister::arg()['ajax']) AND !isset(pRegister::arg()['ajaxLoad']))
			p::Out("</div>");

	}

}