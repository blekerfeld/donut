<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: object.cset.php



// ↓ Object
class pObject{

	public $_icon, $_surface, $_data, $_dfs, $dataObject, $_section, $_app, $_actions, $_actionbar, $_paginated, $_offset, $_itemsperpage, $_condition = '', $_total, $_number_of_pages, $_linked = null, $_structure, $_order = '1', $id, $_activeSection;

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

		// If there are none data fields specified, we need to get all.
		if(empty($this->_dfs->get()))
			$this->_dfs = null;

		$this->dataObject = new pDataObject($table, $this->_dfs, $this->_paginated);
		$this->_total = $this->dataObject->countAll();

		$this->_section = $section;
		$this->_app = $app;

		$this->_activeSection = $this->_structure[$this->_section];
	} 


	public function addLink($section, $key){
		if($this->_linked == null)
			$this->_linked = array();
		$this->_linked[$key] = (new pParser($this->_structure, $this->_structure[$section['section']], $this->_app));
	}

	public function getAction($name){

		if(isset($this->_actions->get()[$name]))
			return $this->_actions->get()[$name];
		else
			@$this->_actionbar->get()[$name];
	}

	public function catchAction($action){
		// The different objects might handle this differently
	}

	// This one lets the dataobject work! 

	public function getData($id = -1){
		if($id == -1){
			return $this->_data = $this->dataObject->getObjects($this->_offset, $this->_itemsperpage)->fetchAll();
		}
		else{
			if(!is_numeric($id))
				$id = pHashId($id, true)[0];
			$this->id = $id;
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
			return "<a href='".pUrl("?".$this->_app . "/". $this->_section . '/offset/'.($this->_offset - $this->_itemsperpage))."' class='back-mini'><i class='fa fa-8 fa-arrow-left'></i></a>";
	}

	public function pageNext(){
		if($this->_total > ($this->_offset + $this->_itemsperpage))
			return "<a href='".pUrl("?".$this->_app . "/". $this->_section . '/offset/'.($this->_offset + $this->_itemsperpage))."' class='back-mini'><i class='fa fa-8 fa-arrow-right' style='font-size: 12px!important;'></i></a> ";
	}

	public function changePagination($value){
		$this->dataObject->changePagination($value);
	}

	public function pageSelect(){

		$number_of_pages = $this->_total / $this->_itemsperpage;
		$this->_number_of_pages = ceil($number_of_pages);

		$current_page_number  = 1;

		$select_page = "<select class='selectPage' onChange='window.location = \"".pUrl("?".$this->_app . "/". $this->_section . '/offset/')."\" + $(this).val();'>";

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

	// This function returns a custom permission profile if it exists, other wise the default one
	public function itemPermission($item, $default_permission){
		if(isset($this->_structure[$item]['permission']))
			return $this->_structure[$item]['permission'];
		else
			return $default_permission;
	}



}



