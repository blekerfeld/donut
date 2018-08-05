<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: ActivationMail.class.php

class pActivationMail extends pMailMessage{

	protected function subject($user){
		return MAIL_ACTIVATE_ACCOUNT;
	}

	protected function content($user){
		return $this->wrap(sprintf(MAIL_ACTIVATE_ACCOUNT_MSG, $user['username'], $this->generateToken($user), CONFIG_LOGO_TITLE));
	}

	protected function generateToken($user){
		$expire = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). ' + 3 days'));
		$token = substr(sha1($user['email']), 0, 4) . substr(sha1($expire), 0, 2) . p::HashId($user['id']) . ':' . $user['id']; 
		(new pDataModel('user_activation'))->prepareForInsert(array($user['id'], $expire, $token, $_SERVER['REMOTE_ADDR']))->insert();
		return "<a href='".p::Url('?auth/activate/token/'.$token)."'>".p::Url('?auth/activate/token/'.$token)."</a>";
	}

}