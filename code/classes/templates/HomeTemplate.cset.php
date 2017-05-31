
<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: HomeTemplate.cset.php

class pHomeTemplate extends pSimpleTemplate{

	public function renderAll(){
		// The home search box! Only if needed!
		if(!(isset(pAdress::arg()['ajax']) and isset(pAdress::arg()['nosearch'])))
			p::Out(new pSearchBox(true));

		p::Out("<div class='home-margin pEntry'><div style='margin: 0 auto;width:80%'>".p::Markdown(file_get_contents(p::FromRoot("static/home.md")), true)."</div></div><br />");
	}

}