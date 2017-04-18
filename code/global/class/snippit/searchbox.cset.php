<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: entrysection.cset.php

class pSearchBox extends pSnippit{

	private $_value, $_enableLanguage, $_section = null, $_enableWholeWord, $_enableAlpbabetBar, $_home = false;

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

		$output = '<div class="header dictionary '.($this->_home ? 'home' : '').'">
			<span class="float-right" style="padding-right: 24px;important;display: block;">
			<input id="wholeword" class="checkbox-wholeword xsmall" name="wholeword" type="checkbox" '.((isset(pAdress::session()['exactMatch']) AND pAdress::session()['exactMatch'] == false) ? '' : 'selected').'>
        	<label for="wholeword"class="checkbox-wholeword-label small">'.DICT_EXACT_MATCH.'</label>  
			</span>';

		$output .= new pAlphabet;

		$output .= '<div class="hWrap"><div class="hSearch">'.pLanguage::dictionarySelector('dictionary-selector').'
				<input type="text" id="wordsearch" class="big word-search" placeholder="'.DICT_KEYWORD.'" value="'.$this->_value.'"/><a class="button search  remember search-button float-right" id="searchb" href="javascript:void(0);">' . (new pIcon('fa-search', 12)) . ' '. DICT_SEARCH.'</a>'.new pIcon('fa-spinner fa-spin load-hide', 12).'
			<br id="cl" /></div></div>
			</div>
			<div class="hSearchResults">
				<div class="searchLoad"></div>
			</div>';	

		// Time for the fancy script
		$output .= "<script type='text/javascript'>

		var options = {
		    callback: function (value) {

		    if($('.word-search').val() == ''){
				$('.searchLoad').slideUp();
				$('.pEntry').slideDown();
				searchLock = '';
				// We are not sending empty queries
      		}else{
      			$('.pEntry').slideUp();
      			$('.searchLoad').slideDown();
      			search();
      		}

		    },
		    wait: 100,
		    highlight: true,
		    allowSubmit: true,
		    captureLength: 2
		}

		$('.word-search').typeWatch( options );

		lock = '';
		html = '';

		function search(bypass){
			$('.load-hide').show();
			lock = $('.word-search').val();
			if(bypass === true || $('.word-search').val().length > 0){
				//$('#loading').slideDown();
				oldHeight = $('.searchLoad').height();
				$('.header.dictionary').removeClass('home').addClass('home-search');
      			$('.searchLoad').load('".pUrl('?search/')."' + $('.dictionary-selector').val() + '/ajax/', {'query': $('.word-search').val(), 'exactMatch': $('.checkbox-wholeword').is(':checked')}, function(e){

      					html = e;
      					$('.load-hide').hide();
      			});
      		}
		}

		$('.hSearch').on('click', function(){
			if($('.word-search').val() == ''){
				
      		}else{
      			if($('.word-search').val() != lock){
      				search();
      			}
      		}
		});

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
        })


		</script>";

		return $output;
	}
	
}