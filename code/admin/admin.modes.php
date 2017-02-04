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
	$donut['page']['title'] = "Manage modes - ".$donut['page']['title']; 

	$die_actions = array('add_mode', 'edit_mode', 'mode_apply', 'mode_types');


	// Actions and ajax

	if(isset($_REQUEST['ajax']) and isset($_REQUEST['action']))
	{

		if($_REQUEST['action'] == 'add_mode'){
			
			// Do we have all fields?

			    if($_REQUEST['mode_name'] == "" or $_REQUEST['mode_name_short'] == "" or $_REQUEST['mode_entry'] == "" or $_REQUEST['mode_mode_type_id'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}

     			else
     			{
     				if(pModeAdd($_REQUEST['mode_name'], $_REQUEST['mode_name_short'], $_REQUEST['mode_entry'], $_REQUEST['mode_mode_type_id']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Mode succesfully added. <a href="'.pUrl('?admin&section=modes').'">Return to overview.</a></div>';
     					echo "<script>
        				$('#busyadd').delay(500).slideUp(function(){
			                  $('.succes-notice').slideDown();
			                  $('.mode_name').val('');
			                  $('.mode_name_short').val('');
			                  $('.mode_entry').val('');
			                  $('.mode_entry_short').val('');
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

		elseif($_REQUEST['action'] == 'edit_mode'){
			
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
		if($_REQUEST['action'] == 'delete_mode_sure' and isset($_REQUEST['mode_id'])){
			
			pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the mode <em>'.pModeName($_REQUEST['mode_id']).'</em>?</strong>
				<a class="actionbutton" href="'.pUrl('?admin&section=modes').'">No</a> 
				 <a class="actionbutton" href="'.pUrl('?admin&section=modes&action=delete_mode&mode_id='.$_REQUEST['mode_id']).'">Yes</a> <br />
			All words of the mode '.pModeName($_REQUEST['mode_id']).' will be deleted.</div>');
		}

		// The delete action
		elseif($_REQUEST['action'] == 'delete_mode' and isset($_REQUEST['mode_id'])){
			pModeDelete($_REQUEST['mode_id']);
			pUrl('?admin&section=modes', true);
		}

		// The action forms are going to be shown here!


		if($_REQUEST['action'] == 'add_mode'){


			// Preparing the mode type select
       		$modetypes = pGetModeTypes();

       		$select_modetype = "<select class='mode_mode_type_id'>
       		<option value=''>Choose a mode type...</option>";

       		while($modetype = $modetypes->fetchObject()){
       			$select_modetype .= '<option value="'.$modetype->id.'">'.$modetype->name.'</option>';
       		}

       		$select_modetype .= "</select>";

			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Mode is being added...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');



			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=modes')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Add a new mode</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='mode_name' type='text' /></td>
				</tr>
				<tr>
					<td><strong>Short name</strong></td>
					<td><input style='width: 100px;' class='mode_name_short' type='text' /></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Native entry</strong></td>
					<td><input style='width: 50px;' class='mode_entry' type='text' /> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Mode type</strong></td>
					<td>".$select_modetype."</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-plus"></i> Add mode</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=modes&ajax&action=add_mode')."', 
		         	{'mode_name_short': $('.mode_name_short').val(), 'mode_name': $('.mode_name').val(), 
		         	'mode_entry': $('.mode_entry').val(), 
		         	'mode_mode_type_id': $('.mode_mode_type_id').val()});
     		 });
			</script>");


		}

		elseif($_REQUEST['action'] == 'edit_mode' and isset($_REQUEST['mode_id'])){



			if(!($mode = pGetMode($_REQUEST['mode_id'])))
				pUrl('?admin&section=modes', true);

			// Preparing the mode type select
       		$modetypes = pGetModeTypes();

       		$select_modetype = "<select class='mode_mode_type_id'>
       		<option value=''>Choose a mode type...</option>";

       		while($modetype = $modetypes->fetchObject()){
       			$select_modetype .= '<option value="'.$modetype->id.'" '.(($modetype->id == $mode->mode_type_id) ? 'selected' : '').'>'.$modetype->name.'</option>';
       		}

       		$select_modetype .= "</select>";
	

			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Mode is being edited...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');


			if(!($mode = pGetMode($_REQUEST['mode_id'])))
				pUrl('?admin&section=modes', true);


			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=modes')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Editing the mode '".$mode->name."'</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='mode_name' type='text' value='".$mode->name."'/></td>
				</tr>
				<tr>
					<td><strong>Short name</strong></td>
					<td><input style='width: 100px;' class='mode_name_short' type='text' value='".$mode->short_name."' /></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Native entry</strong></td>
					<td><input style='width: 50px;' class='mode_entry' type='text' value='".$mode->hidden_native_entry."'/> <em>the id of a (hidden) dictionary entry</em></td>
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
		         $('.ajaxloadadd').load('".pUrl('?admin&section=modes&ajax&action=edit_mode&mode_id='.$mode->id)."', 
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

		elseif($_REQUEST['action'] == 'mode_types'){

			// The delete action -> are you sure?

			if(isset($_REQUEST['delete_link_id_sure'])){
				
				pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the mode type <em>'.pModeTypeName($_REQUEST['delete_link_id_sure']).'</em>?</strong>
					<a class="actionbutton" href="'.pUrl('?admin&section=modes&action=mode_types').'">No</a> 
					 <a class="actionbutton" href="'.pUrl('?admin&section=modes&action=mode_types&delete_link_id='.$_REQUEST['delete_link_id_sure']).'">Yes</a> <br />
				All modes and submodes with this type will get deleted.</div><br />');

			}

			if(isset($_REQUEST['delete_link_id']))
			{
				pModeTypeDelete($_REQUEST['delete_link_id'], $_REQUEST['mode_id']);
				pUrl('?admin&section=modes&action=mode_types', true);
			}

			if(isset($_REQUEST['new_type']))
			{
				pModeTypeAdd(htmlentities($_REQUEST['new_type']));
			}
		
			$mode_types = pGetModeTypes();


			pOut("<a class='actionbutton' href='".pUrl('?admin&section=modes')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a> 
				<br /><br /><strong>Mode types</strong><br /><br />");


			if(!isset($_GET['edit1']) and !isset($_GET['edit2'])){
				pOut("<form METHOD='POST' class='form_new_type' ACTION='".pUrl('?admin&section=modes&action=mode_types')."'>
					<input name='new_type' />

					<a class='actionbutton' href='javascript:void(0);' onClick='$(\".form_new_type\").submit();'><i class='fa fa-plus-circle' style='font-size: 12px!important;' class='add_type'></i> Add new mode type</a></form><br />");


				pOut("<table class='admin' style='width:45%;margin: 0!important;'>
						<tr class='title'>
							<td style='width: 80px;'>Type ID</td>
							<td>Type Name</td>
							<td>Actions</td>
						</tr>");

				if($mode_types->rowCount() == 0)
					pOut("<tr><td colspan=3>No mode types found.</tr>");
				else{

					while($mode_type = $mode_types->fetchObject())
					{


						pOut("<tr><td>$mode_type->id</td><td>$mode_type->name</td>
							<td>
								<a class='actionbutton' href='".pUrl('?admin&section=modes&action=mode_types'."&edit1=".$mode_type->id)."'><i class='fa fa-pencil' style='font-size: 12px!important;'></i> Edit mode type</i></a>
								<a class='actionbutton' href='".pUrl('?admin&section=modes&action=mode_types'."&delete_link_id_sure=".$mode_type->id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete mode typek</i></a>
							</td></tr>");

					}

				}

				pOut("</table><br /><br />");				
			}
			else{

				if(isset($_GET['edit2']))
				{
					pModeTypeUpdate($_GET['edit2'], htmlentities($_REQUEST['name']));
					pUrl('?admin&section=modes&action=mode_types', true);
				}
				else{

					$mode_type = pGetModeType($_GET['edit1']);

					pOut("<form class='form' action='".pUrl('?admin&section=modes&action=mode_types&edit2='.$_GET['edit1'])."' method='POST'><table class='admin' style='width:45%;margin: 0!important;'>
						<tr class='title'>
							<td colspan=2>Editing a mode type</td>
						</tr>
						<tr>
							<td>Name: </td>
							<td><input name='name' value='".html_entity_decode($mode_type->name)."'/></td>
						</ tr>
						<tr>
							<td></td>
							<td><a class='actionbutton' href='javascript:void(0);' onClick='$(\".form\").submit();'><i class='fa fa-floppy-o' style='font-size: 12px!important;' class='add_type'></i> Save mode type</a></td>
						</tr>
						</table></form><br /><br/>");

				}

			}



		}

	}


	// We have not died yet!
	if(!(isset($_REQUEST['action'])) OR !(in_array($_REQUEST['action'], $die_actions))){

		// Offset system loading
			$total_number = pCountTable('modes');
			$offset_system = pSimpleOffsetSystem($total_number, 10, "?admin&section=modes");
			pOut($offset_system['restore_offset_script']);

		// table head


		if(isset($_REQUEST['template']) and is_numeric($_REQUEST['template'])){
			$modes = pGetModes(0, $_REQUEST['template'], "LIMIT ".$offset_system['offset'].",10");
			pOut("<strong class='inline-title'>".sprintf(ADMIN_SHOWINGOFTEMPLATE, "<em>".pModeTypeName($_REQUEST['template'])."</em>")." <a class='tooltip small' href='".pUrl('?admin&section=modes')."'>".ADMIN_SHOWALL."</a></strong><br /><br />");
		}
		else
			$modes = pGetModes(0,0, "LIMIT ".$offset_system['offset'].",10");

		pOut("
			<span class='floatright'>".ADMIN_PAGE.$offset_system['select_box']."</span>
			<a class='actionbutton' href='".pUrl('?admin&section=modes&action=add_mode')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add mode</i></a><a class='actionbutton' href='".pUrl('?admin&section=modes&action=mode_types')."'><i class='fa fa-puzzle-piece fa-12'></i> ".ADMIN_MANAGETEMPLATES."</i></a><br /><br />

			<table class='admin'>
				<thead>
				<tr role='row'  class='title'>
					<td><span class='small'>Using template</small></td>
					<td style='width: 40px;'><span class='small'>id.</span></td>
					<td style='width: 20%;'>Name</td>
					<td>".ADMIN_ABBREV."</td>
					<td>".ADMIN_USEDWITH."</td>
					<td>Actions</td>
				</tr></thead>");

		while($mode = $modes->fetchObject()){


			pOut("<tr>
				<td><span class='small'><a class='tooltip' href='".pUrl('?admin&section=modes&template='.$mode->mode_type_id)."'><i class='fa fa-puzzle-piece fa-8'></i> ".pModeTypeName($mode->mode_type_id)."</a></span></td>
				<td><span class='small'><em>$mode->id</em></span></td>
				<td><span class='small-caps'>$mode->name</span></td>
				<td><span class='small tooltip'><em>$mode->short_name</em></span></td>
				<td>
					<a class='tooltip small' href='".pUrl('?admin&section=modes&action=mode_apply&mode_id='.$mode->id)."'><i class='fa fa-link fa-10'></i> Show associations <span class='counter'>".pCountModesApply($mode->id)."</span></i></a>	
				</td>
				<td><a class='actionbutton' href='".pUrl('?admin&section=modes&action=edit_mode&mode_id='.$mode->id)."'><i class='fa fa-pencil' style='font-size: 12px!important;'></i> Edit mode</i></a>
				<a class='actionbutton' href='".pUrl('?admin&section=modes&action=delete_mode_sure&mode_id='.$mode->id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete mode</i></a>
				</td>
			</tr>");
		}


		// table end

		pOut('</table><br />'."<span class='floatright'>".ADMIN_PAGE.$offset_system['select_box']."</span><a class='actionbutton' href='".pUrl('?admin&section=modes&action=add_mode')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add mode</i></a><a class='actionbutton' href='".pUrl('?admin&section=modes&action=mode_types')."'><i class='fa fa-12 fa-puzzle-piece'></i> ".ADMIN_MANAGETEMPLATES."</i></a>".$offset_system['back_button'].$offset_system['next_button'].'<br /><br />');

	}

	
	
	

 ?>