<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: forms.class.php

class pMagicField{

	private $_field, $_class, $_value, $_select, $_is_null, $_selector;

	public $name;

	// Type can be: textarea, textarea_md, input, boolean, select, etc.
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
	

	public function render(){

		if($this->_field->disableOnNull AND $this->_is_null)
			return false;

		if($this->_field->type != 'hidden')
			p::Out("<div class='btSource'><span class='btLanguage'>".$this->_field->surface."</span>");

		// If required show an asterisk
		if($this->_field->required == true AND $this->_field->type != 'hidden')
			p::Out("<span class='xsmall' style='color: darkred;opacity: .3;'>*</span>");
		if($this->_field->type != 'hidden')
			p::Out("<br /><span class='btNative'>");

		switch ($this->_field->type) {
			case 'textarea':
				p::Out("<textarea name='".$this->_field->name."' class=field_'".$this->_field->name." ".$this->_field->class."'>".$this->_field->_value."</textarea>");
				break;

			case 'boolean':
				p::Out("<select name='".$this->name."' class='field_".$this->name." ".$this->_field->class."'><option value='1' ".($this->value() == 1 ? 'selected' : '').">".DL_ENABLED."</option><option value='0' ".($this->value() == 0 ? 'selected' : '').">".DL_DISABLED."</option></select><script>$('.field_".$this->name."').selectorTabs();</script>");
				break;

			case 'hidden-show':
				p::Out("<input type='hidden' name='".$this->_field->name."' class='btInput nWord small normal-font field_".$this->name." ".$this->_class."' value='".(($this->_value != NULL OR $this->_value != '') ? $this->_value : $this->_field->selectionValues)."' />".$this->value());
				break;

			case 'hidden':
				p::Out("<input type='hidden' name='".$this->_field->name."' class='btInput nWord small normal-font field_".$this->name." ".$this->_class."' value='".(($this->_value != NULL OR $this->_value != '') ? $this->_value : $this->_field->selectionValues)."' />");
				break; 

			case 'prefix':

				p::Out("<input name='".$this->_field->name."' class='btInput nWord small normal-font field_".$this->name." ".$this->_class."' value='".$this->_value."' />
					<script type='text/javascript'>
					if($('.field_".$this->name."').val() == ''){
						$('.field_".$this->name."').val('".$this->_field->selectionValues."');
					}
					$('.field_".$this->name."').keydown(function(e) {
    					var oldvalue=$(this).val();
    					var field=this;
    					setTimeout(function () {
        					if(field.value.indexOf('".$this->_field->selectionValues."') !== 0) {
            					$(field).val(oldvalue);
        					} 
    					}, 1);
					});</script>");
				break;

			case 'select':
				$this->_field->selectionValues->setValue($this->_value);
				
				// If the field is not required, we need to show an optional item
				$optional = '';
				if($this->_field->required == false)
					$optional = "<option value='' ".($this->value() == '' ? 'selected' : '')."><em>(".DA_OPTIONAL.")</em></option>";
				
				p::Out("<select name='".$this->name."'' class='select2 admin_select field_".$this->name." ".$this->_field->class."'>".$optional.$this->_field->selectionValues->render()."</select></span>");
				break;

			case 'markdown':
				p::Out("<textarea name='".$this->name."'' class='minEditor elastic allowtabs field_".$this->name." ".$this->_field->class."' >".$this->_value."</textarea>");
				break;
			
			default:
				p::Out("<input name='".$this->_field->name."' class='btInput nWord small normal-font field_".$this->name." ".$this->_class."' value='".$this->_value."' />");
				break;
		}
		if($this->_field->type != 'hidden')
			p::Out("</span></div>");
	}

}


class pSelector{

	private $_data = NULL, $_showField = null, $_useTable = false, $_interactive, $_overrideSection, $_condition;

	public $dataModel, $value;

