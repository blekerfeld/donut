<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.cset.php

class pSearchStructure extends pStructure{
	

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

		// Starting with the wrapper
		pOut("<div class='home-margin'>");

		// If there is an offset, we need to define that
		if(isset(pAdress::arg()['offset']))
			$this->_parser->setOffset(pAdress::arg()['offset']);

		ajaxSkipOutput:
		// Let's look for an action, that can not be an id! :D
		if(isset(pAdress::arg()['action'])){

			if(isset(pAdress::arg()['id']) AND !in_array(pAdress::arg()['action'], array('link-table')))
				$this->_parser->runData((is_numeric(pAdress::arg()['id']) ?  pAdress::arg()['id'] : pHashId(pAdress::arg()['id'], true)[0]));

			$this->_parser->action(pAdress::arg()['action'], (boolean)isset(pAdress::arg()['ajax']), ((isset(pAdress::arg()['linked']) ? pAdress::arg()['linked'] : null)));
			if(isset(pAdress::arg()['ajax']))
				return true;
		}
		else{
			if(isset(pAdress::arg()['id']))
				$this->_parser->runData(is_numeric(pAdress::arg()['id']) ?  pAdress::arg()['id'] : pHashId(pAdress::arg()['id'], true)[0]);
			else
				$this->_parser->runData();

			$this->_parser->render();
			if(isset(pAdress::arg()['ajax']))
				return true;
		}

		// Ending content
		pOut("</div>");

		// Tooltipster time!
		pOut("<script type='text/javascript'>

			$('.ttip').tooltipster({animation: 'grow', animationDuration: 100,  distance: 0, contentAsHTML: true, interactive: true, side:'left'});

			$('.ttip_actions').tooltipster({animation: 'grow', animationDuration: 150,  distance: 0, contentAsHTML: true, interactive: true, side: 'bottom', trigger: 'click'});

			$('div.d_admin_header_dropdown span').click(function(){
				$('div.d_admin_header_dropdown').toggleClass('clicked');
			});

			$('.ttip_header').tooltipster({ animation: 'grow', animationDuration: 100, distance: 0, contentAsHTML: true, interactive: true, side: 'bottom', trigger: 'click', functionAfter: function(){
					$('div.d_admin_header_dropdown').removeClass('clicked');
			}});

			</script>");

	}

}