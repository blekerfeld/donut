
<?php 

// Template stuff
pDictionaryHeader();

pOut('<table class="noshow" style="width:100%;"><tr>');

// Search area
if(isset($_GET['searchresult']))
	pSearchArea($_SESSION['search'], true);
else
	pSearchArea('', true);

// Start the layout stuff

pOut('<td style="padding-left: 20px;"><div class="notice hide" style="display: none;" id="loading"><i class="fa fa-spinner fa-spin"></i> Loading...</div>
	<br id="cl loading" style="display: none;"/><div class="ajaxload" style="display: none;"></div>
      <div class="drop">');

// Get noun
global $donut;
$rs = $donut['db']->query("SELECT * FROM words WHERE id = ".$_REQUEST['word']."");
if(!$word = $rs->fetchObject()){
	pOut('<div class="notice danger-notice" id="empty"><i class="fa fa-warning"></i> The requested word is not a noun or doesn\'t exist.</div>');
}
else{
	if(!isset($_REQUEST['ajaxOUT']))
		if(isset($_GET['searchresult']))
			pOut('<div class="title"><div class="icon-box fetch"><i class="fa fa-bookmark"></i></div> Dictionary entry</div><br />'."<a class='actionbutton' href='".pUrl('?home&search='.urlencode($_SESSION['search']))."');'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</i></a><br /><br />");
		elseif(isset($_GET['alphabetresult']))
			pOut('<div class="title"><div class="icon-box fetch"><i class="fa fa-bookmark"></i></div> Dictionary entry</div><br />'."<a class='actionbutton' href='".pUrl('?alphabet='.$_GET['alphabetresult'])."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</i></a><br /><br />");
		else
			pOut('<div class="title"><div class="icon-box fetch"><i class="fa fa-bookmark"></i></div> Dictionary entry</div><br />'."<a class='actionbutton' ".((isset($_SERVER['HTTP_REFERER'])) ? ("href='".$_SERVER['HTTP_REFERER']."'") : 'onClick="window.history.back();"')."><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</i></a><br /><br />");




	// Getting languages
	$language = 
	$languages = pGetLanguages();
	$zero_lang = pGetLanguageZero();
	$lang_array = array('');
	$trans_array = array('');
	$lang_array[] = $zero_lang;

	while($language = $languages->fetchObject()){
		$lang_array[] = $language;
		$trans_array[$language->id] = array();
	}

	// Get word type information
	$type = pGetType($word->type_id);
	$classification = pGetClassification($word->classification_id);

	// Get modes
	$modes = pGetModes($word->type_id);

	
	// We need to start
		pOut("<div id='fadeOut_".$word->id."'>");

		// Is this a derivation?
		$derivation_term = "";
		if($word->derivation_of != 0 AND $word->derivation_show_in_title != 0){
			$derivation_word = pGetWord($word->derivation_of);
			if($word->derivation_name != 0)
				$derivation_name = pDerivationName($word->derivation_name);
			else
				$derivation_name = pTypeName($word->derivation_type);
			$derivation_term = " <span class='pDerivationTitle'><em>".$derivation_name."</em> from <span class='native'><a href='".pUrl('?word='.$derivation_word->id)."'>".$derivation_word->native."</a></span></span>";
		}
		

		pOut("<span class='pSectionTitle'><strong class='pWord' id='ajax_dov_".$word->id."'><a href='".pUrl('?word='.$word->id)."'><span class='native'>".html_entity_decode($word->native)."</span></a></strong>$derivation_term</span><div class='pSectionWrapper'>");


		// Show the type and classification
		pOut('<strong class="label">Part of speech:</strong><span id="tab"><em><span class="tooltip" >'.$type->name.'</span></em></span> <br />');
		if(!pTypeInflectClassifications($type->id))
			pOut('<strong class="label">Classification:</strong><span id="tab"><span class="tooltip" ><em><span class="tooltip" >'.$classification->name.'</em></span></span>');

		if($word->subclassification_id != 0 and !pTypeInflectClassifications($type->id))
			pOut('<br /><strong class="label">Subclassification:</strong><span id="tab"><span class="tooltip" ><em><span class="tooltip" >'.pSubclassificationName($word->subclassification_id).'</em></span>');
		pOut('</div>');


		// IMAGE

		if($word->image != '')
			pOut('<span class="pSectionTitle extra">Image</span><div class="pSectionWrapper"><span class="pPron"><img class="pImage" src="'.pUrl('pol://library/entry_images/'.html_entity_decode(($word->image))).'"/></span></div>');


		// IPA

		if($word->ipa != '')
			pOut('<span class="pSectionTitle extra">Pronounciation</span><div class="pSectionWrapper"><span class="pPron">/'.html_entity_decode(($word->ipa)).'/</span></div>');



	// The inflection!
	pOut('<span style="cursor: pointer;" onClick="$(\'.inflections\').slideToggle(\'fast\');$(\'.hideIconInflect\').toggle();$(\'.showIconInflect\').toggle(function(){
	});"><span class="pSectionTitle extra">Inflections <span class="showIconInflect"><i class="fa fa-12 fa-chevron-down "></i></span><span class="hideIconInflect hide"><i class="fa fa-12 fa-chevron-up "></i> </span></span></span><div class="pSectionWrapper hide inflections"><div>');

	// Mode scopus

	foreach($modes as $mode){


		// Start the table
		pOut('<div class="floatleft" style="width: 300px;margin-right: 4px;"><table class="verbs">
				<tr class="temps"><td colspan=2><b>'.strtoupper($mode->name).'</b></td></tr>');

		// Get numbers
		$numbers = pGetNumbers($word->type_id);

		// Number scopus
		foreach($numbers as $number){


			// Getting the submodes
			$submodes = pGetSubModes($word->type_id);

			pOut('<tr><td class="number" colspan=2><b>'.$number->name.'</b></td></tr>');


			foreach($submodes as $submode){

				$td_contents = "";

				$td_contents = '<tr><td class="singa"><em>'.$submode->name.'</em></td>';


				$gotten_classifications = array();
				$show_classification_name = false;

				// Does this word need to be inflected by its classification types?
				if($type->inflect_classifications == 1){
					// YES we need to get all the classifications
					$gotten_classifications = pGetClassifications($type->id);
					$show_classification_name = true;
				}
				else{
					// Just getting one for the sake of the loop!
					$gotten_classifications = pGetClassifications($type->id, true, $word->classification_id);
				}





				if($show_classification_name)
					$td_contents .= '<td class="sing" style="padding: 0!important;margin:0px;">';
				else
					$td_contents .= '<td class="sing" style="width:50%">';

				if($show_classification_name)
						$td_contents .= '<table class="verbs" style="margin:0px;">';

				foreach ($gotten_classifications as $inflect_classification) {
					// The main inflection time!


					if($show_classification_name)
						$td_contents .= "<tr><td style='width:50%' class='classa'>".$inflect_classification->name."</td><td class='singb' style='width:50%'>";

					

					$inflections = pGetInflections($word->id, $word->type_id, $inflect_classification->id, $mode->id, $submode->id, $number->id, $word->subclassification_id);
					if($inflections->rowCount() == 0)
						$td_contents .= "<span class='native'>".pInflect($word->id, false, $number->id, $mode->id, $submode->id, $word->classification_id, 0, '', 0, $word->subclassification_id)."</span><br />";
					else{
						while($inflection = $inflections->fetchObject()){
							$td_contents .= "<span class='native'>".pInflect($word->id, $inflection, $number->id, $mode->id, $submode->id, $word->classification_id, 0, '', 0, $word->subclassification_id)."</span><br />";
						}

					}

					if($show_classification_name)
						$td_contents .= "</td>";




				}

				if($show_classification_name)
					$td_contents .= '</table>';


					$td_contents .= '</td></tr>';



				if (strpos($td_contents, '@[[DISABLE]]') === false) {
				    pOut($td_contents);
				}


			}
		}

						pOut('</table></div>');


	}

		pOut("<br id='cl' /></div></div><br />");


		//  Getting search and return language
		$dict = explode('_', $_SESSION['search_language']);
		$slang = $dict[0];


		// Getting the translations of the original term, if needed
		if($word->derivation_clonetranslations == 1)
			$all_translations = pGetTranslationsByLang($word->id, $slang, true, $word->derivation_of);
		else
			$all_translations = pGetTranslationsByLang($word->id, $slang);
		



		if($all_translations->rowCount() != 0)
		{
		
			pOut('<span class="pSectionTitle extra">Meaning and translations</span><div class="pSectionWrapper">');
			while($show_translation = $all_translations->fetchObject())
			{

				$trans_array[$show_translation->language_id][] = $show_translation;
			}

		}

		
		foreach($lang_array as $lang){

			if(!@empty($trans_array[$lang->id]) and !pDisabledLanguage($lang->id))
			{
				if($lang->id == 0)
					pOut("<span class='pSectionTitle extra sub'>other meanings/alternate forms in <img class='pFlag' src='".pUrl('pol://library/flags/'.$lang->flag.'.png')."' /> 	".$lang->name."</span><ol>");
				else
					pOut("<span class='pSectionTitle extra sub'>Translations into <img class='pFlag' src='".pUrl('pol://library/flags/'.$lang->flag.'.png')."' /> 	".$lang->name."</span><ol>");
				foreach($trans_array[$lang->id] as $trans){
					pOut('<li><span>'.(($trans->specification != '') ? (' <em>('.$trans->specification.')</em>') : ('')).' <span href="javascript:void(0);" class="translation trans_'.$trans->id.' tooltip">'.$trans->translation.'</span>');
					if($description = html_entity_decode(pGetDescription($trans->translation_id)))
						pOut("<br /><p class='desc'>".$description."</p>");
					pOut('</span></li>');
				}
				pOut("</ol>");
			}

		}

		pOut("</div></div>");

		// Idioms

		$idioms = pGetIdiomsOfWords($word->id);
		if($idioms->rowCount() != 0){
			pOut('<span class="pSectionTitle extra">Idiom and examples</span><div class="pSectionWrapper"><ol>');
			while($idiom = $idioms->fetchObject()){
				pOut('<li><span class="pIdiom">'.pHighlight($idiom->keyword, $idiom->idiom, '<span class="pIdiomHighlight">', '</span>'));
				$translations_idiom = pGetTranslationOfIdiomByLang($idiom->idiom_id);
				if($translations_idiom->rowCount() != 0){
					pOut("<br />");
					$count = 0;
					$max_count = $translations_idiom->rowCount();
					foreach ($translations_idiom as $trans_idiom) {
						pOut("<span class='pIdiomTranslation'><em>".pLanguageName($trans_idiom['language_id'])."</em> ".$trans_idiom['translation']."</span>");
						$count++;
						if($count > $max_count)
						{
							pOut(', ');
						}
					}
				}
				pOut('</span></li>'); 
			}
			pOut('</ol></div>');
		}

		// Derivations
		$derivations = pGetDerivations($word->id);
		if(!($derivations == false))
		{

	
			pOut('<span class="pSectionTitle extra">Derived terms</span><div class="pSectionWrapper">');

			$i = 0;
			$max_i = count($derivations);
			foreach($derivations as $derivation){

				pOut("<strong class='pDerivationName'>".$derivation['name']."</strong><ol class='inline'>");

				foreach ($derivation['words'] as $derivation_word) {

					pOut('<li><span class="native"><strong class="pDerivation"><a href="'.pUrl('?word='.$derivation_word->id).'">'.$derivation_word->native.'</a></strong></span></li>');
				}

				pOut("</ol>");

				if($i != $max_i OR $max_i == 1)
					pOut("<BR />");
				$i++;

			}

			pOut('</div>');
		}


	// The origin

	$etymologies =  pGetEtymology($word->id);

	if($etymologies->rowCount() != 0)
	{

		pOut('<span class="pSectionTitle extra">Etymology</span><div class="pSectionWrapper">');

			// For each synonym show the block
			$count = 1;
			while($etymology = $etymologies->fetchObject()){
				pOut("<span class='title extra sub'>".$count.".</span>");
				pOut($etymology->desc);
				if($etymology->cognates_native != '' OR $etymology->cognates_translations != '')
					pOut("<br /><br /><strong>Cognates</strong>: ");
				$count++;
			}

		pOut('</div>');
	}


	// The synonyms

	$synonyms =  pGetSynonyms($word->id);

	if($synonyms->rowCount() != 0)
	{

		pOut('<span class="pSectionTitle extra">Synonyms</span><div class="pSectionWrapper">');

			// For each synonym show the block

			while($synonym = $synonyms->fetchObject()){

				if($synonym->word_id_1 == $word->id)
					$synonym_id = $synonym->word_id_2;
				else
					$synonym_id = $synonym->word_id_1;

				$synonym_word = pGetWord($synonym_id);

				pOut('<a class="synonym score-'.$synonym->score.'" href="'.pUrl('?word='.$synonym_word->id).'"><span class="native">'.$synonym_word ->native.'</span></a>');

			}

		pOut('</div>');
	}

	// The antonyms

	$antonyms =  pGetAntonyms($word->id);

	if($antonyms->rowCount() != 0)
	{

		pOut('<span class="pSectionTitle extra">Antonyms</span><div class="pSectionWrapper">');

			// For each antonym show the block

			while($antonym = $antonyms->fetchObject()){

				if($antonym->word_id_1 == $word->id)
					$antonym_id = $antonym->word_id_2;
				else
					$antonym_id = $antonym->word_id_1;

				$antonym_word = pGetWord($antonym_id);

				pOut('<a class="antonym score-'.$antonym->score.'" href="'.pUrl('?word='.$antonym_word->id).'"><span class="native">'.$antonym_word ->native.'</span></a>');

			}

		pOut('</div>');
	}


	// Regetting older results as a help

	if(isset($_SESSION['wholeword'])){
		pOut("<script>$('#wholeword').attr('checked', ".$_SESSION['wholeword'].");</script>");
	}

	if(isset($_GET['searchresult']))
	{
		pOut("<script>$(document).ready(function(){

			".'$(".moveResults").load("'.pUrl('?getword&wordsonly&ajax').'", {"word": $("#wordsearch").val(), "dict": $("#dictionary").val(), "wholeword":  $("#wholeword").is(":checked")}, function(){
					$(".dWordWrapper ol p.desc").hide();
			});'."

		});</script>");
	}

	

	pOut('<script>
      	$("#searchb").click(function(){
      		$("#pageload i").show();
      		$(".ajaxload").slideUp();
      		$(".drop").hide();
	      		$(".ajaxload").load("'.pUrl('?getword&ajax&wordsearch='.$_GET['word']).'", {"word": $("#wordsearch").val(), "dict": $("#dictionary").val(), "wholeword":  $("#wholeword").is(":checked")}, function(){$(".ajaxload").slideDown(function(){
	      								 $("#pageload i").delay(100).hide(400);
	      		})}, function(){
      		});
      		if($("#wordsearch").val() != ""){
      			window.history.pushState("string", "", "index.php?search=" + $("#wordsearch").val());
      		}
      		else{
      			window.history.pushState("string", "", "index.php?home");
      		}
      		
      	});
      </script>'."<script>
		$(document).ready(function(){

			$('.drop').slideDown();

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
			});</script>");

}

	pOut('</div></td></tr></table><br id="cl"/>');

	pOut('<br id="cl" />');



if(isset($_REQUEST['ajaxOUT']))
	die();

 ?>


