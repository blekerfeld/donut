<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: view.class.php

class pView{

	public $_data, $activeStructure;

	public function __construct($data = null, $activeStructure = null){


		$this->_data = $data;
		$this->activeStructure = $activeStructure;

	}

	public function parseSubEntries($subEntries){
		$output = '';
		foreach($subEntries->get() as $subentry){
			$function = ($subentry->viewFunction);
			if(count($subentry->data()) != 0)
				$output .= $this->$function($subentry->data(), $subentry->icon);
		}
		return $output;
	}

	// For debug purposes only
	public function varDump($data, $icon){
		var_dump($data);
	}

}

class pEntryView extends pView{


}

class pSimpleView extends pView{

	public function renderAll(){
		p::Out("simple view must contain renderAll function");
	}

}

class pLayoutPart{
	
}