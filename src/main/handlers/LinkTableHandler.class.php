<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: LinkTableObject.class.php

class pLinkTableHandler extends pHandler{

	public $_passed_data, $_show, $_matchOn, $_matchOnValue, $_link, $_guests;

	public function passData($guests, $link, $data, $show_parent, $show_child, $matchOn, $matchOnValue){
		$this->_guests = $guests;
		$this->_link = $link;
		$this->_passed_data = $data;
		$this->_show_parent = $show_parent;
		$this->_show_child = $show_child;
		$this->_matchOn = $matchOn;
		$this->_matchOnValue = $matchOnValue;
	}

	public function render(){

		// Generating the action bar
		$this->_actionbar->generate($this->_matchOnValue, $this->_link);

		if($this->_paginated)
			$pages = "<div class='pages'>".$this->pagePrevious()."<div>".sprintf(DA_PAGE_X_OF_Y, $this->pageSelect(), $this->_number_of_pages)."</div>".$this->pageNext()."</div>";
		else
			$pages = '';

		$col_count = 2;
		$records = 0;

		p::Out("<div class='btCard admin link-table'>
			<div class='btTitle'>
				".(new pIcon($this->_icon, 12))." ".$this->_surface."
			</div>
			<div class='btButtonBar up'><a class='btAction wikiEdit' href='".p::Url("?".$this->_app."&".$this->_guests->_data['section_key'])."'>".(new pIcon('fa-arrow-left', 12))." ".BACK."</a>".$pages.$this->_actionbar->output."</div><div class='content'>
			");


		p::Out("<div class='btSource'><span class='btLanguage'>".DA_TABLE_LINKS_PARENT.": </span><br />
			<span class='btNative'>".$this->_guests->_handler->_data[0][$this->_show_parent]."</span></div>");

		p::Out("<div class='btSource'><span class='btLanguage'>".DA_TABLE_LINKS_CHILDREN.": </span></div>");

		p::Out("<table class='admin' style='width:50%;float:left;'>
			<thead>
			<tr class='title' role='row'><td style='width: 10%;'><span class='xsmall'>".DA_TABLE_LINKS_CHILD_ID."</span></td>");

		// Building the table
		foreach ($this->_dfs->get() as $datafield) {
			if($datafield->showInTable == true){
				p::Out("<td style='width: ".$datafield->width."'>".$datafield->surface."</td>");
				$col_count++;
			}
		}

		// Links
		if($this->_linked != null)
			p::Out("<td>".DA_TABLE_LINKS."</td>");

		p::Out("<td>".ACTIONS."</td>
			</tr></thead><tbody>");



		foreach($this->_data as $data){

			foreach($this->_passed_data as $key => $item){
			      if ( $item['id'] == $data[$this->_matchOn] ){
			      	p::Out("<tr class='item_".$data['id']."'><td style='width: auto;max-width: 5px;'><span class='xsmall'>".($item['id'] == 0 ? "<em><strong>".DA_DEFAULT."</em></strong>" : $item['id']) ."</span></td>");
				      foreach($this->_dfs->get() as $key_d => $datafield){
						if($datafield->showInTable == true)
							if(isset($this->_guests->_data['outgoing_links'][pRegister::arg()['linked']]) AND $datafield->name != $this->_guests->_data['outgoing_links'][pRegister::arg()['linked']]['field'])
								p::Out("<td style='width: ".$datafield->width."'>".$datafield->parse($data[$datafield->name])."</td>");
							else{
							
								// Simple and a little dirty support for double parenting
								if((isset($this->_guests->structure[$this->_section]['incoming_links'][$this->_link]['double_parent']) AND $this->_guests->structure[$this->_section]['incoming_links'][$this->_link]['double_parent'] == true) AND $this->_guests->_handler->_data[0][$this->_show_parent] == $item[$this->_show_child]){

									// We have to get the data from another freaking data object

									// Getting the other item we need
									$this->_guests->_handler->dataModel->getSingleObject($data[$this->_guests->structure[$this->_section]['incoming_links'][$this->_link]['child']]);

									p::Out("<td style='width: ".$datafield->width."'>".$datafield->parse($this->_guests->_handler->dataModel->data()->fetchAll()[0]['id'])."</td>");
								}
								else
									p::Out("<td style='width: ".$datafield->width."'>".$datafield->parse($item['id'])."</td>");
							}
							$records++;
				   		}
			   		}
			}

			// The important actions and such
			p::Out("<td class='actions'>");
			foreach($this->_actions->get() as $action){
				if(!($action->name == 'remove' AND $data['id'] == 0))
					p::Out($action->render($data['id'], $this->_link));
			}
			p::Out("</td>");

			p::Out("</tr>");
		}

		if($records == 0)
			p::Out("<tr><td colspan=".$col_count.">".DA_NO_RECORDS."</td>");

		p::Out("</tbody></table><br id='cl' />
		</thead>");

		p::Out("</div>
			<div class='btButtonBar'>
			<a class='btAction wikiEdit' href='".p::Url("?".$this->_app."/".$this->_guests->_data['section_key'])."'><i class='fa fa-12 fa-arrow-left' ></i> ".BACK."</a>
			".$pages.$this->_actionbar->output."</div>
		</div>");
		}
	}
