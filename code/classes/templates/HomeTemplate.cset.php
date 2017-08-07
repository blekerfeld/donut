
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
		if(!(isset(pRegister::arg()['ajax']) and isset(pRegister::arg()['nosearch'])))
			pMainTemplate::throwOutsidePage(new pSearchBox(true));

		p::Out("<div class='home-margin pEntry'>".p::Markdown(file_get_contents(p::FromRoot("static/home.md")), true)."<br /></div>");
	}

}