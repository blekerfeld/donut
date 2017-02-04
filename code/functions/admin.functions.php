<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: admin.functions.php

	function pManageInflectionElement($section, $element, $element_plural, $pagename, $link_type = "ModeType"){

		global $donut;

		// Declaring the name of some functions
		$getFunctionPlural = "pGet".ucfirst($element_plural);
		$getFunctionSingular = "pGet".ucfirst($element);
		$nameFunction = "p".ucfirst($element)."Name";
		$addFunction = "p".ucfirst($element)."Add";
		$updateFunction = "p".ucfirst($element)."Update";
		$deleteFunction = "p".ucfirst($element)."Delete";
		$add_applyFunction = "p".ucfirst($element)."ApplyAdd";
		$delete_applyFunction = "p".ucfirst($element)."ApplyDelete";
		$exist_applyFunction = "pExist".ucfirst($element)."Apply";
		$get_applyFunction = $getFunctionPlural."Apply";
		$get_linkFunction = "pGet".$link_type."s";
		$countLinksFunction = "pCount".$element_plural."Apply";
		$elementID = $element."_id";

		// Manage name
		$donut['page']['title'] = "$pagename- ".$donut['page']['title']; 
		
		// For what do we have to die?
		$die_actions = array('add_'.$element, 'edit_'.$element, $element.'_apply');

		// Actions and ajax

		if(isset($_REQUEST['ajax']) and isset($_REQUEST['action']))
		{

			if($_REQUEST['action'] == 'add_'.$element){
				
				// Do we have all fields?

				    if($_REQUEST[$element.'_name'] == "" or $_REQUEST[$element.'_name_short'] == "" or $_REQUEST[$element.'_entry'] == "")
	        		{
	        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
	        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
	        		}

	     			else
	     			{
	     				if($addFunction($_REQUEST[$element.'_name'], $_REQUEST[$element.'_name_short'], $_REQUEST[$element.'_entry']))
	     				{
	     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Submode succesfully added. <a href="'.pUrl('?admin&section='.$section).'">Return to overview.</a></div>';
	     					echo "<script>
	        				$('#busyadd').delay(500).slideUp(function(){
				                  $('.succes-notice').slideDown();
				                  $('.element_name').val('');
				                  $('.element_name_short').val('');
				                  $('.element_entry').val('');
				                  $('.element_entry_short').val('');
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

			elseif($_REQUEST['action'] == 'edit_'.$element){
				
				// Do we have all fields?
				
				    if($_REQUEST[$element.'_name'] == "" or $_REQUEST[$element.'_name_short'] == "" or $_REQUEST[$element.'_entry'] == "")
	        		{
	        			echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Please submit all fields.</div>';
	        			echo "<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
	        		}

	     			else
	     			{
	     				if($updateFunction($_REQUEST[$elementID], $_REQUEST[$element.'_name'], $_REQUEST[$element.'_name_short'], $_REQUEST[$element.'_entry']))
	     				{
	     					echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Submode succesfully edited. <a href="'.pUrl('?admin&section='.$section).'">Return to overview.</a></div>';
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
			if($_REQUEST['action'] == 'delete_element_sure' and isset($_REQUEST[$elementID])){
				
				pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the submode <em>'.$nameFunction($_REQUEST[$elementID]).'</em>?</strong>
					<a class="actionbutton" href="'.pUrl('?admin&section='.$section).'">No</a> 
					 <a class="actionbutton" href="'.pUrl('?admin&section='.$section.'&action=delete_submode&element_id='.$_REQUEST[$elementID]).'">Yes</a> <br />
				All words of the submode '.$nameFunction($_REQUEST[$elementID]).' will be deleted.</div>');
			}

			// The delete action
			elseif($_REQUEST['action'] == 'delete_'.$element and isset($_REQUEST[$elementID])){
				$deleteFunction($_REQUEST[$elementID]);
				pUrl('?admin&section='.$section, true);
			}

			// The action forms are going to be shown here!


			if($_REQUEST['action'] == 'add_'.$element){



				pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Submode is being added...</div>
	       		<div style="width: 75%" class="ajaxloadadd"></div>');



				pOut("<table class='admin' id='empty' style='width:75%'>
					<tr class='title'>
						<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section='.$section)."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
						<td colspan=2>Add a new submode</td>
					</tr>
					<tr><td></td><td></td></tr>
					<tr>
						<td style='width: 150px;'><strong>Name</strong></td>
						<td><input class='{$element}_name' type='text' /></td>
					</tr>
					<tr>
						<td><strong>Short name</strong></td>
						<td><input style='width: 100px;' class='{$element}_name_short' type='text' /></td>
					</tr>
					<tr>
						<td style='width: 150px;'><strong>Native entry</strong></td>
						<td><input style='width: 50px;' class='{$element}_entry' type='text' /> <em>the id of a (hidden) dictionary entry</em></td>
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
			         $('.ajaxloadadd').load('".pUrl('?admin&section='.$section.'&ajax&action=add_'.$element)."', 
			         	{'{$element}_name_short': $('.{$element}_name_short').val(), '{$element}_name': $('.{$element}_name').val(), 
			         	'{$element}_entry': $('.{$element}_entry').val()});
	     		 });
				</script>");


			}

			elseif($_REQUEST['action'] == 'edit_'.$element and isset($_REQUEST[$elementID])){



				if(!($submode = $getFunctionSingular($_REQUEST[$elementID])))
					pUrl('?admin&section='.$section, true);

		

				pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Submode is being edited...</div>
	       		<div style="width: 75%" class="ajaxloadadd"></div>');


				if(!($submode = $getFunctionSingular($_REQUEST[$elementID])))
					pUrl('?admin&section='.$section, true);


				pOut("<table class='admin' id='empty' style='width:75%'>
					<tr class='title'>
						<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section='.$section)."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
						<td colspan=2>Editing the submode '".$submode->name."'</td>
					</tr>
					<tr><td></td><td></td></tr>
					<tr>
						<td style='width: 150px;'><strong>Name</strong></td>
						<td><input class='{$element}_name' type='text' value='".$submode->name."'/></td>
					</tr>
					<tr>
						<td><strong>Short name</strong></td>
						<td><input style='width: 100px;' class='{$element}_name_short' type='text' value='".$submode->short_name."' /></td>
					</tr>
					<tr>
						<td style='width: 150px;'><strong>Native entry</strong></td>
						<td><input style='width: 50px;' class='{$element}_entry' type='text' value='".$submode->hidden_native_entry."'/> <em>the id of a (hidden) dictionary entry</em></td>
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
			         $('.ajaxloadadd').load('".pUrl('?admin&section='.$section.'&ajax&action=edit_submode&'.$element.'_id='.$submode->id)."', 
			         	{'{$element}_name_short': $('.{$element}_name_short').val(), '{$element}_name': $('.{$element}_name').val(), 
			         	'{$element}_entry': $('.{$element}_entry').val()});
	     		 });
				</script>");
			}

			elseif($_REQUEST['action'] == $element.'_apply' and isset($_REQUEST[$elementID])){

				// The delete action -> are you sure?

				if(isset($_REQUEST['delete_link_id_sure'])){
					
					pOut('<div class="notice" id="empty"><i class="fa fa-question-circle"></i> <strong>Are you sure you want to delete the link between <em>'.$nameFunction($_REQUEST[$elementID]).'</em> and <em>'.pModeTypeName($_REQUEST['delete_link_id_sure']).'</em>?</strong>
						<a class="actionbutton" href="'.pUrl('?admin&section='.$section.'&action='.$element.'_apply&'.$element.'_id='.$_REQUEST[$elementID]).'">No</a> 
						 <a class="actionbutton" href="'.pUrl('?admin&section='.$section.'&action='.$element.'_apply&'.$element.'_id='.$_REQUEST[$elementID]."&delete_link_id=".$_REQUEST['delete_link_id_sure']).'">Yes</a> <br />
					Any inflections linked to this submode won\'t appear in the table of the modes of this type any more.'.pTypeName($_REQUEST['delete_link_id_sure']).'\' will not be able to be inflected by this submode anymore.</div>');
				}

				if(isset($_REQUEST['delete_link_id']))
				{
					$delete_applyFunction($_REQUEST['delete_link_id'], $_REQUEST[$elementID]);
					pUrl('?admin&section='.$section.'&action='.$element_apply.'apply&'.$element.'_id='.$_REQUEST[$elementID], true);
				}

				if(isset($_REQUEST['new_link']))
				{

					if(!($exist_applyFunction($_REQUEST[$elementID], $_REQUEST['new_link'])) AND $_REQUEST['new_link'] != '0')
						$add_applyFunction($_REQUEST[$elementID], $_REQUEST['new_link']);


				}
			
				$applies = $get_applyFunction($_REQUEST[$elementID]);
				$types = $get_linkFunction();


				pOut("<a class='actionbutton' href='".pUrl('?admin&section='.$section)."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a> 
					<br /><br /><strong>The submode '".$nameFunction($_REQUEST[$elementID])."' applies to the following types:</strong><br /><br />");


				pOut("<form METHOD='POST' class='form_new_link' ACTION='".pUrl('?admin&section='.$section.'&action='.$element.'_apply&'.$element.'_id='.$_REQUEST[$elementID])."'>
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
						$type = pGetModeType($link->mode_type_id);

						pOut("<tr><td>$type->id</td><td>$type->name</td>
							<td>
								<a class='actionbutton' href='".pUrl('?admin&section='.$section.'&action='.$element.'_apply&'.$element.'_id='.$link->$elementID."&delete_link_id_sure=".$link->mode_type_id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete link</i></a>
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

				<a class='actionbutton' href='".pUrl('?admin&section='.$section.'&action=add_'.$element)."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add submode</i></a><br /><br />

				<table class='admin'>
					<thead>
					<tr role='row' class='title'>
						<td style='width: 80px;'>ID</td>
						<td style='width:40%;'>Name</td>
						<td>Short name</td>
						<td>Native entry</td>
						<td>Links</td>
						<td>Actions</td>
					</tr></thead>");

			
			$submodes = $getFunctionPlural();

			while($submode = $submodes->fetchObject()){


				pOut("<tr>
					<td>$submode->id</td>
					<td>$submode->name</td>
					<td>$submode->short_name</td>
					<td>$submode->hidden_native_entry</td>
					<td>".$countLinksFunction($submode->id)."
						<a class='actionbutton' href='".pUrl('?admin&section='.$section.'&action='.$element.'_apply&'.$element.'_id='.$submode->id)."'><i class='fa fa-link' style='font-size: 12px!important;'></i> Manage links</i></a>	
					</td>
					<td><a class='actionbutton' href='".pUrl('?admin&section='.$section.'&action=edit_submode&'.$element.'_id='.$submode->id)."'><i class='fa fa-pencil' style='font-size: 12px!important;'></i> Edit submode</i></a>
					<a class='actionbutton' href='".pUrl('?admin&section='.$section.'&action=delete(oid)te_submode_sure&'.$element.'_id='.$submode->id)."'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete submode</i></a>
					</td>
				</tr>");
			}


			// table end

			pOut('</table><br />'."<a class='actionbutton' href='".pUrl('?admin&section='.$section.'&action=add_'.$element)."'><i class='fa fa-plus-circle' style='font-size: 12px!important;'></i> Add submode</i></a>".'<br /><br />');

		}





	}