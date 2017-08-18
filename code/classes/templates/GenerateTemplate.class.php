<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      TerminalTemplate.class.php



class pGenerateTemplate extends pSimpleTemplate{

	public function renderAll(){
		/// Just as simple as that :)

		$dF = new pDictionaryFactory('Dictionary', (isset(pRegister::arg()['language']) ? pRegister::arg()['language'] : 1));
		$dF->compile();
		$dF->produce();


		die();

	}

}