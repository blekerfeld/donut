<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: TerminalView.class.php

class pTerminalView extends pSimpleView{

	public function renderAll(){
		/// Just as simple as that :)

		pTemplate::setNoBorder();

		if(isset(pRegister::arg()['ajax']))
			die((new pTerminal)->ajax());

		p::Out('
		<div class="pentry fake-browser-ui">');
		(new pTerminal)->initialState();
		p::Out('</div>');
	}

}