
<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      HomeTemplate.class.php

class pHomeTemplate extends pSimpleTemplate{

	public function renderAll(){
		// The home search box! Only if needed!
		if(!(isset(pRegister::arg()['nosearch'])))
			pMainTemplate::throwOutsidePage(new pSearchBox(true));

		p::Out("<div class='home-margin pEntry'>".p::Markdown(file_get_contents(p::FromRoot("static/home.md")), true)."</div>");
	}

}