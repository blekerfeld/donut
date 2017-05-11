<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++		File: terminal.cset.php
	// Some handy commands for debuging and such

class pTerminal{


	public function __construct(){

	}

	public function initialState(){

		p::Out("<div class='debugConsole'>");
		p::Out((new pIcon('fa-terminal', 12))." donut ɑ.1 - terminal ~".pUser::read('username').":".$_SERVER['REMOTE_ADDR']." <br />")	;
		p::Out("> <input class='debug init' /><div class='debugLoad-init'></div>");
		p::Out("</div>");
		p::Out("<script type='text/javascript'>
				$('.debug.init').focus();
				$('.debugConsole').mouseover(function(){
					$('.debug').focus();
				});
				$('.debug.init').keydown(function(e) {
				    		switch (e.keyCode) {
				       		 case 13:
				       			$('.debugLoad-init').load('".p::Url('?debug/ajax')."', {'line': $(this).val()}, function(){
				       				$('.debug.init').prop('disabled', true);
				       			});
				   			 }
				   		 return; 
						});
			</script>");
	}

	public function nextLine($line, $prompt = ''){
		$id = date('His');

		return "> <input class='debug loaded $id' value='".$prompt."' />
			<div class='load".$id."'></div>
			<script type='text/javascript'>
			$(document).ready(function(){
				$('.debug.$id').focus().blur().val($('.debug.$id').val()).focus();
			});
			$('.debug').keydown(function(e) {
			    		switch (e.keyCode) {
			       		 case 13:
			       			$('.load".$id."').load('".p::Url('?debug/ajax')."', {'line': $(this).val()}, function(){
			       				$('.debug.init').prop('disabled', true);
			       				$('.debug.loaded').addClass('init');
			       				$('.debugLoad').addClass('debugLoad-init');
			       			});
			       			break;
			       		 case 38:
			       		 	$(this).val('".$line."');
			       		 	break;
			   			 }
			   		 return; 
					});
		</script>";
	}

	public function requireArg($arg, $number, $command){
		if(count($arg) < $number){
			echo "$command requires at least $number arguments, ".count($arg)." given. <br />";
			return false;
		}
		return true;
	}

	public function ajax(){
		if(isset(pAdress::post()['line']))
			$line = pAdress::post()['line']; 
		else
			return false;

		$arguments = explode(' ', $line);
		$command = $arguments[0];
		unset($arguments[0]);

		if(method_exists($this, $command) and !(in_array($command, array('__construct', 'ajax', 'nextLine', 'initialState', 'requireArg')))){
			$this->$command($arguments, $line);
		}
		else{
			echo "command '$command' does not exist. <br />";
			echo $this->nextLine($line);
		}

	}

	public function clear($arg, $line){
		echo "<script>window.location = window.location;</script>";
	}

	public function help($arg, $line){
		echo "<strong>Available commands are: </strong><br />";
		foreach(get_class_methods('pTerminal') as $command)
			if(!(in_array($command, array('__construct', 'ajax', 'nextLine', 'initialState', 'requireArg'))))
				echo "- ".$command."<br />";
		echo $this->nextLine($line);
	}

	public function pholotest($explode, $line){
		if(!$this->requireArg($explode, 1, 'pholotest')){
			echo $this->nextLine($line);
			return false;
		}
		if(isset($explode[2]))
			$twolc = new pTwolc(array($explode[1]));
		else
			$twolc = new pTwolc((new pTwolcRules('phonology_contexts'))->toArray());
		$twolc->compile();
		if(isset($explode[2]))
			echo @$twolc->feed($explode[2])->toDebug()."<br />";
		else
			echo @$twolc->feed($explode[1])->toDebug()."<br />";
		echo $this->nextLine($line);
	}

	public function inftest($explode, $line){
		$explode = explode(' ', $line);
		$twolc = new pTwolc((new pTwolcRules('phonology_contexts'))->toArray());
		$twolc->compile();
		echo @$twolc->feed((new pInflection($explode[1]))->inflect($explode[2]))->toDebug()."<br />";
		echo $this->nextLine($line);
	}

	public function describeinf($arg, $line){
		if(!$this->requireArg($arg, 1, 'describeinf')){
			echo $this->nextLine($line);
			return false;
		}
		$inflection = new pInflection($arg[1]);
		echo $inflection->describeRule();
		echo $this->nextLine($line);
	}

	public function insert(){

	}

	public function addword($explode, $line){
		echo "please enter: {native} {lexform} {type_id} {class_id} {tag_id} {ipa}<br />";
		echo $this->nextLine($line, 'insert words ');
	}

}