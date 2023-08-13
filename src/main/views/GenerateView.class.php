<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: TerminalView.class.php



class pGenerateView extends pSimpleView{

	public function renderAll(){
		/// Just as simple as that :)

		$dF = new pDictionaryFactory('Dictionary', (isset(pRegister::arg()['language']) ? pRegister::arg()['language'] : 1));
		$dF->compile();
		$dF->produce();


		die();

	}

}