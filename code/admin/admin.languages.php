<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: index.admin.php
*/



	// WE ABSOLUTLY NEED TO BE LOGGED IN!!!
	if(!logged())
	{
		pUrl('', true);
	}
	

	$die_actions = array('add_language', 'edit_language');


	// Actions and ajax

	if(isset($_REQUEST['ajax']) and isset($_REQUEST['action']))
	{

		if($_REQUEST['action'] == 'add_language'){
			
			// Do we have all fields?

			    if($_REQUEST['lang_name'] == "" or $_REQUEST['lang_flag'] == "" or $_REQUEST['lang_native'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}

     			else
     			{
     				if(pLanguageAdd($_REQUEST['lang_name'], $_REQUEST['lang_native'], $_REQUEST['lang_flag'], $_REQUEST['lang_status']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Language succesfully added. <a href="'.pUrl('?admin&section=languages').'">Return to overview.</a></div>';
     					echo "<script>
        				$('#busyadd').delay(500).slideUp(function(){
			                  $('.succes-notice').slideDown();
			                  $('.lang_name').val('');
			                  $('.lang_native').val('');
			                  $('.lang_flag').val('');
			                  $('.flagimage').attr('src', '".purl('pol://library/flags/undef.png')."');
			                });	
        				</script>";
     				}
     				else
     				{
     					echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Something went wrong!</div>';
        				echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
     				}
     			}

		}

		elseif($_REQUEST['action'] == 'edit_language'){
			
			// Do we have all fields?

			    if($_REQUEST['lang_id'] == "" OR $_REQUEST['lang_name'] == "" OR $_REQUEST['lang_flag'] == "" OR $_REQUEST['lang_native'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}

     			else
     			{

     				if(pLanguageUpdate($_REQUEST['lang_id'], $_REQUEST['lang_name'], $_REQUEST['lang_native'], $_REQUEST['lang_flag'], $_REQUEST['lang_status']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Language succesfully edited. <a href="'.pUrl('?admin&section=languages').'">Return to overview.</a></div>';
     					echo "<script>
        				$('#busyadd').delay(500).slideUp(function(){
			                  $('.succes-notice').slideDown();
			                });	
        				</script>";
     				}
     				else
     				{
     					echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Something went wrong!</div>';
        				echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
     				}
     			}

		}

	}

	// Actions

	elseif(isset($_REQUEST['action']))
	{

		// The delete action -> are you sure?
		if($_REQUEST['action'] == 'delete_language_sure' and isset($_REQUEST['lang_id'])){
			
			if(isset($_REQUEST['lang_id']) == 1)
				pOut('<div class="notice" id="empty">');

			pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the language '.pLanguageName($_REQUEST['lang_id']).'?</strong>  <a class="actionbutton" href="'.pUrl('?admin&section=languages').'">No</a>  <a class="actionbutton" href="'.pUrl('?admin&section=languages&action=delete_language&lang_id='.$_REQUEST['lang_id']).'">Yes</a> <br />
			All entry descriptions in this language and all translations into '.pLanguageName($_REQUEST['lang_id']).' will be deleted.</div>');
		}

		// The delete action
		if($_REQUEST['action'] == 'delete_language' and isset($_REQUEST['lang_id'])){
			pLanguageDelete($_REQUEST['lang_id']);
			//pUrl('?admin&section=languages', true);
		}


		// The action forms are going to be shown here!


		if($_REQUEST['action'] == 'add_language'){


			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Language is being added...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');

			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=languages')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Add a language</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='lang_name' type='text' /></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Flag</strong></td>
					<td><img class='flagimage' src='".purl('pol://library/flags/undef.png')."' /> <input  style='width:50%'  class='lang_flag' type='text' />.png</td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Dictionary entry</strong></td>
					<td><input style='width: 50px;' class='lang_native' type='text' /> <em>the id of a (hidden) dictionary entry containing the language's name</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Status</strong></td>
					<td><select class='lang_status'><option value='0'>Disabled</option><option value='1'>Enabled</option></select></td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
						<td>".'<a class="button remember" onClick="$(\'#busyadd\').fadeIn();$(\'.ajaxloadadd\').load(\''.pUrl('?admin&section=languages&ajax&action=add_language&ajax').'\', {\'lang_name\': $(\'.lang_name\').val(), \'lang_flag\': $(\'.lang_flag\').val(), \'lang_native\': $(\'.lang_native\').val(), \'lang_status\': $(\'.lang_status\').val()});" class="addbutton1" href="javascript:void(0);"><i class="fa-12 fa-plus"></i> Add language</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>

					$('.lang_flag').change(function() {

				$.get('".purl('pol://library/flags/')."' + $('.lang_flag').val() + '.png')
				    .done(function() { 
				        $('.flagimage').attr('src', '".purl('pol://library/flags/')."' + $('.lang_flag').val() + '.png');
				    }).fail(function() { 
				       $('.flagimage').attr('src', '".purl('pol://library/flags/undef.png')."');
				})
			});

			</script>");


		}

		elseif($_REQUEST['action'] == 'edit_language' and isset($_REQUEST['lang_id'])){


			if(!($language = pGetLanguage($_REQUEST['lang_id'])))
				pUrl('?admin&section=languages', true);


			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Language is being saved...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');

			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=languages')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Editing the language '".$language->name."'</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='lang_name' type='text' value='".html_entity_decode($language->name)."' /></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Flag</strong></td>
					<td><img class='flagimage' src='".purl('pol://library/flags/'.$language->flag.'.png')."' /> <input  style='width:50%'  class='lang_flag' type='text' value='".html_entity_decode($language->flag)."' />.png</td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Dictionary entry</strong></td>
					<td><input style='width: 50px;' class='lang_native' type='text' value='".html_entity_decode($language->hidden_native_entry)."'/> <em>the id of a (hidden) dictionary entry containing the language's name</em></td>
				</tr>
				<tr class='statusrow'>
					<td style='width: 150px;'><strong>Status</strong></td>
					<td><select class='lang_status'><option value='0' ".(($language->activated==0) ? 'selected' : '').">Disabled</option><option value='1' ".(($language->activated==1) ? 'selected' : '').">Enabled</option></select></td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="savebutton" href="javascript:void(0);"><i class="fa-12 fa-floppy-o"></i> Save language</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>
			$(document).ready(function(){

				if(".$_REQUEST['lang_id']." === 0){
					$('.statusrow').hide();
				}

			});
			$('#savebutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=languages&ajax&action=edit_language&lang_id='.$_REQUEST['lang_id'])."', {'lang_name': $('.lang_name').val(), 'lang_flag': $('.lang_flag').val(), 'lang_native': $('.lang_native').val(), 'lang_status': $('.lang_status').val()});
     		 });
			$('.lang_flag').change(function() {

				$.get('".purl('pol://library/flags/')."' + $('.lang_flag').val() + '.png')
				    .done(function() { 
				        $('.flagimage').attr('src', '".purl('pol://library/flags/')."' + $('.lang_flag').val() + '.png');
				    }).fail(function() { 
				       $('.flagimage').attr('src', '".purl('pol://library/flags/undef.png')."');
				})
			});
			</script>");


		}

	}


	// We have not died yet!
	if(!(isset($_REQUEST['action'])) OR !(in_array($_REQUEST['action'], $die_actions))){

		// table head

		pOut("<table class='admin'>
				<tr class='title'>
					<td style='width: 150px;'>ID</td>
					<td style='width: 150px;'>Flag</td>
					<td style='width: 150px;'>Status</td>
					<td style='width: 30%;'>Name</td>
					<td>Native entry</td>
					<td>Actions</td>
				</tr>");

		// First we need to show the zero language

		$zero_language = pGetLanguageZero();

		pOut("<tr>
					<td><b><i>Dictionary language</i></b></td>
					<td><img src='".pUrl('pol://library/flags/'.$zero_language->flag.'.png')."' /></td>
					<td><span class='language-status-check'><i class='fa fa-12 fa-check'></i> Enabled</span></td>
					<td>$zero_language->name</td>
					<td>$zero_language->hidden_native_entry</td>
					<td><a class='actionbutton' href='".pUrl('?admin&section=languages&action=edit_language&lang_id='.$zero_language->id)."'><i class='fa fa-pencil' style='font-size: 12px!important;'></i> Edit language</i></a></td>
				</tr>");


		$languages = pGetLanguages(false);


		while($language = $languages->fetchObject()){

			if($language->activated == 1)
				$status = array('check', 'Enabled');
			else
				$status = array('times', 'Disabled');


			pOut("<tr>
				<td>$language->id</td>
				<td><img src='".pUrl('pol://library/flags/'.$language->flag.'.png')."' /></td>
				<td><span class='language-status-$status[0]'><i class='fa fa-12 fa-$status[0]'></i> $status[1]</td>
				<td>$language->name</td>
				<td>$language->hidden_native_entry</td>
				<td><a class='actionbutton' href='".pUrl('?admin&section=languages&action=edit_language&lang_id='.$language->id)."'><i class='fa fa-pencil' style='font-size: 12px!important;'></i> Edit language</i></a>
				<a class='actionbutton' href='".pUrl('?admin&section=languages&action=delete_language_sure&lang_id='.$language->id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete language</i></a>
				</td>
			</tr>");
		}


		// table end

		pOut('</table><br />'."<a class='actionbutton' href='".pUrl('?admin&section=languages&action=add_language')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add language</i></a>".'<br /><br />');

	}

	
	
	

 ?>