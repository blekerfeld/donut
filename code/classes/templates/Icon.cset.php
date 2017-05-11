<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: icon.cset.php

class pIcon extends pTemplatePiece{

	private $_icon, $_size, $_classes;

	public function __construct($icon, $size = 'inherit', $classes = ''){
		$this->_icon = $icon;
		$this->_size = $size;
		$this->_classes = $classes;
	}

	public function __toString(){
		if(p::StartsWith($this->_icon, 'fa-'))
			$icon = "<i class=\"fa ".$this->_icon." ".$this->_classes." fa-".$this->_size."\"></i>";
		else
			$icon = "<i class=\"mdi mdi-".$this->_icon." ".$this->_classes."\" style=\"font-size: ".$this->_size."px\"></i>";

		return $icon;
	}
	

	public function circle($color){
		return "<span class='icon-circle c-".$this->_size." ".$color."'><span>".$this."</span></span>";
	}
}