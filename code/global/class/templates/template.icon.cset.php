<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: template.icon.cset.php

class pIcon{

	private $_icon, $_size, $_classes;

	public function __construct($icon, $size, $classes = ''){
		$this->_icon = $icon;
		$this->_size = $size;
		$this->_classes = $classes;
	}

	public function __toString(){
		if(pStartsWith($this->_icon, 'fa-'))
			$icon = "<i class=\"fa ".$this->_icon." ".$this->_classes." fa-".$this->_size."\"></i>";
		else
			$icon = "<i class=\"material-icons ".$this->_classes."\" style=\"font-size: ".$this->_size."px\">".$this->_icon."</i>";

		return $icon;
	}
	

	public function circle(){
		return "<span class='icon-circle c-".$this->_size."'><span>".$this."</span></span>";
	}
}