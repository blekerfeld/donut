<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: wiki.functions.php


function pWikiGetArticle($id, $not_new = false){
	if($not_new)
		return pQuery("SELECT * FROM wiki WHERE id = $id LIMIT 1")->fetchObject();
	else
		return pQuery("SELECT * FROM wiki WHERE id = $id OR reference = $id ORDER BY article_date DESC LIMIT 1;")->fetchObject();
}

function pWikiSidebar(){
	return pOut("<div class='wikiSidebar'>
			hoi
		</div>");
}

function pWikiGetRealArticle($id){
	$check = pQuery("SELECT * FROM wiki WHERE id = $id LIMIT 1")->fetchObject();
	if($check->reference == 0)
		return pWikiGetArticle($id);
	else
		return pWikiGetArticle($id, true);
}

function pWikiShowArticle($article){
	
	if(!is_object($article))
		return false;

	$output = '';

	$reference = pWikiGetArticle($article->reference, true);

	// Begining output

	$output .= "<div class='wikiArticle' style='position: normal;'>";

	// Title

	if($article->article_title == '')
		$article->article_title = $reference->article_title;

	$output .= "<a class='float-left back-mini wiki' href='".pUrl('?wiki')."'><i class='fa fa-home' style='font-size: 12px!important;'></i></a><span class='wikiTitle'>".$article->article_title."</span>";
	

	// Content TODO: ADD FETCHING OF LAST UPDATE WITH CONTENT IF EMPTY
	if($article->article_content == '')
		$article->article_content = $reference->content;

	$output .= "<div class='wikiArticleInner'>";
		$output .= pMarkDownParse($article->article_content);
	$output .= "</div>";

	$output .= "</div>";

	return pOut($output);

}