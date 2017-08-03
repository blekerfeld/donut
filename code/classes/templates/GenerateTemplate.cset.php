<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: TerminalTemplate.cset.php

// reference the Dompdf namespace


class pGenerateTemplate extends pSimpleTemplate{

	public function renderAll(){
		/// Just as simple as that :)

		$dF = new pDictionaryFactory;
		$dF->compile();
		$dF->produce();


		die();

	}

}