<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: structure.cset.php


interface pIntStructure{
	public function __construct($name, $type, $app, $default_section, $page_title);
	public function load();
	public function compile();
	public function render();
} 

abstract class pStructure{

	public $_name, $_meta, $_type, $_structure, $_menu, $_menu_content, $_default_section, $_page_title, $_app, $_permission = 0;

	public static $permission;

	public function __construct($name, $type, $app, $default_section, $page_title){
		$this->_name = $name;
		$this->_type = $type;
		$this->_app = $app;
		$this->_default_section = $default_section;
		$this->_page_title = $page_title;
	}


	// This function returns a custom permission profile if it exists, other wise the default one
	public function itemPermission($item){
		if(isset($this->_structure[$item]['permission']))
			return $this->_structure[$item]['permission'];
		else
			return self::$permission;
	}

	public function load(){
		try {
			// Loading the sturcture

			if($this->_type != '')
				$this->_structure = require_once pFromRoot("code/structures/".$this->_name.".".$this->_type.".struct.php");
			else
				$this->_structure = require_once pFromRoot("code/structures/".$this->_name.".struct.php");

			$this->_meta = $this->_structure['MAGIC_META'];
			if(isset($this->_structure['MAGIC_MENU']))
				$this->_menu = $this->_structure['MAGIC_MENU'];
			unset($this->_structure['MAGIC_META']);
			unset($this->_structure['MAGIC_MENU']);

			if(isset($this->_meta['default_permission']))
				self::$permission = $this->_meta['default_permission'];
			else
				self::$permission = 0;

		} catch (Exception $e) {
			die();
		}
		
	}

}