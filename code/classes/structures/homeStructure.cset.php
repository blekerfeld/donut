<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.cset.php

class pHomeStructure extends pStructure{
	
	private $_ajax, $_section, $_template;

	public function compile(){

		global $donut;

		if(isset(pAdress::arg()['section']))
			$this->_section = pAdress::arg()['section'];
		else
			$this->_section = $this->_default_section;

		$this->_template = new pLoginTemplate();

		$donut['page']['title'] = $this->_page_title;

	}

	public function render(){

		// The home search box! Only if needed!
		if(!(isset(pAdress::arg()['ajax']) and isset(pAdress::arg()['nosearch'])))
			pOut(new pSearchBox(true));

		pOut("<br/ ><div class='home-margin pEntry'>".pMarkdown(file_get_contents(pFromRoot("static/home.md")), true)."</div><br />");

	}

}