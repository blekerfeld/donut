<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      ExampleTemplate.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pLoginTemplate extends pTemplate{

	public function loginForm(){
		$output = '';
		$output .= "<div class='btCard admin' style='width: 400px;'>
		<div class='btTitle'>".(new pIcon('fa-lock', 12))." ".LOGIN_TITLE."</div>
		<div class='ajaxChecking'></div>
		<form id='loginForm'>
		<div class='saving hide'>".pMainTemplate::loadDots()."</div>
		<div class='btSource'><span class='btLanguage'>".LOGIN_USERNAME."</span><br />
			<span class='btNative'><input class='btInput nWord small normal-font username' /></span></div>
		<div class='btSource'><span class='btLanguage'>".LOGIN_PASSWORD."</span><br />
			<span class='btNative'><input class='btInput nWord small normal-font password' type='password'/></span></div>
		<div class='btButtonBar'>
			<a class='btAction blue login-button not-smooth'>".(new pIcon('fa-sign-in', 12))." ".LOGIN_TITLE_SHORT."</a><br id='cl' />
		</div>
		</form>
		</div>
		";

		$output.= "<script type='text/javascript'>
		$('.saving').hide();
		$(window).keydown(function(e) {
			    		switch (e.keyCode) {
			       		 case 13:
			       		 	if($('.password').val() != ''){
			       				 $('.login-button').click();
			       		 	}
			   			 }
			   		 return; 
					});
				$('.login-button').click(function(){
					$('.saving').slideDown();
					$('.ajaxMessage').slideUp();
					$('.ajaxChecking').delay(1000).load('".p::Url("?auth/login/ajax")."', {
						'username': $('.username').val(),
						'password': $('.password').val(),
					});
				});
			</script>";
		return $output;
	}

	public function succes(){
		return ;
	}

	public function errorMessage(){
		return pMainTemplate::NoticeBox('fa-warning fa-12', LOGIN_ERROR, 'warning-notice ajaxMessage')."<script type='text/javascript'>$('.saving').delay(1000).slideUp();$('.ajaxMessage').delay(2000).slideDown();</script>";
	}

	public function warning(){
		return pMainTemplate::NoticeBox('fa-info-circle fa-12', SAVED_EMPTY, 'warning-notice ajaxMessage')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}




}