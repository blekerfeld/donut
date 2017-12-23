<?php
// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: AssistantStructure.class.php

class pAssistantStructure extends pStructure{
	
	public function render(){


		$searchBox = new pSearchBox;
		$searchBox->enablePentry();


		if(isset(pRegister::arg()['is:result'], pRegister::freshSession()['searchQuery']))
			$searchBox->setValue(pRegister::freshSession()['searchQuery']);
	
		pTemplate::throwOutsidePage($searchBox);

		p::Out("<div class='pEntry'>");


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