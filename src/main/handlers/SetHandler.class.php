<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: SetHandler.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pSetHandler extends pHandler{

	public $_view, $_rulesheetModel, $_ID;

	// Constructor needs to set up the view as well
	public function __construct(){

		// First we are calling the parent's constructor (pHandler)
		call_user_func_array('parent::__construct', func_get_args());
		// Override the datamodel

		$this->dataModel = new pDataModel($this->_activeSection['table']);

		// First load all other levels

		$id = 0;
		if(isset(pRegister::arg()['id']))
			$id = $this->pathToID(pRegister::arg()['id']);


		// Loading the 'folders' and the items
		$this->dataModel->setCondition(" WHERE parent = '".$id."' ");
		$this->_ruleSets = $this->dataModel->getObjects()->fetchAll();

		// Getting the 'files' aka items
		$complexQuery = array();
		$count = 0;
		foreach($this->_activeSection['sets'] AS $set){
			$complexQuery[] = "SELECT * FROM (SELECT ".$this->_activeSection['sets_fields'].", '".$set[1]."' AS set_type FROM  ".$set[0]." WHERE in_set = 1 AND ".$this->_activeSection['hitOn']." = $id ORDER BY ".$set[0].".".$this->_activeSection['sets_name'][$set[1]]." ASC) as query_".$count." ";
			$count++;
		}

		$this->_rules = $this->dataModel->complexQuery(implode(' UNION ALL ', $complexQuery))->fetchAll();

		$this->dataModel->setCondition(" WHERE id = '".$id."' ");
		if(!isset(pRegister::arg()['action']) OR pRegister::arg()['action'] != 'new' OR pRegister::arg()['action'] != 'edit')
			$this->dataModel->getObjects();

		$this->_ID = $id;
		@$this->_properData = (new pDataModel($this->_activeSection['table']))->setCondition(" WHERE id =  ".$this->_ID)->getObjects()->fetchAll()[0];

		$this->_view = new pSetView($this);

	}

	protected function pathToID($path){
		if(is_numeric($path))
			return $path;
		$dM = new pDataModel($this->_activeSection['table']);
		// No trailing in names
		if(p::StartsWith($path, ':'))
			$path = substr($path, 1);
		if(p::EndsWith($path, ':'))
			$path = substr($path, 0, -1);
		$path = str_replace(':', '/', ":".$path);
		$dM->setCondition(" WHERE name = ".p::Quote($path)." ");
		if(array_key_exists(0, $dM->getObjects()->fetchAll()))
			return $dM->getObjects()->fetchAll()[0]['id'];
		else
			return 0;
	}

	protected function IDtoPath($id){
		if(!is_numeric($id))
			return $id;
			$dM = new pDataModel($this->_activeSection['table']);
		$dM->setCondition(" WHERE id = ".p::Quote($id)." ");
		if(array_key_exists(0, $dM->getObjects()->fetchAll()))
			return $dM->getObjects()->fetchAll()[0]['name'];
		else
			return '/rules';
	}


	public function catchAction($action, $view, $arg = null){

		if($action == 'view')
			return $this->render();

		// Default behaviour
		if($action != 'new' AND $action != 'edit' AND $action != 'remove')
			return parent::catchAction($action, $view, $arg);

		if($action == 'remove'){
			$this->dataModel->complexQuery("DELETE FROM ".$this->_activeSection['table']." WHERE id = '".pRegister::arg()['id']."' OR parent = '".pRegister::arg()['id']."';");
			$this->dataModel->remove(0, 0, pRegister::arg()['id']);
			goto done;
		}

		// Generating the path
		$path = "/rules/";
		if(isset(pRegister::arg()['id'])){
			$path = pRegister::arg()['id'];
			if(p::StartsWith(":", $path))
				$path = substr($path, 1);
			if(p::EndsWith(":", $path))
				$path = substr($path, 0, -1);
			$path = "/".str_replace(':', '/', $path)."/";
		}


		$path = p::Str($path)->replacePrefix("//", '/');
		$path = p::Str($path)->replaceSuffix("//", '/');

		$this->_activeSection['save_strings'][0] = 'New folder in '.$path;

		$dfs = new pSet;
		$pathID = $this->pathToID((isset(pRegister::arg()['id']) ? pRegister::arg()['id'] : ':rules:'));

		if($action == 'new'){
			$dfs->add(new pDataField('name', "Path", '67%', 'prefix', true, true, true, '', false, $path));
			$dfs->add(new pDataField('parent', "Parent", '67%', 'hidden', true, true, true, '', false, $pathID));
		}
		else{
			$dfs->add(new pDataField('name', "Name", '67%', 'input', true, true, true, ''));
			$dfs->add(new pDataField('parent', "Parent folder", '10%', 'select', true, true, true, 'small-caps', false, new pSelector('rulesets', null, 'name', true, '')));
			$this->getData($pathID);
			$oldName = $this->_data[0]['name'];
			$explodeName = explode('/', $this->_data[0]['name']);
			$this->_data[0]['name'] = $explodeName[max(array_keys($explodeName))];
		}

		$actionForm = new pMagicActionForm(pRegister::arg()['action'], $this->_activeSection['table'], $dfs, $this->_activeSection['save_strings'], $this->_app, $this->_section, $this); 

		$actionForm->compile();

		if(isset(pRegister::arg()['ajax'])){

			if($action == 'edit' AND isset(pRegister::post()['admin_form_name'], pRegister::post()['admin_form_parent'])){
				pRegister::changePost('admin_form_name', $this->IDtoPath(pRegister::post()['admin_form_parent']) . '/' . pRegister::post()['admin_form_name']);
				$this->dataModel->setFields($dfs);
			}

			// Replacing spaces
			pRegister::changePost('admin_form_name', str_replace(' ', '_', pRegister::post()['admin_form_name']));
			
			$explodeName = explode('/', pRegister::post()['admin_form_name']);
			if(trim($explodeName[max(array_keys($explodeName))]) == ''){
				echo pTemplate::NoticeBox('fa-exclamation-triangle fa-12', 'Folder name cannot be empty.', 'warning-notice ajaxMessage');
				echo "<script type='text/javascript'>
				$('.saving').slideUp(function(){
					$('.ajaxMessage').slideDown();
				});
				</script>";
				die();
			}

			$actionForm->ajax(false);
		
			if($action == 'edit')
				// We would have to change all children as well
				$this->dataModel->complexQuery("UPDATE rulesets
				SET name = Replace(name, '$oldName', '".pRegister::post()['admin_form_name']."');");

			done: 
			die("<script>window.location = window.location</script>");
		}

		else
			return $actionForm->form(false, (isset(pRegister::arg()['id']) ? pRegister::arg()['id'] : ':rules:'), false);

	
	}


	public function render(){

		$action = (isset(pRegister::arg()['action']) ? pRegister::arg()['action'] : 'view');

		// If the action is not view, then pass it on
		if($action != 'view')
			return $this->catchAction($action, 'pSetView', $this);

		
		return $this->_view->renderTable();

	}


}