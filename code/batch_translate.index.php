<?php

if(!pLogged())
	pUrl('', true);



function pTranslateCard($show_skip = true){

	global $percentage, $num, $hide, $lang_zero, $editor_lang, $text, $word, $cnt, $offset;

	pOut('

				<div class="btCard card-'.$num.' hide">

					<div class="btTitle">Translate

					</div>
							<div class="btPBAR" style="width: '.$percentage.'%"></div>
					<div class="btSource"><span class="btLanguage"><img class="btFlag" src="'.pUrl('pol://library/images/flags/'.$lang_zero->flag.'.png').'" /> '.$lang_zero->name.'</span><br /><span class="btNative"><a target="_blank" href="'.pUrl('?word='.$word->id).'"><strong><span class="native">'.$word->native.'</span></strong></a></span> <span class="btInfo">'.$text.'</span></span></div>

					
					<div class="btTranslate"><span class="btLanguage"><img class="btFlag" src="'.pUrl('pol://library/images/flags/'.$editor_lang->flag.'.png').'" /> '.$editor_lang->name.'</span><br /><textarea placeholder="" class="elastic nWord btInput translations-'.$num.'"></textarea><br /></div>

					<div class="notice small"><i class="fa fa-info-circle" style="font-size: 10px!important;"></i> Enter as many translations as needed, the format is: <span class="outertag">Translation</span> or <span class="outertag">Translation|Specification</span>. <br /><em>Specifications are optional and can be added later on as well.</em></div>

					<div class="btButtonBar">
					<a class="btAction green submit-'.$num.'" href="javascript:void(0);">Submit</i> </a> 
					'.(($show_skip == true) ? '<a class="btAction skip-'.$num.'" href="javascript:void(0);">Skip</a> ' : '').'
					<a class="btAction never-'.$num.'" href="javascript:void(0);" style="float: left;">Untranslatable</a> 
					<br id="cl"/>

					<script>
					$(document).ready(function(){

						$(".translations-'.$num.'").tagsInput({
							"defaultText": "Add a translation"
						});

						$(".translations-'.$num.'").elastic();

					});
					$(".submit-'.$num.'").click(function(){
						$(".btLoadAjaxHere").load("'.pUrl('?batch_translate&ajax&translate').'", {"word_id": "'.$word->id.'", translations: $(".translations-'.$num.'").val()});
						$(".card-'.($num + 1).'").slideDown(1000);
						$(".card-'.$num.'").slideUp(1000);

						'.(($num == $cnt) ? 'loadfunction("'.pUrl('?batch_translate&next').'")' : '').'
						
					});
					$(".skip-'.$num.'").click(function(){
						$(".card-'.($num + 1).'").slideDown();
						$(".card-'.$num.'").slideUp();

						$(".btLoadAjaxHere").load("'.pUrl('?batch_translate&ajax&skip='.$word->id).'")
						
						'.(($num == $cnt) ? '
							loadfunction("'.pUrl('?batch_translate&next').'")' : '').'
						
						
					});

					$(".never-'.$num.'").click(function(){

						$(".btLoadAjaxHere").load("'.pUrl('?batch_translate&ajax&untranslatable&word_id='.$word->id).'");

						$(".card-'.($num + 1).'").slideDown();
						$(".card-'.$num.'").slideUp();

						
					});


					</script>

					</div>
				</div>'."<script> $('.tooltip').tooltipster({theme: 'tooltipster-noir'}); });</script>");


}


if(isset($_REQUEST['ajax'], $_REQUEST['word_id'], $_REQUEST['translate'], $_REQUEST['translations'])){


	// Calling the appropiate function

	pAddTranslations($_REQUEST['translations'], $_REQUEST['word_id']);

}

elseif(isset($_REQUEST['ajax'], $_REQUEST['untranslatable'], $_REQUEST['word_id'])){

	pQuery("INSERT INTO translation_exceptions VALUES (NULL, '".$_REQUEST['word_id']."', '".pEditorLanguage($_SESSION['pUser'])."', '".$_SESSION['pUser']."');");

	die();

}

if (isset($_REQUEST['ajax'], $_REQUEST['skip'])) {
	
	$_SESSION['skip_bt'][] = $_REQUEST['skip'];

}

if(!isset($_REQUEST['next']) and !isset($_REQUEST['ajax']))
	$_SESSION['skip_bt'] = array();



$lang_zero = pGetLanguageZero();
$editor_lang = pGetLanguage(pEditorLanguage($_SESSION['pUser']));


if(isset($_REQUEST['translate']) and !isset($_REQUEST['ajax']) and is_numeric($_REQUEST['translate'])){


	pOut('<span class="title_header"><div class="icon-box white-icon"><i class="fa fa-language"></i></div> '.BTRANS_TITLE_SINGLE.$editor_lang->name.'</span><br /><br />', true);

	$percentage = 0;
	$num = 1;
	$offset = 0;
	$hide = ""; 

	$word = pGetWord($_REQUEST['translate']);
	$type = pGetType($word->type_id);
	$classification = pGetClassification($word->classification_id);

	// Show the type and classification
	$text = '<em><a href="javascript:void(0);"  class="tooltip" title="'.htmlentities($type->name).'">'.html_entity_decode($type->short_name).'</a></em> ';
	if(!pTypeInflectClassifications($type->id))
		$text .= '<em><a href="javascript:void(0);" class="tooltip"  title="'.htmlentities($classification->name).'">'.html_entity_decode($classification->short_name).'</a></em> ';
	if($word->subclassification_id != 0 and !pTypeInflectClassifications($type->id)){
		$subclassification = pGetSubclassification($word->subclassification_id);
		$text .= '<em><a href="javascript:void(0);"  class="tooltip" title="'.htmlentities($subclassification->name).'">'.html_entity_decode($subclassification->short_name).'</a></em> ';
	}

	pOut('<div class="btLoadAjaxHere"></div>');


	pTranslateCard(false);

		pOut("<script> $(document).ready(function() { $('.card-1').slideDown();});</script>");

	return;

}



pOut('<span class="title_header"><div class="icon-box white-icon"><i class="fa fa-language"></i></div> '.BTRANS_TITLE.$editor_lang->name.'</span><br /><br />', true);


// Let's get our words, limit of 10

$count_all = $words = pQuery("SELECT COUNT(DISTINCT words.id) AS cnt
FROM words
JOIN translation_words 
JOIN translations ON translations.id = translation_words.translation_id
WHERE (translation_words.id IS NULL 
OR (translation_words.id IS NOT NULL AND NOT EXISTS (SELECT * FROM translation_words JOIN translations ON translations.id = translation_words.translation_id WHERE translation_words.word_id = words.id AND translations.language_id = ".$editor_lang->id.")  
) AND NOT EXISTS (SELECT * FROM translation_exceptions WHERE word_id = words.id AND language_id = ".$editor_lang->id." AND user_id = ".$_SESSION['pUser'].")) AND words.id NOT IN ( '" . implode($_SESSION['skip_bt'], "', '") . "' );");


$cnt_all = $count_all->fetchObject();


pOut("<center><span class='btLanguage'>There are ".$cnt_all->cnt." words left to translate.</span></center>");

$words = pQuery("SELECT DISTINCT words.id, words.native, words.classification_id, words.type_id, words.subclassification_id 
FROM words
JOIN translation_words 
JOIN translations ON translations.id = translation_words.translation_id
WHERE (translation_words.id IS NULL 
OR (translation_words.id IS NOT NULL AND NOT EXISTS (SELECT * FROM translation_words JOIN translations ON translations.id = translation_words.translation_id WHERE translation_words.word_id = words.id AND translations.language_id = ".$editor_lang->id.")  
) AND NOT EXISTS (SELECT * FROM translation_exceptions WHERE word_id = words.id AND language_id = ".$editor_lang->id." AND user_id = ".$_SESSION['pUser'].")) AND  words.id NOT IN ( '" . implode($_SESSION['skip_bt'], "', '") . "' ) LIMIT 5");


$num = 1;

pOut('<div class="btLoadAjaxHere"></div>');

if($words->rowCount() == 0){
	pOut('<div class="btCard done">

			<div class="btTitle info">Information

			</div>
			<br />
			Great, there is nothing left to translate! <br /><br />
			<a class="actionbutton" href="'.pUrl('?admin').'"><i class="fa fa-arrow-left" style="font-size: 12px!important;"></i> Back to editor panel</a>
		</div>');
}
else{

	$cnt = $words->rowCount();


	pOut('<div class="btCard hide done">

			<div class="btTitle info">Information
			<div class="btPBAR" style="width: 100%"></div>
			</div>
			<br />
			Great, there is nothing left to translate! <br /><br />
			<a class="actionbutton" href="'.pUrl('?admin').'"><i class="fa fa-arrow-left" style="font-size: 12px!important;"></i> Back to editor panel</a>
		</div>');


	while($word = $words->fetchObject()){

		if($num != 1)
			$hide = "hide";
		else
			$hide = "";


			$percentage = ($num / $cnt) * 100;


		$type = pGetType($word->type_id);
		$classification = pGetClassification($word->classification_id);

		// Show the type and classification
		$text = '<em><a href="javascript:void(0);"  class="tooltip" title="'.htmlentities($type->name).'">'.html_entity_decode($type->short_name).'</a></em> ';
		if(!pTypeInflectClassifications($type->id))
			$text .= '<em><a href="javascript:void(0);" class="tooltip"  title="'.htmlentities($classification->name).'">'.html_entity_decode($classification->short_name).'</a></em> ';
		if($word->subclassification_id != 0 and !pTypeInflectClassifications($type->id)){
			$subclassification = pGetSubclassification($word->subclassification_id);
			$text .= '<em><a href="javascript:void(0);"  class="tooltip" title="'.htmlentities($subclassification->name).'">'.html_entity_decode($subclassification->short_name).'</a></em> ';
		}


		pTranslateCard();
		

		$num++;

	}

	pOut("<script> $(document).ready(function() { $('.card-1').slideDown();});</script>");
}






?>