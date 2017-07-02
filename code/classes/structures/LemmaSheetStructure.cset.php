<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.cset.php

class pLemmasheetStructure extends pStructure{
	

	private $_error = null;

	public function compile(){

		// If the user requests a section and if it extist
		if(isset(pRegister::arg()['section']) AND array_key_exists(pRegister::arg()['section'], $this->_structure))
			$this->_section = pRegister::arg()['section'];
		else{

			$this->_error = pMainTemplate::NoticeBox('fa-info-circle fa-12', DA_SECTION_ERROR, 'danger-notice');

			$this->_section = $this->_default_section;
		}


		$this->_parser = new pParser($this->_structure, $this->_structure[$this->_section], $this->_app, $this->_permission);
		;

		$this->_parser->compile();

		pMainTemplate::setTitle($this->_page_title);
	}


	public function render(){

		$searchBox = new pSearchBox;

		if(isset(pRegister::arg()['is:result'], pRegister::session()['searchQuery']))
			$searchBox->setValue(pRegister::session()['searchQuery']);

		if(!isset(pRegister::arg()['ajax']))
			pMainTemplate::throwOutsidePage($searchBox);

		// If there is an offset, we need to define that
		if(isset(pRegister::arg()['offset']))
			$this->_parser->setOffset(pRegister::arg()['offset']);

		if(!isset(pRegister::arg()['ajax']) && (isset(pRegister::arg()['action']) && pRegister::arg()['action'] == 'edit')){
			if(isset(pRegister::arg()['section']))
				$section = pRegister::arg()['section'];
			else
				$section = 'lemma';
			p::Out("<div class='home-margin pEntry'><div class='card-tabs-bar titles'>
					".($section == 'lemma' ? "<a class='ssignore float-right' href='".p::Url('?editor/'.(isset(pRegister::arg()['section']) ? pRegister::arg()['section'] : 'lemma').'/new')."'>".LEMMA_NEW."</a>" : '')."
					<a class='ssignore' href='".p::Url('?entry/'.(isset(pRegister::arg()['section']) ? pRegister::arg()['section'].'/' : '').pRegister::arg()['id'].(isset(pRegister::arg()['is:result']) ? '/is:result' : ''))."'>".LEMMA_VIEW_SHORT."</a>
					<a class='active ssignore' href='javascript:void();'>".LEMMA_EDIT_SHORT."</a>
					<a class='ssignore' href='".p::Url('?entry/'.(isset(pRegister::arg()['section']) ? pRegister::arg()['section'].'/' : '').pRegister::arg()['id'])."/discuss".(isset(pRegister::arg()['is:result']) ? '/is:result' : '')."'>".LEMMA_DISCUSS_SHORT."</a>
				</div>");
		}
		elseif(!isset(pRegister::arg()['ajax']))
			p::Out("<div class='home-margin pEntry'>");



		// Let's handle the action by the object
		if(isset(pRegister::arg()['action'])){
			if(isset(pRegister::arg()['id']))
				if(!$this->_parser->runData(pRegister::arg()['id'])){
					p::Out("<br />".pMainTemplate::NoticeBox('fa-info-circle fa-12', ENTRY_NOT_FOUND, 'danger-notice'));
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
		p::Out("<script type='text/javascript'>

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