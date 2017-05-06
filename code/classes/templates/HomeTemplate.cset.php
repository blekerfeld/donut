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
			pOut(new pSearchBox(true));

		pOut("<br/ ><div class='home-margin pEntry'>".pMarkdown(file_get_contents(pFromRoot("static/home.md")), true)."</div><br />");
	}

}