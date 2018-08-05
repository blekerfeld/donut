<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: admin.structure.class.php

class pSearchStructure extends pStructure{

	public function render(){

		p::Out("<div class='hSearchResults-inner'>");
		// If there is an offset, we need to define that
		if(isset(pRegister::arg()['offset']))
			$this->_parser->setOffset(pRegister::arg()['offset']);
		$this->_parser->render();
		p::Out("</div><br />");

		p::Out("<script type='text/javascript'>
		Split(['#split-1', '#split-2'], {
			sizes: [25, 75], minSize: [350, 200] });
			$(document).ready(function(){
				Split(['#split-1', '#split-2'], {
					sizes: [25, 75], minSize: [350, 200] });
			}

			$('.tooltip').tooltipster({animation: 'grow', animationDuration: 150,  distance: 0, contentAsHTML: true, interactive: true, side: 'bottom'});
			</script>");
	}

}