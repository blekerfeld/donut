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
	$donut['page']['title'] = "Types - ".$donut['page']['title']; 

	$die_actions = array('add_type', 'edit_type');



	// Actions and ajax

	if(isset($_REQUEST['ajax']) and isset($_REQUEST['action']))
	{

		if($_REQUEST['action'] == 'add_type'){
			
			// Do we have all fields?

			    if($_REQUEST['type_name'] == "" or $_REQUEST['type_name_short'] == "" or $_REQUEST['type_entry'] == "" or $_REQUEST['type_entry_short'] == "" or $_REQUEST['type_inflect_classifications'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}

     			else
     			{
     				if(pTypeAdd($_REQUEST['type_name'], $_REQUEST['type_name_short'], $_REQUEST['type_entry'], $_REQUEST['type_entry_short'], $_REQUEST['type_inflect_classifications']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Type succesfully added. <a href="'.pUrl('?admin&section=types').'">Return to overview.</a></div>';
     					echo "<script>
        				$('#busyadd').delay(500).slideUp(function(){
			                  $('.succes-notice').slideDown();
			                  $('.type_name').val('');
			                  $('.type_name_short').val('');
			                  $('.type_entry').val('');
			                  $('.type_entry_short').val('');
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

		elseif($_REQUEST['action'] == 'edit_type'){
			
			// Do we have all fields?
			
				if($_REQUEST['type_id'] == "" or $_REQUEST['type_name'] == "" or $_REQUEST['type_name_short'] == "" or $_REQUEST['type_entry'] == "" or $_REQUEST['type_entry_short'] == "" or $_REQUEST['type_inflect_classifications'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}


     			else
     			{
     				if(pTypeUpdate($_REQUEST['type_id'], $_REQUEST['type_name'], $_REQUEST['type_name_short'], $_REQUEST['type_entry'], $_REQUEST['type_entry_short'], $_REQUEST['type_inflect_classifications']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Type succesfully edited. <a href="'.pUrl('?admin&section=types').'">Return to overview.</a></div>';
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
		if($_REQUEST['action'] == 'delete_type_sure' and isset($_REQUEST['type_id'])){
			
			pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the type <em>'.pTypeName($_REQUEST['type_id']).'</em>?</strong>
				<a class="actionbutton" href="'.pUrl('?admin&section=types').'">No</a> 
				 <a class="actionbutton" href="'.pUrl('?admin&section=types&action=delete_type&type_id='.$_REQUEST['type_id']).'">Yes</a> <br />
			All words of the type '.pTypeName($_REQUEST['type_id']).' will be deleted.</div>');
		}

		// The delete action
		if($_REQUEST['action'] == 'delete_type' and isset($_REQUEST['type_id'])){
			pTypeDelete($_REQUEST['type_id']);
			pUrl('?admin&section=types', true);
		}


		// The action forms are going to be shown here!


		if($_REQUEST['action'] == 'add_type'){


			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Type is being added...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');

			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=types')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Add a new type</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='type_name' type='text' /></td>
				</tr>
				<tr>
					<td><strong>Short name</strong></td>
					<td><input style='width: 100px;' class='type_name_short' type='text' /></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Name entry</strong></td>
					<td><input style='width: 50px;' class='type_entry' type='text' /> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Shorthand entry</strong></td>
					<td><input style='width: 50px;' class='type_entry_short' type='text' /> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Inflection</strong></td>
					<td><select class='type_inflect_classifications'>
						<option value='0'>Don't inflect by its classifications</option>
						<option value='1'>Inflect by its classifications</option>
					</select></td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-plus"></i> Add part of speech</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=types&ajax&action=add_type')."', {'type_name_short': $('.type_name_short').val(), 'type_name': $('.type_name').val(), 'type_entry': $('.type_entry').val(), 'type_entry_short': $('.type_entry_short').val(),'type_inflect_classifications': $('.type_inflect_classifications').val()});
     		 });
			</script>");


		}

		elseif($_REQUEST['action'] == 'edit_type' and isset($_REQUEST['type_id'])){


			if(!($type = pGetType($_REQUEST['type_id'])))
				pUrl('?admin&section=types', true);


			
			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Type is being edited...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');

			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=types')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Editing the type '".html_entity_decode($type->name)."'</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='type_name' type='text'  value='".html_entity_decode($type->name)."'/></td>
				</tr>
				<tr>
					<td><strong>Short name</strong></td>
					<td><input style='width: 100px;' class='type_name_short' type='text'  value='".html_entity_decode($type->short_name)."'/></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Name entry</strong></td>
					<td><input style='width: 50px;' class='type_entry' type='text'  value='".$type->native_hidden_entry."'/> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Shorthand entry</strong></td>
					<td><input style='width: 50px;' class='type_entry_short' type='text' value='".$type->native_hidden_entry_short."'/> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Inflection</strong></td>
					<td><select class='type_inflect_classifications'>
						<option value='0'>Don't inflect by its classifications</option>
						<option value='1' ".(($type->inflect_classifications == 1) ? 'selected' : '').">Inflect by its classifications</option>
					</select></td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-floppy-o"></i> Save type</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=types&ajax&action=edit_type&type_id='.$type->id)."', {'type_name_short': $('.type_name_short').val(), 'type_name': $('.type_name').val(), 'type_entry': $('.type_entry').val(), 'type_entry_short': $('.type_entry_short').val(),'type_inflect_classifications': $('.type_inflect_classifications').val()});
     		 });
			</script>");


		}



		

	}


	// We have not died yet!
	if(!(isset($_REQUEST['action'])) OR !(in_array($_REQUEST['action'], $die_actions))){

		// Offset system loading
			$total_number = pCountTable('types');
			$offset_system = pSimpleOffsetSystem($total_number, 10, "?admin&section=types");
			pOut($offset_system['restore_offset_script']);

		// table head

		pOut("<span class='floatright'>Page: ".$offset_system['select_box']."</span>
			<a class='actionbutton' href='".pUrl('?admin&section=types&action=add_type')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add part of speech</i></a>".$offset_system['back_button'].$offset_system['next_button']."
			<br />
			<br />
			<table class='admin' id='datatable'>
				<thead>
				<tr role='row' class='title'>
					<td style='width: 80px;'>ID</td>
					<td style='width: 20%;'>Name</td>
					<td style='width: 150px;'>Short name</td>
					<td>Native entry</td>
					<td>Shorthand entry</td>
					<td>Inflect by classifications?</td>
					<td>Actions</td>
				</tr></thead>");

		
		$types = pGetTypes("LIMIT ".$offset_system['offset'].",10");


		if($types->rowCount() == 0)
				pOut("<tr><td colspan=7>No types found.</tr>");


		while($type = $types->fetchObject()){

			if($type->inflect_classifications == 1)
				$infl_text = "Yes";
			else
				$infl_text = "No";

			pOut("<tr>
				<td>$type->id</td>
				<td>$type->name</td>
				<td>$type->short_name</td>
				<td>$type->native_hidden_entry</td>
				<td>$type->native_hidden_entry_short</td>
				<td>$infl_text</td>
				<td><a class='actionbutton' href='".pUrl('?admin&section=types&action=edit_type&type_id='.$type->id)."'><i class='fa fa-pencil' style='font-size: 12px!important;'></i> Edit</i></a>
				<a class='actionbutton' href='".pUrl('?admin&section=types&action=delete_type_sure&type_id='.$type->id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete</i></a>
				</td>
			</tr>");
		}


		// table end

		pOut('</table><br />'."<span class='floatright'>Page: ".$offset_system['select_box']."</span><a class='actionbutton' href='".pUrl('?admin&section=types&action=add_type')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add part of speech</i></a>".$offset_system['back_button'].$offset_system['next_button'].'<br /><br />');

	}

	
	
	

 ?>