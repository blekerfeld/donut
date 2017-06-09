<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: MenuTemplate.cset.php

// Accepts a RuleDataModel as $data parameter

class pRulesheetTemplate extends pTemplate{

	public function ruleTypeWatch($section){
		return "<script type='text/javascript'>

				function loadDesc(){
					$('.describeStatement').load('".p::Url('?rulesheet/'.$section.'/describe/ajax')."', {'rule' : $('#rule-content').val()});
					if($('#example').val() != ''){
						loadEx();	
					}
				}

				$(document).ready(function(){
					loadDesc();
				});

				var options = {
			    	callback: function (value) {
			    		loadDesc();
			    	},
			    	wait: 200,
			   		highlight: true,
			    	allowSubmit: false,
			    	captureLength: 1,
				};
				$('#rule-content').typeWatch( options );
				</script>";
	}

	public function exampleTypeWatch($section){
		return "<script type='text/javascript'>

				function loadEx(){
					$('.example').load('".p::Url('?rulesheet/'.$section.'/example/ajax')."', {'rule' : $('#rule-content').val(), 'lexform' : $('#example').val()});
				}

				var options = {
			    	callback: function (value) {
			    		loadEx();
			    	},
			    	wait: 200,
			   		highlight: true,
			    	allowSubmit: false,
			    	captureLength: 1,
				};
				$('#example').typeWatch( options );
				</script>";
	}

