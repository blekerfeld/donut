<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: templates.cset.php

class pTemplate{

	public $_data, $activeStructure;

	public function __construct($data = null, $activeStructure = null){

		$this->_data = $data;
		$this->activeStructure = $activeStructure;

	}

	public function parseSubEntries($subEntries){
		$output = '';
		foreach($subEntries->get() as $subentry){
			$function = ($subentry->templateFunction);
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

class pEntryTemplate extends pTemplate{


}
