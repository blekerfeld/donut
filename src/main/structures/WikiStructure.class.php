<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: structure.class.php

class pWikiStructure extends pStructure{



	public function render(){

		p::Out("<div class='pEntry'>");


		// Disable the word search in favor of an upcoming wiki search! 
		pTemplate::disableSearch();
		pTemplate::setTabbed();
		$this->doTabs();
		
		// If there is an offset, we need to define that
		if(isset(pRegister::arg()['offset']))
			$this->_parser->setOffset(pRegister::arg()['offset']);
			
		// Let's handle the action by the object
		if(isset(pRegister::arg()['action'])){
			if(isset($this->_prototype[$this->_section]['is_admin'])){
				if(isset(pRegister::arg()['id']) AND !in_array(pRegister::arg()['action'], array('link-table')))
					$this->_parser->runData((is_numeric(pRegister::arg()['id']) ?  pRegister::arg()['id'] : p::HashId(pRegister::arg()['id'], true)[0]));
					$this->_parser->action(pRegister::arg()['action'], (boolean)isset(pRegister::arg()['ajax']), ((isset(pRegister::arg()['linked']) ? pRegister::arg()['linked'] : null)));
				if(isset(pRegister::arg()['ajax']))
					return true;
			}else{
				if(isset(pRegister::arg()['id']))
					$this->_parser->runData(pRegister::arg()['id']);
				$this->_parser->passOnAction(pRegister::arg()['action']);
			}
		}
		else{
			$this->_parser->render();
		}


		// Tooltipster time!
		
		p::Out("</div>");

	}

}