<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: Searchbox.class.php

class pSearchBox extends pLayoutPart{

	private $_value = null, $_enableLanguage, $_section = null, $_enableWholeWord, $_enableAlphabetBar, $_home = false, $_idS, $_enableNoBackspace = false;

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

	public function toggleNoBackSpace($value = NULL){
		$this->_enableNoBackspace = ($value == NULL ? !$this->_enableNoBackspace : $value);
	}

	public function setValue($value){
		$this->_value = $value;
	}

	public function __toString(){

		pTemplate::toggleSearchBox();

		$lang_zero = new pLanguage(0);

		$this->_idS = date('s');

		$output = '<div class="hMobile id_'.$this->_idS.'"><div class="header dictionary home'.$this->_floatRight.'">';

		$output .= '<div class="hWrap"><div class="hSearch"><input type="text" id="wordsearch" class="big word-search '.(((isset(pRegister::session()['searchLanguage']) AND p::StartsWith(pRegister::session()['searchLanguage'], $lang_zero->read('locale'))) OR (!isset(pRegister::session()['searchLanguage']) AND CONFIG_ENABLE_DEFINITIONS == 1)) ? 'native' : '').'" placeholder="'.DICT_KEYWORD.'" value="'.$this->_value.'"/>

			</div></div>
			</div>
			</div>
			';

		// To have this in the page then
		p::Out('<div class="hSearchResults">
			'."<div class='hSearchtitle hide'>".pLanguage::dictionarySelector('dictionary-selector').'</div>
				<div class="searchLoad"></div>
								<div class="load-hide hide" style="text-align: center">'.pTemplate::loadDots().'</div>
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

		var reloaded = false;

		orgTitle = '".pTemplate::$orgTitle."';

		"; 
		if(isset(pRegister::arg()['query']) AND isset(pRegister::arg()['dictionary']))
			$output .= "
				$(document).ready(function(){
					orgTitle = document.title;
					$('.word-search').val('".pRegister::arg()['query']."');
					$('.dictionary-selector').val('".strtoupper(pRegister::arg()['dictionary'])."');
		      		$('.landing-content').hide();
		      		$('.pEntry').hide();
      				$('.searchLoad').show();
      				$('.hSearchtitle').show();
      				doSearch(false);

				});";

		$output .= "

		$('.down-click').click(function(){
      				 $('html, body').animate({
        scrollTop: $('.landing-about').offset().top
    }, 500);
      				});


		$('.word-search').keyup(function(e){
			if($(this).val() == ''){
				".($this->_enableNoBackspace ? "" : "loadhome()")."
				";

				if((isset(pRegister::arg()['query'], pRegister::arg()['dictionary'])))

				$output .= "$('.pEntry').load('".p::Url('?home/ajax/nosearch')."', {}, function(){
					window.history.pushState('string', '', '".p::Url("?home")."');

					
					$('.hSearchResults').hide();
				});

				$('.pEntry').show();
				".($this->_home ? : "$('div.dictionary.header').removeClass('pentry');")."
      			$('.searchLoad').hide();
      			$('.hSearchtitle').hide();
				";

				$output .= "
			}
		}	);



		function callBack(bypass = false, bypass2 = false){
			  if($('.word-search').val() == '' && bypass == false){
			  	".($this->_enableNoBackspace ? "" : "loadhome()")."
		    
			  	if(reloaded == false || bypass2 == true){

			  		window.history.pushState('string', '', '".p::Url("?".pRegister::queryString())."');
		    	$('.load-hide').hide();
		    	$('.page').removeClass('min');
				$('.searchLoad').hide();
				$('.hSearchtitle').hide();
				$('.searchLoad').html('');
				$('.pEntry').show();
				".($this->_home ? "$('div.dictionary.header').removeClass('pentry');" : "$('div.dictionary.header').addClass('pentry');")."
				lock = '';
				searchLock = '';
				";

		if($this->_home)
			$output .= "$('.header.dictionary').addClass('home').removeClass('home-search');
			$('.landing').addClass('.landing-p').removeClass('.landing-h');";

		$output .= "
				document.title = orgTitle;
				searchLock = '';
				// We are not sending empty queries

			  	}


      		}else{
      			$('.pEntry').hide();
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
			$('.outerwrap.no-border-h').addClass('no-border');
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
				$('.outerwrap.no-border-h').addClass('no-border');
				lock = '';
				searchLock = '';
			}
			else{
				window.scrollTo(0, 0);
				$('.load-hide').show();
				lock = $('.word-search').val() + $('dictionary-selector').val();
				$('.outerwrap.no-border-h').removeClass('no-border');
				if(bypass === true || $('.word-search').val().length > 0){
					$('.page').addClass('min');
					$('.landing').removeClass('.landing-p').addClass('.landing-h');
					
					$('.hSearchResults').show();
	      			$('.searchLoad').load('".p::Url('?search/')."' + $('.dictionary-selector').val() + '/ajax/', {'query': $('.word-search').val(), 'exactMatch': $('.checkbox-wholeword').is(':checked')}, function(e){
	      					$('.searchLoad').show();
	      					$('.hSearchtitle').show();
	      					if($('.word-search').val() != ''){
	      						window.history.pushState('string', '', '".p::Url("?entry/search/")."' + $('.dictionary-selector').val().toLowerCase() + '/' + $('.word-search').val());
	      					}
	      					html = e;
	      					$('.load-hide').hide();
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
					$('.pEntry').hide();
					$('.searchLoad').show();
					$('.hSearchtitle').show();
			}

		});";


		if(isset(pRegister::arg()['is:result']))
			$output .= "	$('.searchLoad').mouseleave(function(){
			if(($(window).width() < 700)){
				$('.searchLoad').hide();
				$('.hSearchtitle').show();
				loadhome();
				$('.pEntry').show();
				".($this->_home ? : "$('div.dictionary.header').removeClass('pentry');")."
				window.history.pushState('string', '', '".p::Url("?".pRegister::queryString())."');				
			}

		});
		";

	
		$output .= "


		function reloadOnlyFirstTime(loadval){
			if(reloaded == false){
				$('.word-search').val(loadval);
				reloaded = true;
			}
		}



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