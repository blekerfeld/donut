<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: actions.cset.php

class pAction{

	private $_name, $_surface, $_icon, $_class, $_app, $_section;

	private function actionUrl($id){
		return pUrl('?'.$this->_app.'&section='.$this->_section.'&action='.$this->_name.(($id != 0) ? '&id='.$id : ''));
	}

	public function __construct($name, $surface, $icon, $class, $section, $app = 'dictionary-admin'){
		$this->_name = $name;
		$this->_surface = $surface;
		$this->_icon = $icon;
		$this->_class = $class;
		$this->_app = $app;
		$this->_section = $section;
	}

	public function render($id = 0){
		return "<a href='".pUrl($this->actionUrl($id))."' class='".$this->_class." link_".$this->_name."'><i class='".$this->_icon."'></i> ".$this->_surface."</a>";
	}

}

class pActionBar{

	private $_set;
	public $output;

	public function __construct($set){
		$this->_set = $set;
	}

	public function generate(){
		$this->output = ("<div class='actionbar'>");
		foreach($this->_set->get() as $action)
			$this->output .= $action->render();
		$this->output .= "</div>";
	}

}

class pMagicField{

	private $_surface, $_class, $_value, $_type, $_selection_values, $_select;
	public $name;

	// Type can be: textarea, textarea_md, input, boolean, select
	public function __construct($type, $class, $name, $surface, $value, $selection_values = null){
		$this->_type = $type;
		$this->_class = $class;
		$this->name = $name;
		$this->_surface = $surface;
		$this->_value = $value;
		$this->_selection_values = $selection_values;
	}

	public function value($set = false){
		if($set != false)
			return $this->_value = $set;
		else
			return $this->_value;
	}
	
	private function prepareSelect(){

	}

	public function render(){
		pOut("<tr><td class='adminlabel'>".$this->_surface.": </td>");
		// input
		switch ($this->_type) {
			case 'textarea':
				pOut("<td><textarea name=".$this->name." class='".$this->name." ".$this->class."'>".$this->_value."</td>");
				break;

			case 'boolean':
				pOut("<td><select name=".$this->name." class='".$this->name." ".$this->class."'><option value='0' ".($this->value() == 0 ? 'selected' : '').">".DL_DISABLED."</option><option value='1' ".($this->value() == 1 ? 'selected' : '').">".DL_ENABLED."</option></select></td>");
				break;
			
			default:
				pOut("<td><input name=".$this->name." class='".$this->name." ".$this->class."' value='".$this->_value."' /></td>");
				break;
		}
		pOut("</tr>");
	}

}

class pMagicActionForm{

	private $_fields, $_magicfields, $_name, $_table;

	public function __construct($fields, $ajax, $edit = false){
		$this->_fields = $fields;
		$this->_magicfields = new Set;
	}

	public function compile(){
		foreach ($this->_fields as $field) {
			$this->_magicfields->add(new MagicField('input', '', $field->name, $field->surface, ''));
		}
	}

	public function do(){

	}

	public function form(){
		pOut();
		foreach($this->_magicfields as $magicField){
			$magicField->render();
		}
	}
}