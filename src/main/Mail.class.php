<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: MailMessage.class.php

class pMailMessage{

	protected $_data, $_from, $_to = array(), $_type, $_simpleMail;

	public function __construct($to, $data = array()){
		// Settings from config
		$this->_simpleMail = new SimpleMail();
		$this->_to = $to;
		$this->_data = $data;
		$this->_simpleMail->setFrom(CONFIG_MAIL_FROM, CONFIG_MAIL_FROM_NAME)->setHtml()->setWrap(100);
		
	}

	protected function subject($user){
		return '';
	}

	protected function content($user){
		return '';
	}

	protected function css(){
		return "<style type='text/css'>
			html{
				font-family: Noto Sans, sans-serif;
				font-size: 15px;
				background: #1B2B34;
				color: snow;
			}
			a {
			  text-decoration: none;
			  color: #0366D6;
			  outline: 0;
			   transition: color .3s;
			}
			a:hover{
				color: #315780;
			}
			.topbar a{
				color: snow;
			}
			.topbar a:hover{
				color: #0366d6;
			}
			.topbar{
				background: #1B2B34;
				padding: 20px;
				border-bottom: #3B66D6 3px solid;
			}
			.content{
				padding: 20px;
			}
			.footer{
				opacity: .7;
				font-size: 12px;
				padding: 20px;
				padding-top: 40px;
			}

		</style>";
	}

	protected function wrap($text){
		return "<html><head>".$this->css()."</head><body>
		<div class='topbar'><strong><a href='".CONFIG_ABSOLUTE_PATH."'>[".CONFIG_LOGO_SYMBOL."]  
        ".CONFIG_LOGO_TITLE."</a></strong></div>
        <div class='wrapper'> 
		<div class='content'>
		".$text."</div>
		<div class='footer'>
		".MAIL_UNFOLLOW."<br/ >
		</div></div></body></html>";
	}

	public function send(){

		foreach ($this->_to as $user){
			// Prepare the mail
			$this->_simpleMail->setSubject($this->subject($user))->setMessage($this->content($user))->setTo($user['email'], $user['username']);
			// Send the mail
			if($user['disable_notifications'] == 0)
				$this->_simpleMail->send();
		}

	}


}