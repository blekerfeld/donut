<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: admin.cset.php

class pAdminParser{

	public $_section, $_app, $_data, $_paginated = true, $_condition, $_offset, $_adminObject;

	public $structure;

	public function __construct($structure, $data, $app = 'dictionary-admin'){
		$this->structure = $structure;
		$this->_app = $app;
		$this->_section = $data['section_key'];
		$this->_data =  $data;
	}

	// Used to switch off the pagination if needed

	public function togglePagination(){
		$this->_paginated = !$this->_paginated;
	}

	// Used to give the SELECT query of the dataObject a condition 

	// Passing on
	public function setCondition($condition){
		if($this->_adminObject != null)
			$this->_adminObject->setCondition($condition);
	}

	// Passing on
	public function setOrder($order){
		if($this->_adminObject != null)
			$this->_adminObject->setOrder($order);
	}

	public function compile(){

		// Toggle thingies
		if($this->_data['condition'] != false)
			$this->setCondition($this->_data['condition']);
		if($this->_data['disable_pagination'] != false)
			$this->togglePagination();

		// Creating the field set
		$this->_fields = new pSet; 
		foreach($this->_data['datafields'] as $field)
			@$this->_fields->add(new pDataField($field[0], $field[1], $field[2], $field[3], $field[4], $field[5], $field[6], $field[7], $field[8]));

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
				$this->_adminObject = new pTableObject($this->structure, $this->_data['icon'], $this->_data['surface'], $this->_data['table'], $this->_data['items_per_page'], $this->_fields, $this->_actions, $this->_actionbar, $this->_paginated, $this->_section, $this->_app);
				$this->setCondition((isset($this->_data['condition']) ? $this->_data['condition'] : ''));
				$this->setOrder((isset($this->_data['order']) ? $this->_data['order'] : '1'));
				break;
			
			default:
				
				break;
		}

		// Do we need to add links?
		foreach($this->_data['outgoing_links'] as $link)
			$this->_adminObject->addLink($link);


		
	}

	// A shortcut to running the queries of the adminObject which runs it inside its dataObject
	public function runData($id = -1){
		return $this->_adminObject->getData($id);
	}

	public function render(){
		return $this->_adminObject->render();
	}

	// Allias function
	public function setOffset($offset){
		$this->_adminObject->setOffset($offset);
	}

	public function action($name, $ajax, $linked){

		// There are six magic actions that are coordinated by this function:
		// Those are: new, edit, remove, link-table, link-new, link-remove

		// Link table

			$this->_adminObject->changePagination(false);
			@$guests = new pAdminParser($this->structure, $this->structure[$linked]);

			if($linked != null){

				// The needed data fields
				$dfs = new pSet;

				$dfs->add(new pDataField($this->_data['incoming_links'][$linked]['child'], 'Child', '50%', 'select', true, true, true, '', false));
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

				$linkTableObject = new pLinkTableObject($this->structure, 'fa-link',  $this->structure[$linked]['surface']."&#x205F; (&#x205F;".DA_TABLE_LINKS_PARENT."&#x205F;) &#x205F; ↔ &#x205F;".$this->_data['surface']." &#x205F;(&#x205F;".DA_TABLE_LINKS_CHILD."&#x205F;)", $this->_data['incoming_links'][$linked]['table'], 0, $dfs, $actions, $action_bar, false, $this->_section, $this->_app);

				if(isset($_REQUEST['id'])){
					if(!is_numeric($_REQUEST['id']))
						$id = pHashId($_REQUEST['id'], true)[0];
					else
						$id = $_REQUEST['id'];
					$linkTableObject->setCondition("WHERE ".$this->_data['incoming_links'][$linked]['child']." = '".$id."'");
				}

				$this->_adminObject->getData();
				$linkTableObject->getData();

				$linkTableObject->passData($guests, $linked,$this->_adminObject->_data, $this->_data['incoming_links'][$linked]['show_parent'], $this->_data['incoming_links'][$linked]['show_child'], $this->_data['incoming_links'][$linked]['parent'], $_REQUEST['id']);

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


					$action->newLinkPrepare($guests, $this->_data['incoming_links'][$linked]['show_parent'], $this->_data['incoming_links'][$linked]['show_child'], isset($this->_data['incoming_links'][$linked]['fields']), $extra_fields);

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
			$action = $this->_adminObject->getAction($name);
			return $this->_adminObject->dataObject->remove($action->followUp, $action->followUpFields);
		}

		// If the action is like, not removing, then we need something else:


		// Our action!
		$action = $this->_adminObject->getAction($name);
		
		// Replacing the surface string of the action
		$this->_data['save_strings'][0] = $this->_data['surface'];

		$action = new pMagicActionForm($name, $this->_data['table'], $this->_fields, $this->_data['save_strings'], $this->_app, $this->_section, $this->_adminObject); 

		$action->compile();

		if($ajax)
			return $action->ajax();

		else
			return $action->form();
	}

}


