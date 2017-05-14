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

		if(isset(pAdress::arg()['ajax']))
			die((new pTerminal)->ajax());

		(new pTerminal)->initialState();
	
	}

}