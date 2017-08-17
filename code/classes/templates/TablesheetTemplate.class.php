<?php

// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: TablesheetTemplate.class.php


class pTablesheetTemplate extends pTemplate{

	public function tablesheetForm($edit){
		if($edit)
			$data = $this->_data->data()->fetchAll()[0];
		else
			$data = null;

		p::Out("
			<div class='rulesheet'>
			<div class='card-tabs-bar vertical'>
			<a href='".p::Url('?tablesheet/'.$this->_data->id.'/new')."' class='dotted ".(!$edit ? 'active' : '')."'>".(new pIcon('asterisk'))." New table... </a>
			");
				
		// Going through the tables
		foreach($this->_data->_tables as $table)
			p::Out("<a href='javascript:void(0)' class='".(($edit AND isset(pRegister::arg()['table_id']) AND pRegister::arg()['table_id'] == $table['id']) ? 'active' : '')."'>".$table['name']."</a>");

		p::Out("</div><div class='tablesheet'>

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