<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.cset.php

class pSearchStructure extends pStructure{

	public function render(){

		p::Out("<div class='home-margin hSearchResults-inner'>");
		// If there is an offset, we need to define that
		if(isset(pRegister::arg()['offset']))
			$this->_parser->setOffset(pRegister::arg()['offset']);
		$this->_parser->render();
		p::Out("</div><br />");

		p::Out("<script type='text/javascript'>

			$('.tooltip').tooltipster({animation: 'grow', animationDuration: 150,  distance: 0, contentAsHTML: true, interactive: true, side: 'bottom'});
			</script>");

	}

}