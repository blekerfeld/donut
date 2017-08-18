
<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      HomeTemplate.class.php

class pProfileTemplate extends pSimpleTemplate{

	protected $_user;

	public function __construct(){
		if(!isset(pRegister::arg()['id']))
			die();
	}

	public function renderAll(){

		try {
			$this->_user = new pUser(pRegister::arg()['id']);
		} catch (Exception $e) {
			pMainTemplate::setTitle(LOGIN_USERNOTFOUND);
			p::Out(pMainTemplate::NoticeBox('fa-warning', LOGIN_USERNOTFOUND, 'danger-notice'));
			return false;
		}


		pMainTemplate::setTitle((($this->_user->read('longname') != '') ? $this->_user->read('longname') . " (".$this->_user->read('username').")" : $this->_user->read('username')));

		p::Out("<div class='rulesheet'><div class='left-20' >
				<img class='userAvatar' src='".p::Url($this->_user->avatar())."' />
			<div class='markdown-body'>
				<h3>".(($this->_user->read('longname') != '') ? $this->_user->read('longname') : $this->_user->read('username'))."</h3>
			</div>
			</div>
			<div class='right-80'></div></div>");

		unset($this->_user);
	}

}