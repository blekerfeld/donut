<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.cset.php

class pAdminStructure extends pStructure{
	

	private $_error = null;

	public function compile(){

		global $donut;

		// If the user requests a section and if it extist
		if(isset(pAdress::arg()['section']) AND array_key_exists(pAdress::arg()['section'], $this->_structure))
			$this->_section = pAdress::arg()['section'];
		else{

			$this->_error = pNoticeBox('fa-info-circle fa-12', DA_SECTION_ERROR, 'danger-notice');

			$this->_section = $this->_default_section;
		}


		$this->_parser = new pParser($this->_structure, $this->_structure[$this->_section], $this->_app, $this->_permission);
		;

		$this->_parser->compile();

		$donut['page']['title'] = $this->_page_title;
	}


	public function prepareMenu(){
		// We don't accept double items
		foreach($this->_structure as $item)
			if(isset($item['menu']) && pUser::checkPermission($this->itemPermission($item['section_key'])))
				$this->_menu[$item['menu']]['items'][] = $item;
	}

	private function checkActiveMain($name){
		if(isset($this->_menu[$name]['items']))
			foreach($this->_menu[$name]['items'] as $item)
				if(isset(pAdress::arg()['section']) && pAdress::arg()['section'] == $item['section_key'])
					return true;
	}

	private function checkActiveSub($name){

		if(isset(pAdress::arg()['section']) && pAdress::arg()['section'] == $name)
			return true;

	}

	public function renderMenu(){

		// Starting the menu
		$output = "<div class='d_admin_menu'>
				<div class='stack'>";

		$items = 0;

		foreach($this->_menu as $key => $main){
			// Permission check
			if(pUser::checkPermission($this->itemPermission($main['section'])) OR isset($main['items'])){
				$output .= "<a href='".(isset($main['section']) ? pUrl("?".$this->_app."/".$main['section']) : '')."' class='".(($this->checkActiveMain($key) OR (isset(pAdress::arg()['section'], $main['section']) AND pAdress::arg()['section'] == $main['section'])) ? 'active' : '')." ttip' title='
					<strong>".htmlspecialchars($main['surface'])."</strong>";

				if(isset($main['items']))
					foreach($main['items'] as $item){
						$output .= "<a href=\"".pUrl("?".$this->_app."/".$item['section_key'])."\" class=\"ttip-sub ".($this->checkActiveSub($item['section_key']) ? 'active' : '')."\">".(new pIcon($item['icon'], 12))." ". htmlspecialchars($item['surface'])."</a>";
					}

				$items++;

				$output .= "'>".(new pIcon($main['icon'], 24))."</a>";
		}

		}

		$output .= "</div></div>";
		
		if($items == 0)
			return false;

		return pOut($output);

	}

	private function headerDropDown(){

		$output = "<div class=' header dictionary home wiki' ><div class='title_header d_admin_header_dropdown' ><span class='ttip_header' data-tooltip-content='#dropdown_header'>".(new pIcon($this->_meta['icon'], 24))." ".$this->_meta['title']."</span> ".(new pIcon('fa-chevron-down', 12))." </div></div>

			<div class='hide'><div id='dropdown_header' class=''>
			<a href='".pUrl("?".$this->_app)."' class='ttip-sub active'>".(new pIcon($this->_meta['icon'], 10))." ".$this->_meta['title']."</a>";

		foreach($this->_meta['other_apps'] as $app)
			$output .= "<a href='".pUrl("?".$app['app'])."' class='ttip-sub'>".(new pIcon($app['icon'], 10))." ".$app['surface']."</a>";

		$output .= "	</div></div>";

		return $output;
	}

	public function render(){

		// The asynchronous j.a.x. gets to skip a bit 
		if(isset(pAdress::arg()['ajax']))
			goto ajaxSkipOutput;

		// Preparing the menu
		$this->prepareMenu();

		// Starting with the wrapper
		pOut("<div class='d_admin_wrap'><div class='d_admin'>");

		// Header time
		pOut($this->headerDropDown());

		// Showing an error if there is one set.
		if($this->_error != null)
			pOut("<div class='btCard minimal admin'>".$this->_error."</div>");

		// If there is an offset, we need to define that
		if(isset(pAdress::arg()['offset']))
			$this->_parser->setOffset(pAdress::arg()['offset']);

		ajaxSkipOutput:
		// Let's look for an action, that can not be an id! :D
		if(isset(pAdress::arg()['action'])){

			if(isset(pAdress::arg()['id']) AND !in_array(pAdress::arg()['action'], array('link-table')))
				$this->_parser->runData((is_numeric(pAdress::arg()['id']) ?  pAdress::arg()['id'] : pHashId(pAdress::arg()['id'], true)[0]));

			$this->_parser->action(pAdress::arg()['action'], (boolean)isset(pAdress::arg()['ajax']), ((isset(pAdress::arg()['linked']) ? pAdress::arg()['linked'] : null)));
			if(isset(pAdress::arg()['ajax']))
				return true;
		}
		else{
			if(isset(pAdress::arg()['id']))
				$this->_parser->runData(is_numeric(pAdress::arg()['id']) ?  pAdress::arg()['id'] : pHashId(pAdress::arg()['id'], true)[0]);
			else
				$this->_parser->runData();

			$this->_parser->render();
			if(isset(pAdress::arg()['ajax']))
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