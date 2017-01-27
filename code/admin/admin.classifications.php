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
	if(!pLogged())
	{
		pUrl('', true);
	}
	

	// Manage name
	$donut['page']['title'] = "Classifications - ".$donut['page']['title']; 	

	$die_actions = array('add_classification', 'edit_classification', 'classification_apply');


	// Actions and ajax

	if(isset($donut['request']['ajax']) and isset($donut['request']['action']))
	{

		if($donut['request']['action'] == 'add_classification'){
			
			// Do we have all fields?

			    if($donut['request']['classification_name'] == "" or $donut['request']['classification_name_short'] == "" or $donut['request']['classification_entry'] == "" or $donut['request']['classification_entry_short'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}

     			else
     			{
     				if(pClassificationAdd($donut['request']['classification_name'], $donut['request']['classification_name_short'], $donut['request']['classification_entry'], $donut['request']['classification_entry_short']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Classification succesfully added. <a href="'.pUrl('?admin&section=classifications').'">Return to overview.</a></div>';
     					echo "<script>
        				$('#busyadd').delay(500).slideUp(function(){
			                  $('.succes-notice').slideDown();
			                  $('.classification_name').val('');
			                  $('.classification_name_short').val('');
			                  $('.classification_entry').val('');
			                  $('.classification_entry_short').val('');
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

		elseif($donut['request']['action'] == 'edit_classification'){
			
			// Do we have all fields?
			
				if($donut['request']['classification_id'] == "" or $donut['request']['classification_name'] == "" or $donut['request']['classification_name_short'] == "" or $donut['request']['classification_entry'] == "" or $donut['request']['classification_entry_short'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}


     			else
     			{
     				if(pClassificationUpdate($donut['request']['classification_id'], $donut['request']['classification_name'], $donut['request']['classification_name_short'], $donut['request']['classification_entry'], $donut['request']['classification_entry_short']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Classification succesfully edited. <a href="'.pUrl('?admin&section=classifications').'">Return to overview.</a></div>';
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

	elseif(isset($donut['request']['action']))
	{

		// The delete action -> are you sure?
		if($donut['request']['action'] == 'delete_classification_sure' and isset($donut['request']['classification_id'])){
			
			pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the classification <em>'.pClassificationName($donut['request']['classification_id']).'</em>?</strong>
				<a class="actionbutton" href="'.pUrl('?admin&section=classifications').'">No</a> 
				 <a class="actionbutton" href="'.pUrl('?admin&section=classifications&action=delete_classification&classification_id='.$donut['request']['classification_id']).'">Yes</a> <br />
			All words of the classification '.pClassificationName($donut['request']['classification_id']).' will be deleted.</div>');
		}

		// The delete action
		if($donut['request']['action'] == 'delete_classification' and isset($donut['request']['classification_id'])){
			pClassificationDelete($donut['request']['classification_id']);
			pUrl('?admin&section=classifications', true);
		}


		// The action forms are going to be shown here!


		if($donut['request']['action'] == 'add_classification'){


			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Classification is being added...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');

			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=classifications')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Add a new classification</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='classification_name' type='text' /></td>
				</tr>
				<tr>
					<td><strong>Short name</strong></td>
					<td><input style='width: 100px;' class='classification_name_short' type='text' /></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Name entry</strong></td>
					<td><input style='width: 50px;' class='classification_entry' type='text' /> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Shorthand entry</strong></td>
					<td><input style='width: 50px;' class='classification_entry_short' type='text' /> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-plus"></i> Add classification</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=classifications&ajax&action=add_classification')."', 
		         	{'classification_name_short': $('.classification_name_short').val(), 'classification_name': $('.classification_name').val(), 
		         	'classification_entry': $('.classification_entry').val(), 'classification_entry_short': $('.classification_entry_short').val()});
     		 });
			</script>");


		}

		elseif($donut['request']['action'] == 'edit_classification' and isset($donut['request']['classification_id'])){


			if(!($classification = pGetClassification($donut['request']['classification_id'])))
				pUrl('?admin&section=classifications', true);


			
			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Classification is being edited...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');

			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=classifications')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Editing the classification '".html_entity_decode($classification->name)."'</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='classification_name' type='text'  value='".html_entity_decode($classification->name)."'/></td>
				</tr>
				<tr>
					<td><strong>Short name</strong></td>
					<td><input style='width: 100px;' class='classification_name_short' type='text'  value='".html_entity_decode($classification->short_name)."'/></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Name entry</strong></td>
					<td><input style='width: 50px;' class='classification_entry' type='text'  value='".$classification->native_hidden_entry."'/> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Shorthand entry</strong></td>
					<td><input style='width: 50px;' class='classification_entry_short' type='text' value='".$classification->native_hidden_entry_short."'/> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-floppy-o"></i> Save classification</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=classifications&ajax&action=edit_classification&classification_id='.$classification->id)."', {'classification_name_short': $('.classification_name_short').val(), 'classification_name': $('.classification_name').val(), 'classification_entry': $('.classification_entry').val(), 'classification_entry_short': $('.classification_entry_short').val()});
     		 });
			</script>");

		}

		elseif($donut['request']['action'] == 'classification_apply' and isset($donut['request']['classification_id'])){

			// The delete action -> are you sure?

			if(isset($donut['request']['delete_link_id_sure'])){
				
				pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the link between <em>'.pClassificationName($donut['request']['classification_id']).'</em> and <em>'.pTypeName($donut['request']['delete_link_id_sure']).'</em>?</strong>
					<a class="actionbutton" href="'.pUrl('?admin&section=classifications&action=classification_apply&classification_id='.$donut['request']['classification_id']).'">No</a> 
					 <a class="actionbutton" href="'.pUrl('?admin&section=classifications&action=classification_apply&classification_id='.$donut['request']['classification_id']."&delete_link_id=".$donut['request']['delete_link_id_sure']).'">Yes</a> <br />
				All words of the type \''.pTypeName($donut['request']['delete_link_id_sure']).'\' will not be able to be inflected by this classification anymore.</div>');
			}

			if(isset($donut['request']['delete_link_id']))
			{
				pClassificationApplyDelete($donut['request']['delete_link_id'], $donut['request']['classification_id']);
				pUrl('?admin&section=classifications&action=classification_apply&classification_id='.$donut['request']['classification_id'], true);
			}

			if(isset($donut['request']['new_link']))
			{

				if(!(pExistClassificationApply($donut['request']['classification_id'], $donut['request']['new_link'])) AND $donut['request']['new_link'] != '0')
					pClassificationApplyAdd($donut['request']['classification_id'], $donut['request']['new_link']);


			}
		
			$applies = pGetClassificationsApply($donut['request']['classification_id']);
			$types = pGetTypes();


			pOut("<a class='actionbutton' href='".pUrl('?admin&section=classifications')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a> 
				<br /><br /><strong>The classification '".pClassificationName($donut['request']['classification_id'])."' applies to the following types:</strong><br /><br />");


			pOut("<form METHOD='POST' class='form_new_link' ACTION='".pUrl('?admin&section=classifications&action=classification_apply&classification_id='.$donut['request']['classification_id'])."'>
				<select name='new_link'>
				<option value='0'>Choose a type...</option>");

			while($type = $types->fetchObject())
				pOut('<option value="'.$type->id.'">'.html_entity_decode($type->name).'</option>');

			pOut("</select>

				<a class='actionbutton' href='javascript:void(0);' onClick='$(\".form_new_link\").submit();'><i class='fa fa-plus-circle' style='font-size: 12px!important;' class='add_link'></i> Add link</a></form><br />");


			pOut("<table class='admin' style='width:45%;margin: 0!important;'>
					<tr class='title'>
						<td style='width: 80px;'>Type ID</td>
						<td>Type Name</td>
						<td>Actions</td>
					</tr>");

			if($applies->rowCount() == 0)
				pOut("<tr><td colspan=3>No links found.</tr>");
			else{

				while($link = $applies->fetchObject())
				{
					$type = pGetType($link->type_id);

					pOut("<tr><td>$type->id</td><td>$type->name</td>
						<td>
							<a class='actionbutton' href='".pUrl('?admin&section=classifications&action=classification_apply&classification_id='.$link->classification_id."&delete_link_id_sure=".$link->type_id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete link</i></a>
						</td></tr>");

				}

			}

			pOut("</table><br /><br />");

		}

	}


	// We have not died yet!
	if(!(isset($donut['request']['action'])) OR !(in_array($donut['request']['action'], $die_actions))){

		// table head

		pOut("

			<a class='actionbutton' href='".pUrl('?admin&section=classifications&action=add_classification')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add classification</i></a><br /><br />

			<table class='admin'>
				<thead>
				<tr role='row'  class='title'>
					<td style='width: 80px;'>ID</td>
					<td style='width: 20%;'>Name</td>
					<td style='width: 150px;'>Short name</td>
					<td>Links</td>
					<td>Native entry</td>
					<td>Shorthand entry</td>
					<td>Actions</td>
				</tr></thead>");

		
		$classifications = pGetClassifications();

		if($classifications->rowCount() == 0)
				pOut("<tr><td colspan=7>No classifications found.</tr>");

		while($classification = $classifications->fetchObject()){

			pOut("<tr>
				<td>$classification->id</td>
				<td>$classification->name</td>
				<td>$classification->short_name</td>
				<td>".pCountClassificationsApply($classification->id)."
<a class='actionbutton' href='".pUrl('?admin&section=classifications&action=classification_apply&classification_id='.$classification->id)."'><i class='fa fa-link' style='font-size: 12px!important;'></i> Manage links</i></a>	
				</td>
				<td>$classification->native_hidden_entry</td>
				<td>$classification->native_hidden_entry_short</td>
				<td><a class='actionbutton' href='".pUrl('?admin&section=classifications&action=edit_classification&classification_id='.$classification->id)."'><i class='fa fa-pencil' style='font-size: 12px!important;'></i> Edit classification</i></a>
				<a class='actionbutton' href='".pUrl('?admin&section=classifications&action=delete_classification_sure&classification_id='.$classification->id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete classification</i></a>
				</td>
			</tr>");
		}


		// table end

		pOut('</table><br />'."<a class='actionbutton' href='".pUrl('?admin&section=classifications&action=add_classification')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add classification</i></a>".'<br /><br />');

	}

	
	
	

 ?>