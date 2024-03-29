<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: admin.structure.class.php

class pSearchStructure extends pStructure{

	public function render(){

		// If there is an offset, we need to define that
		if(isset(pRegister::arg()['offset']))
			$this->_parser->setOffset(pRegister::arg()['offset']);
		$this->_parser->render();

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