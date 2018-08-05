<?php
// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: HomeView.class.php

class pHomeView extends pSimpleView{

	public function renderAll(){
	


		//pTemplate::setTabbed();
		//p::Out("<div class='pEntry proper'>");
		p::Out("<div class='pEntry proper'>".p::Markdown(file_get_contents(p::FromRoot("static/md/home.md")), true)."</div>");
		//p::Out("<div style='width: 70.5%;float: left'>".(new pAjaxLoader(p::Url('?entry/stats/search/ajaxLoad')))."</div><br id='cl' />");
		//p::Out("</div>");
	}

}