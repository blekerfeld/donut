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

	// Manage name
	$pol['page']['title'] = "Submodes - ".$pol['page']['title']; 
	

	$die_actions = array('add_submode', 'edit_submode', 'submode_apply');


	// Actions and ajax

	if(isset($_REQUEST['ajax']) and isset($_REQUEST['action']))
	{

		if($_REQUEST['action'] == 'add_submode'){
			
			// Do we have all fields?

			    if($_REQUEST['submode_name'] == "" or $_REQUEST['submode_name_short'] == "" or $_REQUEST['submode_entry'] == "" or $_REQUEST['submode_submode_type_id'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}

     			else
     			{
     				if(pSubmodeAdd($_REQUEST['submode_name'], $_REQUEST['submode_name_short'], $_REQUEST['submode_entry'], $_REQUEST['submode_submode_type_id']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Submode succesfully added. <a href="'.pUrl('?admin&section=submodes').'">Return to overview.</a></div>';
     					echo "<script>
        				$('#busyadd').delay(500).slideUp(function(){
			                  $('.succes-notice').slideDown();
			                  $('.submode_name').val('');
			                  $('.submode_name_short').val('');
			                  $('.submode_entry').val('');
			                  $('.submode_entry_short').val('');
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

		elseif($_REQUEST['action'] == 'edit_submode'){
			
			// Do we have all fields?
			
			    if($_REQUEST['submode_name'] == "" or $_REQUEST['submode_name_short'] == "" or $_REQUEST['submode_entry'] == "" or $_REQUEST['submode_submode_type_id'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}

     			else
     			{
     				if(pSubmodeUpdate($_REQUEST['submode_id'], $_REQUEST['submode_name'], $_REQUEST['submode_name_short'], $_REQUEST['submode_entry'], $_REQUEST['submode_submode_type_id']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Submode succesfully edited. <a href="'.pUrl('?admin&section=submodes').'">Return to overview.</a></div>';
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
		if($_REQUEST['action'] == 'delete_submode_sure' and isset($_REQUEST['submode_id'])){
			
			pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the submode <em>'.pSubmodeName($_REQUEST['submode_id']).'</em>?</strong>
				<a class="actionbutton" href="'.pUrl('?admin&section=submodes').'">No</a> 
				 <a class="actionbutton" href="'.pUrl('?admin&section=submodes&action=delete_submode&submode_id='.$_REQUEST['submode_id']).'">Yes</a> <br />
			All words of the submode '.pSubmodeName($_REQUEST['submode_id']).' will be deleted.</div>');
		}

		// The delete action
		elseif($_REQUEST['action'] == 'delete_submode' and isset($_REQUEST['submode_id'])){
			pSubmodeDelete($_REQUEST['submode_id']);
			pUrl('?admin&section=submodes', true);
		}

		// The action forms are going to be shown here!


		if($_REQUEST['action'] == 'add_submode'){


			// Preparing the submode type select
       		$submodetypes = pGetModeTypes();

       		$select_submodetype = "<select class='submode_submode_type_id'>
       		<option value=''>Choose a submode type...</option>";

       		while($submodetype = $submodetypes->fetchObject()){
       			$select_submodetype .= '<option value="'.$submodetype->id.'">'.$submodetype->name.'</option>';
       		}

       		$select_submodetype .= "</select>";

			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Submode is being added...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');



			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=submodes')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Add a new submode</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='submode_name' type='text' /></td>
				</tr>
				<tr>
					<td><strong>Short name</strong></td>
					<td><input style='width: 100px;' class='submode_name_short' type='text' /></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Native entry</strong></td>
					<td><input style='width: 50px;' class='submode_entry' type='text' /> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Submode type</strong></td>
					<td>".$select_submodetype."</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-plus"></i> Add submode</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=submodes&ajax&action=add_submode')."', 
		         	{'submode_name_short': $('.submode_name_short').val(), 'submode_name': $('.submode_name').val(), 
		         	'submode_entry': $('.submode_entry').val(), 
		         	'submode_submode_type_id': $('.submode_submode_type_id').val()});
     		 });
			</script>");


		}

		elseif($_REQUEST['action'] == 'edit_submode' and isset($_REQUEST['submode_id'])){



			if(!($submode = pGetSubmode($_REQUEST['submode_id'])))
				pUrl('?admin&section=submodes', true);

			// Preparing the submode type select
       		$submodetypes = pGetModeTypes();

       		$select_submodetype = "<select class='submode_submode_type_id'>
       		<option value=''>Choose a submode type...</option>";

       		while($submodetype = $submodetypes->fetchObject()){
       			$select_submodetype .= '<option value="'.$submodetype->id.'" '.(($submodetype->id == $submode->mode_type_id) ? 'selected' : '').'>'.$submodetype->name.'</option>';
       		}

       		$select_submodetype .= "</select>";
	

			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Submode is being edited...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');


			if(!($submode = pGetSubmode($_REQUEST['submode_id'])))
				pUrl('?admin&section=submodes', true);


			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=submodes')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Editing the submode '".$submode->name."'</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='submode_name' type='text' value='".$submode->name."'/></td>
				</tr>
				<tr>
					<td><strong>Short name</strong></td>
					<td><input style='width: 100px;' class='submode_name_short' type='text' value='".$submode->short_name."' /></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Native entry</strong></td>
					<td><input style='width: 50px;' class='submode_entry' type='text' value='".$submode->hidden_native_entry."'/> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Submode type</strong></td>
					<td>".$select_submodetype."</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-floppy-o"></i> Save submode</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=submodes&ajax&action=edit_submode&submode_id='.$submode->id)."', 
		         	{'submode_name_short': $('.submode_name_short').val(), 'submode_name': $('.submode_name').val(), 
		         	'submode_entry': $('.submode_entry').val(), 
		         	'submode_submode_type_id': $('.submode_submode_type_id').val()});
     		 });
			</script>");
		}

		elseif($_REQUEST['action'] == 'submode_apply' and isset($_REQUEST['submode_id'])){

			// The delete action -> are you sure?

			if(isset($_REQUEST['delete_link_id_sure'])){
				
				pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the link between <em>'.pSubmodeName($_REQUEST['submode_id']).'</em> and <em>'.pTypeName($_REQUEST['delete_link_id_sure']).'</em>?</strong>
					<a class="actionbutton" href="'.pUrl('?admin&section=submodes&action=submode_apply&submode_id='.$_REQUEST['submode_id']).'">No</a> 
					 <a class="actionbutton" href="'.pUrl('?admin&section=submodes&action=submode_apply&submode_id='.$_REQUEST['submode_id']."&delete_link_id=".$_REQUEST['delete_link_id_sure']).'">Yes</a> <br />
				All words of the type \''.pTypeName($_REQUEST['delete_link_id_sure']).'\' will not be able to be inflected by this submode anymore.</div>');
			}

			if(isset($_REQUEST['delete_link_id']))
			{
				pSubmodeApplyDelete($_REQUEST['delete_link_id'], $_REQUEST['submode_id']);
				pUrl('?admin&section=submodes&action=submode_apply&submode_id='.$_REQUEST['submode_id'], true);
			}

			if(isset($_REQUEST['new_link']))
			{

				if(!(pExistSubmodeApply($_REQUEST['submode_id'], $_REQUEST['new_link'])) AND $_REQUEST['new_link'] != '0')
					pSubmodeApplyAdd($_REQUEST['submode_id'], $_REQUEST['new_link']);


			}
		
			$applies = pGetSubmodesApply($_REQUEST['submode_id']);
			$types = pGetTypes();


			pOut("<a class='actionbutton' href='".pUrl('?admin&section=submodes')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a> 
				<br /><br /><strong>The submode '".pSubmodeName($_REQUEST['submode_id'])."' applies to the following types:</strong><br /><br />");


			pOut("<form METHOD='POST' class='form_new_link' ACTION='".pUrl('?admin&section=submodes&action=submode_apply&submode_id='.$_REQUEST['submode_id'])."'>
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
							<a class='actionbutton' href='".pUrl('?admin&section=submodes&action=submode_apply&submode_id='.$link->submode_id."&delete_link_id_sure=".$link->type_id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete link</i></a>
						</td></tr>");

				}

			}

			pOut("</table><br /><br />");

		}

	}


	// We have not died yet!
	if(!(isset($_REQUEST['action'])) OR !(in_array($_REQUEST['action'], $die_actions))){

		// table head

		pOut("

			<a class='actionbutton' href='".pUrl('?admin&section=submodes&action=add_submode')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add submode</i></a><br /><br />

			<table class='admin'>
				<thead>
				<tr role='row' class='title'>
					<td style='width: 80px;'>ID</td>
					<td>Mode Type</td>
					<td style='width: 20%;'>Name</td>
					<td>Short name</td>
					<td>Native entry</td>
					<td>Links</td>
					<td>Actions</td>
				</tr></thead>");

		
		$submodes = pGetSubmodes();

		while($submode = $submodes->fetchObject()){


			pOut("<tr>
				<td>$submode->id</td>
				<td>".pModeTypeName($submode->mode_type_id)."</td>
				<td>$submode->name</td>
				<td>$submode->short_name</td>
				<td>$submode->hidden_native_entry</td>
				<td>".pCountSubmodesApply($submode->id)."
					<a class='actionbutton' href='".pUrl('?admin&section=submodes&action=submode_apply&submode_id='.$submode->id)."'><i class='fa fa-link' style='font-size: 12px!important;'></i> Manage links</i></a>	
				</td>
				<td><a class='actionbutton' href='".pUrl('?admin&section=submodes&action=edit_submode&submode_id='.$submode->id)."'><i class='fa fa-pencil' style='font-size: 12px!important;'></i> Edit submode</i></a>
				<a class='actionbutton' href='".pUrl('?admin&section=submodes&action=delete_submode_sure&submode_id='.$submode->id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete submode</i></a>
				</td>
			</tr>");
		}


		// table end

		pOut('</table><br />'."<a class='actionbutton' href='".pUrl('?admin&section=submodes&action=add_submode')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add submode</i></a>".'<br /><br />');

	}

	
	
	

 ?>