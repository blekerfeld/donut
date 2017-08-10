<?php

// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: TerminalTemplate.class.php

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