	public function __construct($data, $value = null, $show_field = NULL, $interactive = true, $override_section = null, $condition = NULL){
		
		$this->_data = $data;
		$this->value = $value;
		$this->_interactive = $interactive;
		$this->_overrideSection = $override_section;
		$this->_condition = $condition;

		// In case the table name does not match the linked section, which is mostly! :)
		if($override_section == null && !is_array($data))
			$this->_overrideSection = $data;
		elseif($override_section == null)
			$this->_overrideSection = $override_section;

		// If it is not an array we need to do things with tables
		if(!is_array($data)){

			// There MUST be a field to be shown
			if($show_field == NULL)
				return false;

			$this->_useTable = true;
			$dfs = new pSet;
			$dfs->add(new pDataField($show_field));

			$this->_showField = $show_field; 
			$this->dataModel = new pDataModel($data, $dfs);
		}
	}

	public function setValue($value){
		$this->value = $value;
	}

	// Returns all the <option> elements that are needed
	public function render(){
		if($this->_condition != NULL){
			if(!p::StartsWith($this->_condition, ' WHERE'))
				$this->_condition = " WHERE ".$this->_condition;
			$this->dataModel->setCondition($this->_condition);
		}

		// Fetching the data that we need
		$this->dataModel->getObjects();

		$output = '';

		// If it is not an array we need to do things with tables
		if(is_array($this->_data))
			foreach($this->_data as $value => $name)
				$output .= '<option value="'.$value.'" '.((is_array($this->value) ? (in_array($value, $this->value) ? 'selected' : '') : (($this->value == $value) ? 'selected' : ''))).'>'.$name.'</option>';
		else{
			// Now we have to do things
			foreach($this->dataModel->data()->fetchAll() as $option)
				$output .= '<option value="'.$option['id'].'" '.((is_array($this->value) ? (in_array($option['id'], $this->value) ? 'selected' : '') : (($this->value == $option['id']) ? 'selected' : ''))).'>'.$option[$this->_showField].'</option>';

		}

		return $output;
	}

	public function renderText(){

		$output = '';

		if($this->value == null)
			return false;

		if(is_array($this->_data))
			return $this->_data[$this->value];
		
		// Getting the single item that we need, no more no less

		$key = $this->value."_".$this->_showField."_".$this->_data;

		if($this->_interactive && $this->_useTable){
			$_SESSION['pJSBack'] = true;
			$output .= "<a href='".p::Url("?".pParser::$stApp."/".$this->_overrideSection."/edit/".$this->value)."' class='tooltip'>";
		}

		if(array_key_exists($key, pDataField::stack()))
			$output .= pDataField::stack()[$key];
		else{	
			$this->dataModel->getSingleObject($this->value);
			if($this->dataModel->count() != 0){
				pDataField::addToStack($key, $this->dataModel->data()->fetchAll()[0][$this->_showField]);
				$output .= $this->dataModel->data()->fetchAll()[0][$this->_showField];
			}
			else
				$output .= '';
		}

		if($this->_interactive && $this->_useTable)
			$output .= "</a>";

		return $output;

	}

}


class pMagicActionForm{

	private $_action, $_fields, $_adminobject, $_data, $_name, $_edit, $_magicfields, $_table, $_strings, $_section, $_app, $_extra_fields, $_linked, $dataModel;

	public function __construct($name, $table, $fields, $strings, $app, $section, $object){
		$this->_name = $name;
		$this->_fields = $fields;
		$this->_table = $table;
		$this->_app = $app;
		$this->_handler = $object;
		
		$this->_edit = ($this->_name == 'edit');
		$this->_section = $section;
		$this->_strings = $strings;
		$this->_magicfields = new pSet;
		if($this->_edit){
			// Try the object for data
			if(isset($this->_handler->_data))
				$this->_data = $this->_handler->_data;
			// otherwise try this...
			else
				$this->_data = $this->_handler->dataModel->data()->fetchAll();
		}
		else
			$this->_data = array();
		$this->_name = ($this->_edit ? 'edit' : 'new');
	}

