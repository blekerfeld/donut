<?php

// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: TablesheetTemplate.class.php


class pTablesheetTemplate extends pTemplate{

	public function tablesheetForm($edit){
		
		// The lexical category
		$type = $this->_data->dataModel->data()->fetchAll()[0];
	
	
		p::Out("<div class='rulesheet-header'>".p::Markdown("## ".ucfirst($type['name'])." - inflection tables")."</div><br />");

		p::Out("
			<div class='rulesheet'>
			<div class='card-tabs-bar vertical'>
			<a href='".p::Url('?tablesheet/'.$this->_data->id.'/new')."' class='dotted ".(!$edit ? 'active' : '')."'>".(new pIcon('asterisk'))." New table... </a>
			");
				
		// Going through the tables
		foreach($this->_data->_tables as $table)
			p::Out("<a href='".p::Url('?tablesheet/'.$this->_data->id.'/edit/'.$table['id'])."' class='".(($edit AND isset(pRegister::arg()['table_id']) AND pRegister::arg()['table_id'] == $table['id']) ? 'active' : '')."'>".ucfirst($table['name'])."</a>");

		p::Out("</div><div class='tablesheet'>
			<div class='rulesheet'>
			<div class='left'>
				<div class='btCard full'>
					<div class='btTitle'>Table set-up</div>
				</div>
				
			</div>
			<div class='right'>
				<div class='btCard full'>
					<div class='btTitle'>Table preview</div>
				</div>
			</div>
			</div>
		");

		p::Out("</div>
			</div>");


	}

	public function renderNew(){
		return $this->tablesheetForm(false);
	}

	public function renderEdit(){
		return $this->tablesheetForm(true);
	}

}