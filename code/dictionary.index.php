<div  style="height: auto;">
<?php

	// We need this
	global $donut;
	$donut['taken_care_of'] = array();
	$donut['taken_care_of_words'] = array();
	
	// We have to be in AJAX mode
	if(!isset($donut['request']['ajax']))
		pUrl('', true);


	// Are we looking for whole words?
	if(isset($donut['request']["wholeword"]) and $donut['request']["wholeword"] === 'true')
		$ww = true;
	else
		$ww = false;

	$_SESSION['search'] = $donut['request']['word'];
	$_SESSION['wholeword'] = $donut['request']["wholeword"];
	$_SESSION['search_language'] = $donut['request']['dict'];


	// Title and such...
	if(!isset($donut['get']['wordsonly'])){

		if(isset($donut['get']['alphabetsearch']))
			$url = 'index.php?alphabet=' . $donut['get']['alphabetsearch'];
		elseif(isset($donut['get']['wordsearch']))
			$url = 'index.php?word=' . $donut['get']['wordsearch'];
		else
			$url = 'index.php?home';

		echo '<div class="title"><div class="icon-box fetch"><i class="fa fa-th-list"></i></div> Search results</div><br />'.
	"<a class='actionbutton' href='javascript:void(0);' onClick=\"$('#loading').slideDown();$('.ajaxload').slideUp(function(){ $('.drop').slideDown();$('#loading').slideUp(); });
	$('#wordsearch').val('').focus();window.history.pushState('string', '', '".$url."');\"><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</i></a><br /><br />";


	}
	else
		echo "<br />";


	//  Getting search and return language
	$dict = explode('_', $donut['request']['dict']);
		$slang = $dict[0];
		$rlang = $dict[1];


	// Something has to be done!
	if(trim($donut['request']['word']) == '')
		echo '<div class="notice danger-notice" id="empty"><i class="fa fa-warning"></i> Please submit a search query.</div>
		<br id="cl"/>';


	// Requesting all words!
	if($donut['request']['word'] == 'SYSTEM:GET_ALL_WORDS')
	{	
		// TO BE BUILT!
		echo "It is dangerous to request everything!";
	}
	

	// Okay, so we are searching
	elseif(trim($donut['request']['word']) != '')
	{
		

		echo '<div id="dictionary">';

		// Getting the search results from the translation database
		if($translations = pSearchDict($slang, $rlang, $donut['request']["word"], $ww))
		{
			
			if(!isset($donut['get']['wordsonly']))
			{
				if(count($translations) == 1)
					echo "<span class='results'>Your query returned 1 match</span><br id='cl' />";
				else
					echo "<span class='results'>Your query returned ".count($translations)." matches</span><br id='cl' />";
			}



			// Going through the translations
			foreach($translations as $translation)
			{

	
				if(!in_array($translation->word_id, $donut['taken_care_of']))
					echo "<div class='loadDelete'></div>".pWordShowNative($translation, $slang, isset($donut['get']['wordsonly']));

			}
	}
	else
	{
		echo '<div class="notice danger-notice" id="empty"><i class="fa fa-warning"></i> We are sorry, your search for \''.$donut['request']['word'].'\' did not match any words.</div><br id="cl" />';
	}
	echo '</div>';
}


echo pAjaxLinks();

?>
<script>
      $(document).ready(function() {
            $('.tooltip').tooltipster({theme: 'tooltipster-noir'});

        });</script>
<br id="cl" />