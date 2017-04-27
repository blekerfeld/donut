<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: TableObject.cset.php

class pTableObject extends pObject{
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

			$real_id = $data['id'];
			$showID = $data['id'];

			if(isset($this->_activeSection['id_as_hash']) AND $this->_activeSection['id_as_hash'] AND isset($this->_activeSection['hash_app'])){
				$showID = "<a class='medium tooltip' href='".pUrl('?'.$this->_activeSection['hash_app'].'/'.pHashId($data['id']))."'>".(new pIcon('fa-bookmark-o', 12))." ".pHashId($data['id'])."</a>";
				$data['id'] = pHashId($data['id']);
			}


			pOut("<tr class='item_".$real_id ."'><td><span class='xsmall'>".($real_id == 0 ? "<em><strong>".DA_DEFAULT."</em></strong>" : $showID) ."</span></td>");

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
				foreach($this->_linked as $key => $link){
					if(pUser::checkPermission($this->itemPermission(((isset($link->structure[$this->_section]['outgoing_links'][$key]['double_parent'])) ? $this->_section : $key), pStructure::$permission))){
						$action = new pAction('link-table', "&#x205F;".$link->structure[$this->_section]['outgoing_links'][$key]['surface'], $link->structure[$this->_section]['outgoing_links'][$key]['icon'], 'small table-link', null, null, ((isset($link->structure[$this->_section]['outgoing_links'][$key]['double_parent'])) ? $this->_section : $key), $link->_app);

						// Counting the links
						$dataCount = new pDataObject($link->structure[$this->_section]['outgoing_links'][$key]['table']);

						$dataCount->setCondition("WHERE ((".$link->structure[$this->_section]['outgoing_links'][$key]['field']." = '".$real_id."'" . ((isset($link->structure[$this->_section]['outgoing_links'][$key]['double_parent'])) ? ") OR (". $link->structure[$this->_section]['outgoing_links'][$key]['double_parent'] . " = '".$real_id."'" : '')."))");




							$counter = "<span class='counter'>".$dataCount->countAll($link->structure[$this->_section]['outgoing_links'][$key]['field'] . " = ".$real_id)."</span>";

						$action->_surface = $counter.$action->_surface;

						pOut("<span class='tooltip' title='".(new pIcon($link->structure[$this->_section]['outgoing_links'][$key]['icon'], 12)).$link->structure[$this->_section]['outgoing_links'][$key]['surface']."'>".$action->render($data['id'], ((isset($link->structure[$this->_section]['outgoing_links'][$key]['double_parent'])) ? $key : $this->_section)).'</span>');
					}
				}
				pOut("</td>");
			}

			// The important actions and such
			pOut("<td style='text-align: center'><a href='javascript:void();' class='btAction no-float ttip_actions' data-tooltip-content='#dropdown_".$data['id']."'>".(new pIcon('fa-chevron-down', 10))."</a>");

			pOut("		<div class='hide'><div id='dropdown_".$data['id']."' class=''>");



			foreach($this->_actions->get() as $action){
				if(!($action->name == 'remove' AND $real_id == 0))
					pOut($action->render($data['id']));
			}
			pOut("</div></div>");
			
			pOut("</td>");

			pOut("</tr>");
		}

		if($records == 0)
			pOut("<tr><td colspan=".$col_count.">".DA_NO_RECORDS."</td>");

		pOut("</tbody></table><script>$('.tooltip').tooltipster({animation: 'grow', distance: 0, contentAsHTML: true});</script>");

		pOut("</div>
			<div class='btButtonBar'>".$pages.$this->_actionbar->output."</div>
		</div>");
		}
}