<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: MenuTemplate.class.php

// Accepts a RuleDataModel as $data parameter

class pLemmaSheetTemplate extends pTemplate{

	protected function prepareTranslations($language){
		$output = '';
		if(isset($this->_data->_translations[$language]))
			foreach($this->_data->_translations[$language] as $trans){
				$output .= $trans->getInputForm()."//";
			}
		return $output;
	}


	public function lemmasheetForm($section, $edit = false){
		if($edit)
			$data = $this->_data->data()->fetchAll()[0];
		
		p::Out(pMainTemplate::NoticeBox('fa-spinner fa-spin fa-12', SAVING, 'notice saving hide'));

		// That is where the ajax magic happens:
		p::Out("<div class='ajaxSave'></div>");

		p::Out("
			<form id='LemmaSheetForm'>
			".($edit ? "<br /><span class='native markdown-body'><h2><strong class='pWord'>".$data['native'].'</strong></h2></span>' : '<span class="markdown-body"><h2>'.LEMMA_NEW.'</h2></span><br />')."<br />
			<div class='mainTabs'>
				<div data-tab='Basic information'>
					<div class='rulesheet'>
						<div class='left'>
							
							<div class='btSource'><span class='btLanguage'>Dictionary form <span class='xsmall' style='color: darkred;opacity: 1;'>*</span></span><br />
							<span class='btNative'><input class='btInput nWord small normal-font lemma-native' value='".($edit ? $data['native'] : '')."'/></span></div>
							<div class='btSource'><span class='btLanguage'>Lexical form <span class='xsmall' style='color: darkred;opacity: 1;'>".pMainTemplate::NoticeBox('fa-info-circle fa-12', "This optional form is used as base for regular inflection, instead of the dictionary form", 'medium notice-subtle')."</span></span>
							<span class='btNative'><input class='btInput nWord small normal-font lemma-lexform' value='".($edit ? $data['lexical_form'] : '')."'/></span></div>
							<div class='btSource'><span class='btLanguage'><a class='generate-ipa float-right small' href='javascript:void();' tabindex='-1'>[ generate IPA ]</a> IPA transcription <span class='xsmall' style='color: darkred;opacity: 1;'>*</span></span><br /><span class='ajaxGenerateIPA'></span>
							<span class='btNative'><input class='btInput nWord small normal-font lemma-ipa' value='".($edit ? $data['ipa'] : '')."'/></span></div>
						</div>
						<div class='right'>
							<div class='btSource'><span class='btLanguage'>Lexical category <em class='small'>(part of speech)</em></span><br />
								<span class='btNative'><select class='full-width select-lexcat select2'>".(new pSelector('types', @$data['type_id'], 'name', true, 'rules', true))->render()."</select></span></div>
								<div class='btSource'><span class='btLanguage'>Grammatical category</span><br />
								<span class='btNative'><select class='full-width select-gramcat select2'><option>none</option>".(new pSelector('classifications', @$data['classification_id'], 'name', true, 'rules', true))->render()."</select></span></div>
								<div class='btSource'><span class='btLanguage'>Grammatical tag</span><br />
								<span class='btNative'><select class='full-width select-tags select2'><option>none</option>".(new pSelector('subclassifications', @$data['subclassification_id'], 'name', true, 'rules', false))->render()."</select></span><br /><br />
								
								<div class='btSource'>
									<span class='btLanguage'>Status</span><br />
									<span class='btNative'>
									<select class='select-status btStatus float-right'>
										<option value='0' ".(($edit && $data['hidden'] == 0) ? 'selected' : '').">Visible</option>
										<option value='1' ".(($edit && $data['hidden'] == 1) ? 'selected' : '').">Hidden</option>
									</select>
									</span>
								</div>

								</div>

						</div>
					</div>
				</div>
				<div data-tab='Translations and definitions'><div style='padding: 25px;padding-top: 10px;'>
				");


	
		// Languages
		$languages = pLanguage::allActive(-1);
		$sendLanguages = array();
		foreach($languages as $language){
			$sendLanguages[] = "'".$language->read('id')."': $('.translations-language-".$language->read('id')."').val()";
			p::Out("

				<div class='btSource'><span class='btLanguage'><span class='xmedium'>".(new pDataField(null, null, null, 'flag'))->parse($language->read('flag'))." ".$language->read('name')."</span></span><br />
							<span class='btNative'><textarea class=' tags full-width translations-language-".$language->read('id')." ' style='width: 100%' >".($edit ? $this->prepareTranslations($language->read('id')) : '')."</textarea></span></div><br />

				");


		}

		p::Out("<br /><br />".pMainTemplate::NoticeBox('fa-info-circle fa-12', BATCH_TR_DESC1, 'notice-subtle').pMainTemplate::NoticeBox('fa-info-circle fa-12', sprintf(BATCH_TR_DESC2, '<span class="imitate-tag">', '</span>', '<span class="imitate-tag">', '</span>'),  'notice-subtle'));


		p::Out("
				</div>
				</div>");

		if($edit){
			p::Out("
				<div data-tab='Irregular Forms'>");

			//Irregular Forms
			// The form version of the inflection tables, rendered by the inflector housing in the datamodel
			p::Out($this->_data->_inflector->render("pIrregularTable"));
			p::Out("
				</div>");
		}

		p::Out("	
				<div data-tab='Relationships'>

					<div class='rulesheet'>
						<div class='left'>

					".pMainTemplate::NoticeBox('fa-info-circle fa-12', "Only existing lemmas are allowed!", 'notice-subtle')."
					<br />
	
					<select data-heading='Synonyms' class='select-synonyms' style='width:100%;'>".$this->_data->preloadSpecification('synonyms', '%').(new pSelector('words', null, 'native', true, 'rules', true))->render()."</select>	<br />

					<select data-heading='Antonyms' class='select-antonyms' style='width:100%;'>".$this->_data->preloadSpecification('antonyms', '%').(new pSelector('words', null, 'native', true, 'rules', true))->render()."</select>	<br />


					</div><div class='right'>
					<br /><br /><br />
					<select data-heading='Homophones' class='select-homophones' style='width:100%;'>".$this->_data->preloadSpecification('homophones', '%').(new pSelector('words', null, 'native', true, 'rules', true))->render()."</select>

					</div></div>

				</div>

				<div data-tab='Examples'>
					<div style='padding: 10px'><select class='examples-select' data-heading='Examples'>".$this->_data->preloadSpecification('idiom_words')
					.(new pSelector('idioms', null, 'idiom', true, 'rules', true))->render()."
					</select></div>
				</div>
				<div data-tab='Notes'>
					<div style='padding: 10px'><textarea placeholde='➥ Add notes...' class='gtEditor usage-notes elastic allowtabs'>".($edit ? $this->_data->_links['usage_notes'] : '')."</textarea></div>
				</div></div>
		</form><br />
");
		p::Out("<a class='btAction green submit-form no-float'>".SAVE."</a>");

		if($edit)
			p::Out((new pAction('remove', 'Delete item', 'fa-times', 'btAction no-float redbt', null, null, 'words', 'dictionary-admin', null, -3))->render($data['id']));

		p::Out("<br id='cl' /></div>");

		$hashKey = spl_object_hash($this);

			// Throwing this object's script into a session
			pRegister::session($hashKey, $this->script($sendLanguages, $section, @$data, $edit));

		p::Out("<script type='text/javascript' src='".p::Url('pol://library/assets/js/key.js.php?key='.$hashKey)."'></script>");

		p::Out("
			<br id='cl' /></div>
		");
	}

	protected function script($sendLanguages, $section, $data, $edit){
		return "
		var selectScore = '<select><option value=\"0%\">0%</option><option value=\"25%\">25%</option><option value=\"50%\">50%</option><option value=\"75%\">75%</option><option value=\"100%\">100%</option></select>';

		".pMainTemplate::allowTabs()."

		$('.mainTabs').cardTabs();
		$('.select2').select2({});

		$('.select2m').select2({allowClear: true, placeholder: '➥ Add lemmas'});
		$('.tags').tagsInput({
			'delimiter': '//'
		});
		$(document).ready(function(){
    		$('.elastic').elastic();
		});
		$('.semanticsTabs').cardTabs();
		$('.select-status').selectorTabs();
		$('.generate-ipa').click(function(){
			$('.ajaxGenerateIPA').load('".p::Url("?editor/".$section."/generateipa/ajax")."', {'lemma': $('.lemma-native').val()});
		});
		$('.submit-form').click(function(){
			arrIrreg = [];
			$('input.irreg').each(function(){
				pushThis = {};
				pushThis['value'] = $(this).val();
				pushThis['selector'] = $(this).data('id');
				arrIrreg.push(pushThis);
			});
			$('.errorSave').slideUp();
			$('.saving').slideDown();
			$('.ajaxSave').load('".p::Url("?editor/".$section."/".($edit ? 'edit/'.$data['id'] : 'new')."/ajax")."', {
				'native': $('.lemma-native').val(),
				'lexform': $('.lemma-lexform').val(),
				'ipa': $('.lemma-ipa').val(),
				'lexcat': $('.select-lexcat').val(),
				'gramcat': $('.select-gramcat').val(),
				'tags': $('.select-tags').val(),
				'translations': JSON.stringify({".implode(', ', $sendLanguages)."}),
				'usage_notes': $('.usage-notes').val(),
				'synonyms': $('.select-synonyms').data('storage'),
				'antonyms': $('.select-antonyms').data('storage'),
				'homophones': $('.select-homophones').data('storage'),
				'examples': $('.examples-select').data('storage'),
				'hidden': $('.select-status').val(),
				'irregular': arrIrreg,
			});
		});
		$('.examples-select').selectSpecify({
			'select2': true,
			'select2options': { width: '90%' },
			'minheight': '300px',
			'placeholder': '➥ Add examples',
		});
		$('.select-synonyms').selectSpecify({
			'attributeElement': selectScore,
			'select2': true,
			'select2options': { width: '90%' },
			'itemLabelText': 'Synonym',
			'removeLabelText': 'Remove',
			'attributeSurface': 'Score',
			'saveLabelText': 'Save',
			'placeholder': '➥ Add synonyms',
			'width': '100%',
		});
		$('.select-antonyms').selectSpecify({
			'attributeElement': selectScore,
			'select2': true,
			'select2options': { width: '90%' },
			'itemLabelText': 'Antonym',
			'removeLabelText': 'Remove',
			'attributeSurface': 'Score',
			'saveLabelText': 'Save',
			'placeholder': '➥ Add antonyms',
			'width': '100%',
		});
		$('.select-homophones').selectSpecify({
			'attributeElement': selectScore,
			'select2': true,
			'select2options': { width: '90%' },
			'itemLabelText': 'Homophone',
			'removeLabelText': 'Remove',
			'attributeSurface': 'Score',
			'saveLabelText': 'Save',
			'placeholder': '➥ Add homophones',
			'width': '100%',
		});";
	}


	public function translationForm(){
	}

	public function renderNew(){
		if($this->activeStructure['section_key'] != 'translation')
			return $this->lemmasheetForm($this->activeStructure['section_key']);
		else
			return $this->translationForm($this->activeStructure['section_key']);
	}

	public function renderEdit(){
		if($this->activeStructure['section_key'] != 'translation')
			return $this->lemmasheetForm($this->activeStructure['section_key'], true);
		else
			return $this->translationForm($this->activeStructure['section_key'], true);
	}

}