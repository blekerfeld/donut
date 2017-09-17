<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      admin.structure.class.php

class pSimpleStructure extends pStructure{
	
	private $_ajax, $_section, $_template;

	public function compile(){
		if(isset(pRegister::arg()['section']))
			$this->_section = pRegister::arg()['section'];
		else
			$this->_section = $this->_default_section;

		if(isset($this->_structure['template']))
			$this->_template = new $this->_structure['template'];
		else
			die(pDispatcher::abortError('Could not compile the simple structure, requires a template!')->dispatch()->render());

		pMainTemplate::setTitle('');

	}

	public function render(){

		// Since no parser is used, the permission check needs to be done here
		if(!pUser::checkPermission($this->_dispatchStructure['permission']))
			return p::Out("<div class='btCard minimal admin'>".pMainTemplate::NoticeBox('fa-info-circle fa-12', DA_PERMISSION_ERROR, 'danger-notice')."</div>");
			
		// Call the render function of the simple template
		return $this->_template->renderAll();
			

	}

}