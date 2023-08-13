<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: AssistantStructure.class.php

class pAssistantStructure extends pStructure{
	
	public function render(){


		p::Out("<div class='pEntry'>");
		pTemplate::setNoBorder();
		pTemplate::disableSearch();
		$this->_parser->runData();

		// Let's handle the action by the object
		if(isset(pRegister::arg()['action']))
			$this->_parser->passOnAction(pRegister::arg()['action']);
		else
			$this->_parser->render();

		if(isset(pRegister::arg()['ajax']) OR isset(pRegister::arg()['ajaxLoad']))
			return true;

		p::Out("</div>");

		// Tooltipster time!
		p::Out("<script type='text/javascript'>

			$('.ttip').tooltipster({animation: 'grow'});

			</script>");

	}
}