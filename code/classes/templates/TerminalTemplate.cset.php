<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: TerminalTemplate.cset.php

class pTerminalTemplate extends pSimpleTemplate{

	public function renderAll(){
		/// Just as simple as that :)

		if(isset(pRegister::arg()['ajax']))
			die((new pTerminal)->ajax());

		p::Out('
		<div class="fake-browser-ui">
		    <div class="frame">
		        <span></span>
		        <span></span>
		        <span></span>
		    </div>');
		(new pTerminal)->initialState();
		p::Out('</div>');
	}

}