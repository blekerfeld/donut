<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: TableObject.class.php

class pTableHandler extends pHandler{
	// Used as last

	public function render(){

		// Generating the action bar
		$this->_actionbar->generate();

		if($this->_paginated)
			$pages = "<div class='pages'>".$this->pagePrevious().$this->pageSelect().$this->pageNext()."</div>";
		else
			$pages = '';


		p::Out("<div class='btCard admin'>
			<div class='btTitle'>
				".(new pIcon($this->_icon, 15))." ".$this->_surface."
			</div>
			<div class='btButtonBar no-border up'>".$pages.$this->_actionbar->output."</div><div class='content'>
			");

		$records = 0;
		$col_count = 2;

		p::Out("<table class='admin'>
			<thead>
			<tr class='title' role='row'><td style='width: 50px'><span>".DL_ID."</span></td>");

		// Building the table
		foreach ($this->_dfs->get() as $datafield) {
			if($datafield->showInTable == true){
				p::Out("<td style='width: ".$datafield->width."'>".$datafield->surface."</td>");
				$col_count++;
			}
		}

		// Links
		if($this->_linked != null){
			p::Out("<td style='width: auto;' colspan='2' class='actions'><i class='fa fa-link'></i> ".DA_TABLE_LINKS."</td>");
			$col_count++;
		}
		else
			p::Out("<td class='actions'></td>");

		p::Out("
			</tr></thead><tbody>");

		foreach($this->_data as $data){

			$real_id = $data['id'];
			$showID = $data['id'];

			if(isset($this->_activeSection['id_as_hash']) AND $this->_activeSection['id_as_hash'] AND isset($this->_activeSection['hash_app'])){
				$showID = "<a class='medium tooltip' href='".p::Url('?'.$this->_activeSection['hash_app'].'/'.p::HashId($data['id']))."'>".(new pIcon('fa-bookmark-o', 12))." ".p::HashId($data['id'])."</a>";
				$data['id'] = p::HashId($data['id']);
			}


			p::Out("<tr class='item_".$real_id ."'><td><span class='xsmall'>".($real_id == 0 ? "<em><strong>".DA_DEFAULT."</em></strong>" : $showID) ."</span></td>");

			// Go through the data fields
			foreach($this->_dfs->get() as $datafield){
				if($datafield->showInTable == true){
					p::Out("<td style='width: ".$datafield->width."'>".$datafield->parse($data[$datafield->name])."</td>");
					$records++;
				}
			}

			// The links stuff
			// Links
			if($this->_linked != null){
				p::Out("<td>");
				foreach($this->_linked as $key => $link){
					if((new pUser)->checkPermission($this->itemPermission(((isset($link->structure[$this->_section]['outgoing_links'][$key]['double_parent'])) ? $this->_section : $key), pStructure::$permission))){
						$action = new pAction('link-table', "&#x205F; ".$link->structure[$this->_section]['outgoing_links'][$key]['surface'], $link->structure[$this->_section]['outgoing_links'][$key]['icon'], 'small table-link', null, null, ((isset($link->structure[$this->_section]['outgoing_links'][$key]['double_parent'])) ? $this->_section : $key), $link->_app);

						// Counting the links
						$dataCount = new pDataModel($link->structure[$this->_section]['outgoing_links'][$key]['table']);

						$dataCount->setCondition("WHERE ((".$link->structure[$this->_section]['outgoing_links'][$key]['field']." = '".$real_id."'" . ((isset($link->structure[$this->_section]['outgoing_links'][$key]['double_parent'])) ? ") OR (". $link->structure[$this->_section]['outgoing_links'][$key]['double_parent'] . " = '".$real_id."'" : '')."))");


							$counter = "<span class='counter'>".$dataCount->countAll($link->structure[$this->_section]['outgoing_links'][$key]['field'] . " = ".$real_id)."</span>";

						$action->_surface = $counter.$action->_surface;

						p::Out("<span class='tooltip small' title='".(new pIcon($link->structure[$this->_section]['outgoing_links'][$key]['icon'], 8))." ".$link->structure[$this->_section]['outgoing_links'][$key]['surface']."'>".$action->render($data['id'], ((isset($link->structure[$this->_section]['outgoing_links'][$key]['double_parent'])) ? $key : $this->_section)).'</span>');
					}
				}
				p::Out("</td>");
			}

			// The important actions and such
			
			p::Out("<td style='text-align: center' class='actions'><a href='javascript:void();' class='btAction actions-holder no-float ttip_actions' data-tooltip-content='#dropdown_".$data['id']."'>".(new pIcon('fa-caret-down', 12))."</a>");

			p::Out("		<div class='hide'><div id='dropdown_".$data['id']."' class=''>");

			foreach($this->_actions->get() as $action)
				if(!($action->name == 'remove' AND $real_id == 0))
					p::Out($action->render($data['id']));
			p::Out("</div></div>");
			
			p::Out("</td>");

			p::Out("</tr>");
		}

		if($records == 0)
			p::Out("<tr><td colspan=".$col_count.">".DA_NO_RECORDS."</td>");

		p::Out("</tbody></table><script>$('.tooltip').tooltipster({animation: 'grow', distance: 0, contentAsHTML: true});</script>");

		p::Out("</div>
			<div class='btButtonBar no-border'>".$pages.$this->_actionbar->output."</div>
		</div>");
		}
}