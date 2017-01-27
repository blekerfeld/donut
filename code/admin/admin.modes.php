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

	if(isset($donut['request']['ajax']) and isset($donut['request']['action']))
	{

		if($donut['request']['action'] == 'add_mode'){
			
			// Do we have all fields?

			    if($donut['request']['mode_name'] == "" or $donut['request']['mode_name_short'] == "" or $donut['request']['mode_entry'] == "" or $donut['request']['mode_mode_type_id'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}

     			else
     			{
     				if(pModeAdd($donut['request']['mode_name'], $donut['request']['mode_name_short'], $donut['request']['mode_entry'], $donut['request']['mode_mode_type_id']))
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

		elseif($donut['request']['action'] == 'edit_mode'){
			
			// Do we have all fields?
			
			    if($donut['request']['mode_name'] == "" or $donut['request']['mode_name_short'] == "" or $donut['request']['mode_entry'] == "" or $donut['request']['mode_mode_type_id'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}

     			else
     			{
     				if(pModeUpdate($donut['request']['mode_id'], $donut['request']['mode_name'], $donut['request']['mode_name_short'], $donut['request']['mode_entry'], $donut['request']['mode_mode_type_id']))
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

	elseif(isset($donut['request']['action']))
	{


		// The delete action -> are you sure?
		if($donut['request']['action'] == 'delete_mode_sure' and isset($donut['request']['mode_id'])){
			
			pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the mode <em>'.pModeName($donut['request']['mode_id']).'</em>?</strong>
				<a class="actionbutton" href="'.pUrl('?admin&section=modes').'">No</a> 
				 <a class="actionbutton" href="'.pUrl('?admin&section=modes&action=delete_mode&mode_id='.$donut['request']['mode_id']).'">Yes</a> <br />
			All words of the mode '.pModeName($donut['request']['mode_id']).' will be deleted.</div>');
		}

		// The delete action
		elseif($donut['request']['action'] == 'delete_mode' and isset($donut['request']['mode_id'])){
			pModeDelete($donut['request']['mode_id']);
			pUrl('?admin&section=modes', true);
		}

		// The action forms are going to be shown here!


		if($donut['request']['action'] == 'add_mode'){


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

		elseif($donut['request']['action'] == 'edit_mode' and isset($donut['request']['mode_id'])){



			if(!($mode = pGetMode($donut['request']['mode_id'])))
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


			if(!($mode = pGetMode($donut['request']['mode_id'])))
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

		elseif($donut['request']['action'] == 'mode_apply' and isset($donut['request']['mode_id'])){

			// The delete action -> are you sure?

			if(isset($donut['request']['delete_link_id_sure'])){
				
				pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the link between <em>'.pModeName($donut['request']['mode_id']).'</em> and <em>'.pTypeName($donut['request']['delete_link_id_sure']).'</em>?</strong>
					<a class="actionbutton" href="'.pUrl('?admin&section=modes&action=mode_apply&mode_id='.$donut['request']['mode_id']).'">No</a> 
					 <a class="actionbutton" href="'.pUrl('?admin&section=modes&action=mode_apply&mode_id='.$donut['request']['mode_id']."&delete_link_id=".$donut['request']['delete_link_id_sure']).'">Yes</a> <br />
				All words of the type \''.pTypeName($donut['request']['delete_link_id_sure']).'\' will not be able to be inflected by this mode anymore.</div>');
			}

			if(isset($donut['request']['delete_link_id']))
			{
				pModeApplyDelete($donut['request']['delete_link_id'], $donut['request']['mode_id']);
				pUrl('?admin&section=modes&action=mode_apply&mode_id='.$donut['request']['mode_id'], true);
			}

			if(isset($donut['request']['new_link']))
			{


				if(!(pExistModeApply($donut['request']['mode_id'], $donut['request']['new_link'])) AND $donut['request']['new_link'] != '0')
					pModeApplyAdd($donut['request']['mode_id'], $donut['request']['new_link']);


			}
		
			$applies = pGetModesApply($donut['request']['mode_id']);
			$types = pGetTypes();


			pOut("<a class='actionbutton' href='".pUrl('?admin&section=modes')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a> 
				<br /><br /><strong>The mode '".pModeName($donut['request']['mode_id'])."' applies to the following types:</strong><br /><br />");


			pOut("<form METHOD='POST' class='form_new_link' ACTION='".pUrl('?admin&section=modes&action=mode_apply&mode_id='.$donut['request']['mode_id'])."'>
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

		elseif($donut['request']['action'] == 'mode_types'){

			// The delete action -> are you sure?

			if(isset($donut['request']['delete_link_id_sure'])){
				
				pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the mode type <em>'.pModeTypeName($donut['request']['delete_link_id_sure']).'</em>?</strong>
					<a class="actionbutton" href="'.pUrl('?admin&section=modes&action=mode_types').'">No</a> 
					 <a class="actionbutton" href="'.pUrl('?admin&section=modes&action=mode_types&delete_link_id='.$donut['request']['delete_link_id_sure']).'">Yes</a> <br />
				All modes and submodes with this type will get deleted.</div><br />');

			}

			if(isset($donut['request']['delete_link_id']))
			{
				pModeTypeDelete($donut['request']['delete_link_id'], $donut['request']['mode_id']);
				pUrl('?admin&section=modes&action=mode_types', true);
			}

			if(isset($donut['request']['new_type']))
			{
				pModeTypeAdd(htmlentities($donut['request']['new_type']));
			}
		
			$mode_types = pGetModeTypes();


			pOut("<a class='actionbutton' href='".pUrl('?admin&section=modes')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a> 
				<br /><br /><strong>Mode types</strong><br /><br />");


			if(!isset($donut['get']['edit1']) and !isset($donut['get']['edit2'])){
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

				if(isset($donut['get']['edit2']))
				{
					pModeTypeUpdate($donut['get']['edit2'], htmlentities($donut['request']['name']));
					pUrl('?admin&section=modes&action=mode_types', true);
				}
				else{

					$mode_type = pGetModeType($donut['get']['edit1']);

					pOut("<form class='form' action='".pUrl('?admin&section=modes&action=mode_types&edit2='.$donut['get']['edit1'])."' method='POST'><table class='admin' style='width:45%;margin: 0!important;'>
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
	if(!(isset($donut['request']['action'])) OR !(in_array($donut['request']['action'], $die_actions))){

		// table head

		pOut("

			<a class='actionbutton' href='".pUrl('?admin&section=modes&action=add_mode')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add mode</i></a><a class='actionbutton' href='".pUrl('?admin&section=modes&action=mode_types')."'><i class='fa fa-list-alt' style='font-size: 12px!important;'></i> Manage mode types</i></a><br /><br />

			<table class='admin'>
				<thead>
				<tr role='row'  class='title'>
					<td style='width: 80px;'>ID</td>
					<td>Mode type</td>
					<td style='width: 20%;'>Name</td>
					<td>Short name</td>
					<td>Native entry</td>
					<td>Links</td>
					<td>Actions</td>
				</tr></thead>");

		
		$modes = pGetModes();

		while($mode = $modes->fetchObject()){


			pOut("<tr>
				<td>$mode->id</td>
				<td>".pModeTypeName($mode->mode_type_id)."</td>
				<td>$mode->name</td>
				<td>$mode->short_name</td>
				<td>$mode->hidden_native_entry</td>
				<td>".pCountModesApply($mode->id)."
					<a class='actionbutton' href='".pUrl('?admin&section=modes&action=mode_apply&mode_id='.$mode->id)."'><i class='fa fa-link' style='font-size: 12px!important;'></i> Manage links</i></a>	
				</td>
				<td><a class='actionbutton' href='".pUrl('?admin&section=modes&action=edit_mode&mode_id='.$mode->id)."'><i class='fa fa-pencil' style='font-size: 12px!important;'></i> Edit mode</i></a>
				<a class='actionbutton' href='".pUrl('?admin&section=modes&action=delete_mode_sure&mode_id='.$mode->id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete mode</i></a>
				</td>
			</tr>");
		}


		// table end

		pOut('</table><br />'."<a class='actionbutton' href='".pUrl('?admin&section=modes&action=add_mode')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add mode</i></a><a class='actionbutton' href='".pUrl('?admin&section=modes&action=mode_types')."'><i class='fa fa-list-alt' style='font-size: 12px!important;'></i> Manage mode types</i></a>".'<br /><br />');

	}

	
	
	

 ?>