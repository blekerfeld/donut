<?php
// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
	//	++		File: TabBar.class.php

// Used to build an CardTabs-style card bar

class pTabBar extends pLayoutPart {

	private $_titlePart = '', $_search = '', $_home = '', $icon = '', $_extraClass = '', $_above = false, $_links = array(), $_str = '';

	public function __construct($title, $icon, $above = true, $extraClass = 'titles'){
		$this->_titlePart = $title;
		$this->_icon = $icon; 
		$this->_extraClass = $extraClass;
		$this->_above = $above;
		return $this;
	}

	public function changeTitlePart($title, $icon){
		$this->_titlePart = $title;
		$this->_icon = $icon; 
		return $this;
	}

	public function addLink($id, $link, $href = 'javascript:void(0);', $active = false, $subitems = null){
		$this->_links[$id] = array($link, ($href == null ? 'javascript:void(0);' : $href), ($active ? 'active' : ''), $subitems);
		return $this;
	}

	public function addSearch(){
		$this->_search = "<a class='ssignore gray em' href='javascript:void(0);' onClick='$(\".pEntry\").slideUp();
        	$(\".searchLoad\").slideDown();$(\".word-search\").val(\"\").focus();reloadOnlyFirstTime(\"".(isset(pRegister::session()['searchQuery']) ? pRegister::session()['searchQuery'] : '.')."\");callBack(true);".(!isset(pRegister::session()['searchQuery']) ? "$(\".word-search\").val(\"\");$(\".searchLoad\").slide();" : '')."'>".(new pIcon('magnify'))."</a>";
        return $this;
	}

	public function addHome(){
		$this->_home = "<a class='ssignore gray em' href='".p::Url("?")."'>".(new pIcon('home'))."</a>";
        return $this;
	}

	public function setActive($id){
		foreach($this->_links as $key => $link)
			$this->_links[$key][2] = '';
		$this->_links[$id][2] = 'active';
		return $this;
	}

	public function removeLink($id){
		unset($this->_links[$id]);
		return $this;
	}

	public function changeClass($class){
		$this->_extraClass = $class;
		return $this;
	}

	private function checkActiveSub($name){

		if(isset(pRegister::arg()['section']) && pRegister::arg()['section'] == $name)
			return true;

	}

	public function __toString(){
		$this->write('<div class="card-tabs-bar '.($this->_above ? 'above' : '').' '.$this->_extraClass.'">'."<a class='ssignore disabled no-select ttip' href='javascript:void(0);'>".(new pIcon($this->_icon, 14))." ".$this->_titlePart."</a>".$this->_search.$this->_home);
		foreach($this->_links as $link){
			$output = "<a href='".($link[3] == NULL ? $link[1] : 'javascript:void(0)')."' ";
				if($link[3] != NULL){
					$output .= "title='<strong>".htmlspecialchars($link[0])."</strong>";
					foreach($link[3] as $item){
						$output .= "<a href=\"".$item['href']."\" class=\"ttip-sub ".($this->checkActiveSub($item['section']) ? 'active' : '')."\">".(new pIcon($item['icon'], 12))." ". htmlspecialchars($item['surface'])."</a>";
					}
					$output .= "'";
				}

			$output .= "class='ssignore ttip ".$link[2]."'>".$link[0].($link[3] != NULL ? " ".(new pIcon('fa-caret-down', 14)) : '')."</a>";
			$this->write($output);
		}
		$this->write("<script type='text/javascript'>
			$('.ttip').tooltipster({animation: 'grow', animationDuration: 100,  distance: 0, contentAsHTML: true, interactive: true, side:'bottom', trigger: 'click'});</script>");
		return $this->write("</div>");
	}

	protected function write($str){
		$this->_str .= $str;
		return $this->_str;
	}

}