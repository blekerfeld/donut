<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.cset.php

class pEntryStructure extends pStructure{
	

	public function compile(){

		global $donut;

		// If the user requests a section and if it extist
		if(isset(pAdress::arg()['section']) AND array_key_exists(pAdress::arg()['section'], $this->_structure))
			$this->_section = pAdress::arg()['section'];
		else{

			$this->_error = pNoticeBox('fa-info-circle fa-12', DA_SECTION_ERROR, 'danger-notice');

			$this->_section = $this->_default_section;
		}


		$this->_parser = new pParser($this->_structure, $this->_structure[$this->_section], $this->_app, $this->_permission);
		;

		$this->_parser->compile();

		$donut['page']['title'] = $this->_page_title;
	}


	public function prepareMenu(){
		// We don't accept double items
		foreach($this->_structure as $item)
			if(isset($item['menu']) && pUser::checkPermission($this->itemPermission($item['section_key'])))
				$this->_menu[$item['menu']]['items'][] = $item;
	}

	private function checkActiveMain($name){
		if(isset($this->_menu[$name]['items']))
			foreach($this->_menu[$name]['items'] as $item)
				if(isset(pAdress::arg()['section']) && pAdress::arg()['section'] == $item['section_key'])
					return true;
	}

	private function checkActiveSub($name){

		if(isset(pAdress::arg()['section']) && pAdress::arg()['section'] == $name)
			return true;

	}

	public function render(){

		pOut(new pSearchBox);

		// Starting with the wrapper
		pOut("<div class='pEntry'><div class='home-margin'>");

		// If there is an offset, we need to define that
		if(isset(pAdress::arg()['offset']))
			$this->_parser->setOffset(pAdress::arg()['offset']);

		ajaxSkipOutput:

		if(isset(pAdress::arg()['id'])){
			if(!($this->_parser->runData(is_numeric(pAdress::arg()['id']) ?  pAdress::arg()['id'] : pHashId(pAdress::arg()['id'], true)[0]))){
				pOut(pNoticeBox('fa-info-circle fa-12', ENTRY_NOT_FOUND, 'danger-notice'));
				goto SkipError;
			}
		}
		else
			$this->_parser->runData();

		// Let's handle the action by the object
		if(isset(pAdress::arg()['action']))
			$this->_parser->passOnAction(pAdress::arg()['action']);
		else
			$this->_parser->render();

		if(isset(pAdress::arg()['ajax']))
			return true;


		if(isset(pAdress::arg()['ajax']))
				return true;

		SkipError:

		// Ending content
		pOut("</div></div>");

		// Tooltipster time!
		pOut("<script type='text/javascript'>

			$('.ttip').tooltipster({animation: 'grow'});

			</script>");

	}
}