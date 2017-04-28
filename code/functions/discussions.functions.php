<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: code/functions/discussions.functions.php

	//	Function to generate a discussion thread, takes other compatable tables as well!
	function pDiscussionThreadGet($word_id, $single = false, $parent_id = 0, $table_name = '', $link = ''){

		global $donut;

		if($link == '')
			$link = '?discuss-lemma=';

		if($single){
			$word = "id = $word_id ";
			$parent = "";
		}
		else{
			$word = "word_id = $word_id ";
			$parent = "AND parent_discussion = $parent_id";
		}

		$discussions = pQuery("SELECT * FROM discussions WHERE $word $parent AND table_name = '".$table_name."'");	

		if($discussions->rowCount() == 0 and $parent_id == 0)
			pOut("<div class='notice'><br /><i class='fa fa-info-circle'></i> ".WD_NO_THREADS."</div>");

		while($discussion = $discussions->fetchObject()){

			pOut("<div class='wdComment'>
					<div class='wdUserBar'><strong class='wdUserName'>".pUserName($discussion->user_id)."</strong> <span class='wdDate'>".pDate($discussion->post_date)."</span> <span class='wdPoints'>".pPlural(
			pCountVotes($discussion->id), WD_POINT, WD_POINTS)."</span></div><div class='wdContent'>".pMarkDownParse($discussion->content)."</div>");
			if(!$single)
				pOut("<div class='wdLinksBar'><div class='deleteLoad'></div><a class='reply' href='".pUrl($link.$word_id.'&reply_thread='.$discussion->id)."'>".WD_REPLY."</a> &#8226; <a href='".pUrl($link.$word_id.'&upvote_thread='.$discussion->id)."'>".WD_UPVOTE."</a> &#8226; <a href='".pUrl($link.$word_id.'&downvote_thread='.$discussion->id)."'>".WD_DOWNVOTE."</a> ".(($discussion->user_id == $_SESSION['pUser']) ? "&#8226; <a class='delete-".$discussion->id."' href='".pUrl($link.$word_id.'&delete_thread='.$discussion->id)."'>".WD_DELETE."</a>" : "")."</div>");
			
			// Time for the children

			if(!$single){
				pOut("<div class='children ".(($parent_id == 0) ? 'bottom' : '')."'>");
					pDiscussionThreadGet($word_id, false, $discussion->id, $table_name, $link);
				pOut("</div>");	
			}

			// Delete script
			pOut('<script>
				$(".delete-'.$discussion->id.'").click(function(){
					var r = confirm("'.WD_DELETE_CONFIRM.'");
					if (r == true) {
			    		$(".deleteLoad").load("'.pUrl($link.$word_id.'&ajax&delete_thread='.$discussion->id).'");
					}
				});
			</script>');

			pOut("</div>");

		}

	}

	function pReplyThread($id, $word_id, $message, $user, $table_name = 'discussions', $id_name = 'word_id'){

		global $donut; 

		return pQuery("INSERT INTO discussions($id_name, parent_discussion, user_id, points, content, post_date, table_name) VALUES ($word_id, $id, $user, 1, ".$donut['db']->quote($message).", NOW(), '".$table_name."');");
	}

	function pDeleteThread($id, $table = ''){

		global $donut;


		return pQuery("DELETE FROM discussions WHERE id = $id OR parent_discussion = $id AND table_name = '$table';");

	}

	function pCountVotes($id, $table_name = 'discussions'){

		global $donut;

		$count = pQuery("SELECT SUM(value) AS cnt FROM votes WHERE table_name = '$table_name' AND table_id = '$id';");
		$countObj = $count->fetchObject();

		if($countObj->cnt == NULL)
			return 0;

		return $countObj->cnt;

	}

	function pVote($id, $user_id, $vote, $table_name = 'discussions'){

		global $donut;

		if($vote == 1)
			$invertedVote = "-1";
		else
			$invertedVote = "";

		// TODO TODO TODO TODO But not that important

		
		if(true)                                          
			return;


	}

 
