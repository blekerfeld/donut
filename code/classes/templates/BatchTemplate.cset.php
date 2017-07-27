<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: BatchTemplate.cset.php


class pBatchTemplate extends pTemplate{

	public function renderChooser($id, $text, $values, $name){
		p::Out("<div class='btCard proper chooser'><div class='btTitle'>$text</div><div class='btSource'>
			<select class='btInput btChooser'>");
		foreach($values as $value)
			p::Out("<option value='".$value->read('id')."'>".$value->read('name')."</option>");
		p::Out("</select></div><br />
			<div class='btButtonBar'>
				<a class='btAction green no-float chooser-continue'>Continue</a>
			</div>
			</div>");

		
	}

	public function render($section, $chooser){

		p::Out("<div class='dotsc hide'>".pMainTemplate::loadDots()."</div><div class='btLoad hide'></div><div class='btLoadSide hide'></div>");

		// If the session-chooser is already set we just load the first cards
		if(!isset($_SESSION['btChooser-'.$section]))
			$this->renderChooser($chooser[0], $chooser[1], $chooser[2], $chooser[3]);
	
		$hashKey = spl_object_hash($this);
		// Throwing this object's script into a session
		@pRegister::session($hashKey, $this->script($section));
		p::Out("<script type='text/javascript' src='".p::Url('pol://library/assets/js/key.js.php?key='.$hashKey)."'></script>");

		if(isset($_SESSION['btChooser-'.$section]))
			p::Out("<script type='text/javascript'>serveCard();</script>");

		$function = "renderBottom" . ucfirst($section);
		if(method_exists($this, $function))
				$this->$function();
	}

	public function renderBottomTranslate(){
		return p::Out("<div class='btCard bottomCard'>".pMainTemplate::NoticeBox('fa-info-circle fa-12', BATCH_TR_DESC1 . " " . sprintf(BATCH_TR_DESC2, '<span class="imitate-tag">', '</span>', '<span class="imitate-tag">', '</span>'),  'notice-subtle')."</div>");
	}

	public function cardTranslate($data, $section){
		$lang0 = new pLanguage(0);
		$lang1 = new pLanguage(pRegister::session()['btChooser-translate']);
		p::Out("<div class='btCard transCard proper'>
				<div class='btTitle'>".BATCH_TRANSLATE."</div>
				<div class='btSource'>
					<span class='btLanguage inline-title small'>".(new pDataField(null, null, null, 'flag'))->parse($lang0->read('flag'))." ".$lang0->read('name')."</span><br /><span class='native'>
					<strong class='pWord xxmedium'><a>".$data['native']."</a></strong></span>
				</div><br />
				<div class='btTranslate'>
					<span class='btLanguage inline-title small'>".(new pDataField(null, null, null, 'flag'))->parse($lang1->read('flag'))." ".$lang1->read('name')."</span><br />
					<textarea placeholder='' class='elastic nWord btInput translations'></textarea>
				</div><br />
				<div class='btButtonBar'>
					<a class='btAction no-float'>".BATCH_TR_UNTRANS."</a>
					<a class='btAction blue'>".BATCH_CONTINUE."</a>
					<a class='btAction button-skip'>".BATCH_TR_SKIP."</a>
					<br id='cl' />
				</div>
		</div>
		
		<script type='text/javascript'>
			$(document).ready(function(){
				$('.translations').tagsInput({
							'defaultText': '".BATCH_TR_PLACEHOLDER."'
						});
				$('.translations').elastic();

			});
			$('.button-skip').click(function(){
				$('.btLoadSide').load('".p::Url('?batch/'.$section.'/skip/ajax')."', {'skip': ".$data['id']."}, function(){
					serveCard();
				});
			});
			
		</script>
		");
	}
	
	public function script($section){
		return "
		$('.chooser-continue').click(function(){
			$('.chooser').slideUp();
			$('.dotsc').slideDown();
			$('.btLoad').load('".p::Url('?batch/'.$section.'/choose/ajax')."', {'btChooser': $('.btChooser').val()}, function(){
				serveCard(); 
			});

		});
		function serveCard(){
			$('.btLoad').slideUp();
			$('.bottomCard').hide();
			$('.dotsc').slideDown();
			$('.btLoad').load('".p::Url('?batch/'.$section.'/serve/ajax')."', {}, function(){
				$('.dotsc').slideUp();
				$('.btLoad').slideDown();
				$('.bottomCard').show();
			});
		};
		";
	}

}