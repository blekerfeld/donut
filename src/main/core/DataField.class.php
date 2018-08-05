<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: data.class.php

class pDataField{

	public $name, $width, $surface, $type, $showInTable, $showInForm, $required, $selectionValues, $class, $disableOnNull;

	static $stack = array();

	public static function addToStack($key, $value){
		self::$stack[$key] = $value;
	}

	public static function stack(){
		return self::$stack;
	}

	public function __construct($name, $surface = '', $width= '20%', $type = '', $showTable = true, $showForm = true, $required = false, $class = '', $disableOnNull = false, $selection_values = null){
		$this->name = $name;
		$this->width = $width;
		$this->type = $type;
		$this->surface = $surface;
		$this->showInTable = $showTable;
		$this->showInForm = $showForm;
		$this->required = $required;
		$this->class = $class;
		$this->disableOnNull = $disableOnNull;
		$this->selectionValues = $selection_values;
	}

	public function parse($value = '', $output = ''){

	
		if($this->type == 'flag')
			$output = "<img class='$this->class flagimage' src='".(trim($value) == '' ? p::Url('pol://library/flags/undef.png') : p::Url('pol://library/flags/'.$value))."' />";
		
		elseif($this->type == 'color')
			$output = "<span style='width: 100px;height:20px;background-color: ".$value."' class='$this->class colorfield'></span>";

		elseif($this->type == 'image')
			$output = "<img class='$this->class' src='".$value."' />";

		elseif($this->type == 'boolean')
			$output = (new pFieldBoolean($value))->render();

		elseif($this->type == 'select'){
			$this->selectionValues->setValue($value);
			$output = $this->selectionValues->renderText();
		}

		elseif($this->type == 'markdown')
			$output = "<span class='".$this->class."'>".p::Markdown($value, false)."</span>";

		else
			$output = "<span class='".$this->class."'>".$value."</span>";

		return $output;
	}

}
