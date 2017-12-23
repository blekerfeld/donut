<?php
// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: TerminalView.class.php

class pTerminalView extends pSimpleView{

	public function renderAll(){
		/// Just as simple as that :)

		pTemplate::setNoBorder();

		if(isset(pRegister::arg()['ajax']))
			die((new pTerminal)->ajax());

		p::Out("<br />".((new pTabBar('Terminal','console', true, 'titles reverse'))->addLink('t', 'Command line', 'javascript:void(0);', true)).'
		<div class="fake-browser-ui">
		    <div class="frame">
		    </div>');
		(new pTerminal)->initialState();
		p::Out('</div>');
	}

}