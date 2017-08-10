<?php

// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: admin.structure.class.php

class pDocsStructure extends pStructure{
	
	private $_ajax, $_section, $_template;

	public function compile(){
		if(isset(pRegister::arg()['section']))
			$this->_section = pRegister::arg()['section'];
		else
			$this->_section = $this->_default_section;

		pMainTemplate::setTitle($this->_page_title);
	}


	public function render(){

		// Since no parser is used, the permission check needs to be done here
		if(!pUser::checkPermission($this->_permission))
			return p::Out("<div class='btCard minimal admin'>".pMainTemplate::NoticeBox('fa-info-circle fa-12', DA_PERMISSION_ERROR, 'danger-notice')."</div>");

	}

}