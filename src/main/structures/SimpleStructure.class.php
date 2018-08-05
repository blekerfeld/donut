<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: admin.structure.class.php

class pSimpleStructure extends pStructure{
	
	private $_ajax, $_section, $_view;

	public function compile(){
		if(isset(pRegister::arg()['section']))
			$this->_section = pRegister::arg()['section'];
		else
			$this->_section = $this->_default_section;

		if(isset($this->_prototype['view']))
			$this->_view = new $this->_prototype['view'];
		else
			die(pDispatcher::abortError('Could not compile the simple structure, requires a view!')->dispatch()->render());

		pTemplate::setTitle($this->_page_title);

	}

	public function render(){

		// Since no parser is used, the permission check needs to be done here
		if(!pUser::checkPermission($this->_dispatchStructure['permission']))
			return p::Out("<div class='btCard minimal admin'>".pTemplate::NoticeBox('fa-info-circle fa-12', DA_PERMISSION_ERROR, 'danger-notice')."</div>");
			
		// Call the render function of the simple view

		if(isset($this->_dispatchStructure['view_function'])){
			$funcName = $this->_dispatchStructure['view_function'];
			return $this->_view->$funcName();
		}

		return $this->_view->renderAll();
			

	}

}