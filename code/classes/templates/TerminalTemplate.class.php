<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      TerminalTemplate.class.php

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