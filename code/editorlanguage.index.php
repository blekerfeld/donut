<?php

if(!pLogged())
	pUrl('', true);

if(isset($_GET['ajax']) and is_numeric($_GET['new']))
{
	$donut['db']->query("UPDATE users SET editor_lang = ".$_GET['new']." WHERE id = ".$_SESSION['pUser'].";");
	echo "<script>
	$('.editorlangname').html('".pLanguageName($_GET['new'])."', function(){
		alert();
	});
	loadfunction('".pUrl('?home')."');
	</script>";
	die();
}

$select = pPrepareSelect('pGetLanguages', 'pEditorlanguage', 'id', 'name');

pOut('<div class="load"></div><span class="title_header">Editor language:</span>'.$select.'<br /><br />', true);

pOut("<script>
	$('.pEditorlanguage').val(".pEditorLanguage($_SESSION['pUser']).");
	$('.pEditorlanguage').on('change keyup paste', function(e) {
		$('div.load').load('".pUrl('?editorlanguage&ajax&new=')."' + $(this).val());
	});
	$('.page').hide();</script>");

?>