	public function rulesheetForm($section, $edit = false, $ruleset = ''){
		if($edit)
			$data = $this->_data->data()->fetchAll()[0];
		
		p::Out(pMainTemplate::NoticeBox('fa-spinner fa-spin fa-12', SAVING, 'notice saving hide rulesheet-margin'));

		// That is where the ajax magic happens:
		p::Out("<div class='ajaxSave rulesheet-margin'></div>");
		p::Out("<div class='rulesheet-margin'><span class='pSectionTitle extra'>".(new pIcon('fa-folder', 12))." <strong>".pSetTemplate::breakDownName($ruleset['name'], 'rules')." ".($edit ? ' → '.new pIcon('fa-file-o' , 12) . " " . $data['name']  : ' → New Rule')."</strong></span><div class='pSectionWrapper'>");
		p::Out("<div class='rulesheet no-padding'>
			<div class='left'>
			<div class='btCard rulesheetCard'>
				<div class='btTitle'>Rule</div>
				".(!$edit ? pMainTemplate::NoticeBox('fa-info-circle fa-10', ' This rule will be added in <strong class="medium">'.$ruleset['name']."</strong>.", 'notice-subtle') : '')."
				<div class='btSource'><span class='btLanguage'>Name <span class='xsmall' style='color: darkred;opacity: 1;'>*</span></span><br />
				<span class='btNative'><input class='btInput nWord small normal-font name' value='".($edit ? $data['name'] : '')."'/></span></div>
				".($edit ? "<div class='btSource'><span class='btLanguage'>Ruleset <span class='xsmall' style='color: darkred;opacity: 1;'>*</span></span><br />
							<span class='btNative'><select class='full-width select-ruleset select2a'>".(new pSelector('rulesets', $data['ruleset'], 'name', true, 'rules', true))->render()."</select></span></div>" : "")."
				<div class='btSource'><span class='btLanguage'>Statement </span><span class='xsmall' style='color: darkred;opacity: 1;'>*</span><br />
				<span class='btNative'><textarea placeholder='prefix [stem] suffix' spellcheck='false' class='btInput Rule elastic allowtabs' id='rule-content'>".($edit ? $data['rule'] : '')."</textarea><div class='describeStatement'></div></span></div>
				".$this->ruleTypeWatch($section)."
				<div class='btSource'><span class='btLanguage'>Test rule</span><br />
				<span class='btNative'><input class='btInput nWord small normal-font' id='example' placeholder='Lexical form'/><div class='example'></div></span></div>
				".$this->exampleTypeWatch($section)."
				<div class='btButtonBar'>
						<a class='btAction green submit-form no-float'>".(new pIcon('fa-check-circle', 10))." ".SAVE."</a>
						");

		if($edit)
			p::Out((new pAction('remove', 'Delete item', 'fa-times', 'btAction no-float redbt', null, null, $section, 'rulesheet', null, -3))->render($data['id']));

		p::Out("
						<br id='cl' />
						</div>
			</div>
		</div>
		<div class='right'>	");
		if(!in_array($section, array('context', 'ipa')))
			p::Out("
			<div class='btCard rulesheetCard'>
				<div class='btTitle'>Selectors</div>
					<div class='notice'>".(new pIcon('fa-info-circle', 10))." The combination of the selectors decides when and where the rule is applied.</div><br />
					<div class='rulesheet inner'>
						<div class='left'>
							".p::Markdown("##### Primary selectors ")."<br />
							<div class='btSource'><span class='btLanguage'>Lexical categories <em class='small'>(part of speech)</em></span><br />
							<span class='btNative'><select class='full-width select-lexcat select2' multiple='multiple'>".(new pSelector('types', $this->_data->_links['lexcat'], 'name', true, 'rules', true))->render()."</select></span></div>
							<div class='btSource'><span class='btLanguage'>Grammatical categories</span><br />
							<span class='btNative'><select class='full-width select-gramcat select2' multiple='multiple'>".(new pSelector('classifications', $this->_data->_links['gramcat'], 'name', true, 'rules', true))->render()."</select></span></div>
							<div class='btSource'><span class='btLanguage'>Grammatical tags</span><br />
							<span class='btNative'><select class='full-width select-tags select2' multiple='multiple'>".(new pSelector('subclassifications', $this->_data->_links['tag'], 'name', true, 'rules', true))->render()."</select></span></div>
						</div>
						<div class='right'>
							".p::Markdown("##### Secondary selectors ")."<br />
							<div class='btSource'><span class='btLanguage'>Inflection tables</span><br />
							<span class='btNative'><select class='full-width select-tables select2' multiple='multiple'>".(new pSelector('modes', $this->_data->_links['modes'], 'name', true, 'rules', true))->render()."</select></span></div>
							<div class='btSource'><span class='btLanguage'>Table headings</span><br />
							<span class='btNative'><select class='full-width select-headings select2' multiple='multiple'>".(new pSelector('submodes', $this->_data->_links['submodes'], 'name', true, 'rules', true))->render()."</select></span></div>
							<div class='btSource'><span class='btLanguage'>Table rows</span><br />
							<span class='btNative'><select class='full-width select-rows select2' multiple='multiple'>".(new pSelector('numbers', $this->_data->_links['numbers'], 'name', true, 'rules', true))->render()."</select></span></div>
						</div>

				</div>");
		p::Out("</div>
		</div></div></div></div>
		<script type='text/javascript'>
		$('.submit-form').click(function(){
					$('.saving').slideDown();
					$('.ajaxSave').load('".p::Url("?rulesheet/".$section."/".($edit ? 'edit/'.$data['id'] : pAdress::arg()['action'])."/ajax")."', {
						".(!in_array($section, array('phonology', 'ipa')) ? "
						'lexcat': $('.select-lexcat').val(),
						'gramcat': $('.select-gramcat').val(),
						'tags': $('.select-tags').val(),
						'rows': $('.select-rows').val(),
						'headings': $('.select-headings').val(),
						'tables': $('.select-tables').val()," : "")."
						'rule': $('#rule-content').val(),
						'name': $('.name').val(),
						".($edit ? "'ruleset': $('.select-ruleset').val()": '')."
					});
				});

			$('.select2').select2({placeholder: 'All possible', allowClear: true});
			$('.select2a').select2();
		</script>");
	}

	public function renderNew($ruleset){
		return $this->rulesheetForm($this->activeStructure['section_key'], false, $ruleset);
	}

	public function renderEdit($ruleset){
		return $this->rulesheetForm($this->activeStructure['section_key'], true, $ruleset);
	}

}