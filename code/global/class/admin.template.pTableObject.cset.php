<?php

class pTableObject extends pAdminObject{
	// Used as last

	public function render(){

		// Generating the action bar
		$this->_actionbar->generate();

		if($this->_paginated)
			$pages = "<div class='pages'>".$this->pagePrevious()."<div>".sprintf(DA_PAGE_X_OF_Y, $this->pageSelect(), $this->_number_of_pages)."</div>".$this->pageNext()."</div>";
		else
			$pages = '';


		pOut("<div class='btCard admin'>
			<div class='btTitle'>
				<i class='fa ".$this->_icon."'></i> ".$this->_surface."
			</div>
			<div class='btButtonBar up'>".$pages.$this->_actionbar->output."</div>
			");

		pOut("<table class='admin'>
			<thead>
			<tr class='title' role='row'><td style='width: 20px'><span class='xsmall'>".DL_ID."</span></td>");

		// Building the table
		foreach ($this->_dfs->get() as $datafield) {
			if($datafield->showInTable == true)
				pOut("<td style='width: ".$datafield->width."'>".$datafield->surface."</td>");
		}

		// Links
		if($this->_linked != null)
			pOut("<td>".DA_TABLE_LINKS."</td>");

		pOut("<td>".ACTIONS."</td>
			</tr></thead><tbody>");

		foreach($this->_data as $data){
			pOut("<tr class='item_".$data['id']."'><td><span class='xsmall'>".($data['id'] == 0 ? "<em><strong>".DA_DEFAULT."</em></strong>" : $data['id']) ."</span></td>");

			// Go through the data fields
			foreach($this->_dfs->get() as $datafield){
				if($datafield->showInTable == true)
					pOut("<td style='width: ".$datafield->width."'>".$datafield->parse($data[$datafield->name])."</td>");
			}

			// The links stuff
			// Links
			if($this->_linked != null)
				pOut("<td>".(new pAction('link-table', 'linkTable', 'link', 'actionbutton', null, null, $this->_linked->_section, $this->_linked->_app))->render($data['id'], $this->_section)."</td>");

			// The important actions and such
			pOut("<td class='actions'>");
			foreach($this->_actions->get() as $action){
				if(!($action->name == 'remove' AND $data['id'] == 0))
					pOut($action->render($data['id']));
			}
			pOut("</td>");

			pOut("</tr>");
		}

		pOut("</tbody></table>
		</thead>");

		pOut("
			<div class='btButtonBar'>".$pages.$this->_actionbar->output."</div>
		</div>");
		}
}