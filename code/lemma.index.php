
<?php 

// Do we need to load a different page?

if(isset($_REQUEST['discuss-lemma']) and pLogged()){
	require_once pFromRoot('code/lemma.discussion.php');
	return;
}

if(isset($_REQUEST['edit-lemma']) and !pLogged())
	pUrl("?lemma=".$_REQUEST['edit-lemma']);

if(isset($_REQUEST['edit-lemma']) and isset($_REQUEST['ajax']) AND isset($_REQUEST['action']) and pLogged()){
	if(file_exists(pFromRoot('code/lemma.edit_'.$_REQUEST['action'].'.php')))require_once pFromRoot('code/lemma.edit_'.$_REQUEST['action'].'.php');
	else
		pUrl('', true);
	return;
}

if(isset($_REQUEST['edit-lemma']) and !isset($_REQUEST['lemma'])){
	$_REQUEST['lemma'] = $_REQUEST['edit-lemma'];
	$editMode = true;
	$edit_field = "edit-field";
	$edit = '<i class="edit fa fa-10 fa-pencil-square-o "></i>';
}
else{
	$editMode = false;
	$edit_field = "";
	$edit = "";
}

// Get lemma
if($_REQUEST['lemma'] == '')
	pUrl('', true);
if(is_numeric($_REQUEST['lemma']))
	$lemma = $_REQUEST['lemma'];
elseif(ctype_alnum($_REQUEST['lemma'])){
	$lemma = pHashId($_REQUEST['lemma'], true);
	if(array_key_exists(0, $lemma))
		$lemma = $lemma[0];
	else
		$lemma = 0;
}


if(isset($_REQUEST['edit-lemma'],$_REQUEST['ajax'], $_REQUEST['delete']))
	if(pDeleteLemma($lemma))
		return die(pUrl('?home', true));


// Search area
if(isset($_GET['searchresult']))
	pDictionaryHeader($_SESSION['search']);
else
	pDictionaryHeader('');

// Start the layout stuff

