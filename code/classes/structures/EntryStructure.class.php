<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      admin.structure.class.php

class pEntryStructure extends pStructure{
	
	public function compile(){
		// If the user requests a section and if it extist
		if(isset(pRegister::arg()['section']) AND array_key_exists(pRegister::arg()['section'], $this->_structure))
			$this->_section = pRegister::arg()['section'];
		else{

			// Don't show an error if we are forced to have the search load a search action
			if(!(isset(pRegister::arg()['query'], pRegister::arg()['dictionary'])))
				$this->_error = pMainTemplate::NoticeBox('fa-info-circle fa-12', DA_SECTION_ERROR, 'danger-notice');

			$this->_section = $this->_default_section;
		}


		$this->_parser = new pParser($this);
		;

		$this->_parser->compile();

		pMainTemplate::setTitle($this->_page_title);
	}


	public function render(){

		$searchBox = new pSearchBox;
		$searchBox->enablePentry();


		if(isset(pRegister::arg()['is:result'], pRegister::freshSession()['searchQuery']))
			$searchBox->setValue(pRegister::freshSession()['searchQuery']);
	
		pMainTemplate::throwOutsidePage($searchBox);

		// Starting with the wrapper

		if(!isset(pRegister::arg()['ajax']) AND !isset(pRegister::arg()['ajaxLoad']))
			p::Out("<div class='pEntry ".(($this->_error != '' OR $this->_error != null) ? 'hasErrors' : '')."'><div class='home-margin'>");

		// If logged, show tabs
		if(pUser::noGuest() AND isset($this->_structure[$this->_section]['edit_url'], pRegister::arg()['id']) AND $this->_section != 'stats' AND !isset(pRegister::arg()['ajax']) AND !isset(pRegister::arg()['ajaxLoad']))

			p::Out((new pTabBar(MMENU_DICTIONARY, 'fa-book', true, 'titles pEntry-fix-50'))->addSearch()->addHome()
				->addLink('view', LEMMA_VIEW_SHORT, p::Url("?entry/".$this->_structure[$this->_section]['section_key'].'/'.pRegister::arg()['id'].(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), (!isset(pRegister::arg()['action'])))
				->addLink('edit', LEMMA_EDIT_SHORT, p::Url($this->_structure[$this->_section]['edit_url'].(is_numeric(pRegister::arg()['id']) ?  pRegister::arg()['id'] : p::HashId(pRegister::arg()['id'], true)[0]).(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), false)
				->addLink('discuss', LEMMA_DISCUSS_SHORT, p::Url('?entry/'.$this->_structure[$this->_section]['section_key'].'/'.(is_numeric(pRegister::arg()['id']) ?  pRegister::arg()['id'] : p::HashId(pRegister::arg()['id'], true)[0]).'/discuss'.(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), ((isset(pRegister::arg()['action']) AND pRegister::arg()['action'] == 'discuss')))
			);


		if(!pUser::noGuest() AND isset(pRegister::arg()['id']) AND !isset(pRegister::arg()['ajaxLoad']) AND !isset(pRegister::arg()['ajax']))
			p::Out((new pTabBar(MMENU_DICTIONARY, 'fa-book', true, 'titles pEntry-fix-50'))->addSearch()
				->addLink('view', LEMMA_VIEW_SHORT, p::Url("?entry/".$this->_structure[$this->_section]['section_key'].'/'.pRegister::arg()['id'].(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), (!isset(pRegister::arg()['action']))));

		// If there is an offset, we need to define that
		if(isset(pRegister::arg()['offset']))
			$this->_parser->setOffset(pRegister::arg()['offset']);


		if(!isset(pRegister::arg()['ajax']) AND !isset(pRegister::arg()['ajaxLoad']))
			p::Out("<div class='pEntry-inner'>");

		ajaxSkipOutput:

		if(isset(pRegister::arg()['id']) AND $this->_section != 'stats'){
			if(!($this->_parser->runData(is_numeric(pRegister::arg()['id']) ?  pRegister::arg()['id'] : p::HashId(pRegister::arg()['id'], true)[0]))){
				if(!(isset(pRegister::arg()['query'], pRegister::arg()['dictionary'])))
					p::Out(pMainTemplate::NoticeBox('fa-info-circle fa-12', ENTRY_NOT_FOUND, 'danger-notice'));
				goto SkipError;
			}
		}
		elseif($this->_section != 'stats')
			$this->_parser->runData();

		// Let's handle the action by the object
		if(isset(pRegister::arg()['action']) AND @pRegister::arg()['section'] != 'search')
			$this->_parser->passOnAction(pRegister::arg()['action']);
		elseif(isset(pRegister::arg()['id']))
			$this->_parser->render();
		elseif(in_array($this->_section, array('stats', 'random')))
			$this->_parser->render();

		
		if(!isset(pRegister::arg()['ajax']) AND !isset(pRegister::arg()['ajaxLoad']))
			p::Out("</div>");

		SkipError:

		// Ending content
		if(!isset(pRegister::arg()['ajax']) AND !isset(pRegister::arg()['ajaxLoad']))
			p::Out("</div></div>");

		// Tooltipster time!
		p::Out("<script type='text/javascript'>

			$('.ttip').tooltipster({animation: 'grow'});

			</script>");

	}
}