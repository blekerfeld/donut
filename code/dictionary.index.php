<div  style="height: auto;">
<?php

	// We need this
	global $pol;
	$pol['taken_care_of'] = array();
	$pol['taken_care_of_words'] = array();
	
	// We have to be in AJAX mode
	if(!isset($_REQUEST['ajax']))
		pUrl('', true);


	// Are we looking for whole words?
	if(isset($_REQUEST["wholeword"]) and $_REQUEST["wholeword"] === 'true')
		$ww = true;
	else
		$ww = false;

	$_SESSION['search'] = $_REQUEST['word'];
	$_SESSION['wholeword'] = $_REQUEST["wholeword"];
	$_SESSION['search_language'] = $_REQUEST['dict'];


	// Title and such...
	if(!isset($_GET['wordsonly'])){

		if(isset($_GET['alphabetsearch']))
			$url = 'index.php?alphabet=' . $_GET['alphabetsearch'];
		elseif(isset($_GET['wordsearch']))
			$url = 'index.php?word=' . $_GET['wordsearch'];
		else
			$url = 'index.php?home';

		echo '<div class="title"><div class="icon-box fetch"><i class="fa fa-th-list"></i></div> Search results</div><br />'.
	"<a class='actionbutton' href='javascript:void(0);' onClick=\"$('#loading').slideDown();$('.ajaxload').slideUp(function(){ $('.drop').slideDown();$('#loading').slideUp(); });
	$('#wordsearch').val('').focus();window.history.pushState('string', '', '".$url."');\"><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</i></a><br /><br />";


	}
	else
		echo "<br />";


	//  Getting search and return language
	$dict = explode('_', $_REQUEST['dict']);
		$slang = $dict[0];
		$rlang = $dict[1];


	// Something has to be done!
	if(trim($_REQUEST['word']) == '')
		echo '<div class="notice danger-notice" id="empty"><i class="fa fa-warning"></i> Please submit a search query.</div>
		<br id="cl"/>';


	// Requesting all words!
	if($_REQUEST['word'] == 'SYSTEM:GET_ALL_WORDS')
	{	
		// TO BE BUILT!
		echo "It is dangerous to request everything!";
	}
	

	// Okay, so we are searching
	elseif(trim($_REQUEST['word']) != '')
	{
		

		echo '<div id="dictionary">';

		// Getting the search results from the translation database
		if($translations = pSearchDict($slang, $rlang, $_REQUEST["word"], $ww))
		{
			
			if(!isset($_GET['wordsonly']))
			{
				if(count($translations) == 1)
					echo "<span class='results'>Your query returned 1 match</span><br id='cl' />";
				else
					echo "<span class='results'>Your query returned ".count($translations)." matches</span><br id='cl' />";
			}



			// Going through the translations
			foreach($translations as $translation)
			{

	
				if(!in_array($translation->word_id, $pol['taken_care_of']))
					echo "<div class='loadDelete'></div>".pWordShowNative($translation, $slang, isset($_GET['wordsonly']));

			}
	}
	else
	{
		echo '<div class="notice danger-notice" id="empty"><i class="fa fa-warning"></i> We are sorry, your search for \''.$_REQUEST['word'].'\' did not match any words.</div><br id="cl" />';
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