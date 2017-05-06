<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.cset.php

class pRulesheetStructure extends pStructure{
	

	private $_error = null;

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


	public function render(){

		// If there is an offset, we need to define that
		if(isset(pAdress::arg()['offset']))
			$this->_parser->setOffset(pAdress::arg()['offset']);

		if(!isset(pAdress::arg()['ajax']))
			pOut("<div class='rulesheet-header'>".pMarkdown("## ".$this->_structure[$this->_section]['surface'])."</div><br /><div class='rulesheet'>");
			
		// Let's handle the action by the object
		if(isset(pAdress::arg()['action'])){
			if(isset(pAdress::arg()['id']))
				$this->_parser->runData(pAdress::arg()['id']);
			$this->_parser->passOnAction(pAdress::arg()['action']);
		}
		else{
			if(isset(pAdress::arg()['id']))
				$this->_parser->runData(pAdress::arg()['id']);
			else
				$this->_parser->runData();
			$this->_parser->render();
		}

		if(!isset(pAdress::arg()['ajax']))
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