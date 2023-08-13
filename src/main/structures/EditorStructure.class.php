<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: EditorStructure.class.php

class pEditorStructure extends pStructure{
		
	public function render(){

		if(isset(pRegister::arg()['is:result'], pRegister::freshSession()['searchQuery']))
			$searchBox->setValue(pRegister::freshSession()['searchQuery']);
	

		if(!isset(pRegister::arg()['ajax']))
			p::Out("<div class='pEntry'>");

		if(!isset(pRegister::arg()['ajax']) && (isset(pRegister::arg()['action']) && pRegister::arg()['action'] == 'edit')){
			if(isset(pRegister::arg()['section']))
				$section = pRegister::arg()['section'];
			else
				$section = 'lemma';

			pTemplate::setTabbed();

			p::Out((new pTabBar('Editor', 'lead-pencil', false, 'titles wordsearch nomargin above'))
				->addLink('view', LEMMA_VIEW_SHORT, p::Url("?entry/".$this->_prototype[$this->_section]['section_key'].'/'.pRegister::arg()['id'].(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), false)
				->addLink('edit', LEMMA_EDIT_SHORT, p::Url('?editor/'.$this->_section.'/edit/'.(is_numeric(pRegister::arg()['id']) ?  pRegister::arg()['id'] : p::HashId(pRegister::arg()['id'], true)[0]).(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), true)
				->addLink('discuss', LEMMA_DISCUSS_SHORT, p::Url('?entry/'.$this->_prototype[$this->_section]['section_key'].'/'.(is_numeric(pRegister::arg()['id']) ?  pRegister::arg()['id'] : p::HashId(pRegister::arg()['id'], true)[0]).'/discuss'.(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), false)
			);
		}
		elseif(!isset(pRegister::arg()['ajax'])){
			// Tab bar for new
			
			if((isset(pRegister::arg()['action']) && pRegister::arg()['action'] == 'new'))
				p::Out((new pTabBar('Editor', 'lead-pencil', true, 'titles wordsearch nomargin above'))->addLink('n', 'New Lemma', null, true)."<br />");
		}



		// Let's handle the action by the object
		if(isset(pRegister::arg()['action'])){
			if(isset(pRegister::arg()['id']))
				if(!$this->_parser->runData(pRegister::arg()['id'])){
					p::Out("<br />".pTemplate::NoticeBox('fa-info-circle fa-12', ENTRY_NOT_FOUND, 'danger-notice'));
					goto skipError;
				}

			$this->_parser->passOnAction(pRegister::arg()['action']);
		}
		else{
			if(isset(pRegister::arg()['id']))
				$this->_parser->runData(pRegister::arg()['id']);
			else
				$this->_parser->runData();

			$this->_parser->render();
		}

		skipError:

		// Tooltipster time!
		p::Out("</div><script type='text/javascript'>

			$('.ttip').tooltipster({animation: 'grow', animationDuration: 100,  distance: 0, contentAsHTML: true, interactive: true, side:'left'});

			$('.ttip_actions').tooltipster({animation: 'grow', animationDuration: 150,  distance: 0, contentAsHTML: true, interactive: true, side: 'bottom', trigger: 'click'});

			$('div.d_admin_header_dropdown span').click(function(){
				$('div.d_admin_header_dropdown').toggleClass('clicked');
			});

			$('.ttip_header').tooltipster({ animation: 'grow', animationDuration: 100, distance: 0, contentAsHTML: true, interactive: true, side: 'bottom', trigger: 'click', functionAfter: function(){
					$('div.d_admin_header_dropdown').removeClass('clicked');
			}});

			</script>");

	}

}