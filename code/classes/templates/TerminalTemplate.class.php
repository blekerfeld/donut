<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      TerminalTemplate.class.php

class pTerminalTemplate extends pSimpleTemplate{

	public function renderAll(){
		/// Just as simple as that :)

		pMainTemplate::setNoBorder();

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