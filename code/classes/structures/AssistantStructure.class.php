<?php

// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: AssistantStructure.class.php

class pAssistantStructure extends pStructure{
	
	protected $_error = null;

	public function compile(){
		// If the user requests a section and if it extist
		if(isset(pRegister::arg()['section']) AND array_key_exists(pRegister::arg()['section'], $this->_structure))
			$this->_section = pRegister::arg()['section'];
		else{

			// Don't show an error if we are forced to have the search load a search action
			if(!(isset(pRegister::arg()['query'], pRegister::arg()['dictionary'])))
				$this->_error = pMainTemplate::NoticeBox('fa-info-circle fa-12', DA_SECTION_ERROR, 'danger-notice');

			$this->_section = $this->_default_section;
		}


		$this->_parser = new pParser($this->_structure, $this->_structure[$this->_section], $this->_app, $this->_permission);
		;

		$this->_parser->compile();

		pMainTemplate::setTitle($this->_page_title);
	}


	public function render(){



		// Starting with the wrapper
		if(!isset(pRegister::arg()['ajax']) AND !isset(pRegister::arg()['ajaxLoad'])){
	
			$searchBox = new pSearchBox;
			pMainTemplate::throwOutsidePage($searchBox);

			p::Out("<div class='pEntry ".(($this->_error != '' OR $this->_error != null) ? 'hasErrors' : '')."'><div class='home-margin'>");
		}
	

		// If there is an offset, we need to define that
		if(isset(pRegister::arg()['offset']))
			$this->_parser->setOffset(pRegister::arg()['offset']);

		ajaxSkipOutput:

		if(isset(pRegister::arg()['id']) AND $this->_section != 'stats'){
			if(!($this->_parser->runData(is_numeric(pRegister::arg()['id']) ?  pRegister::arg()['id'] : p::HashId(pRegister::arg()['id'], true)[0]))){
				if(!(isset(pRegister::arg()['query'], pRegister::arg()['dictionary'])))
					p::Out(pMainTemplate::NoticeBox('fa-info-circle fa-12', ENTRY_NOT_FOUND, 'danger-notice'));
				goto SkipError;
			}
		}
		elseif($this->_section != 'stats')
			$this->_parser->runData();

		// Let's handle the action by the object
		if(isset(pRegister::arg()['action']) AND @pRegister::arg()['section'] != 'search')
			$this->_parser->passOnAction(pRegister::arg()['action']);
		else
			$this->_parser->render();

		if(isset(pRegister::arg()['ajax']) OR isset(pRegister::arg()['ajaxLoad']))
			return true;


		SkipError:

		// Ending content
		if(!isset(pRegister::arg()['ajax']) AND !isset(pRegister::arg()['ajaxLoad'])){
			p::Out("</div></div>");
		} 

		// Tooltipster time!
		p::Out("<script type='text/javascript'>

			$('.ttip').tooltipster({animation: 'grow'});

			</script>");

	}
}