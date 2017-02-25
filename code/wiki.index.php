<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: code/wiki.index.php


// Let's fabricate our header
pOut("<div class='title_header'><div class='icon-box throw'><i class='fa fa-map-signs'></i></div> ".WIKI_TITLE."</div>", true);

pOut("<div class='home-margin'>");

// Let's call the sidebar
pWikiSidebar();

pOut("<div class='wikiContent'>");

if(is_numeric($_REQUEST['wiki']) and !isset($_REQUEST['history'])){

	$w_id = $_REQUEST['wiki'];

	// Here we do the article display

	// Getting the article to display
	if(isset($_REQUEST['permalink'])){
		if(!($article = pWikiGetArticle($w_id, true)))
			return pUrl('?wiki', true);
	}
	else{
		if(!($article = pWikiGetRealArticle($w_id)))
			return pUrl('?wiki', true);
	}


	pWikiShowArticle($article);

}


pOut("</div></div><br id='cl' />");
