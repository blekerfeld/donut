<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: admin.structure.class.php

class pDocsStructure extends pStructure{
	
	private $_ajax, $_section, $_view;

	public function compile(){
		if(isset(pRegister::arg()['section']))
			$this->_section = pRegister::arg()['section'];
		else
			$this->_section = $this->_default_section;

		pTemplate::setTitle($this->_page_title);
	}


	public function render(){

		// Since no parser is used, the permission check needs to be done here
		if(!(new pUser)->checkPermission($this->_permission))
			return p::Out("<div class='btCard minimal admin'>".pTemplate::NoticeBox('fa-info-circle fa-12', DA_PERMISSION_ERROR, 'danger-notice')."</div>");

	}

}