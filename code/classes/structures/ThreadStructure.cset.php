<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.cset.php

class pThreadStructure extends pStructure{
	
	private $_ajax, $_section, $_template, $_threads, $_threadsByID;

	public function compile(){

		if(isset(pRegister::arg()['section']))
			$this->_section = pRegister::arg()['section'];
		else
			$this->_section = $this->_default_section;

		if(!isset(pRegister::arg()['id']))
			die();

		$this->_dataModel = new pDataModel('threads');
		$this->_dataModel->setCondition(" WHERE section = '".$this->_section."' AND linked_to = '".pRegister::arg()['id']."' ");
		$this->_dataModel->getObjects();

		foreach($this->_dataModel->data()->fetchAll() as $thread){
			$this->_threads[$thread['thread_id']][] = $thread;
			$this->_threadsByID[$thread['id']] = $thread;
		}

		$this->_template = new pThreadTemplate($this->_threads);

		pMainTemplate::setTitle($this->_page_title);

	}

	protected function delete($id){
		// CHECK
		if(pUser::checkPermission(-4) OR pUser::read('id') == $this->_threadsByID[$id]['user_id'])
			$this->_dataModel->customQuery("DELETE FROM threads WHERE section = '".$this->_section."' AND ((id = $id) OR (thread_id = $id));");
	}

	
	public function render(){

		if(isset(pRegister::arg()['action'], pRegister::arg()['thread_id'])){
			if(pRegister::arg()['action'] == 'remove'){
				$this->delete(pRegister::arg()['thread_id']);
				// TODO: succes message
			}
			return false;
		}

		p::Out("<div class='home-margin'>".

		$this->_template->renderAllMessages()

			."</div>");

	}



}