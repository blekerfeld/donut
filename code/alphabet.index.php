<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: index.home.php
*/

pDictionaryHeader();

pOut('<table class="noshow" style="width:100%;"><tr>');

pSearchArea();
	
pOut('<td style="padding-left: 20px;">
	<div class="notice hide" style="display: none;" id="loading"><i class="fa fa-spinner fa-spin"></i> Loading...</div>
	<br id="cl loading" style="display: none;"/><div class="ajaxload" style="display: none;"></div>
      <div class="drop">
     ');


if(is_numeric($_REQUEST['alphabet']))
	$a_get = $_REQUEST['alphabet'];
elseif(ctype_alnum($_REQUEST['alphabet'])){
	$a_get = pHashId($_REQUEST['alphabet'], true);
	$a_get = $a_get[0];
}
else
	pUrl('', true);


$grapheme = pGetGrapheme($a_get);

if($grapheme != false){

	// Let's do a title
	pOut('<div class="title"><div class="icon-box fetch">'.$grapheme->grapheme.'</div><input id="filter" style="margin-top: -7px;" placeholder="Filter" /></div><br />');

	// Our no results placeholder

	pOut('<div class="notice hide" id="noresults">No matches found. Please note that you are searching in the list of words starting with \''.$grapheme->grapheme.'\'.</div>');

	// Lets gets our alphabet in array form

	$alphabet = pGetAlphabetArray();

	// Do we need to filter though?
	$filter_starts = array();

	$alphabet_filter = pGetAlphabetByStart($grapheme->grapheme);
	foreach ($alphabet_filter as $alphabet_filter_instance) {
		if(!(mb_strlen($alphabet_filter_instance['grapheme'], "UTF-8") == mb_strlen($grapheme->grapheme, "UTF-8")))
			$filter_starts[] = $alphabet_filter_instance['grapheme'];
	}


	// Lets gets our words by start

	$words = pGetWordsByStart($grapheme->grapheme, $filter_starts);

	// Counting... error if there are no words
	if(count($words) == 0){
		pOut('<div class="notice">There doesn\'t seem to be anything here. </div>');

	}
	else{
		// Let's sort the words
		$sorted_words = pSortByAlphabet($words, $alphabet);

		foreach($sorted_words as $word){
			pOut(pWordShowNative($word[1]['id'], 0, false, true, 'a', 'alphabetresult='.$_GET['alphabet']));
		}

	}

}


else{
	pUrl('', true);
}



pOut('
	</td></tr></table>
      <script>
      	$("#searchb").click(function(){
      		$("#pageload i").show();
      		$(".ajaxload").slideUp();
      		$(".drop").hide();
	      		$(".ajaxload").load("'.pUrl('?getword&ajax&alphabetsearch='.$_GET['alphabet']).'", {"word": $("#wordsearch").val(), "dict": $("#dictionary").val(), "wholeword":  $("#wholeword").is(":checked")}, function(){$(".ajaxload").slideDown(function(){
	      								 $("#pageload i").delay(100).hide(400);
	      		})}, function(){
      		});
      		if($("#wordsearch").val() != ""){
      			window.history.pushState("string", "", "index.php?search=" + $("#wordsearch").val());
      		}
      		else{
      			window.history.pushState("string", "", "index.php?alphabet='.$_GET['alphabet'].'");
      		}
      		
      	});
      </script>
      <br id="cl"/>');

	pOut("<script>  $(document).ready(function() {
              $('.tooltip').tooltipster({theme: 'tooltipster-noir'});

          });
		$('#wordsearch').keydown(function(e) {
			    switch (e.keyCode) {
			        case 13:
			        if($('#wordsearch').is(':focus'))
			        {
			        	$('#searchb').click();
			        }

			    }
			    return; 
			});
	$('#filter').on('change keyup paste click', function(e) {
			   
			        
			        if($('#filter').is(':focus'))
			        {
			        	$('.aWordWrapper').fadeOut(0);
			        	var txt = $(this).val();
  						 $('.aWordWrapper:contains('+ txt +')').fadeIn(0);
  						 if ( $('.aWordWrapper:visible').length === 0)
     						 $('#noresults').css('display', 'inline-block');
     					else
     						$('#noresults').fadeOut(500);
  						 
			        }

			    
			    return; 
			});
	</script>");


 ?>