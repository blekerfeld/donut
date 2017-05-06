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
		(new pTerminal)->initialState();
	}

}