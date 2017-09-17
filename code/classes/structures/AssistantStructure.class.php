<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      AssistantStructure.class.php

class pAssistantStructure extends pStructure{
	
	public function render(){

		if(!isset(pRegister::arg()['ajax']) AND !isset(pRegister::arg()['ajaxLoad']))
			p::Out('<div class="btCard no-padding bt upperCard">'.((new pTabBar(BATCH_TITLE,'assistant'))).'</div>');
	
		$this->_parser->runData();

		// Let's handle the action by the object
		if(isset(pRegister::arg()['action']))
			$this->_parser->passOnAction(pRegister::arg()['action']);
		else
			$this->_parser->render();

		if(isset(pRegister::arg()['ajax']) OR isset(pRegister::arg()['ajaxLoad']))
			return true;

		// Tooltipster time!
		p::Out("<script type='text/javascript'>

			$('.ttip').tooltipster({animation: 'grow'});

			</script>");

	}
}