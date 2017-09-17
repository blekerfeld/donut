<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      entrysection.class.php

class pSearchBox extends pTemplatePiece{

	private $_value = null, $_enableLanguage, $_section = null, $_enableWholeWord, $_enableAlphabetBar, $_home = false, $_idS;

	public function __construct($home = false, $language = true, $section = null, $wholeword = true, $alpabetbar = false){
		$this->_home = $home;
		$this->_enableLanguage = $language;
		$this->_section = $section;
		$this->_enableWholeWord = $wholeword;
		$this->_enableAlphabetBar = $alpabetbar;
	}

	public function enablePentry(){
		return $this->_enableAlphabetBar = true;
	}

	public function setValue($value){
		$this->_value = $value;
	}

	public function __toString(){

		pMainTemplate::toggleSearchBox();

		$lang_zero = new pLanguage(0);

		$this->_idS = date('s');

		$output = '<div class="hMobile id_'.$this->_idS.'"><div class="header dictionary '.($this->_enableAlphabetBar ? 'pentry ' : '').($this->_home ? 'home' : '').'">';

		$output .= '<div class="hWrap"><div class="hSearch">
				'.pLanguage::dictionarySelector('dictionary-selector').'
				<input  type="text" id="wordsearch" class="big word-search '.(((isset(pRegister::session()['searchLanguage']) AND p::StartsWith(pRegister::session()['searchLanguage'], $lang_zero->read('locale'))) OR (!isset(pRegister::session()['searchLanguage']) AND CONFIG_ENABLE_DEFINITIONS == 1)) ? 'native' : '').'" placeholder="'.DICT_KEYWORD.'" value="'.$this->_value.'"/>
			</div><br id="cl" /></div>
			</div>
			</div>
			';

		// To have this in the page then
		p::Out('<div class="hSearchResults">
			'."<div class='hSearchtitle hide'><div class='card-tabs-bar titles pEntry-fix'>
			
			</div><br />".'</div>
				<div class="searchLoad"></div>
								<div class="load-hide hide" style="text-align: center">'.new pIcon('fa-spinner fa-spin', 32).'</div>
			</div>');

			$hashKey = sha1(spl_object_hash($this)."sb");

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
      				$('.hSearchtitle').show();
      				doSearch(false);
				});";

		$output .= "


		$('.word-search').keyup(function(e){
			if($(this).val() == ''){
				loadhome();
				";

				if((isset(pRegister::arg()['query'], pRegister::arg()['dictionary'])))

				$output .= "$('.pEntry').load('".p::Url('?home/ajax/nosearch')."', {}, function(){
					window.history.pushState('string', '', '".p::Url("?home")."');
					$('.header.dictionary').addClass('home').removeClass('home-search');	
				});

				$('.pEntry').slideDown();
				".($this->_home ? : "$('div.dictionary.header').removeClass('pentry');")."
      			$('.searchLoad').slideUp();
      			$('.hSearchtitle').hide();
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
				$('.hSearchtitle').hide();
				$('.searchLoad').html('');
				$('.pEntry').slideDown();
				".($this->_home ? "$('div.dictionary.header').removeClass('pentry');" : "$('div.dictionary.header').addClass('pentry');")."
				lock = '';
				searchLock = '';
				";

		if($this->_home)
			$output .= "$('.header.dictionary').addClass('home').removeClass('home-search');";

		$output .= "
				document.title = orgTitle;
				searchLock = '';
				// We are not sending empty queries
      		}else{
      			$('.pEntry').slideUp();
      			doSearch(false);
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
			";

			if((isset(pRegister::arg()['query'], pRegister::arg()['dictionary'])))

				$output .= "$('.pEntry').load('".p::Url('?home/ajax/nosearch')."', {}, function(){
					window.history.pushState('string', '', '".p::Url("?home")."');
					$('.header.dictionary').addClass('home').removeClass('home-search').removeClass('pentry');	
				});";

		$output .= "
		}

		lock = '';
		html = '';

		function doSearch(bypass){
			$('div.dictionary.header').removeClass('pentry');
			if($('.word-search').val() == '' || $('.word-search').val() == ' '){
				$('.searchLoad').html('');
				$('.hSearchtitle').hide();
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
	      					$('.hSearchtitle').show();
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
						doSearch(false);
					}
					$('.pEntry').slideUp();
					$('.searchLoad').slideDown();
					$('.hSearchtitle').show();
			}

		});";


		if(isset(pRegister::arg()['is:result']))
			$output .= "	$('.searchLoad').mouseleave(function(){
			if(($(window).width() < 700)){
				$('.searchLoad').slideUp();
				$('.hSearchtitle').show();
				loadhome();
				$('.pEntry').slideDown();
				".($this->_home ? : "$('div.dictionary.header').removeClass('pentry');")."
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
          		doSearch(false);
        	}
        });

        $('.back-to-search').click(function(){
        	alert();
        });




        $(document).ready(function(){

        
        	$('.word-search').focusin(function(){
        		$('.hSearch .card-tabs-bar.selectorTabs-h a.active').addClass('wordsearch');

     
     			
        	});

        	$('.word-search').blur(function(){
        		$('.hSearch .card-tabs-bar.selectorTabs-h a.active').removeClass('wordsearch');
 
        	});
 
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