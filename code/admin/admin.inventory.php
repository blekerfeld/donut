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
	$donut['page']['title'] = "Phonological inventory - ".$donut['page']['title']; 

	$die_actions = array('add_copy', 'edit_mode', 'mode_apply', 'mode_types');


	// Actions and ajax

	if(isset($_REQUEST['ajax']) and isset($_REQUEST['action']))
	{

		if($_REQUEST['action'] == 'toggle' AND isset($_REQUEST['c']))
		{

			pQuery("UPDATE ipa_c SET active = IF(ipa_c.active=1, 0, 1) WHERE id = ".$_REQUEST['c']." LIMIT 1;");
			pOut("<script>$('.c-".$_REQUEST['c']."').toggleClass('selected');</script>");

		}
		elseif($_REQUEST['action'] == 'toggle' AND isset($_REQUEST['v']))
		{

			pQuery("UPDATE ipa_v SET active = IF(ipa_v.active=1, 0, 1) WHERE id = ".$_REQUEST['v']." LIMIT 1;");

		}

		if($_REQUEST['action'] == 'new_poly' and isset($_REQUEST['poly'])){

			// New poly


			if(count($_REQUEST['poly']) == 1 OR count($_REQUEST['poly']) == 0)
			{

				echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> A polyphthong needs to consist of two or more phonemes.</div>';
        		echo "<script>$('#empty').fadeIn().effect('bounce', {duration: 800}).delay(500).fadeOut('slow');</script>";
        		die();

			}
			$poly_string = implode(',', $_REQUEST['poly']);

			// We will look if this already exists

			$exists = pQuery("SELECT id FROM ipa_polyphthongs WHERE combination = '$poly_string';");


			if($exists->rowCount() == 1){

				echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> The polyphthong '.pParsePolyphthong($poly_string).' already exists.</div>';
        		echo "<script>$('#empty').fadeIn().effect('bounce', {duration: 800}).delay(500).fadeOut('slow');</script>";
        		die();

			}

			else{

				pQuery("INSERT INTO ipa_polyphthongs(combination) VALUES ('".$poly_string."');");
				echo '<div class="notice hide succes-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> The polyphthong '.pParsePolyphthong($poly_string).' was succesfully added to the inventory!</div>';
        		echo "<script>$('#empty').fadeIn().effect('bounce', {duration: 800}).delay(500).fadeOut('slow');setTimeout(function() { loadfunction('".pUrl('?admin&section=inventory')."'); }, 1300);</script>";

			}

		}

		if($_REQUEST['action'] == 'delete_poly' and isset($_REQUEST['poly'])){

			pQuery("DELETE FROM ipa_polyphthongs WHERE id = ".$_REQUEST['poly']);

		}

		die();
	}

	// Actions

	elseif(isset($_REQUEST['action']))
	{


	// Actions

		if($_REQUEST['action'] == 'add_copy' AND isset($_REQUEST['of'])){

			pOut('<div style="width: 74%;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Submode is being added...</div>
       		<div style="width: 75%" class="ajaxloadadd"></div>');


			$char = pParseIPA_Char($_REQUEST['of']);


			pOut("<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin&section=inventory')."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back</a></td>
					<td colspan=2>Add a new copy of the phoneme $char</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Quality override</strong></td>
					<td><input class='quality' type='textbox' /></td>
				</tr>
				<tr>
					<td colspan=2 onClick='$(\".quality\").val($(\".quality\").val() + \" \");'>CLICK</td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Native entry</strong></td>
					<td><input style='width: 50px;' class='submode_entry' type='text' /> <em>the id of a (hidden) dictionary entry</em></td>
				</tr>
				<tr>
					<td style='width: 150px;'><strong>Submode type</strong></td>
					<td></td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="addbutton" href="javascript:void(0);"><i class="fa-12 fa-plus"></i> Add submode</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

			pOut("<script>
				

						vipak({selector:'.quality'});
				


				$('#addbutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?admin&section=submodes&ajax&action=add_submode')."', 
		         	{'submode_name_short': $('.submode_name_short').val(), 'submode_name': $('.submode_name').val(), 
		         	'submode_entry': $('.submode_entry').val(), 
		         	'submode_submode_type_id': $('.submode_submode_type_id').val()});
     		 });
			</script>");

		}
	
	}


	// We have not died yet!
	if(!(isset($_REQUEST['action'])) OR !(in_array($_REQUEST['action'], $die_actions))){

		// Information

		pOut('<div class="notice"><i class="fa fa-12 fa-info-circle"></i> Click on a phoneme to (de-)activate it in the phonology.</div><br />');

		// Action buttons

		pOut("<a class='actionbutton' onClick='$(\"table.consonants\").show();$(\"table.vowels\").hide();' href='javascript:void(0);'>Consonants <i class='fa-caret-down fa-12 fa'></i></a> <a class='actionbutton' href='javascript:void(0);' onClick='$(\"table.consonants\").hide();$(\"table.vowels\").show();'>Vowels <i class='fa-caret-down fa-12 fa'></i></a><br /><br />");

		// Consonants table



				pOut("<div class='ajaxload_toggle'></div>");


				// making the table
				$ipa = pLoadIPA_C();
				$ipa_c_places = pQuery("SELECT * FROM ipa_c_place;");
				$ipa_c_modes = pQuery("SELECT * FROM ipa_c_mode;");
				pOut("<table class='verbs ipa consonants'>");
				pOut("<tr class='temps'><td class='filler'><strong>Consonants</strong></td>");
				$places = array();
				foreach ($ipa_c_places as $ipa_c_place) {
					if(array_key_exists(1, $ipa) AND array_key_exists($ipa_c_place['id'], $ipa[1]) OR array_key_exists(2, $ipa) AND array_key_exists($ipa_c_place['id'], $ipa[2]))
						pOut("<td>".$ipa_c_place['name']."</td>");
					$places[] = $ipa_c_place['id'];
				}
				pOut("</tr>");
				// Rows

				$ipa_c_articulation = pQuery("SELECT * FROM ipa_c_articulation;");
				$ipa_c_places = pQuery("SELECT * FROM ipa_c_place;");
				foreach ($ipa_c_places as $ipa_c_place) {
					foreach ($ipa_c_articulation as $articulation) {

						$e = "";
						$count_shown = 0;

						$e .= "<tr><td class='singa'>".$articulation['name']."</td>";
						foreach ($places as $place_id) {
								if(array_key_exists(1, $ipa) AND array_key_exists($place_id, $ipa[1]) OR array_key_exists(2, $ipa) AND array_key_exists($place_id, $ipa[2])){
									$e .= "<td class='ipa'>";
									// Getting voiceless variant
									$voiceless_shown = false; 

									if(array_key_exists(1, $ipa) and array_key_exists($place_id, $ipa[1]) and array_key_exists($articulation['id'], $ipa[1][$place_id]))
									{

										$voiceless_active = $ipa[1][$place_id][$articulation['id']][0][1];
										$voiceless_symbol = $ipa[1][$place_id][$articulation['id']][0][0];
										$voiceless_id = $ipa[1][$place_id][$articulation['id']][0][2];
										$graphemes = $ipa[1][$place_id][$articulation['id']][0][3];
										$g_string = '';
										foreach ($graphemes as $g) {
											$g_string .= $graphemes.', ';
										}
										$g_string = rtrim($g_string, ',');
										if($g_string != '')
											$g_string = " <".$g_string.">";
										$e .= '<a href="javascript:void(0);" onClick="$(\'.ajaxload_toggle\').load(\''.pUrl('?admin&section=inventory&ajax&action=toggle&c='.$voiceless_id).'\');$(\'.c-'.$voiceless_id.'\').toggleClass(\'selected\');"><span class="ipa c-'.$voiceless_id.' tooltip '.(($voiceless_active  == '1') ? 'selected' : '').'" title="'.pIPA_name(2, $place_id, $articulation['id']).'">'.$voiceless_symbol.'</span></a>';
										$count_shown++;
										$voiceless_shown = true; 
								}
							}
								
								
							// Getting voiced variant

							if(array_key_exists(2, $ipa) and array_key_exists($place_id, $ipa[2]) and array_key_exists($articulation['id'], $ipa[2][$place_id]))
							{
								$voiced_active = $ipa[2][$place_id][$articulation['id']][0][1];
								$voiced_symbol = $ipa[2][$place_id][$articulation['id']][0][0];
								$voiced_id = $ipa[2][$place_id][$articulation['id']][0][2];
								$graphemes = $ipa[2][$place_id][$articulation['id']][0][3];
								$g_string = '';
								foreach ($graphemes as $g) {
									$g_string .= $g.', ';
								}
								$g_string = rtrim($g_string, ', ');
								if($g_string != '')
									$g_string = " &#60;".$g_string."&#62;";
								$e .= '<a href="javascript:void(0);" onClick="$(\'.ajaxload_toggle\').load(\''.pUrl('?admin&section=inventory&ajax&action=toggle&c='.$voiced_id).'\');$(\'.c-'.$voiced_id.'\').toggleClass(\'selected\');"><span class="ipa c-'.$voiced_id.' tooltip '.(($voiced_active  == '1') ? 'selected' : '').'" title="'.pIPA_name(2, $place_id, $articulation['id']).'">'.$voiced_symbol.'</span></a>';
								$count_shown++;
							}

							$e .= "</td>";
						}
						$e .= "</tr>";
						if($count_shown != 0)
							pOut($e);
					}
				}


				pOut("</table>");

		// Vowels table

				$ipa_v = pLoadIPA_V();
				$ipa_v_places = pQuery("SELECT * FROM ipa_v_place;");
				$ipa_v_modes = pQuery("SELECT * FROM ipa_v_mode;");
				pOut("<table class='verbs ipa_v vowels hide'>");
				pOut("<tr class='temps'><td class='filler'><strong>Vowels</strong></td>");
				$places = array();
				foreach ($ipa_v_places as $ipa_v_place) {
					if(array_key_exists(1, $ipa_v) AND array_key_exists($ipa_v_place['id'], $ipa_v[1]) OR array_key_exists(2, $ipa_v) AND array_key_exists($ipa_v_place['id'], $ipa_v[2]))
						pOut("<td>".$ipa_v_place['name']."</td>");
					$places[] = $ipa_v_place['id'];
				}
				pOut("</tr>");
				// Rows

				$ipa_v_articulation = pQuery("SELECT * FROM ipa_v_articulation;");
				$ipa_v_places = pQuery("SELECT * FROM ipa_v_place;");
				foreach ($ipa_v_places as $ipa_v_place) {
					foreach ($ipa_v_articulation as $articulation) {

						$e = "";
						$count_shown = 0;

						$e .= "<tr><td class='singa'>".$articulation['name']."</td>";
						foreach ($places as $place_id) {
								if((array_key_exists(1, $ipa_v) AND array_key_exists($place_id, $ipa_v[1]) OR array_key_exists(2, $ipa_v) AND array_key_exists($place_id, $ipa_v[2]))
									OR array_key_exists('copies_1', $ipa_v) AND array_key_exists($place_id, $ipa_v['copies_1']) OR array_key_exists('copies_2', $ipa_v) AND array_key_exists($place_id, $ipa_v['copies_2'])
									){
									$e .= "<td class='ipa'>";
									// Getting voiceless variant

									if(array_key_exists(1, $ipa_v) and array_key_exists($place_id, $ipa_v[1]) and array_key_exists($articulation['id'], $ipa_v[1][$place_id]))
									{

										$voiceless_active = $ipa_v[1][$place_id][$articulation['id']][0][1];
										$voiceless_symbol = $ipa_v[1][$place_id][$articulation['id']][0][0];
										$voiceless_id = $ipa_v[1][$place_id][$articulation['id']][0][2];
										$graphemes = $ipa_v[1][$place_id][$articulation['id']][0][3];
										$g_string = '';
										foreach ($graphemes as $g) {
											$g_string .= $graphemes.', ';
										}
										$g_string = rtrim($g_string, ',');
										if($g_string != '')
											$g_string = " <".$g_string.">";
										$e .= '<a href="javascript:void(0);" onClick="$(\'.ajaxload_toggle\').load(\''.pUrl('?admin&section=inventory&ajax&action=toggle&v='.$voiceless_id).'\');$(\'.v-'.$voiceless_id.'\').toggleClass(\'selected\');"><span class="v-'.$voiceless_id.' ipa '.(($voiceless_active  == '1') ? 'selected' : '').'">'.$voiceless_symbol.$g_string.'</span></a>';
										$count_shown++;
										$voiceless_shown = true; 
								}

								// We might want to show the copies as well!

								if(array_key_exists('copies_1', $ipa_v) and array_key_exists($place_id, $ipa_v['copies_1']) and array_key_exists($articulation['id'], $ipa_v['copies_1'][$place_id])){
									$voiceless_active = $ipa_v['copies_1'][$place_id][$articulation['id']][0][1];
										$voiceless_symbol = $ipa_v['copies_1'][$place_id][$articulation['id']][0][0];
										$voiceless_id = $ipa_v['copies_1'][$place_id][$articulation['id']][0][2];
										$graphemes = $ipa_v['copies_1'][$place_id][$articulation['id']][0][3];
										$g_string = '';
										foreach ($graphemes as $g) {
											$g_string .= $graphemes.', ';
										}
										$g_string = rtrim($g_string, ',');
										if($g_string != '')
											$g_string = " <".$g_string.">";
										$e .= '<a href="'.pUrl('?admin&section=inventory&action=edit_copy&copy=v_'.$voiceless_id).'"><span class="v-'.$voiceless_id.' ipa '.(($voiceless_active  == '1') ? 'selected' : '').'">'.$voiceless_symbol.$g_string.'</span></a>';
										$count_shown++;
								}

							}
								
								
							// Getting voiced variant

							if(array_key_exists(2, $ipa_v) and array_key_exists($place_id, $ipa_v[2]) and array_key_exists($articulation['id'], $ipa_v[2][$place_id]))
							{
								$voiced_active = $ipa_v[2][$place_id][$articulation['id']][0][1];
								$voiced_symbol = $ipa_v[2][$place_id][$articulation['id']][0][0];
								$voiced_id = $ipa_v[2][$place_id][$articulation['id']][0][2];
								$graphemes = $ipa_v[2][$place_id][$articulation['id']][0][3];
								$g_string = '';
								foreach ($graphemes as $g) {
									$g_string .= $g.', ';
								}
								$g_string = rtrim($g_string, ', ');
								if($g_string != '')
									$g_string = " &#60;".$g_string."&#62;";
								$e .= '<a href="javascript:void(0);" onClick="$(\'.ajaxload_toggle\').load(\''.pUrl('?admin&section=inventory&ajax&action=toggle&v='.$voiced_id).'\');$(\'.v-'.$voiced_id.'\').toggleClass(\'selected\');"><span class="v-'.$voiced_id.' ipa '.(($voiced_active  == '1') ? 'selected' : '').'">'.$voiced_symbol.$g_string.'</span></a>';
								$count_shown++;
							}

							// We might want to show the copies as well!

								if(array_key_exists('copies_2', $ipa_v) and array_key_exists($place_id, $ipa_v['copies_2']) and array_key_exists($articulation['id'], $ipa_v['copies_2'][$place_id])){
									$voiced_active = $ipa_v['copies_2'][$place_id][$articulation['id']][0][1];
									$voiced_symbol = $ipa_v['copies_2'][$place_id][$articulation['id']][0][0];
									$voiced_id = $ipa_v['copies_2'][$place_id][$articulation['id']][0][2];
									$graphemes = $ipa_v['copies_2'][$place_id][$articulation['id']][0][3];
										$g_string = '';
										foreach ($graphemes as $g) {
											$g_string .= $graphemes.', ';
										}
										$g_string = rtrim($g_string, ',');
										if($g_string != '')
											$g_string = " <".$g_string.">";
										$e .= '<a href="'.pUrl('?admin&section=inventory&action=edit_copy&copy=v_'.$voiceless_id).'"><span class="v-'.$voiceless_id.' ipa '.(($voiceless_active  == '1') ? 'selected' : '').'">'.$voiceless_symbol.$g_string.'</span></a>';
										$count_shown++;
								}

							$e .= "</td>";
						}
						$e .= "</tr>";
						if($count_shown != 0)
							pOut($e);
					}
				}


				pOut("</table>");


				// Polyphthongs

				$polyphthongs = pGetPolyphthongs();

				pOut("<table class='verbs ipa_p' style='width:auto;'>");

				pOut("<tr class='polyphthongs'><td class='singa'><strong>Polyphthongs</strong></td>");

				$c = 0;

				foreach ($polyphthongs as $polyphthong) {
					
					pOut('<td class="sing t_p_'.$polyphthong['id'].'"  onMouseEnter="$(\'.a_p_'.$polyphthong['id'].'\').show();" onMouseLeave="$(\'.a_p_'.$polyphthong['id'].'\').hide();"><a href="javascript:void(0);"><span class="poly p_'.$polyphthong['id'].' ipa">'.pParsePolyphthong($polyphthong['combination']).' <a href="javascript:void(0);"  onClick="$(\'.ajaxload_toggle\').load(\''.pUrl('?admin&section=inventory&ajax&action=delete_poly&poly='.$polyphthong['id']).'\');$(\'.t_p_'.$polyphthong['id'].'\').fadeOut().remove();if($( \'.ipa_p tr:nth-child(1) td\' ).length == 1){$(\'tr.polyphthongs\').append(\'<td>- None -</td>\')}" class="hide a_p_'.$polyphthong['id'].'">x</a></span></a></td>');
					$c++;

				}

				if($c == 0)
					pOut('<td class="sing">- None -</td>');

				pOut("</tr>");

				pOut("</table>");

						// New polyphthong field


				// Loading the symbols
			
				// New polyphthong field

				pOut("<br /><input type='hidden' class='temp_poly' /><select style='width: 200px;' class='new_poly' multiple='multiple'>");

				// Loading the symbols

				foreach ($ipa as $ipa_val) {
					foreach ($ipa_val as $ipa_mode => $ipa_val_mode) {
						foreach ($ipa_val_mode as $ipa_place => $ipa_val_place) {
							foreach ($ipa_val_place as $ipa_art => $ipa_val_articulation) {
								
									pOut("<option value='c_".$ipa_val_articulation[2]."'>".$ipa_val_articulation[0]. " </option>");
								
							}
						}
					}
				}

				foreach ($ipa_v as $ipa_v_val) {
					foreach ($ipa_v_val as $ipa_v_mode => $ipa_v_val_mode) {
						foreach ($ipa_v_val_mode as $ipa_v_place => $ipa_v_val_place) {
							foreach ($ipa_v_val_place as $ipa_v_art => $ipa_v_val_articulation) {
								
									pOut("<option value='v_".$ipa_v_val_articulation[2]."'>".$ipa_v_val_articulation[0]."</option>");
								
							}
						}
					}
				}

				pOut("</select>");

				pOut('<script type="text/javascript">
					$(".new_poly").select2();
					$(".new_poly").on("select2:select", function (evt) {
					  var element = evt.params.data.element;
					  var $element = $(element);
					  
					  $element.detach();
					  $(this).append($element);
					  $(this).trigger("change");
					});
									 
				
				</script><a class="actionbutton" href="javascript:void(0);" onClick="$(\'.ajaxload_toggle\').load(\''.pUrl('?admin&section=inventory&ajax&action=new_poly').'\', {\'poly\': $(\'.new_poly\').select2(\'val\')});">Add new polyphthong</a>');




	}

	
	
	

 ?>