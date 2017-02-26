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
	return pOut("<div class='wikiSidebar'><div>
			<input class='wikiSearch' id='search' value='".(isset($_REQUEST['w_search']) ? $_REQUEST['w_search'] : '')."' placeholder='".WIKI_SEARCH_PLACEHOLDER."'/><br /><br />
			<a href='".pUrl('?wiki')."' class='".((!is_numeric($_REQUEST['wiki']) and !isset($_REQUEST['w_search']))? 'orange' : '')."'><i class='fa fa-8 fa-map-signs'></i> ".WIKI_MENU_WIKI."</a>
			<a href='".pUrl('?wiki&random')."'><i class='fa fa-8 fa-random'></i> ".WIKI_MENU_RANDOM."</a>
			</div>
		</div>
		<script>
		$('#search').keydown(function(e) {
			    switch (e.keyCode) {
			        case 13:
			        if($('#search').is(':focus'))
			        {
			        	if(!$('#search').val())
			        	{
			        		loadfunction('".pUrl('?wiki')."')
			        	}
			        	else{
			        		loadfunction('".pUrl('?wiki&w_search=')."' + encodeURIComponent($('#search').val()))
			        	}


			        }

			    }
			    return; 
			});</script>");
}

function pWikiGetRealArticle($id){
	$check = pQuery("SELECT * FROM wiki WHERE id = $id LIMIT 1")->fetchObject();
	if($check->reference == 0)
		return pWikiGetArticle($id);
	else
		return pWikiGetArticle($id, true);
}

function pWikiShowArticle($article, $permalink = false){
	
	if(!is_object($article))
		return false;

	// Is this a revertion?
	if($article->revert_to != 0)
		$article = pWikiGetArticle($article->revert_to, true);

	$output = '';

	if($article->reference == 0)
		$reference = $article;
	else
		$reference = pWikiGetArticle($article->reference, true);

	// Begining output
	if(CONFIG_WIKI_ENABLE_HISTORY != 0)
		$output .= ' <a href="'.pUrl('?wiki&history='.$reference->id).'" class="lemma-code discussion float-right"><i class="fa fa-12 fa-history"></i> '.WIKI_MENU_HISTORY.'</a> ';

	if((CONFIG_WIKI_ALLOW_GUEST_EDITING == 1 and !pLogged()) OR pLogged())
		$output .= ' <a href="'.pUrl('?wiki&edit='.$reference->id).'" class="lemma-code discussion float-right"><i class="fa fa-12 fa-edit"></i> '.WIKI_MENU_EDIT.'</a> ';

	$output .= ' <a href="'.pUrl('?wiki&discussion='.$reference->id).'" class="lemma-code discussion float-right"><i class="fa fa-12 fa-comments"></i> '.WIKI_MENU_DISCUSS.'</a> ';

	if($permalink AND !pWikiCurrent($article->id))
		$output .= "<div class='notice'><i class='fa fa-info-circle'></i> ".sprintf(WIKI_PERMALINK, "<em><u>".strtolower(pDate($article->article_date))."</u></em>")."</div>";

	$output .= "<div class='wikiArticle' style='position: normal;'>";

	// Title

	if($article->article_title == '')
		$article->article_title = $reference->article_title;

	$output .= "<a class='float-left back-mini wiki' href='".pUrl('?wiki')."'><i class='fa fa-home' style='font-size: 12px!important;'></i></a><span class='wikiTitle'>".$article->article_title."</span>";

	$output .= "<div class='wikiArticleInner'>";
		$output .= pMarkDownParse($article->article_content);
	$output .= "</div>";

	// Date, user
	$output .= "";

	$output .= "</div>";

	return pOut($output);

}


