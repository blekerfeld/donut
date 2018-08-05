<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: actions.class.php


class pAction{

	public $_surface, $_icon, $_class, $_app, $_section, $_override = null, $_permission;

	public $name, $followUpFields, $followUp;

	private function actionUrl($id, $ajax = false, $linked = false){

		if($this->_override != null)
			return $this->_override;
		return p::Url('?'.$this->_app.'/'
			.$this->_section.'/'.
			$this->name.(($id != -1) ? '/'.
			$id : '').($linked != false ? '/'.$linked.'/' : '').(($ajax != 0) ? '/ajax': '').
		(isset(pRegister::arg()['offset']) ? "/position/".pRegister::arg()['offset'] : ""));
	}

	public function setOverride($override){
		$this->_override = $override;
	}

	public function __construct($name, $surface, $icon, $class, $follow_up, $follow_up_fields, $section, $app = 'dictionary-admin', $override = null, $permission = 0){
		$this->name = $name;
		$this->_surface = $surface;
		$this->_icon = $icon;
		$this->_class = $class;
		$this->_app = $app;
		$this->_section = $section;
		$this->followUp = $follow_up;
		$this->followUpFields = $follow_up_fields;
		$this->_override = $override;
		$this->_permission = $permission;
	}

	public function render($id = -1, $linked = null){

		if(!pUser::checkPermission($this->_permission))
			return false;

		// Remove-actions need to be done a little different
		if($this->name == 'remove' OR $this->name == 'remove-link')
			return '<span class="delete_load_'.$id.'"></span>
			<a class="'.$this->_class." link_".$this->name.' red-link" href="javascript:void(0);" onClick="
					if(confirm(\''.htmlspecialchars(DA_DELETE_SURE).'\') == true) {
			    		$(\'.delete_load_'.$id.'\').load(\''.p::Url($this->actionUrl($id, true).($linked != null ? "/".$linked : '')).'\');
			    		$(\'.item_'.$id.'\').slideUp(function(){
			    				window.location = window.location;
			    		});
					}">'.(new pIcon($this->_icon, 12)).' '.$this->_surface.'</a>';

		return "<a href='".p::Url($this->actionUrl($id, false, ($linked != null ? $linked : false)))."' class='".$this->_class." link_".$this->name."'>".(new pIcon($this->_icon, 12))." ".$this->_surface."</a>";
	}

}

class pActionBar{

	private $_set;
	public $output;

	public function __construct($set){
		$this->_set = $set;
	}

	// Allias function
	public function get(){
		return $this->_set->get();
	}

	public function generate($id = -1, $linked = ''){
		$this->output = ("<div class='actionbar'>");
		foreach($this->_set->get() as $action)
			$this->output .= $action->render($id, $linked);
		$this->output .= "</div>";
	}

}


class pFieldBoolean{

	private $_icon;

	public function __construct($value){
		if($value){
			$this->_icon = 'check';
		}
		else{
			$this->_icon = 'ban';
		}
	}

	public function render(){
		return "<div class='boolean-".$this->_icon."'><i class='fa-10 fa fa-".$this->_icon."'></i></div>";
	}

}