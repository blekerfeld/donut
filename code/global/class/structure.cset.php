<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: structure.cset.php

class pStructure{

	public $_name, $_meta, $_type, $_structure, $_menu, $_menu_content, $_default_section, $_page_title, $_app, $_permission = 0;

	public static $permission;

	public function __construct($name, $type, $app, $default_section, $page_title){
		$this->_name = $name;
		$this->_type = $type;
		$this->_app = $app;
		$this->_default_section = $default_section;
		$this->_page_title = $page_title;
	}


	// This function returns a custom permission profile if it exists, other wise the default one
	public function itemPermission($item){
		if(isset($this->_structure[$item]['permission']))
			return $this->_structure[$item]['permission'];
		else
			return self::$permission;
	}


	public function load(){
		try {
			// Loading the sturcture
			$this->_structure = require_once pFromRoot("code/structures/".$this->_name.".".$this->_type.".struct.php");
			$this->_meta = $this->_structure['MAGIC_META'];
			$this->_menu = $this->_structure['MAGIC_MENU'];
			unset($this->_structure['MAGIC_META']);
			unset($this->_structure['MAGIC_MENU']);

			if(isset($this->_meta['default_permission']))
				self::$permission = $this->_meta['default_permission'];
			else
				self::$permission = 0;

		} catch (Exception $e) {
			die();
		}
		
	}

}

class pStructureParser{

	public $_section, $_app, $_data, $_paginated = true, $_condition, $_offset, $_object, $structure, $_permission;

	static public $stApp, $stSection;

	public function __construct($structure, $data, $app = 'dictionary-admin'){
		$this->structure = $structure;
		$this->_app = $app;
		$this->_section = $data['section_key'];
		$this->_data =  $data;
		self::$stApp = $app;
		self::$stSection = $data['section_key'];

		// Loading the permission
		if(isset($this->structure[$this->_section]['permission']))
				$this->_permission = $this->structure[$this->_section]['permission'];
		else
			// The default permission
			$this->_permission = pStructure::$permission;
	}

	// Used to switch off the pagination if needed

	public function togglePagination(){
		$this->_paginated = !$this->_paginated;
	}

	// Used to give the SELECT query of the dataObject a condition 

	// Passing on
	public function setCondition($condition){
		if($this->_object != null)
			$this->_object->setCondition($condition);
	}

	// Passing on
	public function setOrder($order){
		if($this->_object != null)
			$this->_object->setOrder($order);
	}

	public function compile(){

		// Toggle thingies
		if(isset($this->_data['order']) && $this->_data['order'] != false)
			$this->setCondition($this->_data['order']);
		if(isset($this->_data['condition']) && $this->_data['condition'] != false)
			$this->setCondition($this->_data['condition']);
		if($this->_data['disable_pagination'] != false)
			$this->togglePagination();

		// Creating the field set
		$this->_fields = new pSet; 
		foreach($this->_data['datafields'] as $field)
			@$this->_fields->add($field);

		// Creating the actions per item set
		$this->_actions = new pSet;
		foreach($this->_data['actions_item'] as $action){
			$pAction = new pAction($action[0], $action[1], $action[2], $action[3], $action[4], $action[5], $this->_section, $this->_app);
			if(isset($action[6]))
				$pAction->setOverride($action[6]);
			$this->_actions->add($pAction);
		}

		// Creating the actions per item set
		$this->_actionbar = new pSet;
		foreach($this->_data['actions_bar'] as $action){
			$pAction = new pAction($action[0], $action[1], $action[2], $action[3], $action[4], $action[5], $this->_section, $this->_app);
			if(isset($action[6]))
				$pAction->setOverride($action[6]);
			$this->_actionbar->add($pAction);
		}

		// Compiling the parsing data into an tableObject
		switch ($this->_data['type']) {
			case 'pTableObject':
				$this->_object = new pTableObject($this->structure, $this->_data['icon'], $this->_data['surface'], $this->_data['table'], $this->_data['items_per_page'], $this->_fields, $this->_actions, $this->_actionbar, $this->_paginated, $this->_section, $this->_app);
				$this->setCondition((isset($this->_data['condition']) ? $this->_data['condition'] : ''));
				$this->setOrder((isset($this->_data['order']) ? $this->_data['order'] : '1'));
				break;
			
			default:
				
				break;
		}

		// Do we need to add links?
		foreach($this->_data['outgoing_links'] as $key => $link)
			$this->_object->addLink($link, $key);


		
	}

	// A shortcut to running the queries of the adminObject which runs it inside its dataObject
	public function runData($id = -1){
		return $this->_object->getData($id);
	}

	public function render(){
		// We can only render if we are allowed to 
		if(pUser::checkPermission($this->_permission))
			return $this->_object->render();
		else
			return pOut("<div class='btCard minimal admin'>".pNoticeBox('fa-info-circle fa-12', DA_PERMISSION_ERROR, 'danger-notice')."</div>");
	}

	// Allias function
	public function setOffset($offset){
		$this->_object->setOffset($offset);
	}

