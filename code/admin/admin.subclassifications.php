<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under MIT
	File: index.admin.php
*/



	// WE ABSOLUTLY NEED TO BE LOGGED IN!!!
	if(!pLogged())
	{
		pUrl('', true);
	}
	

	// Manage name
	$donut['page']['title'] = "Subclassifications - ".$donut['page']['title']; 	

	$die_actions = array('add_subclassification', 'edit_subclassification', 'subclassification_apply');


	// Actions and ajax

	if(isset($_REQUEST['ajax']) and isset($_REQUEST['action']))
	{

		if($_REQUEST['action'] == 'add_subclassification'){
			
			// Do we have all fields?

			    if($_REQUEST['subclassification_name'] == "" or $_REQUEST['subclassification_name_short'] == "" or $_REQUEST['subclassification_entry'] == "" or $_REQUEST['subclassification_entry_short'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}

     			else
     			{
     				if(pSubclassificationAdd($_REQUEST['subclassification_name'], $_REQUEST['subclassification_name_short'], $_REQUEST['subclassification_entry'], $_REQUEST['subclassification_entry_short']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Subclassification succesfully added. <a href="'.pUrl('?admin&section=subclassifications').'">Return to overview.</a></div>';
     					echo "<script>
        				$('#busyadd').delay(500).slideUp(function(){
			                  $('.succes-notice').slideDown();
			                  $('.subclassification_name').val('');
			                  $('.subclassification_name_short').val('');
			                  $('.subclassification_entry').val('');
			                  $('.subclassification_entry_short').val('');
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

		elseif($_REQUEST['action'] == 'edit_subclassification'){
			
			// Do we have all fields?
			
				if($_REQUEST['subclassification_id'] == "" or $_REQUEST['subclassification_name'] == "" or $_REQUEST['subclassification_name_short'] == "" or $_REQUEST['subclassification_entry'] == "" or $_REQUEST['subclassification_entry_short'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}


     			else
     			{
     				if(pSubclassificationUpdate($_REQUEST['subclassification_id'], $_REQUEST['subclassification_name'], $_REQUEST['subclassification_name_short'], $_REQUEST['subclassification_entry'], $_REQUEST['subclassification_entry_short']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Subclassification succesfully edited. <a href="'.pUrl('?admin&section=subclassifications').'">Return to overview.</a></div>';
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
		if($_REQUEST['action'] == 'delete_subclassification_sure' and isset($_REQUEST['subclassification_id'])){
			
			pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the subclassification <em>'.pSubclassificationName($_REQUEST['subclassification_id']).'</em>?</strong>
				<a class="actionbutton" href="'.pUrl('?admin&section=subclassifications').'">No</a> 
				 <a class="actionbutton" href="'.pUrl('?admin&section=subclassifications&action=delete_subclassification&subclassification_id='.$_REQUEST['subclassification_id']).'">Yes</a> <br />
			All words of the subclassification '.pSubclassificationName($_REQUEST['subclassification_id']).' will be deleted.</div>');
		}

		// The delete action
		if($_REQUEST['action'] == 'delete_subclassification' and isset($_REQUEST['subclassification_id'])){
			pSubclassificationDelete($_REQUEST['subclassification_id']);
			pUrl('?admin&section=subclassifications', true);
		}


		// The action forms are going to be shown here!


		if($_REQUEST['action'] == 'add_subclassification'){


			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Subclassification is being added...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');

			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=subclassifications')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Add a new subclassification</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='subclassification_name' type='text' /></td>
				</tr>
				<tr>
					<td><strong>Short name</strong></td>
					<td><input style='width: 100px;' class='subclassification_name_short' type='text' /></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Name entry</strong></td>
					<td><input style='width: 50px;' class='subclassification_entry' type='text' /> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Shorthand entry</strong></td>
					<td><input style='width: 50px;' class='subclassification_entry_short' type='text' /> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-plus"></i> Add subclassification</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=subclassifications&ajax&action=add_subclassification')."', 
		         	{'subclassification_name_short': $('.subclassification_name_short').val(), 'subclassification_name': $('.subclassification_name').val(), 
		         	'subclassification_entry': $('.subclassification_entry').val(), 'subclassification_entry_short': $('.subclassification_entry_short').val()});
     		 });
			</script>");


		}

		elseif($_REQUEST['action'] == 'edit_subclassification' and isset($_REQUEST['subclassification_id'])){


			if(!($subclassification = pGetSubclassification($_REQUEST['subclassification_id'])))
				pUrl('?admin&section=subclassifications', true);


			
			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Subclassification is being edited...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');

			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=subclassifications')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Editing the subclassification '".html_entity_decode($subclassification->name)."'</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='subclassification_name' type='text'  value='".html_entity_decode($subclassification->name)."'/></td>
				</tr>
				<tr>
					<td><strong>Short name</strong></td>
					<td><input style='width: 100px;' class='subclassification_name_short' type='text'  value='".html_entity_decode($subclassification->short_name)."'/></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Name entry</strong></td>
					<td><input style='width: 50px;' class='subclassification_entry' type='text'  value='".$subclassification->native_hidden_entry."'/> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Shorthand entry</strong></td>
					<td><input style='width: 50px;' class='subclassification_entry_short' type='text' value='".$subclassification->native_hidden_entry_short."'/> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-floppy-o"></i> Save subclassification</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=subclassifications&ajax&action=edit_subclassification&subclassification_id='.$subclassification->id)."', {'subclassification_name_short': $('.subclassification_name_short').val(), 'subclassification_name': $('.subclassification_name').val(), 'subclassification_entry': $('.subclassification_entry').val(), 'subclassification_entry_short': $('.subclassification_entry_short').val()});
     		 });
			</script>");

		}

		elseif($_REQUEST['action'] == 'subclassification_apply' and isset($_REQUEST['subclassification_id'])){

			// The delete action -> are you sure?

			if(isset($_REQUEST['delete_link_id_sure'])){
				
				pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the link between <em>'.pSubclassificationName($_REQUEST['subclassification_id']).'</em> and <em>'.pTypeName($_REQUEST['delete_link_id_sure']).'</em>?</strong>
					<a class="actionbutton" href="'.pUrl('?admin&section=subclassifications&action=subclassification_apply&subclassification_id='.$_REQUEST['subclassification_id']).'">No</a> 
					 <a class="actionbutton" href="'.pUrl('?admin&section=subclassifications&action=subclassification_apply&subclassification_id='.$_REQUEST['subclassification_id']."&delete_link_id=".$_REQUEST['delete_link_id_sure']).'">Yes</a> <br />
				All words of the type \''.pTypeName($_REQUEST['delete_link_id_sure']).'\' will not be able to be inflected by this subclassification anymore.</div>');
			}

			if(isset($_REQUEST['delete_link_id']))
			{
				pSubclassificationApplyDelete($_REQUEST['delete_link_id'], $_REQUEST['subclassification_id']);
				pUrl('?admin&section=subclassifications&action=subclassification_apply&subclassification_id='.$_REQUEST['subclassification_id'], true);
			}

			if(isset($_REQUEST['new_link']))
			{

				if(!(pExistSubclassificationApply($_REQUEST['subclassification_id'], $_REQUEST['new_link'])) AND $_REQUEST['new_link'] != '0')
					pSubclassificationApplyAdd($_REQUEST['subclassification_id'], $_REQUEST['new_link']);


			}
		
			$applies = pGetSubclassificationsApply($_REQUEST['subclassification_id']);
			$classifications = pGetClassifications();


			pOut("<a class='actionbutton' href='".pUrl('?admin&section=subclassifications')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a> 
				<br /><br /><strong>The subclassification '".pSubclassificationName($_REQUEST['subclassification_id'])."' applies to the following types:</strong><br /><br />");


			pOut("<form METHOD='POST' class='form_new_link' ACTION='".pUrl('?admin&section=subclassifications&action=subclassification_apply&subclassification_id='.$_REQUEST['subclassification_id'])."'>
				<select name='new_link'>
				<option value='0'>Choose a type...</option>");

			while($classification = $classifications->fetchObject())
				pOut('<option value="'.$classification->id.'">'.html_entity_decode($classification->name).'</option>');

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
					$type = pGetClassification($link->classification_id);

					pOut("<tr><td>$type->id</td><td>$type->name</td>
						<td>
							<a class='actionbutton' href='".pUrl('?admin&section=subclassifications&action=subclassification_apply&subclassification_id='.$link->subclassification_id."&delete_link_id_sure=".$link->classification_id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete link</i></a>
						</td></tr>");

				}

			}

			pOut("</table><br /><br />");

		}

	}


	// We have not died yet!
	if(!(isset($_REQUEST['action'])) OR !(in_array($_REQUEST['action'], $die_actions))){

		// Offset system loading
			$total_number = pCountTable('subclassifications');
			$offset_system = pSimpleOffsetSystem($total_number, 10, "?admin&section=subclassifications");
			pOut($offset_system['restore_offset_script']);

		// table head

		pOut("<span class='float-right'>Page: ".$offset_system['select_box']."</span>

			<a class='actionbutton' href='".pUrl('?admin&section=subclassifications&action=add_subclassification')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add subclassification</i></a>".$offset_system['back_button'].$offset_system['next_button']."<br /><br />

			<table class='admin'>
				<thead>
				<tr role='row'  class='title'>
					<td style='width: 25px;'>ID</td>
					<td style='width: 35%;'>Name</td>
					<td style='width: 10%;'>Short name</td>
					<td>Links</td>
					<td>Actions</td>
				</tr></thead>");

		
		$subclassifications = pGetSubclassifications(0, false, 0, "LIMIT ".$offset_system['offset'].",10");

		if($subclassifications->rowCount() == 0)
				pOut("<tr><td colspan=7>No subclassifications found.</tr>");

		while($subclassification = $subclassifications->fetchObject()){

			pOut("<tr>
				<td>$subclassification->id</td>
				<td>$subclassification->name</td>
				<td>$subclassification->short_name</td>
				<td>".pCountSubclassificationsApply($subclassification->id)."
<a class='actionbutton' href='".pUrl('?admin&section=subclassifications&action=subclassification_apply&subclassification_id='.$subclassification->id)."'><i class='fa fa-link' style='font-size: 12px!important;'></i> Manage links</i></a>	
				</td>
				<td><a class='actionbutton' href='".pUrl('?admin&section=subclassifications&action=edit_subclassification&subclassification_id='.$subclassification->id)."'><i class='fa fa-pencil' style='font-size: 12px!important;'></i> Edit subclassification</i></a>
				<a class='actionbutton' href='".pUrl('?admin&section=subclassifications&action=delete_subclassification_sure&subclassification_id='.$subclassification->id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete subclassification</i></a>
				</td>
			</tr>");
		}


		// table end

		pOut('</table><br />'."<a class='actionbutton' href='".pUrl('?admin&section=subclassifications&action=add_subclassification')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add subclassification</i></a>".$offset_system['back_button'].$offset_system['next_button'].'<br /><br />');

	}

	
	
	

 ?>