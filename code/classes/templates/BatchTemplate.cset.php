<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: BatchTemplate.cset.php


class pBatchTemplate extends pTemplate{

	public function renderChooser($id, $text, $values, $name){
		p::Out("<div class='btCard chooser'><div class='btTitle'>$text</div><div class='btSource'>
			<select class='btInput btChooser'>");
		foreach($values as $value)
			p::Out("<option value='".$value->read('id')."'>".$value->read('name')."</option>");
		p::Out("</select></div><br />
			<div class='btButtonBar'>
				<a class='btAction green chooser-continue'>Continue</a>
			</div>
			</div>");

		
	}

	public function render($section, $chooser){

		p::Out("<div class='dots hide'>".pMainTemplate::loadDots()."</div><div class='btLoad hide'></div>");

		// If the session-chooser is already set we just load the first cards
		if(!isset($_SESSION['btChooser-'.$section]))
			$this->renderChooser($chooser[0], $chooser[1], $chooser[2], $chooser[3]);

		$hashKey = spl_object_hash($this);
		// Throwing this object's script into a session
		pRegister::session($hashKey, $this->script($section));
		p::Out("<script type='text/javascript' src='".p::Url('pol://library/assets/js/key.js.php?key='.$hashKey)."'></script>");

	}
	
	public function script($section){
		return "
		$('.chooser-continue').click(function(){
			$('.chooser').slideUp();
			$('.btLoad').load('".p::Url('?batch/'.$section.'/choose/ajax')."', {'btChooser': $('.btChooser').val()}).slideDown();
			//serveCard();
		});
		function serveCard(){
			$('.dots').slideDown();
			$('.btLoad').load('".p::Url('?batch/'.$section.'/serve/ajax')."');
		};
		";
	}

}