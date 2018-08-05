<?php
// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: admin.structure.class.php

class pThreadStructure extends pStructure{
	
	public $_section, $_view, $_threads, $_threadsByID;

	public function compile(){

		if(isset(pRegister::arg()['section']))
			$this->_section = pRegister::arg()['section'];
		else
			$this->_section = $this->_default_section;

		if(!isset(pRegister::arg()['id']))
			die();

		$this->dataModel = new pDataModel('threads');
		$this->dataModel->setCondition(" WHERE section = '".$this->_section."' AND linked_to = '".pRegister::arg()['id']."' ".(isset(pRegister::arg()['thread_id']) ? " AND id  = ".pRegister::arg()['thread_id'] : ""));
		$this->dataModel->getObjects();

		foreach($this->dataModel->data()->fetchAll() as $thread){
			$this->_threads[$thread['thread_id']][] = $thread;
			$this->_threadsByID[$thread['id']] = $thread;
		}

		$this->_view = new pThreadView($this->_threads);

		pTemplate::setTitle($this->_page_title);

	}

	protected function delete($id){
		// checkdate(month, day, year)
		if(pUser::checkPermission(-4) OR pUser::read('id') == $this->_threadsByID[$id]['user_id'])
			$this->dataModel->complexQuery("DELETE FROM threads WHERE section = '".$this->_section."' AND ((id = $id) OR (thread_id = $id));");
	}

	
	public function render(){

		// Since no parser is used, the permission check needs to be done here
		if(!pUser::checkPermission($this->_meta['permission'][$this->_section]))
			return p::Out("<div class='btCard minimal admin'>".pTemplate::NoticeBox('fa-info-circle fa-12', DA_PERMISSION_ERROR, 'danger-notice')."</div>");

		if(isset(pRegister::arg()['action'])){

			if(p::NoAjax())
				p::Out((new pTabBar(MMENU_DICTIONARY, 'fa-book', true, 'titles y wordsearch nomargin'))
					->addLink('view', LEMMA_VIEW_SHORT, p::Url("?entry/".$this->_section.'/'.pRegister::arg()['id'].(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), false)
					->addLink('edit', LEMMA_EDIT_SHORT, p::Url('?editor/'.$this->_section.'/edit/'.(is_numeric(pRegister::arg()['id']) ?  pRegister::arg()['id'] : p::HashId(pRegister::arg()['id'], true)[0]).(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), false)
					->addLink('discuss', LEMMA_DISCUSS_SHORT, p::Url('?entry/'.$this->_section.'/'.(is_numeric(pRegister::arg()['id']) ?  pRegister::arg()['id'] : p::HashId(pRegister::arg()['id'], true)[0]).'/discuss'.(isset(pRegister::arg()['is:result']) ? '/is:result' : '')), true));

			if(!isset(pRegister::arg()['ajax']))
				p::Out("<div class='home-margin pEntry'>");

			if(pRegister::arg()['action'] == 'remove' AND isset(pRegister::arg()['id'])){
				$this->delete(pRegister::arg()['id']);
				// TODO: succes message
			}
			if(pRegister::arg()['action'] == 'new' OR pRegister::arg()['action'] == 'edit'){
			$dfs = new pSet;
			$dfs->add(new pDataField('linked_to', "Linked to", '', 'hidden', true, true, true, '', false, (isset(pRegister::arg()['id']) ? pRegister::arg()['id'] : 0)));
			$dfs->add(new pDataField('section', "", '', 'hidden', true, true, true, '', false, $this->_section));
			$dfs->add(new pDataField('thread_id', "Thread ID", '1%', 'hidden', true, true, true, '', false, (isset(pRegister::arg()['thread_id']) ? pRegister::arg()['thread_id'] : 0)));
			$dfs->add(new pDataField('title', "Title", '67%', 'input', true, true, true, '', false));		
			$dfs->add(new pDataField('content', "Content", '67%', 'markdown', true, true, true, '', false));		
			$dfs->add(new pDataField('user_id', "", '', 'hidden', true, true, true, '', false, pUser::read('id')));
			$dfs->add(new pDataField('post_date', "", '', 'hidden', true, true, true, '', false, "NOW()"));
			$dfs->add(new pDataField('update_date', "", '', 'hidden', true, true, true, '', false, "NOW()"));
	
	
			$actionForm = new pMagicActionForm(pRegister::arg()['action'], 'threads', $dfs, $this->_meta['save_strings'], $this->_app, $this->_section, $this); 

			$actionForm->compile();

			if(isset(pRegister::arg()['ajax']))
				return $actionForm->ajax();

			$actionForm->form(false, (isset(pRegister::arg()['id']) ? pRegister::arg()['id'] : 0), false, pRegister::arg()['id']);

			}


			if(!isset(pRegister::arg()['ajax']))
				p::Out("</div>");

			if(pRegister::arg()['action'] != 'view')
				return false;
		}

		p::Out("<div class='home-margin'>".

		$this->_view->renderAllMessages()

			."</div>");



	}



}