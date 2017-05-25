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
		
		p::Out(pMainTemplate::NoticeBox('fa-spinner fa-spin fa-12', SAVING, 'notice saving hide'));

		// That is where the ajax magic happens:
		p::Out("<div class='ajaxSave'></div>");

		p::Out("
			<form id='LemmaSheetForm'>
			".($edit ? "<span class='native markdown-body'><h2><strong class='pWord'>".$data['native'].'</strong></h2></span>' : '<span class="markdown-body"><h2>'.LEMMA_NEW.'</h2></span><br />')."<br />
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
							<span class='markdown-body'><h2>Categories</h2></span><br />
							<div class='btSource'><span class='btLanguage'>Lexical category <em class='small'>(part of speech)</em></span><br />
								<span class='btNative'><select class='full-width select-lexcat select2'>".(new pSelector('types', @$data['type_id'], 'name', true, 'rules', true))->render()."</select></span></div>
								<div class='btSource'><span class='btLanguage'>Grammatical category</span><br />
								<span class='btNative'><select class='full-width select-gramcat select2'><option>none</option>".(new pSelector('classifications', @$data['classification_id'], 'name', true, 'rules', true))->render()."</select></span></div>
								<div class='btSource'><span class='btLanguage'>Grammatical tag</span><br />
								<span class='btNative'><select class='full-width select-tags select2'><option>none</option>".(new pSelector('subclassifications', @$data['subclassification_id'], 'name', true, 'rules', false))->render()."</select></span></div>
						</div>
					</div>
				</div>
				<div data-tab='Translations'><div style='padding: 25px;padding-top: 10px;'>
				");


		p::Out(pMainTemplate::NoticeBox('fa-info-circle fa-12', "Add as many translations as needed. Existing translations are linked and new ones are created on the fly.", 'notice-subtle')."<br />");
	
		// Languages
		$languages = pLanguage::allActive();
		$sendLanguages = array();
		foreach($languages as $language){
			$sendLanguages[] = "'".$language->read('id')."': $('.translations-language-".$language->read('id')."').val()";
			p::Out("

				<div class='btSource'><span class='btLanguage'><strong>".(new pDataField(null, null, null, 'flag'))->parse($language->read('flag'))." ".$language->read('name')."</strong></span><br />
							<span class='btNative'><textarea class=' tags full-width translations-language-".$language->read('id')." ' style='width: 100%' >".($edit ? $this->prepareTranslations($language->read('id')) : '')."</textarea></span></div>

				");


		}
		p::Out(pMainTemplate::NoticeBox('fa-info-circle fa-12', "The input format is <span class='imitate-tag'>translation</span> or <span class='imitate-tag'>translation>specification</span>", 'notice-subtle')."<br />");

		p::Out("
				</div>
				</div>
				<div data-tab='Relationships'>
				</div>
				<div data-tab='Notes'>
				</div>
			</div>
		</form>
");
		p::Out("<a class='btAction green submit-form no-float'>".(new pIcon('fa-check-circle', 10))." ".SAVE."</a>");

		if($edit)
			p::Out((new pAction('remove', 'Delete item', 'fa-times', 'btAction no-float redbt', null, null, 'words', 'dictionary-admin', null, -3))->render($data['id']));

		p::Out("
			<br id='cl' /></div>
		<script type='text/javascript'>
		$('.mainTabs').cardTabs();
		$('.linksTabs').cardTabs();
		$('.select2').select2({});
		$('.select2m').select2({allowClear: true});
		$('.tags').tagsInput({
			'delimiter': '//'
		});
		$('.catTabs').cardTabs();
		$('.semanticsTabs').cardTabs();
		$('.generate-ipa').click(function(){
			$('.ajaxGenerateIPA').load('".p::Url("?lemmasheet/".$section."/generateipa/ajax")."', {'lemma': $('.lemma-native').val()});
		});
		$('.submit-form').click(function(){
					$('.errorSave').slideUp();
					$('.saving').slideDown();
					$('.ajaxSave').load('".p::Url("?lemmasheet/".$section."/".($edit ? 'edit/'.$data['id'] : 'new')."/ajax")."', {
						'native': $('.lemma-native').val(),
						'lexform': $('.lemma-lexform').val(),
						'ipa': $('.lemma-ipa').val(),
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