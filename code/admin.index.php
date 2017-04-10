<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under MIT
	File: index.admin.php
*/



	// WE ABSOLUTLY NEED TO BE LOGGED IN!!!
	if(!pLogged() OR !pCheckAdmin())
	{
		pUrl('', true);
	}

	// title_header
	pOut("<div class='header dictionary home wiki'><div class='title_header'><div class='header-icon'><i class='fa fa-tasks'></i></div> Control panel</div></div>");


	// Allowed sections

	$allowed_sections = array('dictionary', 'languages', 'types', 'classifications', 'subclassifications', 'modes', 'submodes', 'rule_groups', 'numbers', 'words', 'translations', 'descriptions', 'semantics', 'auxilary', 'mode_types', 'regular_inflections', 'irregular_inflections', 'preview_inflections', 'examples', 'settings', 'inventory', 'allophones');

	// The subsections

	$subsection_grammar = array('types', 'classifications', 'subclassifications', 'numbers',  'modes', 'submodes', 'mode_types');
	$subsection_dictionary = array('words', 'translations', 'descriptions', 'semantics', 'examples');
	$subsection_settings = array('languages', 'settings', 'wiki');
	$subsection_phonology = array('consonants', 'inventory');
	$subsection_inflections = array('regular_inflections', 'irregular_inflections', 'preview_inflections', 'rule_groups', 'auxilary');

	// Controling the menu

	function pAMenuLink($section){

		if($section == 'home' AND !isset($_REQUEST['section']))
				return 'orange';
		elseif(isset($_REQUEST['section']) AND $_REQUEST['section'] == $section)
			return 'orange';
		elseif(is_array($section))
			if(isset($_REQUEST['section']) AND in_array($_REQUEST['section'], $section))
				return 'orange';

		else
			return '';

	}


	pOut('

		<div class="wikiSidebar">
			<div>
				<a href="'.pUrl('?admin&section=words').'" class="'.pAMenuLink($subsection_dictionary).'"><i class="fa fa-book"></i> Dictionary</a>'); 


	// Dictionary section
	if(isset($_REQUEST['section']) AND in_array($_REQUEST['section'], $subsection_dictionary))
		pOut('
				<a href="'.pUrl('?admin&section=words').'"  class="sub '.pAMenuLink('words').'"><i class="fa fa-font"></i> Word entries</a>
				<a href="'.pUrl('?admin&section=translations').'"  class="sub '.pAMenuLink('translations').'"><i class="fa fa-globe"></i> Translations</a>
				<a href="'.pUrl('?admin&section=examples').'"  class="sub '.pAMenuLink('examples').'"><i class="fa fa-quote-right"></i> Examples</a>
				<a href="'.pUrl('?admin&section=descriptions').'"  class="sub '.pAMenuLink('descriptions').'"><i class="fa fa-info-circle"></i> Descriptions</a>
				<a href="'.pUrl('?admin&section=semantics').'"  class="sub '.pAMenuLink('semantics').'"><i class="fa fa-sitemap "></i> Semantic relations</a>');

	pOut('
				<a href="'.pUrl('?admin&section=types').'" class="'.pAMenuLink($subsection_grammar).'"><i class="fa fa-database"></i> Grammar</a>');

	// Grammar section
	if(isset($_REQUEST['section']) AND in_array($_REQUEST['section'], $subsection_grammar))
		pOut('
				<a href="'.pUrl('?admin&section=types').'" class="sub '.pAMenuLink('types').'"><i class="fa fa-cubes"></i> Lexical categories</a>
				<a href="'.pUrl('?admin&section=classifications').'" class="sub '.pAMenuLink('classifications').'"><i class="fa fa-code-fork "></i> Grammatical divisions</a>
				<a href="'.pUrl('?admin&section=subclassifications').'"  class="sub '.pAMenuLink('subclassifications').'"><i class="fa fa-tags "></i> Grammatical tags</a>

				<a href="'.pUrl('?admin&section=modes').'"  class="sub '.pAMenuLink('modes').'"><i class="fa fa-th-list "></i> Inflection Tables</a>
				<a href="'.pUrl('?admin&section=numbers').'" class="sub '.pAMenuLink('numbers').'"><i class="fa fa-header"></i> Headings</a>
				<a href="'.pUrl('?admin&section=submodes').'" class="sub '.pAMenuLink('submodes').'"><i class="fa fa-table "></i> Rows</a>');

	pOut('			<a href="'.pUrl('?admin&section=rule_groups').'" '.pAMenuLink($subsection_inflections).'><i class="fa fa-adjust"></i> Inflections</a>');
	
	// Inflections section
	if(isset($_REQUEST['section']) AND in_array($_REQUEST['section'], $subsection_inflections))
		pOut('
				<a href="'.pUrl('?admin&section=rule_groups').'" class="sub '.pAMenuLink('rule_groups').'"><i class="fa fa-object-group "></i> Inflection groups</a>
				<a href="'.pUrl('?admin&section=regular_inflections').'" class="sub '.pAMenuLink('regular_inflections').'"><i class="fa fa-industry"></i> Regular inflections</a>
				<a href="'.pUrl('?admin&section=irregular_inflections').'" class="sub '.pAMenuLink('irregular_inflections').'"><i class="fa fa-eyedropper"></i> Irregular Inflections</a>
				<a href="'.pUrl('?admin&section=auxilary').'" class="sub '.pAMenuLink('auxilary').'"><i class="fa fa-external-link-square"></i> Auxilary rules</a>
				<a href="'.pUrl('?admin&section=preview_inflections').'" class="sub '.pAMenuLink('preview_inflections').'"><i class="fa fa-book"></i> Dictionary inflections</a>
		');


	pOut('			<a href="'.pUrl('?admin&section=inventory').'" '.pAMenuLink($subsection_phonology).'><i class="fa fa-bullhorn"></i> Phonology and Ortography</a>
				<a href="'.pUrl('?admin&section=settings').'" '.pAMenuLink($subsection_settings).'><i class="fa fa-cogs"></i> Settings</a>');

	// Settings section
	if(isset($_REQUEST['section']) AND in_array($_REQUEST['section'], $subsection_settings))
		pOut('
				<a href="'.pUrl('?admin&section=settings').'" class="sub '.pAMenuLink('settings').'"><i class="fa fa-wrench"></i> General settings</a>
				<a href="'.pUrl('?admin&section=wiki').'" class="sub '.pAMenuLink('wiki').'"><i class="fa fa-map-signs"></i> Wiki settings</a>
				<a href="'.pUrl('?admin&section=languages').'" class="sub '.pAMenuLink('languages').'"><i class="fa fa-language"></i> Languages</a>
			');

	pOut('
			</div>
		</div>
		');

	//The sub menu

	



	// Phonology section
	if(isset($_REQUEST['section']) AND in_array($_REQUEST['section'], $subsection_phonology))
		pOut('
			<div class="adminsubmenu">
				<a href="'.pUrl('?admin&section=inventory').'" '.pAMenuLink('inventory').'><i class="fa fa-archive"></i> Phonological inventory</a>
				<a href="'.pUrl('?admin&section=examples').'" '.pAMenuLink('examples').'><i class="fa fa-quote-right"></i> Examples</a>
				<a href="'.pUrl('?admin&section=descriptions').'" '.pAMenuLink('descriptions').'><i class="fa fa-info-circle"></i> Descriptions</a>
				<a href="'.pUrl('?admin&section=semantics').'" '.pAMenuLink('semantics').'><i class="fa fa-sitemap "></i> Semantic relations</a>

			</div>


			', true);



	// Wiki content
	pOut("<div class='wikiContent'><span class='pSectionTitle extra'><br /></span><div class='pSectionWrapper'>");

	// Do we need to load a section?

	if(isset($_GET['section'])){

		if (in_array($_GET['section'], $allowed_sections)) {
			if(file_exists($donut['root_path'] . '\code\admin\admin.' . $_GET['section'] . '.php')){
				pOut("<div class='adminsection'>");
				require $donut['root_path'] . '\code\admin\admin.' . $_GET['section'] . '.php';
				pOut("</div>");
			} else {
				pOut('<div class="notice danger-notice" id="empty"><i class="fa fa-warning"></i> The section does not exist.</div><br />');
			}			
		}
	}

	pOut("</div></div><br id='cl' />");
