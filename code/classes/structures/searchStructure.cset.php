<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.cset.php

class pSearchStructure extends pStructure{
	

	public function compile(){

		// If the user requests a section and if it extist
		if(isset(pAdress::arg()['section']) AND array_key_exists(pAdress::arg()['section'], $this->_structure))
			$this->_section = pAdress::arg()['section'];
		else{

			$this->_error = p::Notice('fa-info-circle fa-12', DA_SECTION_ERROR, 'danger-notice');
			$this->_section = $this->_default_section;
		}


		$this->_parser = new pParser($this->_structure, $this->_structure[$this->_section], $this->_app, $this->_permission);
		;

		$this->_parser->compile();

		pMainTemplate::setTitle($this->_page_title);
	}

	public function render(){

		p::Out("<div class='home-margin no-padding-bottom'>");
		// If there is an offset, we need to define that
		if(isset(pAdress::arg()['offset']))
			$this->_parser->setOffset(pAdress::arg()['offset']);
		$this->_parser->render();
		p::Out("</div>");

		p::Out("<script type='text/javascript'>

			$('.tooltip').tooltipster({animation: 'grow', animationDuration: 150,  distance: 0, contentAsHTML: true, interactive: true, side: 'bottom'});
			</script>");

	}

}