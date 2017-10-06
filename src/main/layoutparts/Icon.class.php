<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      icon.class.php

class pIcon extends pLayoutPart{

	private $_icon, $_size, $_sizePX, $_classes;

	public function __construct($icon, $size = 'inherit', $classes = ''){
		$this->_icon = $icon;
		$this->_size = $size;
		if($size != 'inherit')
			$this->_sizePX = $size."px";
		else
			$this->_sizePX = $size;
		$this->_classes = $classes;
	}

	public function __toString(){
		if(p::StartsWith($this->_icon, 'fa-'))
			$icon = "<i class=\"fa ".$this->_icon." ".$this->_classes." fa-".$this->_size."\"></i>";
		else
			$icon = "<i class=\"mdi mdi-".$this->_icon." ".$this->_classes."\" style=\"font-size: ".$this->_sizePX."\"></i>";

		return $icon;
	}
	

	public function circle($color){
		return "<span class='icon-circle c-".$this->_size." ".$color."'><span>".$this."</span></span>";
	}
}