function pWikiShowEmptyArticle($name){


	$output = '';

	// Begining output

	if(!((CONFIG_WIKI_ALLOW_GUEST_EDITING == 1 and !pLogged()) OR pLogged()))
		return pOut("<div class='danger-notice'><i class='fa fa-warning'></i> ".WIKI_DOES_NOT_EXIST."</div>");
	else
		$output .= '<div class="ajaxLoad"></div><span class="wikiTitle red">'.$name.'</span>'."<div class='notice'><i class='fa fa-info-circle'></i> ".WIKI_DOES_NOT_EXIST_MAKE."</div><textarea class='wikiEditContent elastic allowtabs' style='min-height: 100px;'>".WIKI_ENTER_CONTENT."</textarea><div class='btButtonBar'><a class='btAction green wikiEdit create'>".WIKI_CREATE."</a><br id='cl' /></div>
		<script>
			$('.create').click(function(){
				$('.ajaxLoad').load('".pUrl('?wiki&create&ajax')."', {'name': '".$name."', 'text': $('.wikiEditContent').val()});
			});
		</script>";



	return pOut($output);

}


function pWikiGetHistory($id){
	return pQuery("SELECT * FROM wiki WHERE id = $id OR reference = $id ORDER BY article_date DESC");
}

function pWikiShowHistory($article){

	if(CONFIG_WIKI_ENABLE_HISTORY == 0)
		return pUrl('?wiki', true);

	if(!is_object($article))
		return false;

	$output = '';

	// Begining output


	$output .= ' <a href="'.pUrl('?wiki&discussion='.$article->id).'" class="lemma-code discussion float-right"><i class="fa fa-12 fa-comments"></i> '.WIKI_MENU_DISCUSS.'</a> ';

	$output .= ' <a href="'.pUrl('?wiki='.urlencode($article->article_title)).'" class="lemma-code discussion float-right"><i class="fa fa-12 fa-file-text"></i> '.WIKI_MENU_BACK.'</a> ';

	$output .= "<div class='wikiArticle' style='position: normal;'>";

	$output .= "<a class='float-left back-mini wiki' href='".pUrl('?wiki')."'><i class='fa fa-home' style='font-size: 12px!important;'></i></a><span class='wikiTitle'>".sprintf(WIKI_HISTORY_OF, "<a href='".pUrl('?wiki='.$article->id)."'><em>".$article->article_title."</em></a>")."</span>";

	$history = pWikiGetHistory($article->id);

	$output .= "<ul class='wikiHistory'>";

	$count = 0;

	while($history_item = $history->fetchObject())
		$output .= "<li>".($count == 0 ? '<strong class="wikiCurrent"> '.WIKI_CURRENT.'</strong> ' : '')."<a href='".pUrl('?wiki='.$history_item->id.'&permalink')."'>(".pDate($history_item->article_date).") ".(($history_item->revert_to != 0 AND $reverted = pWikiGetArticle($history_item->revert_to, true)) ? "<span class='wikiReverted'><i class='fa fa-repeat fa-8'></i> ".sprintf(WIKI_REVERTED, "<em>".pDate($reverted->article_date)."</em>", "<strong>".pUsername($reverted->user_id)."</strong>")."</span>" : '')." by <strong>".pUsername($history_item->user_id)."</strong></a>".($history_item->specification != '' ? ': <em>'.$history_item->specification.'</em>' : '')."<a href='".pUrl('?wiki&revert='.$article->id.'&to='.$history_item->id)."' class='tooltip float-right xsmall'><i class='fa fa-8 fa-repeat'></i> ".WIKI_REVERT."</a></li>".($count++ ? '' : '');

	$output .= "</ul></div>";

	return pOut($output);

}

function pWikiAddRevision($reference, $title, $specification, $content, $user){
	global $donut;
	return pQuery("INSERT INTO wiki(reference, article_title, article_content, article_date, user_id, specification) VALUES (".$reference.", ".$donut['db']->quote($title).", ".$donut['db']->quote($content).", NOW(), '".$user."', ".$donut['db']->quote($specification).");");
}

function pWikiRevert($revert, $to){
	return pQuery("INSERT INTO wiki(reference, article_date, revert_to, user_id) VALUES ($revert, NOW(), $to, ".pUser().");");
}


