<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: ExampleTemplate.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pLoginTemplate extends pTemplate{

	public function loginForm(){
		$output = '';
		$output .= "<div class='btCard admin' style='width: 400px;'>
		<div class='btTitle'>".(new pIcon('fa-lock', 12))." ".LOGIN_TITLE."</div>
		<div class='ajaxChecking'></div>
		<form id='loginForm'>
		".pMainTemplate::NoticeBox('fa-spinner fa-spin fa-12', LOGIN_CHECKING, 'notice saving')."
		<div class='btSource'><span class='btLanguage'>".LOGIN_USERNAME."</span><br />
			<span class='btNative'><input class='btInput nWord small normal-font username' /></span></div>
		<div class='btSource'><span class='btLanguage'>".LOGIN_PASSWORD."</span><br />
			<span class='btNative'><input class='btInput nWord small normal-font password' type='password'/></span></div>
		<div class='btButtonBar'>
			<a class='btAction green login-button not-smooth'>".(new pIcon('fa-sign-in', 12))." ".LOGIN_TITLE_SHORT."</a><br id='cl' />
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
					$('.ajaxChecking').load('".p::Url("?auth/login/ajax")."', {
						'username': $('.username').val(),
						'password': $('.password').val(),
					});
				});
			</script>";
		return $output;
	}

	public function succes(){
		return pMainTemplate::NoticeBox('fa-spinner fa-spin fa-12', LOGIN_SUCCESS, 'succes-notice')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}

	public function errorMessage(){
		return pMainTemplate::NoticeBox('fa-warning fa-12', LOGIN_ERROR, 'danger-notice ajaxMessage')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}

	public function warning(){
		return pMainTemplate::NoticeBox('fa-info-circle fa-12', SAVED_EMPTY, 'warning-notice ajaxMessage')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}




}