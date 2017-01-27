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
	$donut['page']['title'] = "Numbers - ".$donut['page']['title']; 

	$die_actions = array('add_number', 'edit_number', 'number_apply');


	// Actions and ajax

	if(isset($donut['request']['ajax']) and isset($donut['request']['action']))
	{

		if($donut['request']['action'] == 'add_number'){
			
			// Do we have all fields?

			    if($donut['request']['number_name'] == "" or $donut['request']['number_name_short'] == "" or $donut['request']['number_entry'] == "" or $donut['request']['number_entry_short'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}

     			else
     			{
     				if(pNumberAdd($donut['request']['number_name'], $donut['request']['number_name_short'], $donut['request']['number_entry'], $donut['request']['number_entry_short']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Number succesfully added. <a href="'.pUrl('?admin&section=numbers').'">Return to overview.</a></div>';
     					echo "<script>
        				$('#busyadd').delay(500).slideUp(function(){
			                  $('.succes-notice').slideDown();
			                  $('.number_name').val('');
			                  $('.number_name_short').val('');
			                  $('.number_entry').val('');
			                  $('.number_entry_short').val('');
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

		elseif($donut['request']['action'] == 'edit_number'){
			
			// Do we have all fields?
			
				if($donut['request']['number_id'] == "" or $donut['request']['number_name'] == "" or $donut['request']['number_name_short'] == "" or $donut['request']['number_entry'] == "" or $donut['request']['number_entry_short'] == "")
        		{
        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
        		}


     			else
     			{
     				if(pNumberUpdate($donut['request']['number_id'], $donut['request']['number_name'], $donut['request']['number_name_short'], $donut['request']['number_entry'], $donut['request']['number_entry_short']))
     				{
     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Number succesfully edited. <a href="'.pUrl('?admin&section=numbers').'">Return to overview.</a></div>';
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
		if($donut['request']['action'] == 'delete_number_sure' and isset($donut['request']['number_id'])){
			
			pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the number <em>'.pNumberName($donut['request']['number_id']).'</em>?</strong>
				<a class="actionbutton" href="'.pUrl('?admin&section=numbers').'">No</a> 
				 <a class="actionbutton" href="'.pUrl('?admin&section=numbers&action=delete_number&number_id='.$donut['request']['number_id']).'">Yes</a> <br />
			All words of the number '.pNumberName($donut['request']['number_id']).' will be deleted.</div>');
		}

		// The delete action
		if($donut['request']['action'] == 'delete_number' and isset($donut['request']['number_id'])){
			pNumberDelete($donut['request']['number_id']);
			pUrl('?admin&section=numbers', true);
		}


		// The action forms are going to be shown here!


		if($donut['request']['action'] == 'add_number'){


			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Number is being added...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');

			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=numbers')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Add a new number</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='number_name' type='text' /></td>
				</tr>
				<tr>
					<td><strong>Short name</strong></td>
					<td><input style='width: 100px;' class='number_name_short' type='text' /></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Name entry</strong></td>
					<td><input style='width: 50px;' class='number_entry' type='text' /> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Shorthand entry</strong></td>
					<td><input style='width: 50px;' class='number_entry_short' type='text' /> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-plus"></i> Add number</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=numbers&ajax&action=add_number')."', 
		         	{'number_name_short': $('.number_name_short').val(), 'number_name': $('.number_name').val(), 
		         	'number_entry': $('.number_entry').val(), 'number_entry_short': $('.number_entry_short').val()});
     		 });
			</script>");


		}

		elseif($donut['request']['action'] == 'edit_number' and isset($donut['request']['number_id'])){


			if(!($number = pGetNumber($donut['request']['number_id'])))
				pUrl('?admin&section=numbers', true);


			
			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Number is being edited...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');

			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=numbers')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Editing the number '".html_entity_decode($number->name)."'</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Name</strong></td>
					<td><input class='number_name' type='text'  value='".html_entity_decode($number->name)."'/></td>
				</tr>
				<tr>
					<td><strong>Short name</strong></td>
					<td><input style='width: 100px;' class='number_name_short' type='text'  value='".html_entity_decode($number->short_name)."'/></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Name entry</strong></td>
					<td><input style='width: 50px;' class='number_entry' type='text'  value='".$number->native_hidden_entry."'/> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Shorthand entry</strong></td>
					<td><input style='width: 50px;' class='number_entry_short' type='text' value='".$number->native_hidden_entry_short."'/> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-floppy-o"></i> Save number</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=numbers&ajax&action=edit_number&number_id='.$number->id)."', {'number_name_short': $('.number_name_short').val(), 'number_name': $('.number_name').val(), 'number_entry': $('.number_entry').val(), 'number_entry_short': $('.number_entry_short').val()});
     		 });
			</script>");

		}

		elseif($donut['request']['action'] == 'number_apply' and isset($donut['request']['number_id'])){

			// The delete action -> are you sure?

			if(isset($donut['request']['delete_link_id_sure'])){
				
				pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the link between <em>'.pNumberName($donut['request']['number_id']).'</em> and <em>'.pTypeName($donut['request']['delete_link_id_sure']).'</em>?</strong>
					<a class="actionbutton" href="'.pUrl('?admin&section=numbers&action=number_apply&number_id='.$donut['request']['number_id']).'">No</a> 
					 <a class="actionbutton" href="'.pUrl('?admin&section=numbers&action=number_apply&number_id='.$donut['request']['number_id']."&delete_link_id=".$donut['request']['delete_link_id_sure']).'">Yes</a> <br />
				All words of the type \''.pTypeName($donut['request']['delete_link_id_sure']).'\' will not be able to be inflected by this number anymore.</div>');
			}

			if(isset($donut['request']['delete_link_id']))
			{
				pNumberApplyDelete($donut['request']['delete_link_id'], $donut['request']['number_id']);
				pUrl('?admin&section=numbers&action=number_apply&number_id='.$donut['request']['number_id'], true);
			}

			if(isset($donut['request']['new_link']))
			{

				if(!(pExistNumberApply($donut['request']['number_id'], $donut['request']['new_link'])) AND $donut['request']['new_link'] != '0')
					pNumberApplyAdd($donut['request']['number_id'], $donut['request']['new_link']);


			}
		
			$applies = pGetNumbersApply($donut['request']['number_id']);
			$types = pGetTypes();


			pOut("<a class='actionbutton' href='".pUrl('?admin&section=numbers')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a> 
				<br /><br /><strong>The number '".pNumberName($donut['request']['number_id'])."' applies to the following types:</strong><br /><br />");


			pOut("<form METHOD='POST' class='form_new_link' ACTION='".pUrl('?admin&section=numbers&action=number_apply&number_id='.$donut['request']['number_id'])."'>
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
							<a class='actionbutton' href='".pUrl('?admin&section=numbers&action=number_apply&number_id='.$link->number_id."&delete_link_id_sure=".$link->type_id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete link</i></a>
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

			<a class='actionbutton' href='".pUrl('?admin&section=numbers&action=add_number')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add number</i></a><br /><br />

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

		
		$numbers = pGetNumbers();


		if($numbers->rowCount() == 0)
				pOut("<tr><td colspan=7>No numbers found.</tr>");


		while($number = $numbers->fetchObject()){

			pOut("<tr>
				<td>$number->id</td>
				<td>$number->name</td>
				<td>$number->short_name</td>
				<td>".pCountNumbersApply($number->id)."
<a class='actionbutton' href='".pUrl('?admin&section=numbers&action=number_apply&number_id='.$number->id)."'><i class='fa fa-link' style='font-size: 12px!important;'></i> Manage links</i></a>	
				</td>
				<td>$number->native_hidden_entry</td>
				<td>$number->native_hidden_entry_short</td>
				<td><a class='actionbutton' href='".pUrl('?admin&section=numbers&action=edit_number&number_id='.$number->id)."'><i class='fa fa-pencil' style='font-size: 12px!important;'></i> Edit number</i></a>
				<a class='actionbutton' href='".pUrl('?admin&section=numbers&action=delete_number_sure&number_id='.$number->id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete number</i></a>
				</td>
			</tr>");
		}


		// table end

		pOut('</table><br />'."<a class='actionbutton' href='".pUrl('?admin&section=numbers&action=add_number')."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add number</i></a>".'<br /><br />');

	}

	
	
	

 ?>