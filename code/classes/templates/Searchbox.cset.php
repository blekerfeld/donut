<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: entrysection.cset.php

class pSearchBox extends pTemplatePiece{

	private $_value = null, $_enableLanguage, $_section = null, $_enableWholeWord, $_enableAlpbabetBar, $_home = false, $_idS;

	public function __construct($home = false, $language = true, $section = null, $wholeword = true, $alpabetbar = true){
		$this->_home = $home;
		$this->_enableLanguage = $language;
		$this->_section = $section;
		$this->_enableWholeWord = $wholeword;
		$this->_enableAlpbabetBar = $alpabetbar;
	}

	public function setValue($value){
		$this->_value = $value;
	}

	public function __toString(){

		$this->_idS = date('s');

		$output = '<div class="hMobile id_'.$this->_idS.'"><div class="header dictionary '.($this->_home ? 'home' : '').'">
			<span class="float-right" style="padding-right: 24px;important;display: block;"><span class="wholewordWrap">
			<input id="wholeword" class="checkbox-wholeword xsmall" name="wholeword" type="checkbox" '.((isset(pAdress::session()['exactMatch']) AND pAdress::session()['exactMatch'] == false) ? '' : 'selected').'>
        	<label for="wholeword" class="checkbox-wholeword-label small">'.DICT_EXACT_MATCH.'</label></span>
			</span>';

		$output .= new pAlphabet;

		$output .= '<div class="hWrap"><div class="hSearch">'.pLanguage::dictionarySelector('dictionary-selector').'
				<input type="text" id="wordsearch" class="big word-search" placeholder="'.DICT_KEYWORD.'" value="'.$this->_value.'"/><a class="button search  remember search-button float-right" id="searchb" href="javascript:void(0);">' . (new pIcon('fa-search', 12)) . ' '. DICT_SEARCH.'</a>'.new pIcon('fa-spinner fa-spin load-hide', 12).'
			<br id="cl" /></div></div>
			</div>
			</div>
			<div class="hSearchResults">
				<div class="searchLoad"></div>
			</div>';

			$hashKey = spl_object_hash($this);

			// Throwing this object's script into a session
			pAdress::session($hashKey, $this->script());

			$output .= "<script type='text/javascript' src='".p::Url('pol://library/assets/js/jsMover.js.php?key='.$hashKey)."'></script>";

			return $output;
	}

	public function script(){

		// Time for the fancy script
		$output = "

		orgTitle = '".pMainTemplate::$orgTitle."';

		"; 
		if(isset(pAdress::arg()['query']) AND isset(pAdress::arg()['dictionary']))
			$output .= "
				$(document).ready(function(){
					orgTitle = document.title;
					$('.word-search').val('".pAdress::arg()['query']."');
					$('.dictionary-selector').val('".strtoupper(pAdress::arg()['dictionary'])."');
		      		$('.pEntry').slideUp();
      				$('.searchLoad').slideDown();
      				search();
				});";

		$output .= "


		function callBack(){
			  if($('.word-search').val() == ''){
		    	window.history.pushState('string', '', '".p::Url("?".pAdress::queryString())."');
		    	$('.page').removeClass('min');
				$('.searchLoad').slideUp();
				loadhome();
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
      			search();
      		}

		}

		var options = {
		    callback: function (value) {
		    	callBack();
		    },
		    wait: 100,
		    highlight: true,
			    allowSubmit: true,
		    captureLength: 1	
		}

		var options2 = {
		    callback: function (value) {
		    	$('.word-search').blur();
		    },
		    wait: 2000,
		    highlight: true,
		    allowSubmit: true,
		    captureLength: 1
		}

		$('.word-search').typeWatch( options );
		$('.word-search').typeWatch( options2 );

		$('.word-search').keyup(function(e){
			if(e.keyCode == 8){
				callBack();
			}
			if(e.keyCode == 46){
				callBack();
			}
		}	);

		function loadhome(){
			";

			// Don't show an error if we are forced to have the search load a search action
			if((isset(pAdress::arg()['query'], pAdress::arg()['dictionary'])))

				$output .= "$('.pEntry').load('".p::Url('?home/ajax/nosearch')."', {}, function(){
					window.history.pushState('string', '', '".p::Url("?home")."');
					$('.header.dictionary').addClass('home');	
				});";

		$output .= "
		}

		lock = '';
		html = '';

		function search(bypass){
			window.scrollTo(0, 0);
			$('.load-hide').show();
			lock = $('.word-search').val();
			if(bypass === true || $('.word-search').val().length > 0){
				$('.page').addClass('min');
				$('.header.dictionary').removeClass('home').addClass('home-search');
      			$('.searchLoad').load('".p::Url('?search/')."' + $('.dictionary-selector').val() + '/ajax/', {'query': $('.word-search').val(), 'exactMatch': $('.checkbox-wholeword').is(':checked')}, function(e){
      					$('.searchLoad').slideDown();
      					if($('.word-search').val() != ''){
      						window.history.pushState('string', '', '".p::Url("?entry/search/")."' + $('.dictionary-selector').val().toLowerCase() + '/' + $('.word-search').val());
      					}
      					html = e;
      					$('.load-hide').hide();
      					document.title = $('.word-search').val() + ' - Searching';
      			});
      		}
		}

		$('.hSearch').on('click', function(){
			if($('.word-search').val() != ''){
					if($('.word-search').val() != lock){
						search();
					}
					$('.pEntry').slideUp();
					$('.searchLoad').slideDown();
				
			}

		});";


		if(isset(pAdress::arg()['is:result']))
			$output .= "	$('.searchLoad').mouseleave(function(){
			if(($(window).width() < 700)){
				$('.searchLoad').slideUp();
				loadhome();
				$('.pEntry').slideDown();
				window.history.pushState('string', '', '".p::Url("?".pAdress::queryString())."');				
			}

		});
		";

	
		$output .= "

		$('.word-search').keydown(function(){
			$('.load-hide').show();
		});

		$('.word-search').keyup(function(){
			$('.load-hide').hide();
		});

		$('.search-button').click(function(){
			search(true);
		});

		$('.dictionary-selector').on('change', function(e) {
          search();
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