<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: actions.cset.php


class pAction{

	public $_surface, $_icon, $_class, $_app, $_section;

	public $name, $followUpFields, $followUp;

	private function actionUrl($id, $ajax = false){
		return pUrl('?'.$this->_app.'&section='.$this->_section.'&action='.$this->name.(($id != -1) ? '&id='.$id : '').(($ajax != 0) ? '&ajax': ''));
	}

	public function __construct($name, $surface, $icon, $class, $follow_up, $follow_up_fields, $section, $app = 'dictionary-admin'){
		$this->name = $name;
		$this->_surface = $surface;
		$this->_icon = $icon;
		$this->_class = $class;
		$this->_app = $app;
		$this->_section = $section;
		$this->followUp = $follow_up;
		$this->followUpFields = $follow_up_fields;
	}

	public function render($id = -1, $linked = null){

		// Remove-actions need to be done a little different
		if($this->name == 'remove' OR $this->name == 'remove-link')
			return '<span class="delete_load_'.$id.'"></span>
			<a class="'.$this->_class." link_".$this->name.' red-link" href="javascript:void(0);" onClick="
					if (confirm(\''.DA_DELETE_SURE.'\') == true) {
			    		$(\'.delete_load_'.$id.'\').load(\''.pUrl($this->actionUrl($id, true).($linked != null ? "&linked=".$linked : '')).'\');
			    		$(\'.item_'.$id.'\').slideUp();
					}"><i class="'.$this->_icon.'""></i> '.$this->_surface.'</a>';

		return "<a href='".pUrl($this->actionUrl($id).($linked != null ? "&linked=".$linked : ''))."' class='".$this->_class." link_".$this->name."'><i class='".$this->_icon."'></i> ".$this->_surface."</a>";
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
		$this->output .= "</div><br id='cl' />";
	}

}

class pMagicField{

	private $_field, $_class, $_value, $_select, $_is_null;

	public $name;

	// Type can be: textarea, textarea_md, input, boolean, select
	public function __construct($field, $value = '', $isNull = false){
		$this->_field = $field;
		$this->_value = $value;
		$this->name = $field->name;
		$this->_is_null = $isNull;
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

		if($this->_field->disableOnNull AND $this->_is_null)
			return false;

		pOut("<div class='btSource'><span class='btLanguage'>".$this->_field->surface."</span>");
		// If required show an asterisk
		if($this->_field->required == true)
			pOut("<span class='xsmall' style='color: darkred;opacity: .3;'>*</span>");
		pOut("<br />");
		// input
		switch ($this->_field->type) {
			case 'textarea':
				pOut("<td><textarea name='".$this->_field->name."' class=field_'".$this->_field->name." ".$this->_field->class."'>".$this->_field->_value."</td>");
				break;

			case 'boolean':
				pOut("<span class='btNative'><select name='".$this->name."'' class='field_".$this->name." ".$this->_field->class."'><option value='1' ".($this->value() == 1 ? 'selected' : '').">".DL_ENABLED."</option><option value='0' ".($this->value() == 0 ? 'selected' : '').">".DL_DISABLED."</option></select></span>");
				break;
			
			default:
				pOut("<span class='btNative'><input name='".$this->_field->name."' class='btInput nWord small normal-font field_".$this->name." ".$this->_class."' value='".$this->_value."' /></span>");
				break;
		}
		pOut("</div>");
	}

}

class pMagicActionForm{

	private $_action, $_fields, $_adminobject, $_data, $_name, $_edit, $_magicfields, $_table, $_strings, $_section, $_app;

	public function __construct($name, $table, $fields, $strings, $app, $section, $adminobject){
		$this->_name = $name;
		$this->_fields = $fields;
		$this->_table = $table;
		$this->_app = $app;
		$this->_adminobject = $adminobject;
		$this->_action = $this->_adminobject->getAction($name);
		$this->_edit = ($this->_name == 'edit');
		$this->_section = $section;
		$this->_strings = $strings;
		$this->_magicfields = new pSet;
		if($this->_edit)
			$this->_data = $this->_adminobject->data();
		else
			$this->_data = array();
		$this->_name = ($this->_edit ? 'edit' : 'new');
	}

	public function compile(){
		foreach ($this->_fields->get() as $field) {
			if($this->_edit)
				$this->_magicfields->add(new pMagicField($field, $this->_data[0][$field->name], ($this->_data[0]['id'] == 0)));
			else
				$this->_magicfields->add(new pMagicField($field));
		}
	}

	public function ajax(){

		// Checking if we have any empty required fields.
		$empty_error = 0;
		foreach($this->_fields->get() as $field)
			if($field->required AND $_REQUEST['admin_form_'.$field->name] == '')
				$empty_error++;

		if($empty_error != 0){
			pOut(pNoticeBox('fa-warning fa-12', $this->_strings[3], 'danger-notice ajaxMessage'));
		}
		else{

			// Preparing the values
			$values = array();
			foreach($this->_fields->get() as $field)
				$values[] = $_REQUEST['admin_form_'.$field->name];

			try {

				// Editing
				if($this->_edit){
					$this->_adminobject->dataObject->prepareForUpdate($values);
					$this->_adminobject->dataObject->update();
				}
				//Adding
				else{
					$this->_adminobject->dataObject->prepareForInsert($values);
					$this->_adminobject->dataObject->insert();
				}

				pOut(pNoticeBox('fa-check fa-12', $this->_strings[5].". <a href='".pUrl("?".$this->_app."&section=".$this->_section)."'>".$this->_strings[6]."</a>", 'succes-notice ajaxMessage'));

			} catch (Exception $e) {
				pOut(pNoticeBox('fa-warning fa-12', $this->_strings[4], 'danger-notice ajaxMessage'));
			}

		}
		pOut("<script type='text/javascript'>
				$('.saving').slideUp(function(){
					$('.ajaxMessage').slideDown();
				});
			</script>");

	}

	public function newLinkPrepare($linkObject, $show_parent, $show_child){
		$this->_linkObject = $linkObject->_adminObject;
		$this->_guestObject = $linkObject;
		$this->_show_parent = $show_parent;
		$this->_show_child = $show_child;
	}

	public function newLinkAjax(){


		if(!empty($_REQUEST['admin_form_child']) and is_numeric($_REQUEST['admin_form_child'])){


			// We have to check if the relation already exists
			if($this->_adminobject->dataObject->countAll($this->_guestObject->structure[$this->_section]['incoming_links'][$this->_guestObject->_section]['child'] . " = '" . $this->_adminobject->_matchOnValue . "' AND " . $this->_guestObject->structure[$this->_section]['incoming_links'][$this->_guestObject->_section]['parent'] . " = '" . $_REQUEST['admin_form_child'] . "'")){

				pOut(pNoticeBox('fa-info-circle fa-12', DA_TABLE_RELATION_EXIST.". <a href='".pUrl("?".$this->_app."&section=".$this->_section."&action=link-table&id=".$this->_linkObject->data()[0]['id']."&linked=".$this->_guestObject->_data['section_key'])."'>".$this->_strings[6]."</a>", 'notice ajaxMessage'));

			}
			else{
				// Time to insert
				$this->_adminobject->dataObject->prepareForInsert(array($this->_adminobject->_matchOnValue, $_REQUEST['admin_form_child']));
				$this->_adminobject->dataObject->insert();
				$this->_adminobject->dataObject->getSingleObject(1);
				pOut(pNoticeBox('fa-info-circle fa-12', DA_TABLE_RELATION_ADDED.". <a href='".pUrl("?".$this->_app."&section=".$this->_section."&action=link-table&id=".$this->_linkObject->data()[0]['id']."&linked=".$this->_guestObject->_data['section_key'])."'>".$this->_strings[6]."</a>", 'succes-notice ajaxMessage'));
			}
		}
		else
			pOut(pNoticeBox('fa-warning fa-12', $this->_strings[3], 'danger-notice ajaxMessage'));

		pOut("<script type='text/javascript'>
				$('.saving').slideUp(function(){
					$('.ajaxMessage').slideDown();
				});
			</script>");

	}

	public function newLinkForm(){
		pOut("<div class='btCard admin link-table'>");
		pOut("<div class='btTitle'><i class='fa fa-plus-circle'></i> ".DA_TABLE_NEW_RELATION."<span class='medium'>".$this->_adminobject->_surface."</span></div>");

		pOut(pNoticeBox('fa-spinner fa-spin fa-12', $this->_strings[2], 'notice saving hide'));

		// That is where the ajax magic happens:
		pOut("<div class='ajaxSave'></div>");
		pOut("<div class='btSource'><span class='btLanguage'>".DA_TABLE_LINKS_PARENT."</span><br />
			<span class='btNative'>".$this->_linkObject->data()[0][$this->_show_parent]."</span></div>");

		pOut("<div class='btSource'><span class='btLanguage'>".DA_TABLE_LINKS_CHILD."</span><br /><span class='btNative'><select class='field_child'>");

		foreach($this->_adminobject->_passed_data as $data)
			pOut("<option value='".$data['id']."'>".$data[$this->_show_child]."</option>");

		pOut("</select></span></div>");

		pOut("<div class='btButtonBar'>
			<a class='btAction wikiEdit' href='".pUrl("?".$this->_app."&section=".$this->_section."&action=link-table&id=".$this->_linkObject->data()[0]['id']."&linked=".$this->_guestObject->_data['section_key'])."'><i class='fa fa-12 fa-arrow-left' ></i> ".BACK."</a>
			<a class='btAction green submit-form'><i class='fa fa-12 fa-check-circle'></i> ".$this->_strings[1]."</a><br id='cl'/></div>");
		pOut("</div>");

		pOut("<script type='text/javascript'>
				$('.field_child').select2();
				$('.submit-form').click(function(){
					$('.saving').slideDown();
					$('.ajaxSave').load('".pUrl("?".$this->_app."&section=".$this->_section."&action=new-link&id=".$this->_linkObject->data()[0]['id']."&linked=".$this->_guestObject->_data['section_key'])."&ajax', {admin_form_child: $('.field_child').val()});
				});
			</script>");
	}

	public function form(){
		pOut("<div class='btCard admin'>");
		pOut("<div class='btTitle'>".$this->_strings[0]."</div>");

		pOut(pNoticeBox('fa-spinner fa-spin fa-12', $this->_strings[2], 'notice saving hide'));

		// That is where the ajax magic happens:
		pOut("<div class='ajaxSave'></div>");

		foreach($this->_magicfields->get() as $magicField){
			$magicField->render();
		}
		pOut("<div class='btButtonBar'>
			<a class='btAction wikiEdit' href='".pUrl("?".$this->_app."&section=".$this->_section)."'><i class='fa fa-12 fa-arrow-left' ></i> ".BACK."</a>
			<a class='btAction green submit-form'><i class='fa fa-12 fa-check-circle'></i> ".$this->_strings[1]."</a><br id='cl'/></div>");
		pOut("</div>");
		$loadValues = array();

		foreach ($this->_fields->get() as $field){
			if($this->_edit AND $field->disableOnNull AND $this->_data[0]['id'] == 0)
				$loadValues[] = "'admin_form_".$field->name."': '".$this->_data[0][$field->name]."'";
			else 
				$loadValues[] = "'admin_form_".$field->name."': $('.field_".$field->name."').val()";
		}

		pOut("<script type='text/javascript'>
				$('.btCard select').select2();
				$('.submit-form').click(function(){
					$('.saving').slideDown();
					$('.ajaxSave').load('".pUrl("?".$this->_app."&section=".$this->_section."&action=".$this->_name.(($this->_edit) ? '&id='.$this->_data[0]['id'] : '')."&ajax")."', {
						".implode(", ", $loadValues)."
					});
				});
			</script>");
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