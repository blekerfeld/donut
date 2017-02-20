
<?php 

global $word, $lemma;


if(!isset($_REQUEST['ajax'])){

	$type_select = pPrepareSelect('pGetTypes', 'types', 'id', 'name', '', '', $word->type_id);
	$class_select = pPrepareSelect('pGetClassifications', 'classifications', 'id', 'name', '', '', $word->classification_id);
	$subclass_select = pPrepareSelect('pGetSubclassifications', 'subclassifications', 'id', 'name', '0', '(optional)', $word->subclassification_id);

	$lang_zero = pGetLanguageZero();
	$editor_lang = pGetLanguage(pEditorLanguage($_SESSION['pUser']));
	$types = pGetTypes();
		

	pOut("<br id='cl' />
	<div class='btCard editing'>

		<div class='btTitle'>
			<i class='fa fa-pencil-square-o'></i> ".LE_BASICS_TITLE."
		</div>

				<div class='saveLoad'>
		</div>".pNoticeBox('fa-spinner fa-spin', SAVING, 'notice hide', 'busysave')."
		<div class='btTranslate'>
			<span class='btLanguage btField'> ".sprintf(LE_BASICS_WORD, $lang_zero->name)."</span><br />
			<input class='nWord' value=".$word->native." />
		</div>
		<div class='btTranslate'>
			<table class='noshow' style='width: 100%;'>
				<tr>
					<td><span class='btLanguage btField'>".LE_BASICS_TYPE."</span></td>
					<td class='class_select'><span class='btLanguage btField'>".LE_BASICS_CLASSIFICATION."</span></td>
					<td class='subclass_select'><span class='btLanguage btField'>".LE_BASICS_TAGS."</span></td>
				</tr>
				<tr>
					<td>
						".$type_select."
					</td>
					<td class='class_select'>
						".$class_select."
					</td>
					<td class='subclass_select'>
						".$subclass_select."
					</td>
				</tr>
			</table>
		</div>


		<div class='btButtonBar'>
		<a class='btAction' style='float: left;'  href='".pUrl("?edit-lemma=".$_REQUEST['edit-lemma'])."'><i class='fa-12 fa-arrow-left'></i> ".BACK."</a>
			<a class='btAction green submit' id='editbutton' href='javascript:void(0);'><i class='fa-12 fa-floppy-o'></i> ".SAVE."</a> 
			<br id='cl'/>
		</div>
	</div>");

	$array_types = array();

	while($type = $types->fetchObject())
		if($type->inflect_not == 1 OR $type->inflect_classifications == 1)
			$array_types[] = $type->id;



	$array_types_json = json_encode($array_types);

	// Script

	pOut("<script>
		$(document).ready(function(){"); 


	if(in_array($word->type_id, $array_types))
		pOut("$('.class_select').css('opacity', 0);
	          $('.subclass_select').css('opacity', 0);");


	pOut("
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


			var arrTypes = ".$array_types_json.";

			 $('.types').on('change', function(e) {
	          if(arrTypes.indexOf($('.types').val()) != -1)
	          {
	          	$('.class_select').css('opacity', 0);
	          	$('.subclass_select').css('opacity', 0);
	          }
	          else{
	          	$('.class_select').css('opacity', 1);
	          	$('.subclass_select').css('opacity', 1);
	          }
	        });
		})

		$('#editbutton').click(function(){
		        $('.saved').fadeOut();
		        $('#busysave').fadeIn();
		         $('.saveLoad').load('".pUrl('?edit-lemma='.$_REQUEST['edit-lemma'].'&action=basics&ajax')."', 
		         	{'native': $('.nWord').val(),  
		         	'type': $('.types').val(), 'class': $('.classifications').val(), 'subclass': $('.subclassifications').val()});
     		 });
			</script>");


}
else{

	if(isset($_REQUEST['native'], $_REQUEST['type'], $_REQUEST['class'], $_REQUEST['subclass']) AND !pMempty($_REQUEST['native'], $_REQUEST['type'], $_REQUEST['class'], $_REQUEST['subclass']))
		if(pEL_Basics(pGetWordByHash($_REQUEST['edit-lemma'], true), $_REQUEST['native'], $_REQUEST['type'], $_REQUEST['class'], $_REQUEST['subclass']))
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
