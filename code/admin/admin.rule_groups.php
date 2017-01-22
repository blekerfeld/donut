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
	$donut['page']['title'] = "Manage rule groups- ".$donut['page']['title']; 

	$die_actions = array('add_rule_group', 'edit_rule_group');


	// Actions and ajax

	if(isset($_REQUEST['ajax']) and isset($_REQUEST['action']))
	{

		if($_REQUEST['action'] == 'add_rule_group'){
			
			// Do we have all fields?

			    if($_REQUEST['group_name'] == "" or $_REQUEST['type'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}

     			else
     			{
     				if(pRulegroupAdd($_REQUEST['group_name'], $_REQUEST['type']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Condition group succesfully added. <a href="'.pUrl('?admin&section=rule_groups').'">Return to overview.</a></div>';
     					echo "<script>
        				$('#busyadd').delay(500).slideUp(function(){
			                  $('.succes-notice').slideDown();
			                  $('.group_name').val('');
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

		elseif($_REQUEST['action'] == 'edit_rule_group'){
			
			// Do we have all fields?
			
			    if($_REQUEST['mode_name'] == "" or $_REQUEST['mode_name_short'] == "" or $_REQUEST['mode_entry'] == "" or $_REQUEST['mode_mode_type_id'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}

     			else
     			{
     				if(pModeUpdate($_REQUEST['mode_id'], $_REQUEST['mode_name'], $_REQUEST['mode_name_short'], $_REQUEST['mode_entry'], $_REQUEST['mode_mode_type_id']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Mode succesfully edited. <a href="'.pUrl('?admin&section=modes').'">Return to overview.</a></div>';
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
		if($_REQUEST['action'] == 'delete_group_sure' and isset($_REQUEST['id'])){
			
			pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the group <em>'.pRulegroupName($_REQUEST['id']).'</em>?</strong>
				<a class="actionbutton" href="'.pUrl('?admin&section=rule_groups').'">No</a> 
				 <a class="actionbutton" href="'.pUrl('?admin&section=rule_groups&action=delete_group&id='.$_REQUEST['id']).'">Yes</a> <br />
			All inflection rules within this group '.pRulegroupName($_REQUEST['id']).' will be deleted.</div>');
		}

		// The delete action
		elseif($_REQUEST['action'] == 'delete_group' and isset($_REQUEST['id'])){
			pRulegroupDelete($_REQUEST['id']);
			pUrl('?admin&section=rule_groups', true);
		}

		// The action forms are going to be shown here!


		if($_REQUEST['action'] == 'add_rule_group'){


			// Preparing the mode type select
       		$rule_groupstypes = pGetTypes();

       		$select_modetype = "<select class='type_id'>
       		<option value=''>Choose a part of speech to which the group applies...</option>";

       		while($rule_groupstype = $rule_groupstypes->fetchObject()){
       			$select_modetype .= '<option value="'.$rule_groupstype->id.'">'.$rule_groupstype->name.'</option>';
       		}

       		$select_modetype .= "</select>";

			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Group is being added...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');



			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=modes')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Add a new condition group</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='group_name' type='text' /></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Part of speech</strong></td>
					<td>".$select_modetype."</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-plus"></i> Add group</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=rule_groups&ajax&action=add_rule_group')."', 
		         	{'group_name': $('.group_name').val(), 'type': $('.type_id').val()});
     		 });
			</script>");


		}

		elseif($_REQUEST['action'] == 'edit_rule_group' and isset($_REQUEST['mode_id'])){



			if(!($rule_groups = pGetMode($_REQUEST['mode_id'])))
				pUrl('?admin&section=modes', true);

			// Preparing the mode type select
       		$rule_groupstypes = pGetModeTypes();

       		$select_modetype = "<select class='mode_mode_type_id'>
       		<option value=''>Choose a mode type...</option>";

       		while($rule_groupstype = $rule_groupstypes->fetchObject()){
       			$select_modetype .= '<option value="'.$rule_groupstype->id.'" '.(($rule_groupstype->id == $rule_groups->mode_type_id) ? 'selected' : '').'>'.$rule_groupstype->name.'</option>';
       		}

       		$select_modetype .= "</select>";
	

			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Mode is being edited...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');


			if(!($rule_groups = pGetMode($_REQUEST['mode_id'])))
				pUrl('?admin&section=modes', true);


			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=modes')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Editing the mode '".$rule_groups->name."'</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='mode_name' type='text' value='".$rule_groups->name."'/></td>
				</tr>
				<tr>
					<td><strong>Short name</strong></td>
					<td><input style='width: 100px;' class='mode_name_short' type='text' value='".$rule_groups->short_name."' /></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Native entry</strong></td>
					<td><input style='width: 50px;' class='mode_entry' type='text' value='".$rule_groups->hidden_native_entry."'/> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Mode type</strong></td>
					<td>".$select_modetype."</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-floppy-o"></i> Save mode</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=modes&ajax&action=edit_rule_group&mode_id='.$rule_groups->id)."', 
		         	{'mode_name_short': $('.mode_name_short').val(), 'mode_name': $('.mode_name').val(), 
		         	'mode_entry': $('.mode_entry').val(), 
		         	'mode_mode_type_id': $('.mode_mode_type_id').val()});
     		 });
			</script>");
		}

		elseif($_REQUEST['action'] == 'mode_apply' and isset($_REQUEST['mode_id'])){

			// The delete action -> are you sure?

			if(isset($_REQUEST['delete_link_id_sure'])){
				
				pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the link between <em>'.pModeName($_REQUEST['mode_id']).'</em> and <em>'.pTypeName($_REQUEST['delete_link_id_sure']).'</em>?</strong>
					<a class="actionbutton" href="'.pUrl('?admin&section=modes&action=mode_apply&mode_id='.$_REQUEST['mode_id']).'">No</a> 
					 <a class="actionbutton" href="'.pUrl('?admin&section=modes&action=mode_apply&mode_id='.$_REQUEST['mode_id']."&delete_link_id=".$_REQUEST['delete_link_id_sure']).'">Yes</a> <br />
				All words of the type \''.pTypeName($_REQUEST['delete_link_id_sure']).'\' will not be able to be inflected by this mode anymore.</div>');
			}

			if(isset($_REQUEST['delete_link_id']))
			{
				pModeApplyDelete($_REQUEST['delete_link_id'], $_REQUEST['mode_id']);
				pUrl('?admin&section=modes&action=mode_apply&mode_id='.$_REQUEST['mode_id'], true);
			}

			if(isset($_REQUEST['new_link']))
			{

				if(!(pExistModeApply($_REQUEST['mode_id'], $_REQUEST['new_link'])) AND $_REQUEST['new_link'] != '0')
					pModeApplyAdd($_REQUEST['mode_id'], $_REQUEST['new_link']);


			}
		
			$applies = pGetModesApply($_REQUEST['mode_id']);
			$types = pGetTypes();


			pOut("<a class='actionbutton' href='".pUrl('?admin&section=modes')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a> 
				<br /><br /><strong>The mode '".pModeName($_REQUEST['mode_id'])."' applies to the following types:</strong><br /><br />");


			pOut("<form METHOD='POST' class='form_new_link' ACTION='".pUrl('?admin&section=modes&action=mode_apply&mode_id='.$_REQUEST['mode_id'])."'>
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
							<a class='actionbutton' href='".pUrl('?admin&section=modes&action=mode_apply&mode_id='.$link->mode_id."&delete_link_id_sure=".$link->type_id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete link</i></a>
						</td></tr>");

				}

			}

			pOut("</table><br /><br />");

		}

	}


	// We have not died yet!
	if(!(isset($_REQUEST['action'])) OR !(in_array($_REQUEST['action'], $die_actions))){

		// Offset system loading
			$total_number = pCountTable('submode_groups');
			$offset_system = pSimpleOffsetSystem($total_number, 20, "?admin&section=rule_groups");
			pOut($offset_system['restore_offset_script']);

		// table head

		pOut("
			<span class='floatright'>Page: ".$offset_system['select_box']."</span>
			<a class='actionbutton' href='".pUrl('?admin&section=rule_groups&action=add_rule_group')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> New condition group</i></a>".$offset_system['back_button'].$offset_system['next_button']."<br /><br />

			<table class='admin' id='#DataTable'>
				<thead>
				<tr role='row' class='title'>
					<td style='width: 80px;'>ID</td>
					<td style='width: 50%;'>Name</td>
					<td>Type</td>
					<td>Actions</td>
				</tr></thead>");



		$rule_groups = pGetRulegroups("LIMIT ".$offset_system['offset'].",20");

		while($rule_group = $rule_groups->fetchObject()){


			pOut("<tr>
				<td>$rule_group->id</td>
				<td>$rule_group->name</td>
				
				<td>".pTypeName($rule_group->type_id)."</td>

				<td>
					<a class='actionbutton' href='".pUrl('?admin&section=rule_groups&action=manage_members&id='.$rule_group->id)."'><i class='fa fa-link' style='font-size: 12px!important;'></i> Manage members</i></a>	
				
					<a class='actionbutton' href='".pUrl('?admin&section=rule_groups&action=manage_scripts&id='.$rule_group->id)."'><i class='fa fa-file-code-o' style='font-size: 12px!important;'></i> Manage scripts</i></a>	
				<a class='actionbutton' href='".pUrl('?admin&section=modes&action=edit_rule_group&mode_id='.$rule_group->id)."'><i class='fa fa-pencil' style='font-size: 12px!important;'></i> Edit group</i></a>
				<a class='actionbutton' href='".pUrl('?admin&section=rule_groups&action=delete_group_sure&id='.$rule_group->id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete group</i></a>
				</td>
			</tr>");
		}


		// table end

		pOut('</table><br /><br />'."<span class='floatright'>Page: ".$offset_system['select_box']."</span><a class='actionbutton' href='".pUrl('?admin&section=modes&action=add_rule_group')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> New condition group</i></a>".$offset_system['back_button'].$offset_system['next_button'].'<br /><br />');

	}

	
	
	

 ?>