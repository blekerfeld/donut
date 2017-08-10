<?php

// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: TerminalTemplate.class.php



class pGenerateTemplate extends pSimpleTemplate{

	public function renderAll(){
		/// Just as simple as that :)

		$dF = new pDictionaryFactory('Dictionary', (isset(pRegister::arg()['language']) ? pRegister::arg()['language'] : 1));
		$dF->compile();
		$dF->produce();


		die();

	}

}