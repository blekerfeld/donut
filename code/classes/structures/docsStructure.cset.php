<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.cset.php

class pDocsStructure extends pStructure{
	
	private $_ajax, $_section, $_template;

	public function compile(){
		if(isset(pAdress::arg()['section']))
			$this->_section = pAdress::arg()['section'];
		else
			$this->_section = $this->_default_section;

		pMainTemplate::setTitle($this->_page_title);
	}


	public function render(){

	}

}