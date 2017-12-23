<?php
// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: HomeView.class.php

class pHomeView extends pSimpleView{

	public function renderAll(){
		// The home search box! Only if needed!
		if(!(isset(pRegister::arg()['nosearch'])))
			pTemplate::throwOutsidePage(new pSearchBox(true));
		pTemplate::setNoBorder();
		pTemplate::throwOutsidePage("<div class='pEntry landing-content'><div class='landing-about'>
			".p::Markdown(file_get_contents(p::FromRoot("static/md/home.md")), true)."</div></div>");
	}

	public static function landingTable(){
		// For now
		return  '';	
		return "<table class='landing-table'>
			<tr>
				<td>aaa</td>
				<td>aaa</td>
			</tr>
		</table>";
	}

}