<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: code/wiki.index.php


pOut("<div class='header dictionary home wiki'><div class='title_header'><div class='header-icon'><i class='fa fa-map-signs'></i></div> ".WIKI_TITLE."</div></div>");

// Let's call the sidebar
pWikiSidebar();

if(isset($_REQUEST['ajax'], $_REQUEST['create'], $_REQUEST['name'], $_REQUEST['text']) AND $_REQUEST['text'] != '' AND $_REQUEST['name'] != ''){
	$id = pWikiNewArticle($_REQUEST['name'], $_REQUEST['text'], pUser());
	die(pUrl("?wiki=".urlencode($_REQUEST['name']), true));
}


pOut("<div class='wikiContent'>");

start_of_page:

if(isset($_REQUEST['random'])){
	$article = pQuery("SELECT article_title FROM wiki WHERE reference = 0 ORDER BY RAND() LIMIT 1")->fetchObject();
	pUrl('?wiki='.urlencode($article->article_title), true);
}


if(!empty($_REQUEST['wiki']) and !is_numeric($_REQUEST['wiki'])){
	$wiki_name = urldecode($_REQUEST['wiki']);
	$search = pWikiArticleByName(urldecode($_REQUEST['wiki']))->fetchObject();
	if(!($_REQUEST['wiki'] = @$search->id)){
		$_REQUEST['wiki'] = -1;
	}
		
}

if(isset($_REQUEST['history']) and !empty($_REQUEST['history']) and !is_numeric($_REQUEST['history'])){
	$search = pWikiArticleByName(urldecode($_REQUEST['history']))->fetchObject();
	$_REQUEST['history'] = $search->id;
}

if(is_numeric($_REQUEST['wiki'])){

	$w_id = $_REQUEST['wiki'];

	if($w_id == -1)
		goto handle_non_existence;

	// Here we do the article display

	// Getting the article to display
	if(isset($_REQUEST['permalink'])){
		if(!($article = @pWikiGetArticle($w_id, true)))
			goto handle_non_existence;
	}
	else{
		if(!($article = @pWikiGetRealArticle($w_id)))
			goto handle_non_existence;
	}


	pWikiShowArticle($article, isset($_REQUEST['permalink']));

	goto end_of_page;

	handle_non_existence:
	pWikiShowEmptyArticle($wiki_name);
}

if(isset($_REQUEST['history']) and is_numeric($_REQUEST['history']) and !is_numeric($_REQUEST['wiki'])){

	$w_id = $_REQUEST['history'];
	$article = pWikiGetArticle($w_id, true);
	if($article->reference != 0)
		pUrl("?wiki&history=".$article->reference, true, true);

	pWikiShowHistory($article);

	goto end_of_page;
}

if(isset($_REQUEST['edit']) and is_numeric($_REQUEST['edit']) and !is_numeric($_REQUEST['wiki'])){

	$w_id = $_REQUEST['edit'];
	$article = pWikiGetArticle($w_id, true);
	if($article->reference != 0)
		pUrl("?wiki&edit=".$article->reference, true, true);

	$article_real = pWikiGetRealArticle($w_id);

	pWikiShowEdit($article_real, $article);

	goto end_of_page;
}


if(isset($_REQUEST['discussion']) and is_numeric($_REQUEST['discussion']) and !is_numeric($_REQUEST['wiki'])){

	$w_id = $_REQUEST['discussion'];
	$article = pWikiGetArticle($w_id, true);
	if($article->reference != 0)
		pUrl("?wiki&discussion=".$article->reference, true, true);

	pWikiShowDiscussion(pWikiGetArticle($_REQUEST['discussion'], true));

	goto end_of_page;
}



if(isset($_REQUEST['w_search']) and $_REQUEST['w_search'] != '')
{

	pWikiShowSearch(urldecode($_REQUEST['w_search']));

	goto end_of_page;

}

if(isset($_REQUEST['revert'], $_REQUEST['to'])){
	$article_real = pWikiGetRealArticle($_REQUEST['revert']);
	if(pLogged() AND $article_real->id != $_REQUEST['to']){
		pWikiRevert($_REQUEST['revert'], $_REQUEST['to']);
		$_REQUEST['wiki'] = $_REQUEST['revert'];
		pOut("<div class='notice'><i class='fa fa-info-circle'></i> ".WIKI_REVERTED_S."</div>");
	}
	else{
		$_REQUEST['wiki'] = $_REQUEST['revert'];
		pOut("<div class='danger-notice'><i class='fa fa-warning'></i> ".WIKI_REVERTED_ERROR."</div>");
	}

	goto start_of_page;
}


end_of_page:
pOut("</div></div><br id='cl' />");