function pDiscussionStructure($thread, $table, $section, $ajax = false, $normal = false){

	if($normal)
		goto normal;

	if(isset($_REQUEST['ajax'], $_REQUEST['reply_thread'], $_REQUEST['content'])){


		if(pReplyThread($_REQUEST['reply_thread'], $thread, $_REQUEST['content'], $_SESSION['pUser'], 'wiki') AND trim($_REQUEST['content']) != ''){
			pUrl('?'.$section.'='.$thread, true, true);
			echo "<script>loadfunction('".pUrl('?'.$section.'='.$thread)."');</script>";
		}
		elseif(trim($_REQUEST['content']) == '')
			echo "<div class='notice danger-notice'><i class='fa fa-warning'></i> ".WD_REPLY_EMPTY."</div>";
		else
			echo "<div class='notice danger-notice'><i class='fa fa-warning'></i> ".WD_REPLY_ERROR."</div>";
		
		die();
	}
	elseif(isset($_REQUEST['ajax'], $_REQUEST['delete_thread'])){

		if(pDeleteThread($_REQUEST['delete_thread'], $table)){
			//echo "<script>loadfunction('".pUrl('?'.$section.'='.$thread)."');</script>";
		}


	}

	if($ajax)
		return die();

	normal:

	if(isset($_REQUEST['reply_thread']) AND !isset($_REQUEST['ajax'])){

		// Title
		
		pOut("<div class='wdComments'>");
		pDiscussionThreadGet($_REQUEST['reply_thread'], true, 0, $table);
		pOut("<div class='children'>
				<div class='wdComment'>
				<div class='ajaxLoad'></div>
				<div class='title small'><div class='icon-box fetch small'><i class='fa fa-pencil-square-o fa-10'></i></div> ".WD_REPLY_TITLE."</div><textarea class='nWord small elastic message' placeholder='".WD_REPLY_PLACEHOLDER."'></textarea><br /><a class='actionbutton throw small' id='send-action'><i class='fa fa-paper-plane'></i> ".WD_REPLY_SEND."</a><br /></div>
			</div>
			<script>
				$('#send-action').click(function(){
					$('.ajaxLoad').slideUp().load('".pUrl('?'.$section.'='.$thread.'&ajax&reply_thread='.$_REQUEST['reply_thread'])."', {'content': $('.message').val()}).slideDown();
				})
				$(document).ready(function(){
			        $('.elastic').elastic();
			    });

			</script>");
		pOut("</div>");
						pOut("<br /><a href='".pUrl('?'.$section.'='.$thread)."' class='editing small actionbutton'><i class='fa fa-12 fa-arrow-left'></i> ".WD_BACK_TO_THREAD."</a>");

	}
	elseif(isset($_REQUEST['new_thread']) AND !isset($_REQUEST['ajax'])){

		// Title
		

		pOut("<div class='wdComments'>");
		pOut("<div class='children'>
				<div class='wdComment'>
				<div class='ajaxLoad'></div>
				<div class='title small'><div class='icon-box fetch small'><i class='fa fa-plus'></i></div> ".WD_NEW_THREAD_TITLE."</div><textarea class='nWord small elastic message' placeholder='".WD_REPLY_PLACEHOLDER."'></textarea><br /><a class='actionbutton throw small' id='send-action'><i class='fa fa-paper-plane'></i> ".WD_REPLY_SEND."</a><br /></div>
			</div>
			<script>
				$('#send-action').click(function(){
					$('.ajaxLoad').slideUp().load('".pUrl('?'.$section.'='.$thread.'&ajax&reply_thread=0')."', {'content': $('.message').val()}).slideDown();
				})
				$(document).ready(function(){
			        $('.elastic').elastic();
			    });

			</script>");
		pOut("</div>");
				pOut("<br /><a href='".pUrl('?'.$section.'='.$thread)."' class='editing small actionbutton'><i class='fa fa-12 fa-arrow-left'></i> ".WD_BACK_TO_THREAD."</a>");

	}
	else{

		// Getting the dicussions for this word
		pOut("<br /><a class='actionbutton' href='".pUrl('?'.$section.'='.$thread."&new_thread")."'><i class='fa fa-plus'></i> ".WD_NEW_THREAD."</a><br id='cl' />");
		pOut("<div class='wdComments'>");
		pDiscussionThreadGet($thread, false, 0, 'wiki', '?'.$section.'=');
		pOut("</div>");

	}
}
