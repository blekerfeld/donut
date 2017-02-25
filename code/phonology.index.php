<?php


$lang_zero = pGetLanguageZero();

pOut('    
      <span class="title_header">'.$lang_zero->name.' Phonology</span><br /><br />
      ', true);


// IPA table

// making the table
$ipa = pLoadIPA_C(true);
$ipa_c_places = pQuery("SELECT * FROM ipa_c_place;");
$ipa_c_modes = pQuery("SELECT * FROM ipa_c_mode;");
pOut("<table class='verbs ipa' style='width: 50%!important;'>");
pOut("<tr class='temps'><td class='filler' style='width: 20px;'><strong>Consonants</strong></td>");
$places = array();
foreach ($ipa_c_places->fetchAll() as $ipa_c_place) {
	if(array_key_exists(1, $ipa) AND array_key_exists($ipa_c_place['id'], $ipa[1]) OR array_key_exists(2, $ipa) AND array_key_exists($ipa_c_place['id'], $ipa[2]))
		pOut("<td>".$ipa_c_place['name']."</td>");
	$places[] = $ipa_c_place['id'];
}
pOut("</tr>");
// Rows

$ipa_c_articulation = pQuery("SELECT * FROM ipa_c_articulation;");
$ipa_c_places = pQuery("SELECT * FROM ipa_c_place;");
foreach ($ipa_c_places->fetchAll() as $ipa_c_place) {
	foreach ($ipa_c_articulation->fetchAll() as $articulation) {

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
						$graphemes = $ipa[1][$place_id][$articulation['id']][0][3];
						$g_string = '';
						foreach ($graphemes as $g) {
							$g_string .= $graphemes.', ';
						}
						$g_string = rtrim($g_string, ',');
						if($g_string != '')
							$g_string = " <".$g_string.">";
						$e .= '<span class="ipa tooltip '.(($voiceless_active  == '1') ? 'selected' : '').'" title="'.pIPA_name(2, $place_id, $articulation['id']).'">'.$voiceless_symbol.$g_string.'</span>';
						$count_shown++;
						$voiceless_shown = true; 
				}
			}
				
				
			// Getting voiced variant

			if(array_key_exists(2, $ipa) and array_key_exists($place_id, $ipa[2]) and array_key_exists($articulation['id'], $ipa[2][$place_id]))
			{
				$voiced_active = $ipa[2][$place_id][$articulation['id']][0][1];
				$voiced_symbol = $ipa[2][$place_id][$articulation['id']][0][0];
				$graphemes = $ipa[2][$place_id][$articulation['id']][0][3];
				$g_string = '';
				foreach ($graphemes as $g) {
					$g_string .= $g.', ';
				}
				$g_string = rtrim($g_string, ', ');
				if($g_string != '')
					$g_string = " &#60;".$g_string."&#62;";
				$e .= '<span class="ipa tooltip '.(($voiced_active  == '1') ? 'selected' : '').'" title="'.pIPA_name(2, $place_id, $articulation['id']).'">'.$voiced_symbol.'</span>';
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

// making the table fo the vowels
$ipa = pLoadIPA_V(true);
$ipa_v_places = pQuery("SELECT * FROM ipa_v_place;");
$ipa_v_modes = pQuery("SELECT * FROM ipa_v_mode;");
pOut("<table class='verbs ipa_v' style='width: 30%!important;'>");
pOut("<tr class='temps'><td class='filler' style='width: 20px;'><strong>Vowels</strong></td>");
$places = array();
foreach ($ipa_v_places as $ipa_v_place) {
	if(array_key_exists(1, $ipa) AND array_key_exists($ipa_v_place['id'], $ipa[1]) OR array_key_exists(2, $ipa) AND array_key_exists($ipa_v_place['id'], $ipa[2]))
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
				if((array_key_exists(1, $ipa) AND array_key_exists($place_id, $ipa[1]) OR array_key_exists(2, $ipa) AND array_key_exists($place_id, $ipa[2]))
									OR array_key_exists('copies_1', $ipa) AND array_key_exists($place_id, $ipa['copies_1']) OR array_key_exists('copies_2', $ipa) AND array_key_exists($place_id, $ipa['copies_2'])
									){
					$e .= "<td class='ipa'>";
					// Getting voiceless variant
					$voiceless_shown = false; 

					if(array_key_exists(1, $ipa) and array_key_exists($place_id, $ipa[1]) and array_key_exists($articulation['id'], $ipa[1][$place_id]))
					{

						$voiceless_active = $ipa[1][$place_id][$articulation['id']][0][1];
						$voiceless_symbol = $ipa[1][$place_id][$articulation['id']][0][0];
						$graphemes = $ipa[1][$place_id][$articulation['id']][0][3];
										$g_string = '';
										foreach ($graphemes as $g) {
											$g_string .= $graphemes.', ';
										}
						$g_string = rtrim($g_string, ',');
						if($g_string != '')
							$g_string = " <".$g_string.">";
						$e .= '<span class="ipa '.(($voiceless_active  == '1') ? 'selected' : '').'">'.$voiceless_symbol.$g_string.'</span>';
						$count_shown++;
						$voiceless_shown = true; 
				}

				// We might want to get the copies as well
				if(array_key_exists('copies_1', $ipa) and array_key_exists($place_id, $ipa['copies_1']) and array_key_exists($articulation['id'], $ipa['copies_1'][$place_id])){
					$voiceless_active = $ipa['copies_1'][$place_id][$articulation['id']][0][1];
						$voiceless_symbol = $ipa['copies_1'][$place_id][$articulation['id']][0][0];
						$voiceless_id = $ipa['copies_1'][$place_id][$articulation['id']][0][2];
						$graphemes = $ipa['copies_1'][$place_id][$articulation['id']][0][3];
						$g_string = '';
						foreach ($graphemes as $g) {
							$g_string .= $graphemes.', ';
						}
						$g_string = rtrim($g_string, ',');
						if($g_string != '')
							$g_string = " <".$g_string.">";
						$e .= '<span class="ipa '.(($voiceless_active  == '1') ? 'selected' : '').'">'.$voiceless_symbol.$g_string.'</span>';
						$count_shown++;
				}

			}
				
				
			// Getting voiced variant

			if(array_key_exists(2, $ipa) and array_key_exists($place_id, $ipa[2]) and array_key_exists($articulation['id'], $ipa[2][$place_id]))
			{
				$voiced_active = $ipa[2][$place_id][$articulation['id']][0][1];
				$voiced_symbol = $ipa[2][$place_id][$articulation['id']][0][0];
				$graphemes = $ipa[2][$place_id][$articulation['id']][0][3];
				$g_string = '';
				foreach ($graphemes as $g) {
					$g_string .= $g.', ';
				}
				$g_string = rtrim($g_string, ', ');
				if($g_string != '')
					$g_string = " &#60;".$g_string."&#62;";
				$e .= '<span class="ipa '.(($voiced_active  == '1') ? 'selected' : '').'">'.$voiced_symbol.'</span>';
				$count_shown++;
			}

			// We might want to show the copies as well!

				if(array_key_exists('copies_2', $ipa) and array_key_exists($place_id, $ipa['copies_2']) and array_key_exists($articulation['id'], $ipa['copies_2'][$place_id])){
					$voiced_active = $ipa['copies_2'][$place_id][$articulation['id']][0][1];
					$voiced_symbol = $ipa['copies_2'][$place_id][$articulation['id']][0][0];
					$voiced_id = $ipa['copies_2'][$place_id][$articulation['id']][0][2];
					$graphemes = $ipa['copies_2'][$place_id][$articulation['id']][0][3];
						$g_string = '';
						foreach ($graphemes as $g) {
							$g_string .= $graphemes.', ';
						}
						$g_string = rtrim($g_string, ',');
						if($g_string != '')
							$g_string = " <".$g_string.">";
						$e .= '<span class="ipa '.(($voiceless_active  == '1') ? 'selected' : '').'">'.$voiceless_symbol.$g_string.'</span>';
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

pOut("<script>
      $(document).ready(function() {
            $('.tooltip').tooltipster({theme: 'tooltipster-noir'});

        });</script>");


// Polyphthongs

				$polyphthongs = pGetPolyphthongs();

				pOut("<table class='verbs ipa_p' style='width:auto;'>");

				pOut("<tr class='polyphthongs'><td class='singa'><strong>Polyphthongs</strong></td>");

				$c = 0;

				foreach ($polyphthongs as $polyphthong) {
					
					pOut('<td class="sing t_p_'.$polyphthong['id'].'"><span class="poly p_'.$polyphthong['id'].' ipa">'.pParsePolyphthong($polyphthong['combination']).'</span></td>');
					$c++;

				}

				if($c == 0)
					pOut('<td class="sing">- None -</td>');

				pOut("</tr>");

				pOut("</table><br />");
