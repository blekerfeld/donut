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
					$('.describeStatement').load('".pUrl('?rulesheet/'.$section.'/describe/ajax')."', {'rule' : $('#rule-content').val()});
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
					$('.example').load('".pUrl('?rulesheet/'.$section.'/example/ajax')."', {'rule' : $('#rule-content').val(), 'lexform' : $('#example').val()});
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

	public function rulesheetForm($section, $edit = false){
		if($edit)
			$data = $this->_data->data()->fetchAll()[0];
		
		pOut(pNoticeBox('fa-spinner fa-spin fa-12', SAVING, 'notice saving hide rulesheet-margin'));

		// That is where the ajax magic happens:
		pOut("<div class='ajaxSave rulesheet-margin'></div>");

		pOut("<div class='rulesheet'>
			<div class='left'>
			<div class='btCard rulesheetCard'>
				<div class='btTitle'>Rule</div>
				<div class='btSource'><span class='btLanguage'>Name <span class='xsmall' style='color: darkred;opacity: 1;'>*</span></span><br />
				<span class='btNative'><input class='btInput nWord small normal-font name' value='".($edit ? $data['name'] : '')."'/></span></div>
				<div class='btSource'><span class='btLanguage'>Statement </span><span class='xsmall' style='color: darkred;opacity: 1;'>*</span><br />
				<span class='btNative'><textarea placeholder='prefix [stem] suffix' spellcheck='false' class='btInput Rule elastic allowtabs' id='rule-content'>".($edit ? $data['rule'] : '')."</textarea><div class='describeStatement'></div></span></div>
				".$this->ruleTypeWatch($section)."
				<div class='btSource'><span class='btLanguage'>Test rule</span><br />
				<span class='btNative'><input class='btInput nWord small normal-font' id='example' placeholder='Lexical form'/><div class='example'></div></span></div>
				".$this->exampleTypeWatch($section)."
				<br />
			</div>
		</div>
		<div class='right'>	");
		if(!in_array($section, array('phonology', 'ipa')))
			pOut("
			<div class='btCard rulesheetCard'>
				<div class='btTitle'>Selectors</div>
					<div class='notice'>".(new pIcon('fa-info-circle', 10))." The combination of the selectors decides when and where the rule is applied.</div><br />
					<div class='rulesheet inner'>
						<div class='left'>
							".pMarkdown("##### Primary selectors ")."<br />
							<div class='btSource'><span class='btLanguage'>Lexical categories <em class='small'>(part of speech)</em></span><br />
							<span class='btNative'><select class='select-lexcat select2' multiple='multiple'>".(new pSelector('types', $this->_data->_links['lexcat'], 'name', true, 'rules', true))->render()."</select></span></div>
							<div class='btSource'><span class='btLanguage'>Grammatical categories</span><br />
							<span class='btNative'><select class='select-gramcat select2' multiple='multiple'>".(new pSelector('classifications', $this->_data->_links['gramcat'], 'name', true, 'rules', true))->render()."</select></span></div>
							<div class='btSource'><span class='btLanguage'>Grammatical tags</span><br />
							<span class='btNative'><select class='select-tags select2' multiple='multiple'>".(new pSelector('subclassifications', $this->_data->_links['tag'], 'name', true, 'rules', true))->render()."</select></span></div>
						</div>
						<div class='right'>
							".pMarkdown("##### Secondary selectors ")."<br />
							<div class='btSource'><span class='btLanguage'>Inflection tables</span><br />
							<span class='btNative'><select class='select-tables select2' multiple='multiple'>".(new pSelector('modes', $this->_data->_links['modes'], 'name', true, 'rules', true))->render()."</select></span></div>
							<div class='btSource'><span class='btLanguage'>Table headings</span><br />
							<span class='btNative'><select class='select-headings select2' multiple='multiple'>".(new pSelector('submodes', $this->_data->_links['submodes'], 'name', true, 'rules', true))->render()."</select></span></div>
							<div class='btSource'><span class='btLanguage'>Table rows</span><br />
							<span class='btNative'><select class='select-rows select2' multiple='multiple'>".(new pSelector('numbers', $this->_data->_links['numbers'], 'name', true, 'rules', true))->render()."</select></span></div>
						</div>
				</div>");
		pOut("</div><br /><a class='btAction no-float green submit-form'>Click</a><br id='cl' />
		</div></div>
		<script type='text/javascript'>
		$('.submit-form').click(function(){
					$('.saving').slideDown();
					$('.ajaxSave').load('".pUrl("?rulesheet/".$section."/".($edit ? 'edit/'.$data['id'] : 'new')."/ajax")."', {
						".(!in_array($section, array('phonology', 'ipa')) ? "
						'lexcat': $('.select-lexcat').val(),
						'gramcat': $('.select-gramcat').val(),
						'tags': $('.select-tags').val(),
						'rows': $('.select-rows').val(),
						'headings': $('.select-headings').val(),
						'tables': $('.select-tables').val()," : "")."
						'rule': $('#rule-content').val(),
						'name': $('.name').val(),
					});
				});

			$('.select2').select2({placeholder: 'All possible', allowClear: true});
		</script>");
	}

	public function renderNew(){
		return $this->rulesheetForm($this->activeStructure['section_key']);
	}

	public function renderEdit(){
		return $this->rulesheetForm($this->activeStructure['section_key'], true);
	}

}