	public function action($name, $ajax, $linked){

		// Permission check again
		if(!pUser::checkPermission($this->_permission))
			return pOut("<div class='btCard minimal admin'>".pNoticeBox('fa-info-circle fa-12', DA_PERMISSION_ERROR, 'danger-notice')."</div>");

		// There are six magic actions that are coordinated by this function:
		// Those are: new, edit, remove, link-table, link-new, link-remove

		// Link table


			if($linked != null){
				// Guests
				$this->_object->changePagination(false);

				@$guests = new pStructureParser($this->structure, $this->structure[$this->structure[$this->_section]['incoming_links'][$linked]['section']], $this->_app);

				// The needed data fields
				$dfs = new pSet;

				$dfs->add(new pDataField($this->_data['incoming_links'][$linked]['child'], DA_TABLE_LINKS_CHILD, '50%', 'select', true, true, true, '', false, new pSelector($this->structure[$this->_section]['table'], null, $this->_data['incoming_links'][$linked]['show_child'])));

				if(isset($this->_data['incoming_links'][$linked]['fields']) and is_array($this->_data['incoming_links'][$linked]['fields']))
					foreach($this->_data['incoming_links'][$linked]['fields'] as $field)
						$dfs->add($field);

				$dfs->add(new pDataField($this->_data['incoming_links'][$linked]['parent'], DA_TABLE_LINKS_PARENT, '', 'select', false, false, false, '', false));

				// The needed actions
				$actions = new pSet;

				$actions->add(new pAction('remove-link', DA_DELETE_LINK, 'fa-12 fa-times', 'actionbutton', null, null, $this->_section, $this->_app));
				$action_bar = new pSet;
				$action_bar->add(new pAction('new-link', DA_TABLE_NEW_LINK, 'fa-12 fa-plus-circle', 'btAction float-left blue', null, null, $this->_section, $this->_app));

				$guests->compile();

				if(isset($_REQUEST['id']))
					$guests->runData($_REQUEST['id']);

				$linkTableObject = new pLinkTableObject($this->structure, 'fa-link',  $this->structure[$this->_data['incoming_links'][$linked]['section']]['surface']."&#x205F; (&#x205F;".DA_TABLE_LINKS_PARENT."&#x205F;) &#x205F; ↔ &#x205F;".$this->_data['surface']." &#x205F;(&#x205F;".DA_TABLE_LINKS_CHILD."&#x205F;)", $this->_data['incoming_links'][$linked]['table'], 0, $dfs, $actions, $action_bar, false, $this->_section, $this->_app);


				if(isset($_REQUEST['id'])){
					if(!is_numeric($_REQUEST['id']))
						$id = pHashId($_REQUEST['id'], true)[0];
					else
						$id = $_REQUEST['id'];

					// The condition allows for up to two parents
					$linkTableObject->setCondition("WHERE ((".$this->_data['incoming_links'][$linked]['child']." = '".$id."'" . (
						(isset($this->_data['incoming_links'][$linked]['double_parent']) && $this->_data['incoming_links'][$linked]['double_parent'] == true) ? ") OR (". $this->_data['incoming_links'][$linked]['parent'] . " = '".$id."'" : '')."))");
				}

				$this->_object->getData();
				$linkTableObject->getData();

				// Passing some very useful information to the linking table
				$linkTableObject->passData($guests, $linked,$this->_object->_data, $this->_data['incoming_links'][$linked]['show_parent'], $this->_data['incoming_links'][$linked]['show_child'], $this->_data['incoming_links'][$linked]['parent'], $_REQUEST['id']);

				if($name == 'link-table')
					return $linkTableObject->render();
				elseif($name == 'remove-link' && $ajax){
					$dataObject = new pDataObject($this->_data['incoming_links'][$linked]['table'], new pSet);
					return $dataObject->remove(0, 0, $_REQUEST['id']);
				}
				elseif($name == 'new-link'){
					$action = new pMagicActionForm('new-link', $this->_data['incoming_links'][$linked]['table'], $dfs, $this->_data['save_strings'], $this->_app, $this->_section, $linkTableObject);

					$action->compile();

					$extra_fields = null;
					if(isset($this->_data['incoming_links'][$linked]['fields']))
						$extra_fields = $this->_data['incoming_links'][$linked]['fields'];


					$action->newLinkPrepare($linked, $guests, $this->_data['incoming_links'][$linked]['show_parent'], $this->_data['incoming_links'][$linked]['show_child'], isset($this->_data['incoming_links'][$linked]['fields']), $extra_fields);

					if($ajax)
						return $action->newLinkAjax();
					else
						return $action->newLinkForm();
				}
			}


		// Removing a link is like relativly simple!
		elseif($name == 'remove-link' && $ajax && $linked != null){
			
		}

		elseif($name == 'new-link' && $linked != null){

			$fields = new pSet;
			$fields->add(new pDataField($this->_data['incoming_links'][$linked]['child'], 'Child', '40%', 'select', true, true, true, '', false));

			

		}



		// Removing is like very simple! 
		elseif($name == 'remove' && $ajax){
			$action = $this->_object->getAction($name);
			return $this->_object->dataObject->remove($action->followUp, $action->followUpFields);
		}

		// If the action is like, not removing, then we need something else:


		// Our action!
		$action = $this->_object->getAction($name);
		
		// Replacing the surface string of the action
		$this->_data['save_strings'][0] = $this->_data['surface'];

		$action = new pMagicActionForm($name, $this->_data['table'], $this->_fields, $this->_data['save_strings'], $this->_app, $this->_section, $this->_object); 

		$action->compile();

		if($ajax)
			return $action->ajax();

		else
			return $action->form();
	}


	// This is used to be able to get to the active section and action from within other classes
	public static function getApp(){
		
	}

}
