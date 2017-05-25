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
		if(isset(pAdress::arg()['section']) AND array_key_exists(pAdress::arg()['section'], $this->_structure))
			$this->_section = pAdress::arg()['section'];
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

		if(isset(pAdress::arg()['is:result'], pAdress::session()['searchQuery']))
			$searchBox->setValue(pAdress::session()['searchQuery']);

		if(!isset(pAdress::arg()['ajax']))
			p::Out($searchBox);

		// If there is an offset, we need to define that
		if(isset(pAdress::arg()['offset']))
			$this->_parser->setOffset(pAdress::arg()['offset']);

		if(!isset(pAdress::arg()['ajax']) && (isset(pAdress::arg()['action']) && pAdress::arg()['action'] == 'edit'))
			p::Out("<div class='home-margin pEntry'><div class='card-tabs-bar titles'>
					<a class='ssignore float-right' href='".p::Url('?lemmasheet/new')."'>".LEMMA_NEW."</a>
					<a class='ssignore' href='".p::Url('?entry/'.pAdress::arg()['id'].(isset(pAdress::arg()['is:result']) ? '/is:result' : ''))."'>".LEMMA_VIEW_SHORT."</a>
					<a class='active ssignore' href='javascript:void();'>".LEMMA_EDIT_SHORT."</a>
					<a class='ssignore' href='".p::Url('?entry/'.pAdress::arg()['id'])."/discuss".(isset(pAdress::arg()['is:result']) ? '/is:result' : '')."'>".LEMMA_DISCUSS_SHORT."</a>
				</div>");
		elseif(!isset(pAdress::arg()['ajax']))
			p::Out("<div class='home-margin pEntry'>");



		// Let's handle the action by the object
		if(isset(pAdress::arg()['action'])){
			if(isset(pAdress::arg()['id']))
				if(!$this->_parser->runData(pAdress::arg()['id'])){
					p::Out("<br />".pMainTemplate::NoticeBox('fa-info-circle fa-12', ENTRY_NOT_FOUND, 'danger-notice'));
					goto skipError;
				}

			$this->_parser->passOnAction(pAdress::arg()['action']);
		}
		else{
			if(isset(pAdress::arg()['id']))
				$this->_parser->runData(pAdress::arg()['id']);
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