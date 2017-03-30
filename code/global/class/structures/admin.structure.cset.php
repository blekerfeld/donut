<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: admin.structure.cset.php

class pAdminStructure extends pStructure{
	

	private $_error = null;

	public function compile(){

		global $donut;

		if(isset($_REQUEST['section']) and array_key_exists($_REQUEST['section'], $this->_structure))
			$this->_section = $_REQUEST['section'];
		else{

			$this->_error = pNoticeBox('fa-info-circle fa-12', DA_SECTION_ERROR, 'danger-notice');

			$this->_section = $this->_default_section;
		}

		$this->_adminParser = new pAdminParser($this->_structure, $this->_structure[$this->_section]);
		;

		$this->_adminParser->compile();

		$donut['page']['title'] = $this->_page_title;
	}


	public function prepareMenu(){
		// We don't accept double items
		foreach($this->_structure as $item)
			if(isset($item['menu']))
				$this->_menu[$item['menu']]['items'][] = $item;
	}

	private function checkActiveMain($name){
		if(isset($this->_menu[$name]['items']))
			foreach($this->_menu[$name]['items'] as $item)
				if(isset($_REQUEST['section']) && $_REQUEST['section'] == $item['section_key'])
					return true;
	}

	private function checkActiveSub($name){

		if(isset($_REQUEST['section']) && $_REQUEST['section'] == $name)
			return true;

	}

	public function renderMenu(){

		// Starting the menu
		pOut("<div class='d_admin_menu'>
				<div class='stack'>");

		foreach($this->_menu as $key => $main){

			pOut("<a href='".(isset($main['section']) ? pUrl("?".$this->_app."&section=".$main['section']) : '')."' class='".(($this->checkActiveMain($key) OR (isset($_REQUEST['section'], $main['section']) AND $_REQUEST['section'] == $main['section'])) ? 'active' : '')." ttip' title='
				<strong>".htmlspecialchars($main['surface'])."</strong>");


			if(isset($main['items']))
				foreach($main['items'] as $item){
					pOut("<a href=\"".pUrl("?".$this->_app."&section=".$item['section_key'])."\" class=\"ttip-sub ".($this->checkActiveSub($item['section_key']) ? 'active' : '')."\">".(new pIcon($item['icon'], 12))." ". htmlspecialchars($item['surface'])."</a>");
				}

			pOut("'>".(new pIcon($main['icon'], 24))."</a>");

		}

		pOut("</div></div>");
	

	}

	private function headerDropDown(){

		$output = "<div class=' header dictionary home wiki' ><div class='title_header d_admin_header_dropdown' ><span class='ttip_header' data-tooltip-content='#dropdown_header'>".(new pIcon($this->_meta['icon'], 24))->circle()." ".$this->_meta['title']."</span> ".(new pIcon('fa-chevron-down', 12))." </div></div>

			<div class='hide'><div id='dropdown_header' class=''>
			<a href='".pUrl("?".$this->_app)."' class='ttip-sub active'>".(new pIcon($this->_meta['icon'], 10))." ".$this->_meta['title']."</a>";

		foreach($this->_meta['other_apps'] as $app)
			$output .= "<a href='".pUrl("?".$app['app'])."' class='ttip-sub'>".(new pIcon($app['icon'], 10))." ".$app['surface']."</a>";

		$output .= "	</div></div>";

		return $output;
	}

	public function render(){

		if(isset($_REQUEST['ajax']))
			goto ajax;

		// Preparing the menu
		$this->prepareMenu();

		// Starting with the wrapper
		pOut("<div class='d_admin_wrap'><div class='d_admin'>");

		// Header time
		pOut($this->headerDropDown());

		if($this->_error != null)
			pOut("<div class='btCard minimal admin'>".$this->_error."</div>");

		// If there is an offset, we need to define that
		if(isset($_REQUEST['offset']))
			$this->_adminParser->setOffset($_REQUEST['offset']);

		ajax:
		if(isset($_REQUEST['action'])){

			if(isset($_REQUEST['id']) AND !in_array($_REQUEST['action'], array('link-table')))
				$this->_adminParser->runData((is_numeric($_REQUEST['id']) ?  $_REQUEST['id'] : pHashId($_REQUEST['id'], true)[0]));

			$this->_adminParser->action($_REQUEST['action'], (boolean)isset($_REQUEST['ajax']), ((isset($_REQUEST['linked']) ? $_REQUEST['linked'] : null)));
			if(isset($_REQUEST['ajax']))
				return true;
		}
		else{

			$this->_adminParser->runData();
			$this->_adminParser->render();
			if(isset($_REQUEST['ajax']))
				return true;
		}

		// Ending content
		pOut("</div>");

		// Time for the menu
		$this->renderMenu();


		// Tooltipster time!
		pOut("<script type='text/javascript'>

			$('.ttip').tooltipster({animation: 'grow', animationDuration: 100,  distance: 0, contentAsHTML: true, interactive: true, side:'left'});

			$('.ttip_actions').tooltipster({animation: 'grow', animationDuration: 150,  distance: 0, contentAsHTML: true, interactive: true, side: 'bottom', trigger: 'click'});

			$('div.d_admin_header_dropdown span').click(function(){
				$('div.d_admin_header_dropdown').toggleClass('clicked');
			});

			$('.ttip_header').tooltipster({ animation: 'grow', animationDuration: 100, distance: 0, contentAsHTML: true, interactive: true, side: 'bottom', trigger: 'click', functionAfter: function(){
					$('div.d_admin_header_dropdown').removeClass('clicked');
			}});

			</script>");

		// Ending overall 
		pOut("</div>");

	}

}