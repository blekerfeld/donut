<?php

class pLinkTableObject extends pAdminObject{

	private $_passed_data, $_show, $_matchOn;

	public function passData($data, $show, $matchOn){
		$this->_passed_data = $data;
		$this->_show = $show;
		$this->_matchOn = $matchOn;
	}

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

		pOut("<table class='admin' style='width:50%;float:left;'>
			<thead>
			<tr class='title' role='row'><td style='width: 100px'><span class='xsmall'>".DA_TABLE_LINKS_CHILD_ID."</span></td>");

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

			foreach($this->_passed_data as $key => $item){
			      if ( $item['id'] == $data[$this->_matchOn] ){
			      	pOut("<tr class='item_".$data['id']."'><td><span class='xsmall'>".($item['id'] == 0 ? "<em><strong>".DA_DEFAULT."</em></strong>" : $item['id']) ."</span></td>");
				      foreach($this->_dfs->get() as $datafield){
						if($datafield->showInTable == true)
							pOut("<td style='width: ".$datafield->width."'>".$datafield->parse($item['name'])."</td>");
				   		}
			   		}
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

		pOut("</tbody></table><br id='cl' />
		</thead>");

		pOut("
			<div class='btButtonBar'>".$pages.$this->_actionbar->output."</div>
		</div>");
		}
	}
