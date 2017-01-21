<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: index.generate.php
*/

if(!logged())
	pUrl('', true);

if(isset($_REQUEST['ajax']) and isset($_REQUEST['language']) and isset($_REQUEST['subtitle']) and isset($_REQUEST['author'])){


//Language
$zerolang = pGetLanguageZero();
$lang = pGetLanguage($_REQUEST['language']);


$txt = '\documentclass[11pt,a5paper,twoside]{article} % 10pt font size, A4 paper and two-sided margins

\usepackage[top=2cm,bottom=2cm,left=2.2cm,right=3.2cm,columnsep=12pt]{geometry} % Document margins and spacings

\usepackage[utf8x]{inputenc}

\usepackage{palatino} % Use the Palatino font


\usepackage{fontspec}

\usepackage{microtype}% Improves spacing

\newfontfamily\timesfont{Times New Roman}


\usepackage{multicol} % Required for splitting text into multiple columns

\usepackage[bf,sf,center]{titlesec} % Required for modifying section titles - bold, sans-serif, centered

\usepackage{fancyhdr} % Required for modifying headers and footers
\fancyhead[L]{\textsf{\rightmark}} % Top left header
\fancyhead[R]{\textsf{\leftmark}} % Top right header
\renewcommand{\headrulewidth}{1.4pt} % Rule under the header
\fancyfoot[C]{\textbf{\textsf{\thepage}}} % Bottom center footer
\renewcommand{\footrulewidth}{1.4pt} % Rule under the footer
\pagestyle{fancy} % Use the custom headers and footers throughout the document

\newcommand\tab[1][0.3cm]{\hspace*{#1}}

\newcommand{\entry}[4]{\markboth{\timesfont{#1}}{\timesfont{#1}}\textbf{\timesfont{#1}}\ {\timesfont#2}\ \textit{#3}\ \\ $\bullet$ \ {#4} }  % Defines the command to print each word on the page, \markboth{}{} prints the first word on the page in the top left header and the last word in the top right
\title{\textbf {'.pLatexEscape($zerolang->name).' - '.pLatexEscape($lang->name).' '.pLatexEscape($lang->name).' - '.pLatexEscape($zerolang->name).' dictionary}}
\author{'.pLatexEscape($_REQUEST['author']).'}
\date{'.pLatexEscape($_REQUEST['subtitle']).'}';


// Starting with native - language

$txt.= '
\begin{document}
\maketitle
\newpage
\section*{\timesfont{'.pLatexEscape($zerolang->name).' - '.pLatexEscape($lang->name).'}}
';

// Getting the alphabet
$alphabet = pGetAlphabetArray();

$filter_starts = array();
foreach ($alphabet as $section_letter) {
	// Do we need to filter though?
	

	$alphabet_filter = pGetAlphabetByStart($section_letter);
	foreach ($alphabet_filter as $alphabet_filter_instance) {
		if(!(mb_strlen($alphabet_filter_instance['grapheme'], "UTF-8") == mb_strlen($section_letter, "UTF-8")))
			$filter_starts[] = $alphabet_filter_instance['grapheme'];
	}
	

	// Lets gets our words by start

	$words = pGetWordsByStart($section_letter, $filter_starts);

	// Sorting

	$sorted_words = pSortByAlphabet($words, $alphabet);

	// Only output if there is something to output

	if(count($sorted_words) != 0){

		$count = 0;

		foreach($sorted_words as $get_word){


			$word = $get_word[1];
			$type = pGetType($word['type_id']);
			$class = pGetClassification($word['classification_id']);
			if($word['subclassification_id'] != 0)
				$subclass = pGetSubclassification($word['subclassification_id']);
			$entry = '';
			
			// Getting translations

			if($word['derivation_clonetranslations'] != 0)
				$translations = pGetTranslations($word['id'], '', $lang->id, true, $word['derivation_of']);
			else
				$translations = pGetTranslations($word['id'], '', $lang->id);

			if($translations->rowCount() == 0)
				break;

			// Let's add a title if needed

			if($count == 0){
				// Section title

				$txt .= '

				\section*{\timesfont{'.pLatexEscape($section_letter).'}}
				';


				// Col. stuff

				$txt .= '

				\begin{multicols*}{2}
				';
			}
			

			$entry .= '
			\entry{'.pLatexEscape($word['native']).'}';

			if($word['ipa'] != '')
				$entry .= '{'.'/'.$word['ipa'].'/'.'}';
			else
				$entry .= '{}';

			$entry .= '{'.pLatexEscape($type->short_name);

			if($type->inflect_classifications != 1)
				$entry .= ' '.pLatexEscape($class->short_name);

			if($word['subclassification_id'] != 0)
				$entry .= ' '.pLatexEscape($subclass->short_name);

			// Previewing the inflections
			$preview_inflections =  pGetPreviewInflections($type->id);
			if($preview_inflections->rowCount() != 0)
			{
				$entry .= ' (';
				$all = array();
				while($pi = $preview_inflections->fetchObject()){
					$all[] = '\textbf{'.$pi->name.' }'.strip_tags(pSearchAndInflect(pGetWord($word['id']), $type->id, $class->id, $pi->mode_id, $pi->submode_id, $pi->number_id, pLatexEscape('~'), $pi->no_aux));
				}
				$entry .= implode('; ', $all).') ';
			}

			$entry .= '}{';

			$count_t = 1;

			foreach($translations as $translation)
			{
				if($count_t > 1)
					$entry .= '\tab';
				if($translations->rowCount() != 1)
					$entry .= $count_t	.'. ';
				if($translation['specification'] != '')
					$entry .= '(\textit{'.pLatexEscape($translation['specification']).'}) ';
				$entry .= pLatexEscape($translation['translation']);
				$count_t++;
			}

			// Ending with translations
			$entry .= '}
			';

			$txt .= $entry;

			$count++;
		}

		// End of section
		if($count > 0)
			$txt .= '\end{multicols*}
			';

	}

	

}

// Now it is time for the reverse dictionary! 

$txt .='
\newpage
\section*{'.pLatexEscape($lang->name).' - '.pLatexEscape($zerolang->name).'}
';

// We need to get the alphabet in an array if there is any
if($lang->alphabet != '')
	$reverse_alphabet = explode(',', $lang->alphabet);
else
	$reverse_alphabet = mb_range('a', 'z');


// Reverse filter time!!
$filter_starts = array();

foreach ($reverse_alphabet as $section_letter) {
	// Do we need to filter though?
	
	$alphabet_filter_reverse = array_filter($reverse_alphabet, function ($v) {
	  global $section_letter;
	  return substr($v, 0, 1) === $section_letter;
	});

	$reverse_alphabet_filter = array_diff($reverse_alphabet, $alphabet_filter_reverse);

	foreach ($reverse_alphabet_filter  as $reverse_alphabet_filter_instance) {
		if(!(mb_strlen($reverse_alphabet_filter_instance, "UTF-8") == mb_strlen($section_letter, "UTF-8")))
			$filter_starts[] = $alphabet_filter_instance;
	}

	// Getting our translations by start
	$translations = pGetTranslationsByStart($section_letter, $lang->id, $filter_starts);
	$sorted_translations = pSortByAlphabet($translations, $reverse_alphabet);

	if(count($sorted_translations) != 0){

		$count = 0;

		foreach ($sorted_translations as $work_translation) {
			$translation = $work_translation[1];
			$words_by_translation = pGetWordsByTranslation($translation['id']);

			if(count($words_by_translation) == 0)
				break;

			if($count == 0){
				// Section title

				$txt .= '

				\section*{'.pLatexEscape($section_letter).'}
				';


				// Col. stuff

				$txt .= '

				\begin{multicols*}{2}
				';
			}

			$entry = '';

			$entry .= '
			\entry{'.pLatexEscape($translation['translation']).'}';

		
			$entry .= '{}';

			$entry .= '{}';

			$entry .= '{';

			$count_w = 1;

			foreach($words_by_translation as $word)
			{
				if($count_w > 1)
					$entry .= '\tab';
				if(count($words_by_translation) != 1)
					$entry .= $count_w	.'. ';

				$type = pGetType($word['type_id']);
				$class = pGetClassification($word['classification_id']);
				$subclass_name = '';
				if($word['subclassification_id'] != 0){
					$subclass = pGetSubclassification($word['subclassification_id']);
					$subclass_name = ' '.$subclass->short_name;
				}

				$specification = '';
				
				if($word['specification'] != '')
					$specification = '(\textit{'.pLatexEscape($word['specification']).'}) ';

				$entry .= $specification.pLatexEscape($word['native']).'\tab \textit{'.pLatexEscape($type->short_name).' '.pLatexEscape($class->short_name).$subclass_name.'}';
				$count_w++;
			}

			// Ending with words
			$entry .= '}
			';

			$txt .= $entry;

			$count++;

		}
		// End of section
		if($count > 0)
			$txt .= '\end{multicols*}
			';
	}


}
	


// End of document

$txt .= '\end{document}
';

// Time to write

$file_name = "dictionary_".$lang->name."_".date('Ymd').".tex";

if(pWriteUTF8File($file_name, $txt))
	
	echo '<div class="notice succes-notice hide" id="empty" style="margin-bottom: 20px;"><i class="fa fa-check"></i> Generation succeeded! You can find your file <a href="'.pUrl($file_name).'">here</a></div>'."<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";
else
	echo '<div class="notice hide danger-notice" id="empty" style="margin-bottom: 20px;"><i class="fa fa-warning"></i> Generation failed</div>'."<script>$('#busyadd').fadeOut().delay(1000);$('#empty').show().delay(400).effect('bounce', {duration: 1000});</script>";

die();
}
else{

	pOut('<span class="title_header">Generate dictionary TEX-file</span><br /><br />', true);

	$languages = pGetLanguages(true);

	$languages_select = '<select id="lang">';

	foreach($languages as $lang){
		$languages_select .= '<option value="'.$lang['id'].'">'.$lang['name'].'</option>';
	}

	$languages_select .= '</select>';

	pOut('<div style="width: 74%;margin: 0 auto;" class="notice hide" id="busyadd" ><i class="fa fa-spinner fa-spin"></i> Generating dictionary...</div>
       		<div style="width: 74%;margin: 0 auto;" class="ajaxloadadd"></div>');

	pOut("<div class='notice' style='width: 74%;margin: 0 auto;display:block;'><strong><i class='fa fa-12 fa-info-circle'></i> Information </strong><br />
		This page allows you to generate a LaTeX file containting a bilingual dictionary. Please note that this file still needs to be compiled in a LaTeX engine, XeLaTeX is the recommended typesetting engine, because of the Unicode support. Alternatively, you can convert the tex file to a pdf online <a href='https://cloudconvert.com/tex-to-pdf'>here</a>.
		</div><BR />
		<table class='admin' id='empty' style='width:75%'>
				<tr class='title'>
					<td style='width: 100px;'><a class='actionbutton' href='".pUrl('?admin')."'><i class='fa fa-tasks' style='font-size: 12px!important;'></i> <i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Back to manage</a></td>
					<td colspan=2>Generate dictionary TEX-file</td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td style='width: 150px;'><strong>Secondary language</strong></td>
					<td>$languages_select</td>
				</tr>
				<tr>
					<td><strong>Subtile</strong></td>
					<td><input style='width: 200px;' class='subtitle' type='text' /></td>
				</tr>
				<tr>
					<td><strong>Author</strong></td>
					<td><input style='width: 200px;' class='author' type='text' /></td>
				</tr>
				<tr><td></td><td></td></tr>
				<tr>
					<td></td>
					<td>".'<a class="button remember" id="generatebutton" href="javascript:void(0);"><i class="fa-12 fa-refresh"></i> Generate dictionary</a>'."<br /></td>
				</tr>
				<tr><td></td><td></td></tr>
			</table><br /><br />");

	pOut("<script>$('#generatebutton').click(function(){
		        $('#busyadd').fadeIn();
		         $('.ajaxloadadd').load('".pUrl('?generate&ajax')."', 
		         	{'language': $('#lang').val(), 'author': $('.author').val(), 
		         	'subtitle': $('.subtitle').val()});
     		 });
			</script>");


}




?>
