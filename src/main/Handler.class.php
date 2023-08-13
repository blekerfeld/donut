<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: object.class.php



// ↓ Object
class pHandler{

	public $_icon, $_surface, $_data, $_dfs, $dataModel, $_section, $_app, $_actions, $_actionbar, $_paginated, $_offset, $_itemsperpage, $_condition = '', $_total, $_number_of_pages, $_linked = null, $_prototype, $_order = '1', $id, $_activeSection, $_tabs;

	public function __construct($parent){
		$this->_parent = $parent;

		$this->_prototype = $parent->structure;
		$this->_surface = $parent->_data['surface'];
		$this->_icon = $parent->_data['icon'];

		$this->_dfs = $parent->_fields;
		$this->_actions = $parent->_actions;
		$this->_actionbar = new pActionBar($parent->_actionbar);

		$this->_paginated = $parent->_paginated;
		$this->_offset = 0;
		$this->_itemsperpage = $parent->_data['items_per_page'];

		$this->_tabs = $parent->_parent->_tabs;

		// If there are none data fields specified, we need to get all.
		if(empty($this->_dfs->get()))
			$this->_dfs = null;

		$this->dataModel = new pDataModel($parent->_data['table'], $this->_dfs, $this->_paginated);
		
		$this->_total = $this->dataModel->countAll();
		$this->_section = $parent->_section;
		$this->_app = $parent->_app;

		$this->_activeSection = $this->_prototype[$this->_section];
	} 


	public function addLink($section, $key){
		if($this->_linked == null)
			$this->_linked = array();
		$this->_linked[$key] = (new pParser($this->_prototype, $this->_prototype[$section['section']], $this->_app));
	}

	public function getAction($name){

		if(isset($this->_actions->get()[$name]))
			return $this->_actions->get()[$name];
		else
			@$this->_actionbar->get()[$name];
	}

	// This is only the default behaviour of the catchAction, other objects might handle this differently!
	public function catchAction($action, $view, $arg = null){


		$this->_view = new $view(($arg == null ? $this->dataModel : $arg), $this->_prototype[$this->_section]);
		// The different objects might handle this differently, default is looks for methods
		if(isset(pRegister::arg()['ajax'])){
			$method = "ajax".ucfirst($action);
			if(method_exists($this, $method))
				return $this->$method();
		}
		else{
			$method = "render".ucfirst($action);
			if(method_exists(get_class($this->_view), $method))
				return $this->_view->$method();
		}
	}

	// This one lets the datamodel work! 

	public function getData($id = -1){

		if($id == -1){
			return $this->_data = $this->dataModel->getObjects($this->_offset, $this->_itemsperpage)->fetchAll();
		}
		else{
			if(!is_numeric($id))
				$id = p::HashId($id, true)[0];
			$this->id = $id;
			return $this->_data = $this->dataModel->getSingleObject($id)->fetchAll();
		}
	}


	public function setData($query){
		return $this->_data = $this->dataModel->complexQuery($this->dataModel->paginateQuery($query, $this->_offset, $this->_itemsperpage))->fetchAll();
	}


	// Passing on
	public function setCondition($condition){
		$this->dataModel->setCondition($condition);
	}

	// Passing on
	public function setOrder($order){
		$this->dataModel->setOrder($order);
	}

	public function pagePrevious(){
		if($this->_offset >= $this->_itemsperpage)
			return "<a href='".p::Url("?".$this->_app . "/". $this->_section . '/'.(isset(pRegister::arg()['id']) ? pRegister::arg()['id'] .'/' : '') . 'offset/'.($this->_offset - $this->_itemsperpage))."' class='arrow'>".(new pIcon('arrow-left'))."</a>";
	}

	public function pageNext(){
		if($this->_total > ($this->_offset + $this->_itemsperpage))
			return "<a  href='".p::Url("?".$this->_app . "/". $this->_section . '/'.(isset(pRegister::arg()['id']) ? pRegister::arg()['id'] .'/' : '') . 'offset/'.($this->_offset + $this->_itemsperpage))."' class='arrow'>".(new pIcon('arrow-right'))."</a> ";
	}

	public function changePagination($value){
		$this->dataModel->changePagination($value);
	}

	public function pageSelect(){

		$number_of_pages = $this->_total / $this->_itemsperpage;
		$this->_number_of_pages = ceil($number_of_pages);

		if($this->_number_of_pages == 1 OR $this->_number_of_pages == 0)
			return false;

		$current_page_number  = 1;
		$calculated_pagenum = ($this->_offset + $this->_itemsperpage) / $this->_itemsperpage;
		$sep1 = false;
		$sep2 = false;
		$output = '';

		while($number_of_pages > 0){
			$calculated_offset = (($current_page_number * $this->_itemsperpage) - $this->_itemsperpage);
			if($current_page_number == 1  OR $this->_number_of_pages == $current_page_number OR  ($current_page_number <= $calculated_pagenum + 2 AND $current_page_number >= $calculated_pagenum - 2) OR $current_page_number == $calculated_pagenum)
				$output .= "<a class='num ".($current_page_number == $calculated_pagenum ? 'active' : '')."' href='".p::Url("?".$this->_app . "/". $this->_section . '/' .(isset(pRegister::arg()['id']) ? pRegister::arg()['id'] .'/' : '') .'offset/'. $calculated_offset)."'>".$current_page_number."</a> ";
			else{
				if($current_page_number < $calculated_pagenum + 2 AND $sep1 == false){
					$output .= "<span class='sep'>...</span> ";
					$sep1 = true;
				}
				elseif($current_page_number > $calculated_pagenum - 2 AND $sep2 == false){
					$output .= "<span class='sep'>...</span> ";
					$sep2 = true;

				}
			}
			
			$number_of_pages--;
			$current_page_number++;
			}

		return $output;
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
		if(isset($this->_prototype[$item]['permission']))
			return $this->_prototype[$item]['permission'];
		else
			return $default_permission;
	}



}



