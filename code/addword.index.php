<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: index.generate.php
*/

if(!pLogged())
	pUrl('', true);

if(isset($_REQUEST['ajax'])){

	if(isset($_REQUEST['do']))
	{
		// We might just show an error if something is wrong
		if(false)
		die();		// TODO!!	
		// At first we are going to insert the word to the database
		$insert_query = "INSERT INTO words(native, ipa, type_id, classification_id, subclassification_id) VALUES(".$donut['db']->quote($_REQUEST['new_word']).", ".$donut['db']->quote($_REQUEST['ipa']).", ".$donut['db']->quote($_REQUEST['type']).", ".$donut['db']->quote($_REQUEST['classification']).", ".$donut['db']->quote($_REQUEST['subclassification']).");";
		pQuery($insert_query);
		
		// We need to get a hold of the last inserted id
		$new_word_id = $donut['db']->lastInsertId(); 

		pAddTranslations($_REQUEST['translations'], $new_word_id);

		echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Entry succesfully added!</div>'."<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";

	}
	// In case they want to get a fancy generated IPA string!
	elseif(isset($_REQUEST['get_ipa']) AND isset($_REQUEST['string']))
	{
			$string = strtolower($_REQUEST['string']);
			echo "<script>$('.ipa').val('".pGetIPA($string)."');</script>";
	}

}
else{

	pOut("<a class='actionbutton' href='".pUrl('?admin')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back to editor panel</a><br /><br />", true);

	$lang_zero = pGetLanguageZero();
	$editor_lang = pGetLanguage(pEditorLanguage($_SESSION['pUser']));

	// Prepare select boxes

	$type_select = pPrepareSelect('pGetTypes', 'types', 'id', 'name');
	$class_select = pPrepareSelect('pGetClassifications', 'classifications', 'id', 'name');
	$subclass_select = pPrepareSelect('pGetSubclassifications', 'subclassifications', 'id', 'name', '0', '(optional)');

	pOut('<div style="width: 74%;margin: 0 auto;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Saving entry...</div>
       		<div style="width: 74%;margin: 0 auto;" class="ajaxloadadd"></div><div class="ajaxscripts"></div>');

	pOut("
		<table class='admin addword' id='empty' style='width:100%'>
				<tr class='title'>
					<td colspan=4>
					Add a new word to the dictionary</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td colspan=4 class='center'><input required class='nWord' placeholder='Word in ".$lang_zero->name."' value=''/></td>
				</tr>

				<tr>
					<td class='center' style='width: 10%'><strong class='title'>Pronounciation</strong></td>
					<td style='width: 40%'><input required placeholder='Enter IPA transcription' class='ipa' style='width: 200px;'' type='text' /> <a class='ipaclick small' href='javascript:void(0);' onClick='$(\".ajaxscripts\").load(\"".pUrl('?addword&ajax&get_ipa&string=')."\" + encodeURIComponent($(\".nWord\").val()));'>Calculate IPA</a></td>

					<td class='center' style='width: 10%'><strong class='title'>Translations</strong><br /><span class='small'>into ".$editor_lang->name."</span><span class='hover gray'><i class='fa fa-12 fa-info-circle'></i><span class='hide'>Format: <span class='outertag'>Translation</span> or <span class='outertag'>Translation|Specification</span><br /><br /><em class='small'> Specifications are optional and can be added later as well, along with more detailed descriptions. <br /><br />If you want to add the translations in another language, you have to change the editor language <a href='".pUrl('?editorlanguage')."'>here</a>.</em></span></span></td>
					<td style='width: 40%'><input id='tags' style='width: 100%;' class='translations' type='text' /><span class='small'></span></td>
				</tr>
				
				<tr>
					<td class='center'><strong class='title'>Part of speech</strong></td>
					<td>".$type_select."</td>
					<td class='center'><strong class='title'>Classification</strong></td>
					<td>".$class_select."</td>
				</tr>
				<tr>
					<td></td><td></td>
					<td class='center'><strong class='title'>Subclassification</strong><br /><em class='small'>optional</em></td>
					<td>".$subclass_select."</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-plus-square"></i> Add to dictionary</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

	pOut("<script>
		$(document).ready(function(){
			$('#tags').tagsInput({
				'defaultText': 'Add a translation'
			});
			$('.types').select2({
			  placeholder: 'Select a part of speech'
			});
			$('.classifications').select2({
			  placeholder: 'Classification'
			});$('.subclassifications').select2({
			  placeholder: 'Subclassification'
			});
		})
		$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?addword&ajax&do')."', 
		         	{'new_word': $('.nWord').val(), 'ipa': $('.ipa').val(), 'translations': $('.translations').val(),
		         	'type': $('.types').val(), 'classification': $('.classifications').val(), 'subclassification': $('.subclassifications').val()});
     		 });
			</script>");


}




?>