function pWikiNewArticle($name, $content, $user){
	global $donut;

	pWikiAddRevision(0, $name, WIKI_INITIAL, $content, $user);

	return $donut['db']->lastInsertID();

}

function pWikiShowEdit($article, $reference){

	global $donut;

	if(!is_object($article))
		return false;

	if(isset($_REQUEST['ajax'])){
		if(isset($_REQUEST['title'], $_REQUEST['text'], $_REQUEST['specification']) and !pMempty($_REQUEST['text'])){

			if($_REQUEST['title'] == '' AND $article->title == '')
				$title = $reference->title;
			elseif($_REQUEST['title'] == '')
				$title = $article->title;
			else
				$title = $_REQUEST['title'];

			pWikiAddRevision($reference->id, $title, $_REQUEST['specification'], $_REQUEST['text'], pUser());

			die("<script>$('#busysave').fadeOut();</script>");
		}
	}

	$output = '';

	// Begining output

	$output .= ' <a href="'.pUrl('?wiki&discussion='.$article->id).'" class="lemma-code discussion float-right"><i class="fa fa-12 fa-comments"></i> '.WIKI_MENU_DISCUSS.'</a> ';

	$output .= ' <a href="'.pUrl('?wiki='.urlencode($reference->article_title)).'" class="lemma-code discussion float-right"><i class="fa fa-12 fa-file-text"></i> '.WIKI_MENU_BACK.'</a> ';

	$output .= "<div class='wikiArticle' style='position: normal;'>";

	$output .= "<a class='float-left back-mini wiki' href='".pUrl('?wiki')."'><i class='fa fa-home' style='font-size: 12px!important;'></i></a><span class='wikiTitle'>".sprintf(WIKI_EDITING, "<a href='".pUrl('?wiki='.urlencode($article->article_title))."'><em>".$article->article_title."</em></a>")."</span>";

	$output .= "<div class='ajaxpreview hide btCard' style='width:100%!important;margin-bottom: 0px!important;'></div><div class='ajaxsave'>
		</div>".pNoticeBox('fa-spinner fa-spin', SAVING, 'notice hide', 'busysave')."
	<div class='btCard' style='width:100%;'><div class='btTitle'>Title: <input class='wikiEditTitle' value='".$article->article_title."' placeholder='".$reference->article_title." **".WIKI_UNCHANGED."**'/></div><textarea class='allowtabs elastic wikiEditContent'>".$article->article_content."</textarea>
	
	<div class='btButtonBar'>
	<a class='btAction green submit'><i class='fa fa-save fa-12'></i> ".WIKI_EDIT_SAVE."</a><a class='btAction preview'><i class='fa fa-eye fa-12'></i> ".WIKI_EDIT_PREVIEW."</a>
	<a class='btAction wikiEdit' onClick='wrapText(\".wikiEditContent\", \"**\", \"**\");'><i class='fa fa-bold fa-8'></i></a><a class='btAction wikiEdit' onClick='wrapText(\".wikiEditContent\", \"*\", \"*\");'><i class='fa fa-italic fa-8'></i></a><a class='btAction wikiEdit' onClick='wrapText(\".wikiEditContent\", \"[[\", \"]]\");'><i class='fa fa-map-signs fa-8'></i></a><a class='btAction wikiEdit' onClick='wrapText(\".wikiEditContent\", \"{{\", \"}}\");'><i class='fa fa-book fa-8'></i></a>
	<input class='wikiEditSpecification' placeholder='".WIKI_SPECIFICATION."'/>
	<br id='cl' />
	</div>
	</div>
	<script>
	$('.preview').click(function(){
		$('.ajaxpreview').load('".pUrl('?wiki&preview&ajax')."', {'text': $('.wikiEditContent').val()}).show();
	});$('.submit').click(function(){
		$('#busysave').fadeIn();
		$('.ajaxsave').load('".pUrl('?wiki&edit='.$_REQUEST['edit'].'&ajax')."', {'text': $('.wikiEditContent').val(), 'title': $('.wikiEditTitle').val(), 'specification': $('.wikiEditSpecification').val()});
	});</script>";

	return pOut($output);

}


