<div  style="height: auto;">
<?php

	// We need this

	$donut['taken_care_of'] = array();
	$donut['taken_care_of_words'] = array();
	
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
			$url = 'index.php?lemma=' . pHashId($_GET['wordsearch']);
		else
			$url = 'index.php?home';

		echo '<div class="title"><div class="icon-box fetch"><i class="fa fa-search"></i></div> Search results</div><br />'.
	"<a class='float-left back-mini search' href='javascript:void(0);' onClick=\"$('.header.dictionary').removeClass('home-search').addClass('home');$('#loading').slideDown();$('.ajaxload').slideUp(function(){ $('.drop').slideDown();$('#loading').slideUp();});
	$('#wordsearch').val('').focus();window.history.pushState('string', '', '".$url."');\"><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i></a>";


	}
	else
		echo "<br />";


	//  Getting search and return language
	$dict = explode('_', $_REQUEST['dict']);
		$slang = $dict[0];
		$rlang = $dict[1];


	// Something has to be done!
	if(trim($_REQUEST['word']) == '')
		echo "<script>
				$('.header.dictionary').removeClass('home-search').addClass('home');$('.ajaxload').slideUp(function(){ $('.drop').slideDown(); });
					$('#wordsearch').val('').focus().pulsate({color: 'red', repeat: 2, glow: false, speed: 500});


		</script>";


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
			
			$top = '';
			$rest = '';



			$count = 0;
			// Going through the translations
			foreach($translations as $translation)
			{

	
				if(!in_array($translation->word_id, $donut['taken_care_of'])){
					$count += 1; 
					$rest .= "<div class='loadDelete'></div>".pWordShowNative($translation, (($slang == 0) ? $rlang : $slang), isset($_GET['wordsonly']));
				}

			}

			if(!isset($_GET['wordsonly']))
			{
				if($count == 1)
					$top =  "<span class='results small'>".sprintf(DICT_MATCH, 1)."</span>";
				else
					$top = "<span class='results small'>".sprintf(DICT_MATCHES, $count)."</span>";
			}

			echo $top."<br id='cl' />".$rest;

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