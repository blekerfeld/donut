
<?php 

global $word, $lemma;


if(!isset($_REQUEST['ajax'])){

		

	pOut("<br id='cl' />
	<div class='btCard editing'>

		<div class='btTitle'>
			<i class='fa fa-pencil-square-o'></i> ".LE_IPA_TITLE."
		</div>
		<div class='ajaxscripts'>
		</div>
		<div class='saveLoad'>
		</div>".pNoticeBox('fa-spinner fa-spin', SAVING, 'notice hide', 'busysave')."
		<div class='btTranslate'>
			<span class='btLanguage btField'> ".LE_IPA."</span><br />
			<input class='nWord opensans ipa' value=".$word->ipa." /><br /><br /><input class='bWord' value='".$word->native."' />
			<a class='ipaclick small' href='javascript:void(0);' onClick='$(\".ajaxscripts\").load(\"".pUrl('?addword&ajax&get_ipa&string=')."\" + encodeURIComponent($(\".bWord\").val()));'>Calculate IPA</a>
		</div>
	

		<div class='btButtonBar'>
		<a class='btAction' style='float: left;'  href='".pUrl("?edit-lemma=".$_REQUEST['edit-lemma'])."'><i class='fa-12 fa-arrow-left'></i> ".BACK."</a>
			<a class='btAction green submit' id='editbutton' href='javascript:void(0);'><i class='fa-12 fa-floppy-o'></i> ".SAVE."</a> 
			<br id='cl'/>
		</div>
	</div>");



	// Script

	pOut("<script>
		$('#editbutton').click(function(){
		        $('.saved').fadeOut();
		        $('#busysave').fadeIn();
		         $('.saveLoad').load('".pUrl('?edit-lemma='.$_REQUEST['edit-lemma'].'&action=ipa&ajax')."', 
		         	{'ipa': $('.ipa').val()});
     		 });
			</script>");


}
else{

	if(isset($_REQUEST['ipa']) AND !pMempty($_REQUEST['ipa']))
		if(pEL_IPA(pGetWordByHash($_REQUEST['edit-lemma'], true), $_REQUEST['ipa']))
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
