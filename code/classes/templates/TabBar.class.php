<?php
// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
	//	++		File: TabBar.class.php

// Used to build an CardTabs-style card bar

class pTabBar extends pTemplatePiece {

	private $_titlePart = '', $_search = '', $_home = '', $icon = '', $_extraClass = '', $_above = false, $_links = array(), $_str = '';

	public function __construct($title, $icon, $above = true, $extraClass = 'titles'){
		$this->_titlePart = $title;
		$this->_icon = $icon; 
		$this->_extraClass = $extraClass;
		$this->_above = $above;
		return $this;
	}

	public function addLink($id, $link, $href = 'javascript:void(0);', $active = false){
		$this->_links[$id] = array($link, ($href == null ? 'javascript:void(0);' : $href), ($active ? 'active' : ''));
		return $this;
	}

	public function addSearch(){
		$this->_search = "<a class='ssignore gray em' href='javascript:void(0);' onClick='$(\".pEntry\").slideUp();
        	$(\".searchLoad\").slideDown();$(\".word-search\").val(\"\").focus().val(\"".(isset(pRegister::session()['searchQuery']) ? pRegister::session()['searchQuery'] : '.')."\");callBack();".(!isset(pRegister::session()['searchQuery']) ? "$(\".word-search\").val(\"\");$(\".searchLoad\").slideUp();" : '')."'>".(new pIcon('magnify'))."</a>";
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

	public function __toString(){
		$this->write('<div class="card-tabs-bar '.($this->_above ? 'above' : '').' '.$this->_extraClass.'">'."<a class='ssignore disabled no-select' href='javascript:void(0);'>".(new pIcon($this->_icon, 14))." ".$this->_titlePart."</a>".$this->_search.$this->_home);
		foreach($this->_links as $link)
			$this->write("<a href='".$link[1]."'  class='ssignore ".$link[2]."'>".$link[0]."</a>");
		return $this->write("</div>");
	}

	protected function write($str){
		$this->_str .= $str;
		return $this->_str;
	}

}