function pWikiShowSearch($search){

	if(CONFIG_WIKI_ENABLE_HISTORY == 0)
		return pUrl('?wiki', true);

	$results = pWikiArticleByName($search, "", "LIKE", "%");

	$output = '';

	// Begining output


	$output .= "<div class='wikiArticle' style='position: normal;'>";

	$output .= "<a class='float-left back-mini wiki' href='".pUrl('?wiki')."'><i class='fa fa-home' style='font-size: 12px!important;'></i></a><span class='wikiTitle'>".WIKI_SEARCH_RESULTS."<em>".$search."</em></span>";

	$output .= "<ul class='wikiSearchResults'>";

	$count = 0;

	while($search_item = $results->fetchObject())
		$output .= "<li>".$search_item->article_title."</li>".($count++ ? '' : '');

	if($count == 0)
		$output .= "<div class='notice'><i class='fa fa-info-circle'></i> ".WIKI_NO_ARTICLES."</div>";

	$output .= "</ul></div>";

	return pOut($output);

}


function pWikiCurrent($id){
	$article = pWikiGetArticle($id, true);
	if($article->reference == 0)
		return ($id == pWikiGetRealArticle($id)->id);
	else
		return ($id == pWikiGetRealArticle($article->reference)->id);
}

function pWikiArticleByName($name, $limit = "LIMIT 1", $operator = "=", $extra = ""){
	global $donut;
	return pQuery("SELECT DISTINCT article_title, id FROM wiki WHERE article_title $operator ".$donut['db']->quote($extra.pEscape($name).$extra)." ORDER BY article_date DESC $limit;");
}

function pWikiLinks($text){

		return preg_replace_callback('/\[\[([^\]]+)\]\]/', function($matches) {

		$link_par = explode("|", $matches[1]);

		if($search = pWikiArticleByName($link_par[0])->fetchObject() and is_object($search)){
				$link = '?wiki='.urlencode($search->article_title);
				$class = "";
			}
			else{
				$link = '?wiki&w_search='.urlencode($link_par[0]);
				$class = "red";
			}
		

		if(count($link_par) == 1 and is_object($search))
			$link_par[1] = $search->article_title;
		elseif(count($link_par) == 1)
			$link_par[1] = $link_par[0];

		return '<a class="wikiLink '.$class.' tooltip" href="' . pUrl($link) . '"><span class="native">' . $link_par[1] . '</span></a>';

		}, $text);

	}

	function pWikiShowDiscussion($article){
		
	$output = '';

	// Begining output
	if(CONFIG_WIKI_ENABLE_HISTORY != 0)
		$output .= ' <a href="'.pUrl('?wiki&history='.$article->id).'" class="lemma-code discussion float-right"><i class="fa fa-12 fa-history"></i> '.WIKI_MENU_HISTORY.'</a> ';

	if((CONFIG_WIKI_ALLOW_GUEST_EDITING == 1 and !pLogged()) OR pLogged())
		$output .= ' <a href="'.pUrl('?wiki&edit='.$article->id).'" class="lemma-code discussion float-right"><i class="fa fa-12 fa-edit"></i> '.WIKI_MENU_EDIT.'</a> ';

	$output .= ' <a href="'.pUrl('?wiki='.urlencode($article->article_title)).'" class="lemma-code discussion float-right"><i class="fa fa-12 fa-file-text"></i> '.WIKI_MENU_BACK.'</a> ';

		$output .= "<span class='wikiTitle'>".sprintf(WIKI_DISCUSSION_OF, "<a href='".pUrl('?wiki='.$article->id)."'><em>".$article->article_title."</em></a>")."</span>";

		pOut($output);

		pDiscussionStructure($article->id, 'wiki', 'wiki&discussion');

	}