
<?php 

global $word, $lemma;


if(!isset($_REQUEST['ajax'])){

	$usage_notes = pGetUsageNotes($word->id);
	$note = $usage_notes->fetchObject();

	if($usage_notes->rowCount() == 0)
		$text = '';
	else
		$text = $note->note;



	pOut("<br id='cl' />
	<div class='btCard editing'>

		<div class='btTitle'>
			<i class='fa fa-pencil-square-o'></i> ".LE_BASICS_TITLE."
		</div>

				<div class='saveLoad'>
		</div>".pNoticeBox('fa-spinner fa-spin', SAVING, 'notice hide', 'busysave')."
		<div class='btTranslate'>
			<textarea class='allowtabs elastic wikiEditContent' id='MDE'>".$text."</textarea>
		</div>
		<div class='btButtonBar'>
		<a class='btAction' style='float: left;'  href='".pUrl("?edit-lemma=".$_REQUEST['edit-lemma'])."'><i class='fa-12 fa-arrow-left'></i> ".BACK."</a>
			<a class='btAction green submit' id='editbutton' href='javascript:void(0);'><i class='fa-12 fa-floppy-o'></i> ".SAVE."</a> 
			<br id='cl'/>
		</div>
	</div>");

	

	// Script

	pOut("<script>
		var simplemde = new SimpleMDE({spellChecker: false});</script>
		<script>

		$('#editbutton').click(function(){
		        $('.saved').fadeOut();
		        $('#busysave').fadeIn();
		         $('.saveLoad').load('".pUrl('?edit-lemma='.$_REQUEST['edit-lemma'].'&action=usage-notes&ajax')."', 
		         	{'text': simplemde.value()});
     		 });
			</script>");


}
else{

	if(isset($_REQUEST['text']))
		if(pEL_UsageNotes(pGetWordByHash($_REQUEST['edit-lemma'], true), $_REQUEST['text']))
				echo pNoticeBox('fa-spin fa-spinner', SAVED_REDIRECT, 'succes-notice saved hide')."<script>$('#busysave').fadeOut();
				$('.saved').fadeIn().delay(1500).fadeOut(300, function(){
					loadfunction('".pUrl('?edit-lemma='.$_REQUEST['edit-lemma'])."');
				});</script>";
		else
			echo pNoticeBox('fa-warning', SAVED_ERROR, 'danger-notice saved hide')."<script>$('#busysave').fadeOut();
				$('.saved').fadeIn();</script>";
	else
		echo pNoticeBox('fa-warning', SAVED_EMPTY, 'danger-notice saved hide')."<script>$('#busysave').fadeOut();
				$('.saved').fadeIn();</script>";

}
