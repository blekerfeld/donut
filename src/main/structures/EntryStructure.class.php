<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: admin.structure.class.php

class pEntryStructure extends pStructure{

	public $_tabs;
	
	public function compile(){
		// If the user requests a section and if it extist
		if(isset(pRegister::arg()['section']) AND array_key_exists(pRegister::arg()['section'], $this->_prototype))
			$this->_section = pRegister::arg()['section'];
		else{

			// Don't show an error if we are forced to have the search load a search action
			if(!(isset(pRegister::arg()['query'], pRegister::arg()['dictionary'])))
				$this->_error = pTemplate::NoticeBox('fa-info-circle fa-12', DA_SECTION_ERROR, 'danger-notice');

			$this->_section = $this->_default_section;
		}


		$this->_parser = new pParser($this);
		;

		$this->_parser->compile();

		pTemplate::setTitle($this->_page_title);
	}


	public function render(){
		pTemplate::setTabbed();
		$specialEntries = array('stats', 'random', 'year');

		// Starting with the wrapper

		if(p::NoAjax())
			p::Out("<div class='pEntry ".(($this->_error != '' OR $this->_error != null) ? 'hasErrors' : '')."'><div class='home-margin'>");


		// Let's create a tab bar for this app
		$this->_tabs = (new pTabBar(MMENU_DICTIONARY, 'fa-book', true, 'titles wordsearch nomargin above'));

		// If logged, show tabs
		if(pUser::noGuest() AND isset($this->_prototype[$this->_section]['edit_url'], pRegister::arg()['id']) AND !in_array($this->_section, $specialEntries) AND p::NoAjax())

			$this->_tabs
				->addLink('view', LEMMA_VIEW_SHORT, p::Url("?entry/".$this->_prototype[$this->_section]['section_key'].'/'.pRegister::arg()['id'].(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), (!isset(pRegister::arg()['action'])))
				->addLink('edit', LEMMA_EDIT_SHORT, p::Url($this->_prototype[$this->_section]['edit_url'].(is_numeric(pRegister::arg()['id']) ?  pRegister::arg()['id'] : p::HashId(pRegister::arg()['id'], true)[0]).(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), false)
				->addLink('discuss', LEMMA_DISCUSS_SHORT, p::Url('?entry/'.$this->_prototype[$this->_section]['section_key'].'/'.(is_numeric(pRegister::arg()['id']) ?  pRegister::arg()['id'] : p::HashId(pRegister::arg()['id'], true)[0]).'/discuss'.(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), ((isset(pRegister::arg()['action']) AND pRegister::arg()['action'] == 'discuss'))
			);


		if(!pUser::noGuest() AND isset(pRegister::arg()['id']) AND !isset(pRegister::arg()['ajaxLoad']) AND !isset(pRegister::arg()['ajax']))
			$this->_tabs
				->addLink('view', LEMMA_VIEW_SHORT, p::Url("?entry/".$this->_prototype[$this->_section]['section_key'].'/'.pRegister::arg()['id'].(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), (!isset(pRegister::arg()['action'])));


		pRegister::tabs($this->_tabs);

		// If there is an offset, we need to define that
		if(isset(pRegister::arg()['offset']))
			$this->_parser->setOffset(pRegister::arg()['offset']);


		if((p::NoAjax()))
			p::Out("<div class='pEntry-inner'>");

		ajaxSkipOutput:

		if(isset(pRegister::arg()['id']) AND !in_array($this->_section, $specialEntries)){
			if(!($this->_parser->runData(is_numeric(pRegister::arg()['id']) ?  pRegister::arg()['id'] : p::HashId(pRegister::arg()['id'], true)[0]))){
				if(!(isset(pRegister::arg()['query'], pRegister::arg()['dictionary'])))
					p::Out(pTemplate::NoticeBox('fa-info-circle fa-12', ENTRY_NOT_FOUND, 'danger-notice'));
				goto SkipError;
			}
		}
		elseif(!in_array($this->_section, $specialEntries))
			$this->_parser->runData();

		// Let's handle the action by the object
		if(isset(pRegister::arg()['action']) AND @pRegister::arg()['section'] != 'search')
			$this->_parser->passOnAction(pRegister::arg()['action']);
		elseif(isset(pRegister::arg()['id']))
			$this->_parser->render();
		elseif(in_array($this->_section, $specialEntries))
			$this->_parser->render();

		
		if(p::NoAjax())
			p::Out("</div>");

		SkipError:

		// Ending content
		if(p::NoAjax())
			p::Out("</div></div>");

		// Tooltipster time!
		p::Out("<script type='text/javascript'>

			$('.ttip').tooltipster({animation: 'grow'});

			</script>");

	}
}