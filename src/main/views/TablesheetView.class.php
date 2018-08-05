<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: TablesheetView.class.php


class pTablesheetView extends pView{

	public function tablesheetForm($edit, pParadigm $paradigm = null){
		
		// The lexical category

		$type = $this->_data->dataModel->data()->fetchAll()[0];

		if($edit)
			$mode = $paradigm->_data;



		p::Out("<br /><div class='card-tabs-bar titles border'>");
		foreach($this->_data->_types as $typeFetch)
			p::Out("<a class='".($typeFetch['id'] == $type['id'] ? 'active' : '')."' href='".p::Url('?grammar/tablesheet/new/'.$typeFetch['id'])."'>".ucfirst($typeFetch['name'])."</a>");
		p::Out("</div>");

			p::Out("<div class='saving hide'>".pTemplate::loadDots()."</div>");
			p::Out("<div class='ajaxSave rulesheet-margin'></div>");

		// p::Out("<div class='rulesheet-header'>".p::Markdown("## ".ucfirst($type['name'])." - inflection tables")."</div><br />");

		p::Out("
			<div class='rulesheet border'>
			<div class='card-tabs-bar vertical'>
			<a href='".p::Url('?grammar/tablesheet/new/'.$this->_data->id)."' class='dotted ".(!$edit ? 'active' : '')."'>".(new pIcon('asterisk'))." New table... </a>
			");
				
		// Going through the tables
		foreach($this->_data->_tables as $table)
			p::Out("<a href='".p::Url('?grammar/tablesheet/edit/'.$this->_data->id.'/'.$table['id'])."' class='".(($edit AND isset(pRegister::arg()['table_id']) AND pRegister::arg()['table_id'] == $table['id']) ? 'active' : '')."'>".ucfirst($table['name'])."</a>");

		p::Out("</div><div class='tablesheet'>
					<div class='btTopField'>
					<div class='btSource'><span class='btLanguage'><strong>Name</strong> <span class='xsmall darkred'>*</span></span></span><br />
							<span class='btNative'><input class='btInput nWord medium normal-font table-name' value='".($edit ? $mode['name'] : '')."'/></span></div>
					</div>
			<div class='rulesheet'>

			<div class='left'>
				<div class='btCard full'>
					<div class='btTitle'>".TS_TABLE_SETUP."</div>

					<div class='btSource'><span class='btLanguage'><span class='xmedium'>".TS_ROWS."</span></span><br />
					<span class='btNative'><textarea class='no-border tags rows full-width' style='width: 100%' >".(!empty($paradigm->_rows) ? implode('//', array_column($paradigm->_rows, 'name')) : '')."</textarea></span></div><br />
					<div class='btSource'><span class='btLanguage'><span class='xmedium'>".TS_HEADINGS." <span class='medium'><em>".TS_OPTIONAL."</em></span></span></span><br />
							<span class='btNative'><textarea class='no-border tags headings full-width' style='width: 100%' >".(!empty($paradigm->_headings) ? implode('//', array_column($paradigm->_headings, 'name')) : '')."</textarea></span></div><br />
					<div class='btSource'><span class='btLanguage'><span class='xmedium'>".TS_COLUMNS." <span class='medium'><em>".TS_OPTIONAL."</em></span></span></span><br />
					<span class='btNative'><textarea class='no-border tags columns full-width' style='width: 100%' >".(!empty($paradigm->_columns) ? implode('//', array_column($paradigm->_columns, 'name')) : '')."</textarea></span></div><br />

					<div class='btButtonBar'>
						<a class='btAction green submit-form no-float'>".(new pIcon('fa-check-circle', 10))." ".SAVE."</a>
						");

		if($edit)
			p::Out((new pAction('remove', DA_DELETE, 'fa-times', 'btAction no-float redbt', null, null, 'tablesheet', 'tablesheet', null, -3))->render($mode['id']));

		p::Out("
						<br id='cl' />
						</div>

				</div>
				
			</div>
			<div class='right'>
				<div class='btCard full'>
					<div class='btTitle'>".TS_TABLE_PREVIEW."</div>
					".($edit ? "<a class='btAction xsmall no-float' href='javascript:void(0);' onClick='updatePreviewRandom(".$mode['id'].");'>".TS_RANDOM_EXAMPLE."</a>" : '')."
					<div class='previewing hide loaddots'>".pTemplate::loadDots('center inline-block padding-left-40')."</div>
					<div class='tablePreview'></div>
				</div>
			</div>
			</div>

		");

		p::Out("</div>
			</div>");

		$hashKey = spl_object_hash($this);

			// Throwing this object's script into a session
			pRegister::session($hashKey, $this->script());

		p::Out("<script type='text/javascript' src='".p::Url('pol://library/assets/js/key.js.php?key='.$hashKey)."'></script>");


	}

	protected function script(){
		return "$('.tags.headings').tagsInput({
			'delimiter': '//',
			'placeholder':'➥ ".TS_HEADINGS_ADD."',
			'onChange':function(){
				updatePreview();
			},
		});
		$('.submit-form').click(function(){
			$('.saving').slideDown();
			$('.ajaxSave').load();
			$('.saving').slideUp();
		})
$('.tags.rows').tagsInput({
	'delimiter': '//',
	'placeholder':'➥ ".TS_ROWS_ADD."',
	'onChange':function(){
		updatePreview();
	},
});
$('.tags.columns').tagsInput({
	'delimiter': '//',
	'placeholder':'➥ ".TS_COLUMNS_ADD."',
	'onChange':function(){
		updatePreview();
	},
});
updatePreview();
$('.table-name').keyup(function(){
	updatePreview();
});
function updatePreview(){
	$('.previewing').show();
	$('.tablePreview').hide().load('".p::Url('?grammar/tablesheet/preview/'.pRegister::arg()['id'].'/ajax')."', {
		'name':$('.table-name').val(),
		'headings':$('.tags.headings').val(),
		'rows':$('.tags.rows').val(),
		'columns':$('.tags.columns').val()
	}, function(){
		$('.previewing').hide(function(){
			$('.tablePreview').show();
		});
	});
};
function updatePreviewRandom(modeID){
	$('.previewing').show();
	$('.tablePreview').hide().load('".p::Url('?grammar/tablesheet/randompreview/'.pRegister::arg()['id'])."' + '/' + modeID + '/ajax', {
		'name':$('.table-name').val(),
		'headings':$('.tags.headings').val(),
		'rows':$('.tags.rows').val(),
		'columns':$('.tags.columns').val()
	}, function(){
		$('.previewing').hide(function(){
			$('.tablePreview').show();
		});
	});
};";

}

	public function renderNew(){
		return $this->tablesheetForm(false);
	}

	public function renderEdit($mode){
		return $this->tablesheetForm(true, (new pParadigm($mode)));
	}

	public function renderOverview(){

		p::Out("<br /><div class='card-tabs-bar titles'>");
		foreach($this->_data->_types as $typeFetch)
			p::Out("<a class='' href='".p::Url('?grammar/tablesheet/new/'.$typeFetch['id'])."'>".ucfirst($typeFetch['name'])."</a>");
		p::Out("</div>");
	}

}