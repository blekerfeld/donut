<?php

class pLinkTableObject extends pAdminObject{

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

		pOut("<div class='btCard admin link-table'>
			<div class='btTitle'>
				".(new pIcon($this->_icon, 12))." ".$this->_surface."
			</div>
			<div class='btButtonBar up'><a class='btAction wikiEdit' href='".pUrl("?".$this->_app."&section=".$this->_guests->_data['section_key'])."'>".(new pIcon('fa-arrow-left', 12))." ".BACK."</a>".$pages.$this->_actionbar->output."</div><div class='content'>
			");


		pOut("<div class='btSource'><span class='btLanguage'>".DA_TABLE_LINKS_PARENT.": </span><br />
			<span class='btNative'>".$this->_guests->_adminObject->data()[0][$this->_show_parent]."</span></div>");

		pOut("<div class='btSource'><span class='btLanguage'>".DA_TABLE_LINKS_CHILDREN.": </span></div>");

		pOut("<table class='admin' style='width:50%;float:left;'>
			<thead>
			<tr class='title' role='row'><td style='width: 10%;'><span class='xsmall'>".DA_TABLE_LINKS_CHILD_ID."</span></td>");

		// Building the table
		foreach ($this->_dfs->get() as $datafield) {
			if($datafield->showInTable == true){
				pOut("<td style='width: ".$datafield->width."'>".$datafield->surface."</td>");
				$col_count++;
			}
		}

		// Links
		if($this->_linked != null)
			pOut("<td>".DA_TABLE_LINKS."</td>");

		pOut("<td>".ACTIONS."</td>
			</tr></thead><tbody>");


		foreach($this->_data as $data){

			foreach($this->_passed_data as $key => $item){
			      if ( $item['id'] == $data[$this->_matchOn] ){
			      	pOut("<tr class='item_".$data['id']."'><td style='width: auto;max-width: 5px;'><span class='xsmall'>".($item['id'] == 0 ? "<em><strong>".DA_DEFAULT."</em></strong>" : $item['id']) ."</span></td>");
				      foreach($this->_dfs->get() as $key_d => $datafield){
						if($datafield->showInTable == true)
							if($datafield->name != $this->_guests->_data['outgoing_links'][$this->_section]['field'])
								pOut("<td style='width: ".$datafield->width."'>".$datafield->parse($data[$datafield->name])."</td>");
							else
								pOut("<td style='width: ".$datafield->width."'>".$datafield->parse($item[$this->_show_child])."</td>");
							$records++;
				   		}
			   		}
			}

			// The important actions and such
			pOut("<td class='actions'>");
			foreach($this->_actions->get() as $action){
				if(!($action->name == 'remove' AND $data['id'] == 0))
					pOut($action->render($data['id'], $this->_link));
			}
			pOut("</td>");

			pOut("</tr>");
		}

		if($records == 0)
			pOut("<tr><td colspan=".$col_count.">".DA_NO_RECORDS."</td>");

		pOut("</tbody></table><br id='cl' />
		</thead>");

		pOut("</div>
			<div class='btButtonBar'>
			<a class='btAction wikiEdit' href='".pUrl("?".$this->_app."&section=".$this->_guests->_data['section_key'])."'><i class='fa fa-12 fa-arrow-left' ></i> ".BACK."</a>
			".$pages.$this->_actionbar->output."</div>
		</div>");
		}
	}