// ↓ Table object
class pAdminObject{

	public $_icon, $_surface, $_data, $_dfs, $dataObject, $_section, $_app, $_actions, $_actionbar, $_paginated, $_offset, $_itemsperpage, $_condition = '', $_total, $_number_of_pages, $_linked = null, $_structure, $_order = '1';

	public function __construct($structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin'){
		$this->_structure = $structure;
		$this->_surface = $surface;
		$this->_icon = $icon;

		$this->_dfs = $dfs;
		$this->_actions = $actions;
		$this->_actionbar = new pActionBar($actionbar);

		$this->_paginated = $paginated;
		$this->_offset = 0;
		$this->_itemsperpage = $itemsperpage;

		$this->dataObject = new pDataObject($table, $this->_dfs, $this->_paginated);
		$this->_total = $this->dataObject->countAll();

		$this->_section = $section;
		$this->_app = $app;
	} 


	public function addLink($section){
		if($this->_linked == null)
			$this->_linked = new pSet;
			$this->_linked->add(new pAdminParser($this->_structure, $this->_structure[$section['section']]));
	}

	public function getAction($name){


		if(isset($this->_actions->get()[$name]))
			return $this->_actions->get()[$name];
		else
			$this->_actionbar->get()[$name];
	}

	// This one lets the dataobject work! 

	public function getData($id = -1){
		if($id == -1)
			return $this->_data = $this->dataObject->getObjects($this->_offset, $this->_itemsperpage)->fetchAll();
		else{
			if(!is_numeric($id)){
				$id = pHashId($id, true)[0];
			}
			return $this->_data = $this->dataObject->getSingleObject($id)->fetchAll();
		}
	}

	// Passing on
	public function setCondition($condition){
		$this->dataObject->setCondition($condition);
	}

	// Passing on
	public function setOrder($order){
		$this->dataObject->setOrder($order);
	}

	public function pagePrevious(){
		if($this->_offset >= $this->_itemsperpage)
			return "<a href='".pUrl("?".$this->_app . "&section=". $this->_section . '&offset='.($this->_offset - $this->_itemsperpage))."' class='btAction page blue small'><i class='fa fa-8 fa-arrow-left'></i></a>";
	}

	public function pageNext(){
		if($this->_total > ($this->_offset + $this->_itemsperpage))
			return "<a href='".pUrl("?".$this->_app . "&section=". $this->_section . '&offset='.($this->_offset + $this->_itemsperpage))."' class='btAction page blue small'><i class='fa fa-8 fa-arrow-right' style='font-size: 12px!important;'></i></a> ";
	}

	public function changePagination($value){
		$this->dataObject->changePagination($value);
	}

	public function pageSelect(){

		$number_of_pages = $this->_total / $this->_itemsperpage;
		$this->_number_of_pages = ceil($number_of_pages);

		$current_page_number  = 1;

		$select_page = "<select class='selectPage' onChange='window.location = \"".pUrl("?".$this->_app . "&section=". $this->_section . '&offset=')."\" + $(this).val();'>";

			while($number_of_pages > 0){
				$calculated_offset = (($current_page_number * $this->_itemsperpage) - $this->_itemsperpage);
				$select_page .= "<option value='".$calculated_offset."' ".(($this->_offset == $calculated_offset) ? 'selected' : '').">$current_page_number</option>";
				$number_of_pages--;
				$current_page_number++;
			}

		$select_page .= "</select>";

		return $select_page;
	}


	public function setOffset($offset){
		$this->_offset = $this->validateOffset($offset);
	}

	private function validateOffset($offset){
		if(!$this->checkOffset($offset, $this->_itemsperpage, $this->_total)){
				$offset = $this->calcOffset($offset, $this->_itemsperpage, $this->_total);
				return $offset;
			}
		else
			return $offset;
	}

	private function calcOffset($offset, $items_per_page, $max_offset){
		if($offset < 0)
			$offset = 0;
		else if($offset > $max_offset)
			$offset = $max_offset;

		$new_offset = (ceil($offset)%$items_per_page === 0) ? ceil($offset) : round(($offset+$items_per_page/2)/$items_per_page)*$items_per_page;

		// We need to check it though
		while($this->validateOffset($new_offset, $items_per_page, $max_offset) === false){
			$new_offset = $new_offset - $items_per_page;
		}

		return $new_offset;
	}

	private function checkOffset($offset, $items_per_page, $max_offset){
		if($offset < 0)
			return false;
		else if($offset == 0)
			return true;
		else if($offset > $max_offset)
			return false;
		else if (!($offset % $items_per_page == 0))
			return false;
		else if ($offset % $items_per_page == 0)
			return true;
	}


}



