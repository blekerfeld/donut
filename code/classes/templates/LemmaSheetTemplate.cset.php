<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: MenuTemplate.cset.php

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
		
		p::Out(pMainTemplate::NoticeBox('fa-spinner fa-spin fa-12', SAVING, 'notice saving hide rulesheet-margin'));

		// That is where the ajax magic happens:
		p::Out("<div class='ajaxSave rulesheet-margin'></div>");

		p::Out("<a class='btAction green submit-form float-right'>".(new pIcon('fa-check-circle', 10))." ".SAVE."</a><br />
			<div class='rulesheet'>
			<div class='left'>
			<div class='btCard rulesheetCard'>
				<div class='btTitle'>Lemma</div>
				<div class='btSource'><span class='btLanguage'>Dictionary form <span class='xsmall' style='color: darkred;opacity: 1;'>*</span></span><br />
				<span class='btNative'><input class='btInput nWord small normal-font lemma-native' value='".($edit ? $data['native'] : '')."'/></span></div>
				<div class='btSource'><span class='btLanguage'>Lexical form <span class='xsmall' style='color: darkred;opacity: 1;'>".pMainTemplate::NoticeBox('fa-info-circle fa-12', "This form is used as base for regular inflection", 'medium notice')."</span></span>
				<span class='btNative'><input class='btInput nWord small normal-font lemma-lexical-form' value='".($edit ? $data['lexical_form'] : '')."'/></span></div>
				<div class='btSource'><span class='btLanguage'><a class='generate-ipa float-right small' href='javascript:void();'>[ generate IPA ]</a> IPA transcription <span class='xsmall' style='color: darkred;opacity: 1;'>*</span></span><br /><span class='ajaxGenerateIPA'></span>
				<span class='btNative'><input class='btInput nWord small normal-font lemma-ipa' value='".($edit ? $data['ipa'] : '')."'/></span></div>
			</div><br /><br />
			<div class='btCard rulesheetCard'>

			<div class='semanticsTabs'>
				<div class='card-tabs-bar titles'>
					<a href='javascript:void();' data-tab='synonyms'>Synonyms</a>
					<a href='javascript:void();' data-tab='antonyms'>Antonyms</a>
					<a href='javascript:void();' data-tab='homophones'>Homophones</a>
					<a href='javascript:void();' data-tab='etyomologies'>Etyomologies</a>
				</div>
				<div class='card-tabs-stack'>
					<div data-tab='synonyms'>
						dit zijn vertalingen.
					</div>
					<div data-tab='antonyms'>
						dit zijn vertalingen.
					</div>
					<div data-tab='homophones'>
						dit zijn voorbeelden
					</div>
					<div data-tab='etyomologies'>
						dit zijn voorbeelden
					</div>
				</div>
			</div>
			
			</div>
		</div>
		<div class='right'><div class='btCard rulesheetCard'>

			<div class='linksTabs'>
				<div class='card-tabs-bar titles'>
					<a href='javascript:void();' data-tab='categories'>Categories</a>
					<a href='javascript:void();' data-tab='translations'>Translations</a>
					<a href='javascript:void();' data-tab='examples'>Examples</a>
					<a href='javascript:void();' data-tab='usagenotes'>Usage notes</a>
				</div>
				<div class='card-tabs-stack'>
					<div data-tab='categories'>
						<br />
						<div class='btSource'><span class='btLanguage'>Lexical category <em class='small'>(part of speech)</em></span><br />
							<span class='btNative'><select class='full-width select-lexcat select2'>".(new pSelector('types', $data['type_id'], 'name', true, 'rules', true))->render()."</select></span></div>
							<div class='btSource'><span class='btLanguage'>Grammatical category</span><br />
							<span class='btNative'><select class='full-width select-gramcat select2'><option>none</option>".(new pSelector('classifications', $data['classification_id'], 'name', true, 'rules', true))->render()."</select></span></div>
							<div class='btSource'><span class='btLanguage'>Grammatical tag</span><br />
							<span class='btNative'><select class='full-width select-lexcat select2'><option>none</option>".(new pSelector('subclassifications', $data['subclassification_id'], 'name', true, 'rules', false))->render()."</select></span></div>
					</div>
					<div data-tab='translations'>
						");


		p::Out(pMainTemplate::NoticeBox('fa-info-circle fa-12', "The input format is <span class='imitate-tag'>translation</span> or <span class='imitate-tag'>translation|tag</span>.", 'notice-subtle'));
	
		// Languages
		$languages = pLanguage::allActive();
		$sendLanguages = array();
		foreach($languages as $language){
			$sendLanguages[] = "'".$language->read('id')."': $('.translations-language-".$language->read('id')."').val()";
			p::Out("

				<div class='btSource'><span class='btLanguage'><strong>".(new pDataField(null, null, null, 'flag'))->parse($language->read('flag'))." ".$language->read('name')."</strong></span><br />
							<span class='btNative'><textarea class=' tags full-width translations-language-".$language->read('id')." ' style='width: 100%' >".($edit ? $this->prepareTranslations($language->read('id')) : '')."</textarea></span></div>

				")
			;
		}

		p::Out("
					</div>
					<div data-tab='examples'>
						dit zijn voorbeelden
					</div>
					<div data-tab='usagenotes'>
						dit zijn voorbeelden
					</div>
				</div>
			</div>
			
			</div>

			<br />	");
		p::Out("</div><br />
		</div><a class='btAction green submit-form float-right'>".(new pIcon('fa-check-circle', 10))." ".SAVE."</a><br id='cl' /></div>
		<script type='text/javascript'>
		$('.linksTabs').cardTabs();
		$('.select2').select2({});
		$('.select2m').select2({allowClear: true});
		$('.tags').tagsInput({
			'delimiter': '//'
		});
		$('.semanticsTabs').cardTabs();
		$('.generate-ipa').click(function(){
			$('.ajaxGenerateIPA').load('".p::Url("?lemmasheet/".$section."/generateipa/ajax")."', {'lemma': $('.lemma-native').val()});
		});
		$('.submit-form').click(function(){
					$('.saving').slideDown();
					$('.ajaxSave').load('".p::Url("?lemmasheet/".$section."/".($edit ? 'edit/'.$data['id'] : 'new')."/ajax")."', {
						'lexcat': $('.select-lexcat').val(),
						'gramcat': $('.select-gramcat').val(),
						'tags': $('.select-tags').val(),
						'translations': JSON.stringify({".implode(', ', $sendLanguages)."}),
					});
				});
		</script>");
	}

	public function renderNew(){
		return $this->lemmasheetForm($this->activeStructure['section_key']);
	}

	public function renderEdit(){
		return $this->lemmasheetForm($this->activeStructure['section_key'], true);
	}

}