<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: entrysection.cset.php

class pSearchBox extends pTemplatePiece{

	private $_value = null, $_enableLanguage, $_section = null, $_enableWholeWord, $_enableAlphabetBar, $_home = false, $_idS;

	public function __construct($home = false, $language = true, $section = null, $wholeword = true, $alpabetbar = true){
		$this->_home = $home;
		$this->_enableLanguage = $language;
		$this->_section = $section;
		$this->_enableWholeWord = $wholeword;
		$this->_enableAlphabetBar = $alpabetbar;
	}

	public function setValue($value){
		$this->_value = $value;
	}

	public function __toString(){

		p::Out("<div style='margin-bottom:10px';></div>	");
		pMainTemplate::toggleSearchBox();

		$this->_idS = date('s');

		$output = '<div class="hMobile id_'.$this->_idS.'"><div class="header dictionary '.($this->_home ? 'home' : '').'">';

		$output .= '<div class="hWrap"><div class="hSearch">'.pLanguage::dictionarySelector('dictionary-selector').'

				<input type="text" id="wordsearch" class="big word-search" placeholder="'.DICT_KEYWORD.'" value="'.$this->_value.'"/>
			<br id="cl" /></div></div>
			</div>
			</div>
			';

		// To have this in the page then
		p::Out('<div class="hSearchResults">
				<div class="searchLoad"></div>
								<div class="load-hide hide" style="text-align: center">'.pMainTemplate::loadDots().'</div>
			</div>');

			$hashKey = spl_object_hash($this);

			// Throwing this object's script into a session
			pRegister::session($hashKey, $this->script());

			$output .= "<script type='text/javascript' src='".p::Url('pol://library/assets/js/key.js.php?key='.$hashKey)."'></script>";

		if(isset(pRegister::arg()['print']))
			return '';

		return $output;
	}

	public function script(){

		// Time for the fancy script
		$output = "

		orgTitle = '".pMainTemplate::$orgTitle."';

		"; 
		if(isset(pRegister::arg()['query']) AND isset(pRegister::arg()['dictionary']))
			$output .= "
				$(document).ready(function(){
					orgTitle = document.title;
					$('.word-search').val('".pRegister::arg()['query']."');
					$('.dictionary-selector').val('".strtoupper(pRegister::arg()['dictionary'])."');
		      		$('.pEntry').slideUp();
      				$('.searchLoad').slideDown();
      				doSearch();
				});";

		$output .= "


		$('.word-search').keyup(function(e){
			if($(this).val() == ''){
				loadhome();
				";

				if((isset(pRegister::arg()['query'], pRegister::arg()['dictionary'])))

				$output .= "$('.pEntry').load('".p::Url('?home/ajax/nosearch')."', {}, function(){
					window.history.pushState('string', '', '".p::Url("?home")."');
					$('.header.dictionary').addClass('home');	
				});

				$('.pEntry').slideDown();
      			$('.searchLoad').slideUp();

				";

				$output .= "
			}
		}	);



		function callBack(){
			  if($('.word-search').val() == ''){
			  	loadhome();
		    	window.history.pushState('string', '', '".p::Url("?".pRegister::queryString())."');
		    	$('.load-hide').hide();
		    	$('.page').removeClass('min');
				$('.searchLoad').slideUp();
				$('.searchLoad').html('');
				$('.pEntry').slideDown();
				";

		if($this->_home)
			$output .= "$('.header.dictionary').addClass('home');";

		$output .= "
				document.title = orgTitle;
				searchLock = '';
				// We are not sending empty queries
      		}else{
      			$('.pEntry').slideUp();
      			doSearch();
      		}

		}

		var options = {
		    callback: function (value) {
		    	callBack();
		    },
		    wait: 300,
		    highlight: true,
			allowSubmit: true,
		    captureLength: 1	
		}

		$('.word-search').typeWatch( options );

		function loadhome(){
			
			$('.word-search').blur().focus();
			";

			if((isset(pRegister::arg()['query'], pRegister::arg()['dictionary'])))

				$output .= "$('.pEntry').load('".p::Url('?home/ajax/nosearch')."', {}, function(){
					window.history.pushState('string', '', '".p::Url("?home")."');
					$('.header.dictionary').addClass('home');	
				});";

		$output .= "
		}

		lock = '';
		html = '';

		function doSearch(bypass){
			if($('.word-search').val() == '' || $('.word-search').val() == ' '){
				$('.searchLoad').html('');
			}
			else{
				window.scrollTo(0, 0);
				$('.load-hide').slideDown();
				lock = $('.word-search').val() + $('dictionary-selector').val();
				if(bypass === true || $('.word-search').val().length > 0){
					$('.page').addClass('min');
					$('.header.dictionary').removeClass('home').addClass('home-search');
	      			$('.searchLoad').load('".p::Url('?search/')."' + $('.dictionary-selector').val() + '/ajax/', {'query': $('.word-search').val(), 'exactMatch': $('.checkbox-wholeword').is(':checked')}, function(e){
	      					$('.searchLoad').slideDown();
	      					if($('.word-search').val() != ''){
	      						window.history.pushState('string', '', '".p::Url("?entry/search/")."' + $('.dictionary-selector').val().toLowerCase() + '/' + $('.word-search').val());
	      					}
	      					html = e;
	      					$('.load-hide').slideUp();
	      					document.title = $('.word-search').val() + ' - Searching';
	      			});
	      		}
			}
		}

		$('.hSearch').on('click', function(){
			if($('.word-search').val() != ''){
					if($('.word-search').val() + $('dictionary-selector').val() != lock){
						doSearch();
					}
					$('.pEntry').slideUp();
					$('.searchLoad').slideDown();
				
			}

		});";


		if(isset(pRegister::arg()['is:result']))
			$output .= "	$('.searchLoad').mouseleave(function(){
			if(($(window).width() < 700)){
				$('.searchLoad').slideUp();
				loadhome();
				$('.pEntry').slideDown();
				window.history.pushState('string', '', '".p::Url("?".pRegister::queryString())."');				
			}

		});
		";

	
		$output .= "
		$('.search-button').click(function(){
			doSearch(true);
		});

		$('.dictionary-selector-selector').on('change', function(e) {
          	 if($('.word-search').val() != ''){
          		doSearch();
        	}
        });

        $('.back-to-search').click(function(){
        	alert();
        });


        $(document).ready(function(){
 
   			 $(window).scroll(function(){
   			 	pos = $('.header.dictionary').position();
        		scrollPos = $('.header.dictionary').outerHeight() + pos.top - 20;
        		if($(window).scrollTop() > scrollPos){
        			$('.hMobile').addClass('fixed');
        			$('.ulWrap').addClass('fixed');
        		}
		    	else{
		    		$('.hMobile').removeClass('fixed');
		    		$('.ulWrap').removeClass('fixed');
		    	}
			});
		});";

		return $output;

	}
	
}