	public function compile(){
		foreach ($this->_fields->get() as $field) {
			if($this->_edit){
				$this->_magicfields->add(new pMagicField($field, $this->_data[0][$field->name], ($this->_data[0]['id'] == 0)));
			}
			else
				$this->_magicfields->add(new pMagicField($field));
		}
	}

	public function ajax($showBack = true){

		$exception = false;

		// Checking if we have any empty required fields.
		$empty_error = 0;
		foreach($this->_fields->get() as $field)
			if($field->required AND pRegister::post()['admin_form_'.$field->name] == '')
				$empty_error++;

		if($empty_error != 0){
			p::Out(pTemplate::NoticeBox('fa-warning fa-12', $this->_strings[3], 'danger-notice ajaxMessage'));
		}
		else{

			// Preparing the values
			$values = array();

			foreach($this->_fields->get() as $field)
				$values[] = pRegister::post()['admin_form_'.$field->name];

			try {

				// Editing
				if($this->_edit){
					$this->_handler->dataModel->prepareForUpdate($values, $this->_data[0]['id'], $this->_fields);
					$this->_handler->dataModel->update();
				}
				//Adding
				else{
					$this->_handler->dataModel->prepareForInsert($values);
					$this->_handler->dataModel->insert();
				}

				p::Out(pTemplate::NoticeBox('fa-check fa-12', $this->_strings[5].".".($showBack ? " <a href='".p::Url("?".$this->_app."/".$this->_section. (isset($_REQUEST['position']) ? "/offset/".$_REQUEST['position'] : ""))."'>".$this->_strings[6]."</a>" : ''), 'succes-notice ajaxMessage'));

				// If this is not a edit action, we need to reload the form
				if(!$this->_edit)
				p::Out("<script type='text/javascript'>
					$('#adminForm').trigger('reset');
				</script>");

			} catch (Exception $e) {
				p::Out(pTemplate::NoticeBox('fa-warning fa-12', $this->_strings[4], 'danger-notice ajaxMessage'));
				$exception = true;
			}

		}
		p::Out("<script type='text/javascript'>
				$('.saving').slideUp(function(){
					$('.ajaxMessage').slideDown();
				});
			</script>");


		// This will redirect to a defined url, if there is one.

		if(isset($this->_handler->_meta['return'][$this->_section]))
			$returnUrl = $this->_handler->_meta['return'][$this->_section];
		elseif(isset($this->_handler->_meta['return']['default']))
			$returnUrl = $this->_handler->_meta['return']['default'];
		else
			return false;

		if($exception == false AND $empty_error == 0)
			return $this->parseUrlRedirect($returnUrl);
		else
			return false;
	}


	protected function parseUrlRedirect($subject){
		$search = array("SECT", "ACT", "ID", "TID");
		$replace = array($this->_section, (isset(pRegister::arg()['action']) ? pRegister::arg()['action'] : ''), (isset(pRegister::arg()['id']) ? pRegister::arg()['id'] : ''), (isset(pRegister::arg()['thread_id']) ? pRegister::arg()['thread_id'] : ''));
		return p::Out("<script type='text/javascript'>window.location = '".p::Url(str_replace($search, $replace, $subject))."';</script>");
	}

	public function form($showBack = true, $forceID = null, $forceBtCard = true, $forceFirstID = null){
		if($forceBtCard == true){
			p::Out("<div class='btCard admin'>");
			p::Out("<div class='btTitle'>".$this->_strings[0]."</div>");
		}
		else{
			p::Out("<div class='miniForm'>");
		}

		p::Out("<div class='saving hide'>".pTemplate::loadDots()."</div>");

		// That is where the ajax magic happens:
		p::Out("<div class='ajaxSave'></div>");

		p::Out("<form id='adminForm'>");

			foreach($this->_magicfields->get() as $magicField){
				$magicField->render();
			}

		p::Out("</form>");

		if(isset($_SESSION['pJSBack']) && $_SESSION['pJSBack'] == true){
			$hrefBack = "javascript:window.history.back();";
			$_SESSION['pJSBack'] = false;
		}
		else
			$hrefBack = p::Url("?".$this->_app."/".$this->_section.(isset($_REQUEST['position']) ? "/offset/".$_REQUEST['position'] : ""));

		if($forceBtCard)
			p::Out("<div class='btButtonBar'>
				".($showBack ? "<a class='btAction no-float' href='".$hrefBack."'><i class='fa fa-12 fa-arrow-left' ></i> ".BACK."</a>" : ""));

		p::Out("
			<a class='btAction green ".($forceBtCard ? '' : 'little ')."submit-form ssignore no-float'><i class='fa fa-12 fa-check-circle'></i> ".$this->_strings[1]."</a>");
		
		if($forceBtCard)
			p::Out("<br id='cl'/></div></div>");
		else
			p::Out("</div>");
	
		$loadValues = array();

		foreach ($this->_fields->get() as $field){
			if($this->_edit AND $field->disableOnNull AND $this->_data[0]['id'] == 0)
				$loadValues[] = "'admin_form_".$field->name."': '".$this->_data[0][$field->name]."'";
			else 
				$loadValues[] = "'admin_form_".$field->name."': $('.field_".$field->name."').val()";
		}

		p::Out("<script type='text/javascript'>
				$('#adminForm .select2').select2();
				$('.submit-form').click(function(){
					$('.saving').slideDown();
					$('.ajaxSave').load('".p::Url("?".$this->_app."/".$this->_section."/".$this->_name.(($this->_edit) ? '/'.(($forceFirstID != null) ? $forceFirstID : $this->_data[0]['id']) : '').(($forceID != null) ? '/'.$forceID : '')."/ajax")."', {
						".implode(", ", $loadValues)."
					});
				});
			</script>");
	}


	public function newLinkPrepare($linked, $linkObject, $show_parent, $show_child, $doExtraFields = false, $fields = null){

		$this->_linked = $linked;
		$this->_linkObject = $linkObject->_handler;
		$this->_guestObject = $linkObject;
		$this->_show_parent = $show_parent;
		$this->_show_child = $show_child;
		if($doExtraFields)
			$this->_extra_fields = $fields;
		else
			$this->_extra_fields = null;
	}

	public function newLinkAjax(){

		$empty_error = 0;

		if(pRegister::post()['admin_form_'.$this->_guestObject->structure[$this->_section]['incoming_links'][$this->_linked]['parent']] == null){
			$empty_error++;
		}

		if(isset($this->_extra_fields))
			foreach ($this->_extra_fields as $field)
				if($field->showInForm && $field->required)
					if(isset(pRegister::post()['admin_form_'.$field->name])AND empty(pRegister::post()['admin_form_'.$field->name]))
						$empty_error++;


		if($empty_error == 0){

			// We have to check if the relation already exists
			if($this->_handler->dataModel->countAll($this->_guestObject->structure[$this->_section]['incoming_links'][$this->_linked]['child'] . " = '" . $this->_handler->_matchOnValue . "' AND " . $this->_guestObject->structure[$this->_section]['incoming_links'][$this->_linked]['parent'] . " = '" . pRegister::post()['admin_form_'.$this->_guestObject->structure[$this->_section]['incoming_links'][$this->_linked]['parent']] . "'")){

				p::Out(pTemplate::NoticeBox('fa-info-circle fa-12', DA_TABLE_RELATION_EXIST.". <a href='".p::Url("?".$this->_app."&".$this->_section."&link-table&id=".$this->_linkObject->_data[0]['id']."&linked=".$this->_linked)."'>".$this->_strings[6]."</a>", 'notice ajaxMessage'));

			}
			else{
				// Time to insert
					// Preparing the values
			
				foreach($this->_fields->get() as $field)
					if($field->name == $this->_guestObject->structure[$this->_section]['incoming_links'][$this->_linked]['child'])
						$values[] = $this->_handler->_matchOnValue;
					else
						$values[] = pRegister::post()['admin_form_'.$field->name];

				$this->_handler->dataModel->prepareForInsert($values);
				$this->_handler->dataModel->insert();
				$this->_handler->dataModel->getSingleObject(1);
				p::Out(pTemplate::NoticeBox('fa-info-circle fa-12', DA_TABLE_RELATION_ADDED.". <a href='".p::Url("?".$this->_app."&".$this->_section."&link-table&id=".$this->_linkObject->data()[0]['id']."&linked=".$this->_linked)."'>".$this->_strings[6]."</a>", 'succes-notice ajaxMessage'));
			}
		}
		else
			p::Out(pTemplate::NoticeBox('fa-warning fa-12', $this->_strings[3], 'danger-notice ajaxMessage'));

		p::Out("<script type='text/javascript'>
				$('.saving').slideUp(function(){
					$('.ajaxMessage').slideDown();
				});
			</script>");

	}

	public function newLinkForm(){

		p::Out("<div class='btCard admin link-table'>");
		p::Out("<div class='btTitle'><i class='fa fa-plus-circle'></i> ".DA_TABLE_NEW_RELATION."<span class='medium'>".$this->_handler->_surface."</span></div>");

		p::Out(pTemplate::NoticeBox('fa-spinner fa-spin fa-12', $this->_strings[2], 'notice saving hide'));

		// That is where the ajax magic happens:
		p::Out("<div class='ajaxSave'></div>");
		p::Out("<div class='btSource'><span class='btLanguage'>".DA_TABLE_LINKS_PARENT."</span><br />
			<span class='btNative'>".$this->_linkObject->_data[0][$this->_show_parent]."</span></div>");

		p::Out("<div class='btSource'><span class='btLanguage'>".DA_TABLE_LINKS_CHILD."</span><br /><span class='btNative'><select class='select2 field_".$this->_guestObject->structure[$this->_section]['incoming_links'][$this->_linked]['parent']."'>");

		p::Out($this->_fields->get()[$this->_guestObject->structure[$this->_section]['incoming_links'][$this->_linked]['child']]->selectionValues->render());

		p::Out("</select></span></div>");


		// Extra fields	
		if($this->_extra_fields != null)
			foreach($this->_extra_fields as $field)
				(new pMagicField($field))->render();

		p::Out("<div class='btButtonBar'>
			<a class='btAction no-float' href='".p::Url("?".$this->_app."/".$this->_section."/link-table/".$this->_linkObject->_data[0]['id']."/".$this->_linked)."'><i class='fa fa-12 fa-arrow-left' ></i> ".BACK."</a>
			<a class='btAction green submit-form not-smooth'><i class='fa fa-12 fa-check-circle'></i> ".$this->_strings[1]."</a><br id='cl'/></div>");
		p::Out("</div>");

		$loadValues = array();

		foreach ($this->_fields->get() as $field){
			if($this->_edit AND $field->disableOnNull AND $this->_data[0]['id'] == 0)
				$loadValues[] = "'admin_form_".$field->name."': '".$this->_data[0][$field->name]."'";
			else 
				$loadValues[] = "'admin_form_".$field->name."': $('.field_".$field->name."').val()";
		}

		p::Out("<script type='text/javascript'>
				".(!(isset($this->_guestObject->structure[$this->_section]['incoming_links']['disable_enter']) AND $this->_guestObject->structure[$this->_section]['incoming_links']['disable_enter'] != true) ? "$(window).keydown(function(e) {
			    		switch (e.keyCode) {
			       		 case 13:
			       			 $('.submit-form').click();
			   			 }
			   		 return; 
					});" : '')."
				$('.field_".$this->_guestObject->structure[$this->_section]['incoming_links'][$this->_linked]['parent']."').select2();
				$('.submit-form').click(function(){
					$('.saving').slideDown();
					$('.ajaxSave').load('".p::Url("?".$this->_app."/".$this->_section."/new-link/".$this->_linkObject->_data[0]['id']."/".$this->_linked)."/ajax', {".implode(", ", $loadValues)."});
				});
			</script>");
	}

}
