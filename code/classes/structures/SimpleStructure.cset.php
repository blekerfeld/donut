<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.cset.php

class pSimpleStructure extends pStructure{
	
	private $_ajax, $_section, $_template;

	public function compile(){
		if(isset(pRegister::arg()['section']))
			$this->_section = pRegister::arg()['section'];
		else
			$this->_section = $this->_default_section;

		if(isset($this->_structure['template']))
			$this->_template = new $this->_structure['template'];
		else
			die('Could not compile the simple structure, requires a template!');

		pMainTemplate::setTitle($this->_page_title);

	}

	public function render(){

		// Call the render function of the simple template
		$this->_template->renderAll();

	}

}