pOut('<div class="home-margin"><div class="notice hide" style="display: none;" id="loading"><i class="fa fa-spinner fa-spin"></i> '.LOADING.'</div>
	<br id="cl loading" style="display: none;"/><div class="ajaxload" style="display: none;"></div>
      <div class="drop">');


$rs = pQuery("SELECT * FROM words WHERE id = ".$lemma."");

$word = $rs->fetchObject();

if($word == false){
	pOut('<div class="notice danger-notice" id="empty"><i class="fa fa-warning"></i> '.LEMMA_ERROR.' (Lemma: '.pHashId($lemma).')</div>');
	goto end;
}
else{
	// Page title, we may need to force it through again later
	$donut['page']['title'] = $word->native." - ".$donut['page']['title']; 
	
	// Lemma-code
	pOut('<a class="lemma-code float-right" href="'.pUrl('?lemma='.pHashId($word->id)).'"title="'.$word->id.'"><i class="fa fa-bookmark-o"></i> '.pHashId($word->id).'</a>');
	// Discussion button
	if(pLogged())
		pOut('
			<a class="lemma-code discussion float-right" href="'.pUrl('?edit-lemma='.$_REQUEST['lemma']).'"><i class="fa fa-pencil-square-o "></i> '.LEMMA_EDIT.'</a><a class="lemma-code discussion float-right" href="'.pUrl('?discuss-lemma='.$_REQUEST['lemma']).'"><i class="fa fa-comments"></i> '.WD_TITLE.'</a>
			');

	// Title



	if(!isset($_REQUEST['ajaxOUT']))
		if(isset($_GET['searchresult']))
			$url = "href='".pUrl('?home&search='.urlencode($_SESSION['search']))."'";

		elseif(isset($_GET['alphabetresult']))
			$url = "href='".pUrl('?alphabet='.$_GET['alphabetresult'])."'";
		else
			$url = ((isset($_SERVER['HTTP_REFERER'])) ? ("href='".$_SERVER['HTTP_REFERER']."'") : 'href="javascript:void();" onClick="window.history.back();"');

}

		if($editMode)
			pOut('<div class="title">'.LEMMA_EDIT_MODE.'</div><br />'."<a class='float-left back-mini search' href='".((isset($_REQUEST['action']) ? pUrl('?edit-lemma='.$_REQUEST['edit-lemma']) : pUrl('?lemma='.$_REQUEST['edit-lemma'])))."'><i class='fa fa-arrow-left' ></i></a>");

if($word != false AND $editMode and !isset($_REQUEST['ajax']) AND isset($_REQUEST['action']) and pLogged()){
	if(file_exists(pFromRoot('code/lemma.edit_'.$_REQUEST['action'].'.php')))
		require_once pFromRoot('code/lemma.edit_'.$_REQUEST['action'].'.php');
	else
		pUrl('', true);
}
else{

	
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
			$derivation_term = "<br /><span class='pDerivationTitle'><em>".$derivation_name."</em> from <span class='native'><a href='".pUrl('?lemma='.pHashId($derivation_word->id))."'>".$derivation_word->native."</a></span></span>";
		}

		
		// Get word type information
			$type = pGetType($word->type_id);
			$classification = pGetClassification($word->classification_id);


			pOut("<span class='pSectionTitle float-left first ".(($editMode) ? "editing" : "")."'><strong class='pWord $edit_field' id='ajax_dov_".$word->id."'><a href='".(($editMode) ? pUrl('?edit-lemma='.$_REQUEST['lemma']."&action=basics") : pUrl('?lemma='.$_REQUEST['lemma']))."'><span class='native'>".html_entity_decode($word->native)."</span> ".$edit."</a></strong>$derivation_term<br />");

			// Show the type and classification
			if(!$editMode)
			pOut('<em class="info"><span class="tooltip '.$edit_field.'" >'.$type->name.' '.$edit.'</span></em> ');
			else
				pOut('<em class="info"><a href="'.pUrl('?edit-lemma='.$_REQUEST['lemma']."&action=basics").'" class="tooltip '.$edit_field.'" >'.$type->name.' '.$edit.'</a></em> ');
			if(!pTypeInflectClassifications($type->id) and $type->inflect_not != 1)
				if($editMode)
					pOut('<em class="info"><a href="'.pUrl('?edit-lemma='.$_REQUEST['lemma']."&action=basics").'" class="tooltip '.$edit_field.'" >'.$classification->name.' '.$edit.'</a></em>');
				else
					pOut('<em class="info"><span class="tooltip '.$edit_field.'" >'.$classification->name.' '.$edit.'</span></em>');

			if($word->subclassification_id != 0 and !pTypeInflectClassifications($type->id) and $type->inflect_not != 1)
				if($editMode)
					pOut('<em class="info"><a href="'.pUrl('?edit-lemma='.$_REQUEST['lemma']."&action=basics").'" class="tooltip " >'.pSubclassificationName($word->subclassification_id).' '.$edit.'</a></em>');
				else
					pOut('<span class="tooltip" ><em class="info">'.pSubclassificationName($word->subclassification_id).'</em></span>');

			if($editMode)
			pOut('<br /><div class="deleteLoad"></div><a class="red-link small delete-lemma first" href="javascript:void(0);" onClick="
					$(this).removeClass(\'first\');
					if (confirm(\''.LEMMA_EDIT_DELETE_SURE.'\') == true) {
			    		$(\'.deleteLoad\').load(\''.pUrl(
			    			'?edit-lemma='.$_REQUEST['edit-lemma'].'&ajax&delete').'\');
					}">'.LEMMA_EDIT_DELETE.'</a>
				<script>

				};
			</script>');


			pOut('</span><br id="cl" />');

	// Back buttons
		if(!$editMode)
			pOut("<a class='float-left back-mini' ".$url."'><i class='fa fa-arrow-left' ></i></a><br />");

	// IPA & AUDIO

			if($word->ipa != '')
				$ipa_s = "/".html_entity_decode(($word->ipa))."/";
			else
				$ipa_s = "<em>No IPA transcription found</em>";

			if($word->ipa != '' OR $editMode)
				if($editMode)
					pOut('<span class="pSectionTitle extra editing">Pronounciation</span><div  style="position: relative;" class="pSectionWrapper editing"><a href="'.pUrl('?edit-lemma='.$_REQUEST['edit-lemma']."&action=ipa").'"class="pPron tooltip '.$edit_field.'">'.$ipa_s.$edit.'</a>'); 
				else
					pOut('<span class="pSectionTitle extra '.(($editMode) ? "editing" : "").'">Pronounciation</span><div  style="position: relative;" class="pSectionWrapper '.(($editMode AND $word->ipa == '') ? "editing" : "").'"><span class="pPron">/'.html_entity_decode(($word->ipa)).'/</span>'); 

			// Image
			if($word->image != '')
				pOut('<img class="pImage '.($editMode ? 'editing' : '').'" src="'.pUrl('pol://library/images/entries/'.html_entity_decode(($word->image))).'"/>');

			// Audio
			pOut(pGetAudioPlayers($word->id));

			pOut('</div>');	

// The inflection!
	if(!$editMode and $type->inflect_not != 1)
		pOut('<span style="cursor: pointer;" onClick="$(\'.inflections_title\').toggleClass(\'closed\');$(\'.inflections\').slideToggle(\'fast\');$(\'.hideIconInflect\').toggle();$(\'.showIconInflect\').toggle(function(){
		});"><span class="pSectionTitle extra closed inflections_title">'.LEMMA_INFLECTIONS.'<span class="showIconInflect"><i class="fa fa-12 fa-chevron-down "></i></span><span class="hideIconInflect hide"><i class="fa fa-12 fa-chevron-up "></i> </span></span></span><div class="pSectionWrapper hide inflections"><div>'.pAllInflections($word, $type)."<br id='cl' /></div></div><br />");
	elseif($type->inflect_not != 1 and $editMode)
		pOut("<span class='pSectionTitle extra editing'>".LEMMA_INFLECTIONS."</span><div class='pSectionWrapper editing'>
				".(($stems = pEL_StemOverview($lemma) AND $stems != '') ? "<strong class='small-caps' style='font-weight: bolder!important;'> Irregular stems:</strong> <span class='native tooltip'>".$stems."</span>	<br /><br />" : '')."
				".(($stems = pEL_IrregularOverview($lemma) AND $stems != '') ? "<strong class='small-caps' style='font-weight: bolder!important;'> Irregular forms:</strong> <span class='native tooltip'>".$stems."</span>	<br /><br />" : '')."
				<a class='actionbutton editing' href='".pUrl('?edit-lemma='.$_REQUEST['lemma'].'&action=stems')."'><i class='fa fa-tree'></i> ".LEMMA_EDIT_STEMS."</a>
				<a class='actionbutton editing' href='".pUrl('?edit-lemma='.$_REQUEST['lemma'].'&irregular')."'><i class='fa fa-sliders'></i> ".LEMMA_EDIT_IRREGULAR."</a>
				</div>");


// The Translations

		if(!$editMode){
			$slang = 0; 
			//  Getting search and return language
			if(isset($_SESSION['search_language']) and isset($_REQUEST['searchresult'])){
				$dict = explode('_', $_SESSION['search_language']);
				if($dict[0] != 0)
					$slang = $dict[0];
				else
					$slang = $dict[1];
			}
		}
		else
			$slang = pEditorLanguage($_SESSION['pUser']);



		// Getting the translations of the original term, if needed
		if($word->derivation_clonetranslations == 1)
			$all_translations = pGetTranslationsByLang($word->id, $slang, true, $word->derivation_of);
		else
			$all_translations = pGetTranslationsByLang($word->id, $slang);
		



		if($all_translations->rowCount() != 0 OR $editMode)
		{
		
			pOut('<span class="pSectionTitle extra '.(($editMode) ? "editing" : "").'">'.LEMMA_TRANSLATIONS.'</span><div class="pSectionWrapper '.(($editMode) ? "editing" : "").'">');

			$edit_button = "";

			if($editMode)
					$edit_button = "<a class='float-right actionbutton editing' href='".pUrl('?translate='.$word->id)."'><i class='fa fa-language'></i> ".LEMMA_EDIT_TRANSLATIONS."</a>";

			while($show_translation = $all_translations->fetchObject())
				$trans_array[$show_translation->language_id][] = $show_translation;


		}

		$check = 0;
		foreach($lang_array as $lang){

			if(!@empty($trans_array[$lang->id]) and !pDisabledLanguage($lang->id) )
			{

				$check++;

				if($lang->id == 0)
					pOut("<span class='pSectionTitle extra sub".(($editMode) ? "editing" : "")."'>".sprintf(LEMMA_TRANSLATIONS_MEANINGS, "<img class='pFlag' src='".pUrl('pol://library/images/flags/'.$lang->flag.'.png')."' /> ".$lang->name)."</span><ol>");
				else
					pOut("<span class='pSectionTitle extra sub ".(($editMode) ? "editing" : "")."'>".sprintf(LEMMA_TRANSLATIONS_INTO, "<img class='pFlag' src='".pUrl('pol://library/images/flags/'.$lang->flag.'.png')."' /> ".$lang->name)."</span><ol>");


				pOut($edit_button);

				foreach($trans_array[$lang->id] as $trans){
					pOut('<li><span>'.(($trans->specification != '') ? (' <em>('.$trans->specification.')</em>') : ('')).' <span href="javascript:void(0);" class="translation trans_'.$trans->translation_id.' tooltip">'.$trans->translation.'</span>');
					if($description = html_entity_decode(pGetDescription($trans->translation_id)))
						pOut("<p class='desc'>".$description."</p>");
					pOut('</span></li>');
				}
				pOut("</ol>");
			}

		}

		if($check == 0){
			pOut("<a class='actionbutton editing' href='".pUrl('?translate='.$_REQUEST['lemma'].'&stems')."'><i class='fa fa-language'></i> ".LEMMA_EDIT_TRANSLATIONS."</a> ");
		}


		if($all_translations->rowCount() != 0 OR $editMode)
			pOut("</div>");

// Idioms

		$idioms = pGetIdiomsOfWords($word->id);
		if($idioms->rowCount() != 0 OR $editMode){
			pOut('<span class="pSectionTitle extra '.(($editMode) ? "editing" : "").'">Idiom and examples</span><div class="pSectionWrapper '.(($editMode) ? "editing" : "").'">');
			if(!$editMode){
				pOut("<ol>");
				while($idiom = $idioms->fetchObject()){
					pOut('<li><span class="pIdiom native">'.pHighlight($idiom->keyword, pMarkDownParse($idiom->idiom), '<span class="pIdiomHighlight">', '</span>'));
					$translations_idiom = pGetTranslationOfIdiomByLang($idiom->idiom_id);
					// Audio
					$audio_p = pGetAudioPlayers($idiom->idiom_id, true);
					if($audio_p != false)
						pOut($audio_p);
					if($translations_idiom->rowCount() != 0){
						pOut("<br />");
						$count = 0;
						$max_count = $translations_idiom->rowCount();
						while ($trans_idiom = $translations_idiom->fetchObject()) {
							pOut("<span class='pIdiomTranslation'><em>".pLanguageName($trans_idiom->language_id)."</em> ".$trans_idiom->translation."</span>");
							$count++;
							if($count > $max_count)
							{
								pOut(', ');
							}
						}
					}
					pOut('</span></li>'); 
				}
				pOut('</ol>');
				pOut("</div>");
			}
			else{
				pOut("<table class='admin editing' style='width: 100%;'>
						<thead>
						<tr class='title' style=''>
							<td style='width: 60%;'><i class='fa fa-quote-left xsmall'></i>  Example / idiom</td>
							<td><i class='fa xsmall fa-link'></i> Linked on</td>
						</tr></thead>
						<tbody>");
				if($idioms->rowCount() == 0)
					pOut("<tr><td>No examples found</td><td> - </td></tr></tbody></table>");
				else
					while($idiom = $idioms->fetchObject())
						pOut("<tr class='ipa'>
								<td>
	
										<a class='float-right actionbutton editing editing xsmall'><i class='fa fa-pencil-square-o'></i> Edit example</a>
										<a class='tooltip float-right editing small'><i class='fa fa-volume-up'></i> Audio</a>
										<a class='tooltip small editing float-right'><i class='fa fa-language'></i> Translations  </a>  
									<span class='pIdiom native'>".pHighlight($idiom->keyword, pMarkDownParse($idiom->idiom), '<span class="pIdiomHighlight">', '</span>')."</span>
								</td>
								<td>
									<a class='float-right actionbutton editing xsmall'><i class='fa fa-times'></i> Delete link</a>
									<a class='float-right actionbutton editing xsmall'><i class='fa fa-pencil-square-o'></i> Edit link</a>
									<span class='native pIdiom'>$idiom->keyword</span>
								</td>
							</tr>");


				pOut("</tbody></table>");

				pOut("<a class='actionbutton editing' href='".pUrl('?edit-lemma='.$_REQUEST['lemma'].'&action=new-idiom')."'><i class='fa fa-plus xsmall'></i> Add new example</a><a class='actionbutton editing' href='".pUrl('?edit-lemma='.$_REQUEST['lemma'].'&action=new-idiom')."'><i class='fa fa-external-link xsmall'></i> Link an existing example</a>");

			}
			pOut("</div>");
		}
		
usage_notes:
// Usage notes

		$usage_notes = pGetUsageNotes($word->id);

		if($usage_notes->rowCount() == 0 and !$editMode)
			goto derivations;

		$note = $usage_notes->fetchObject();

		pOut("<span class='pSectionTitle extra'>
				".LEMMA_USAGE_NOTES."
			</span>

			<div class='pSectionWrapper'>

				<div class='pNotes'>

						".($usage_notes->rowCount() == 0 ? '' :pMarkDownParse($note->note))."

				</div>

			</div>");

derivations:
// Derivations
		$derivations = pGetDerivations($word->id);
		if(!($derivations == false) AND !$editMode)
		{

	
			pOut('<span class="pSectionTitle extra">Derived terms</span><div class="pSectionWrapper">');

			$i = 0;
			$max_i = count($derivations);

			foreach($derivations as $derivation){

				pOut("<strong class='pDerivationName'>".$derivation['name']."</strong><ol class='inline'>");

				foreach ($derivation['words'] as $derivation_word) {

					pOut('<li><span class="native"><strong class="pDerivation"><a href="'.pUrl('?lemma='.pHashId($derivation_word->id)).'">'.$derivation_word->native.'</a></strong></span></li>');
				}

				pOut("</ol>");

				if($i != $max_i OR $max_i == 1)
					pOut("<BR />");
				$i++;

			}
			pOut("</div>");
		}
		elseif($editMode){
			pOut("
				<span class='pSectionTitle editing extra'>Derived terms</span><div class='pSectionWrapper editing'>
				<table class='admin editing' style='width: 100%;'>
								<thead>
								<tr class='title' style=''>
									<td style='width: 14%;'><i class='fa fa-folder xsmall'></i>  Type of derivation</td>
									<td><i class='fa fa-bookmark-o xsmall'></i> Lemma</td>
								</tr></thead>
								<tbody>");
						if($derivations == false)
							pOut("<tr><td>No derivations found</td><td> - </td></tr></tbody></table>");

						else{
							foreach($derivations as $derv)
								foreach($derv['words'] as $derivation_word)
								pOut("<tr class='ipa'>
										<td>
												".$derv['name']."
										</td>
										<td>	
										".pWordLinks("[[".$derivation_word->id."]]")."
												<a class='float-right actionbutton editing xsmall'><i class='fa fa-times'></i> Delete link</a>
											<a class='float-right actionbutton editing xsmall'><i class='fa fa-pencil-square-o'></i> Edit link</a> 
											
										</td>
									</tr>");


							pOut("</tbody></table>");

		
					}
			pOut("<a class='actionbutton editing' href='".pUrl('?edit-lemma='.$_REQUEST['lemma'].'&action=new-idiom')."'><i class='fa fa-external-link xsmall'></i> Link derivation</a>");

			pOut("</div>");
		}


// The origin

	$etymologies =  pGetEtymology($word->id);

	if($etymologies->rowCount() != 0)
	{

		pOut('<span class="pSectionTitle extra">Etymology</span><div class="pSectionWrapper">');

			// For each synonym show the block
		$count = 1;
			while($etymology = $etymologies->fetchObject()){
				pOut("<span class='pSectionTitle extra sub'>$count. <em class='small'>First attested in $etymology->first_attestation:</em></span><span class='pEtymology'>".pMarkDownParse($etymology->desc).'</span>');
				if($etymology->cognates_native != '' OR $etymology->cognates_translations != ''){
					pOut("<span class='pCognates'><strong>Cognates</strong>: ");
					$nat  = explode(',', $etymology->cognates_native);
					$trans = explode(',', $etymology->cognates_translations);
					$save = array();
					foreach($nat as $nat_c)
						if($nat_c != '')
							$save[] = pWordLinks("[[".$nat_c."]]");
					foreach($trans as $trans_c){
						$trans_c = pGetTranslation($trans_c);
						if($trans_c != '')
							$save[] = $trans_c->translation." (".pLanguageName($trans_c->language_id).")";
					}
					pOut(implode(', ', $save));
					pOut("</span>");
				}
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

				pOut('<a class="synonym score-'.$synonym->score.'" href="'.pUrl('?lemma='.pHashId($synonym_word->id)).'"><span class="native">'.$synonym_word ->native.'</span></a>');

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

				pOut('<a class="antonym score-'.$antonym->score.'" href="'.pUrl('?lemma='.pHashId($antonym_word->id)).'"><span class="native">'.$antonym_word ->native.'</span></a>');

			}

		pOut('</div>');
	}


	// // Re-getting older results as a help

	// if(isset($_SESSION['wholeword'])){
	// 	pOut("<script>$('#wholeword').attr('checked', ".$_SESSION['wholeword'].");</script>");
	// }

	// if(isset($_GET['searchresult']))
	// {
	// 	pOut("<script>$(document).ready(function(){

	// 		".'$(".moveResults").load("'.pUrl('?getword&wordsonly&ajax').'", {"word": $("#wordsearch").val(), "dict": $("#dictionary").val(), "wholeword":  $("#wholeword").is(":checked")}, function(){
	// 				$(".dWordWrapper ol p.desc").hide();
	// 				document.title = "'.pEscape($donut['page']['title']).'";
	// 		});

	// 		});</script>');
	// }

}

end:

// Search script

pOut(pSearchScript("&wordsearch=".$_REQUEST['lemma']));
pOut('</div></div>');
if(isset($_REQUEST['ajaxOUT']))
	die();

 ?>


