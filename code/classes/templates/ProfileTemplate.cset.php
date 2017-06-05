
<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: HomeTemplate.cset.php

class pProfileTemplate extends pSimpleTemplate{

	protected $_user;

	public function __construct(){
		if(!isset(pAdress::arg()['id']))
			die();
		$this->_user = new pUser(pAdress::arg()['id']);
	}

	public function renderAll(){
		p::Out("<div class='rulesheet'><div class='left-20' >
				<img class='userAvatar' src='".p::Url($this->_user->read('avatar'))."' />
			<div class='markdown-body'>
				<h3>".(($this->_user->read('longname') != '') ? $this->_user->read('longname') : $this->_user->read('username'))."</h3>
			</div>
			</div>
			<div class='right-80'></div></div>");

		unset($this->_user);
	}

}