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
			<div class='btButtonBar up'>".$pages.$this->_actionbar->output."</div><div class='content'>
			");

		$records = 0;
		$col_count = 2;

		pOut("<table class='admin'>
			<thead>
			<tr class='title' role='row'><td style='width: 50px'><span class='xsmall'>".DL_ID."</span></td>");

		// Building the table
		foreach ($this->_dfs->get() as $datafield) {
			if($datafield->showInTable == true){
				pOut("<td style='width: ".$datafield->width."'>".$datafield->surface."</td>");
				$col_count++;
			}
		}

		// Links
		if($this->_linked != null){
			pOut("<td style='width: auto;'><i class='fa fa-link'></i> ".DA_TABLE_LINKS."</td>");
			$col_count++;
		}

		pOut("<td></td>
			</tr></thead><tbody>");

		foreach($this->_data as $data){
			pOut("<tr class='item_".$data['id']."'><td><span class='xsmall'>".($data['id'] == 0 ? "<em><strong>".DA_DEFAULT."</em></strong>" : $data['id']) ."</span></td>");

			// Go through the data fields
			foreach($this->_dfs->get() as $datafield){
				if($datafield->showInTable == true){
					pOut("<td style='width: ".$datafield->width."'>".$datafield->parse($data[$datafield->name])."</td>");
					$records++;
				}
			}

			// The links stuff
			// Links
			if($this->_linked != null){
				pOut("<td>");
				foreach($this->_linked->get() as $link){
					$action = new pAction('link-table', "&#x205F;".$link->structure[$this->_section]['outgoing_links'][$link->_data['section_key']]['surface'], $link->structure[$this->_section]['outgoing_links'][$link->_data['section_key']]['icon']." fa-10", 'small table-link', null, null, $link->_section, $link->_app);

						// Counting the links
						$dataCount = new pDataObject($link->structure[$this->_section]['outgoing_links'][$link->_data['section_key']]['table'], new pSet);
						$counter = "<span class='counter'>".$dataCount->countAll($link->structure[$this->_section]['outgoing_links'][$link->_data['section_key']]['field'] . " = ".$data['id'])."</span>";

						$action->_surface = $counter.$action->_surface;

						pOut("<span class='tooltip' title='<i class=\"".$link->structure[$this->_section]['outgoing_links'][$link->_data['section_key']]['icon']." fa-10\"></i> ".$link->structure[$this->_section]['outgoing_links'][$link->_data['section_key']]['surface']."'>".$action->render($data['id'], $this->_section).'</span><div class="hide">
							<div id="tooltip_test" class="hide"><i class="fa fa-globe"></i> test</div></div>');
				}
				pOut("</td>");
			}

			// The important actions and such
			pOut("<td style='text-align: center'><a href='javascript:void();' class='btAction no-float ttip_actions' title='");

			foreach($this->_actions->get() as $action){
				if(!($action->name == 'remove' AND $data['id'] == 0))
					pOut(str_replace("'", '"', $action->render($data['id'])));
			}

			pOut("'><i class='fa fa-ellipsis-v fa-15' style='width: 13px;text-align: center;'></i></a>");
			
			pOut("</td>");

			pOut("</tr>");
		}

		if($records == 0)
			pOut("<tr><td colspan=".$col_count.">".DA_NO_RECORDS."</td>");

		pOut("</tbody></table><script>$('.tooltip').tooltipster({theme: 'tooltipster-noir', animation: 'grow', distance: 0, contentAsHTML: true});</script>");

		pOut("</div>
			<div class='btButtonBar'>".$pages.$this->_actionbar->output."</div>
		</div>");
		}
}