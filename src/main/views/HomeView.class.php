<?php
// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: HomeView.class.php

class pHomeView extends pSimpleView{

	public function renderAll(){
		// The home search box! Only if needed!
		$searchBox = new pSearchBox(true);
		$searchBox->toggleNoBackSpace(true);

		if(isset(pRegister::arg()['is:result'], pRegister::freshSession()['searchQuery']))
			$searchBox->setValue(pRegister::freshSession()['searchQuery']);
	
		if(!isset(pRegister::arg()['ajax'], pRegister::arg()['ajaxLoad']))
			pTemplate::throwOutsidePage($searchBox);


		pTemplate::setTabbed();
		p::Out("<div class='pEntry proper'>");
		p::Out("<div class='pEntry proper'>".p::Markdown(file_get_contents(p::FromRoot("static/md/home.md")), true)."</div>");
		//p::Out("<div style='width: 70.5%;float: left'>".(new pAjaxLoader(p::Url('?entry/stats/search/ajaxLoad')))."</div><br id='cl' />");
		p::Out("</div>");
	}

}