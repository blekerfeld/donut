<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      HomeView.class.php

class pInternView extends pSimpleView{

	public function renderAll(){
		
	}

	public static function about(){
		p::Out((new pTabBar('donut.','dots-horizontal-circle'))->addLink('about', 'About', null, true));
		p::Out("<div class='home-margin'>
			<br />
			<img src='".p::Url('library/staticimages/logo.png')."' style='height: auto;width:200px;'/><br /><br />
			<strong>donut.</strong> – the dictionary toolkit<br />
			version 0.11-dev <br />
			<br /><span class='tooltip small'>&copy; 2017 Thomas de Roo</span><br /><br />
		<div class='pLicense'>".p::Markdown(file_get_contents(p::FromRoot("LICENSE")), true)."</div></div>");
	}

}