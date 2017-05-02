<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: MenuTemplate.cset.php

class pMenuTemplate extends pTemplate{

	protected $_menu, $_meta, $_dispatch, $_permission;

	public function __construct(){
		$dispatch = pDispatcher::structure();
		$this->_permission = $dispatch['MAGIC_MENU']['default_permission'];
		$this->_meta = $dispatch['MAGIC_MENU']['items'];
		unset($dispatch['MAGIC_MENU']);
		$this->_dispatch = $dispatch;
		$this->prepareMenu();
	}

	protected function itemPermission($key){
		if(isset($this->_meta[$key]['permission']))
			return $this->_meta[$key]['permission'];
		else
			return $this->_permission;
	}

	protected function checkSubItemPermission($items){
		$output = false;
		foreach ($items as $key => $item) {
			if(isset($item['permission']) AND pUser::checkPermission($item['permission']))
				$output = true;
			elseif(pUser::checkPermission($this->_permission))
				$output = true;
		}
		return $output;
	}

	public function render($pOut = false){

		// Starting the menu
		$output = "<div class='nav'>";

		$items = 0;

		foreach($this->_menu as $key => $main){
			if(isset($this->_meta[$key])){
				if(pUser::checkPermission($this->itemPermission($key)) OR (isset($this->_meta[$key]['subitems']) AND $this->checkSubItemPermission($this->_meta[$key]['subitems']))){
					$output .= "<a href='".(isset($this->_meta[$key]['app']) ? pUrl("?".$this->_meta[$key]['app']) : '')."' class=' ".(isset($this->_meta[$key]['class']) ? $this->_meta[$key]['class'] : '')." ".($this->checkActiveMain($key) ? 'active' : '')." ttip_menu'";

					if(isset($this->_meta[$key]['subitems']) AND $this->checkSubItemPermission($this->_meta[$key]['subitems'])){
						$output .= "title='";
						foreach($this->_meta[$key]['subitems'] as $item){
							if(pUser::checkPermission($this->itemPermission($key)))
								$output .= "<a href=\"".pUrl("?".$item['app'])."\" class=\"ttip-sub nav ".($this->checkActiveSub($key) ? 'active' : '')."\">".(new pIcon($item['icon'], 12))." ". htmlspecialchars($item['name'])."</a>";
						}
						$output .= "'";	
					}
					$items++;
					$output .= ">".(isset($this->_meta[$key]['icon']) ? (new pIcon($this->_meta[$key]['icon'], 8))." " : '').$this->_meta[$key]['name']."</a>";
				}
			}
		}

		$output .= "</div><script type='text/javascript'>
			$('.ttip_menu').tooltipster({animation: 'grow', animationDuration: 100,  distance: 0, contentAsHTML: true, interactive: true, side:'bottom'});
		</script>";
		
		if($items == 0)
			return '<div class="nav"></div>';
		if($pOut)
			return pOut($output);

		return $output;

	}


	public function __toString(){
		return $this->render();
	}

	protected function prepareMenu(){
		// We don't accept double items
		foreach($this->_dispatch as $key => $item)
			if(isset($item['menu']) && pUser::checkPermission($this->itemPermission($key)))
				$this->_menu[$item['menu']]['items'][$key] = $item;
	}

	protected function checkActiveMain($name){
		if(isset($this->_menu[$name]['items']))
			foreach($this->_menu[$name]['items'] as $key => $item)
				if(isset(pDispatcher::$active) && pDispatcher::active() == $key)
					return true;
	}

	protected function checkActiveSub($name){

		if(isset(pDispatcher::$active) && pDispatcher::active() == $name)
			return true;

	}

}