<?php

if(!pLogged())
	pUrl('', true);

if(isset($_GET['ajax']) and is_numeric($_GET['new']))
{
	pQuery("UPDATE users SET editor_lang = ".$_GET['new']." WHERE id = ".$_SESSION['pUser'].";");
	echo "<script>
	$('.editorlangname').html('".pLanguageName($_GET['new'])."', function(){
		alert();
	});
	loadfunction('".pUrl('?home')."');
	</script>";
	die();
}

$select = pPrepareSelect('pGetLanguagesAll', 'pEditorlanguage', 'id', 'name');

pOut('<div class="header dictionary home"><span class="load"></span><span class="title_header"><div class="icon-box white-icon"><i class="fa fa-language"></i></div> Editor language:</span>'.$select.'<br /></div><div class="notice"><i class="fa fa-info-circle" style="font-size: 10px!important;"></i> Your editor language is the active secondary language. It is the language you will be editing and translating into.</div>');

pOut("<script>
	$('.pEditorlanguage').val(".pEditorLanguage($_SESSION['pUser']).");
	$('.pEditorlanguage').on('change keyup paste', function(e) {
		$('span.load').load('".pUrl('?editorlanguage&ajax&new=')."' + $(this).val());
	});
	</script>");

?>