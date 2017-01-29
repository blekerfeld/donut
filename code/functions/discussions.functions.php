<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: code/functions/discussions.functions.php

	//	Function to generate a discussion thread, takes other compatable tables as well!
	function pDiscussionThreadGet($word_id, $single = false, $parent_id = 0, $table_name = 'discussions', $link = ''){

		global $donut;

		if($link == '')
			$link = '?word_discussion='.$word_id;
		else
			$link .= $word_id;

		if($single){
			$word = "id = $word_id ";
			$parent = "";
		}
		else{
			$word = "word_id = $word_id ";
			$parent = "AND parent_discussion = $parent_id";
		}

		$discussions = pQuery("SELECT * FROM discussions WHERE $word $parent");

		if($discussions->rowCount() == 0 and $parent_id == 0)
			pOut("<div class='notice'><br /><i class='fa fa-info-circle'></i> ".WD_NO_THREADS."</div>");

		while($discussion = $discussions->fetchObject()){

			pOut("<div class='wdComment'>
					<div class='wdUserBar'><strong class='wdUserName'>".pUserName($discussion->user_id)."</strong> <span class='wdDate'>".pDate($discussion->post_date)."</span> <span class='wdPoints'>".pPlural(
			pCountVotes($discussion->id), WD_POINT, WD_POINTS)."</span></div><div class='wdContent'>".pMarkDownParse($discussion->content)."</div>");
			if(!$single)
				pOut("<div class='wdLinksBar'><div class='deleteLoad'></div><a class='reply' href='".pUrl($link.'&reply_thread='.$discussion->id)."'>".WD_REPLY."</a> &#8226; <a href='".pUrl($link.'&upvote_thread='.$discussion->id)."'>".WD_UPVOTE."</a> &#8226; <a href='".pUrl($link.'&downvote_thread='.$discussion->id)."'>".WD_DOWNVOTE."</a> ".(($discussion->user_id == $_SESSION['pUser']) ? "&#8226; <a class='delete-".$discussion->id."' href='".pUrl($link.'&delete_thread='.$discussion->id)."'>".WD_DELETE."</a>" : "")."</div>");
			
			// Time for the children

			if(!$single){
				pOut("<div class='children ".(($parent_id == 0) ? 'bottom' : '')."'>");
					pDiscussionThreadGet($word_id, false, $discussion->id);
				pOut("</div>");	
			}

			// Delete script
			pOut('<script>
				$(".delete-'.$discussion->id.'").click(function(){
					var r = confirm("'.WD_DELETE_CONFIRM.'");
					if (r == true) {
			    		$(".deleteLoad").load("'.pUrl($link.'&ajax&delete_thread='.$discussion->id).'");
					}
				});
			</script>');

			pOut("</div>");

		}

	}

	function pReplyThread($id, $word_id, $message, $user, $table = 'discussions', $id_name = 'word_id'){

		global $donut; 

		return pQuery("INSERT INTO $table($id_name, parent_discussion, user_id, points, content, post_date) VALUES ($word_id, $id, $user, 1, ".$donut['db']->quote($message).", NOW());");
	}

	function pDeleteThread($id, $table = 'discussions'){

		global $donut;

		return pQuery("DELETE FROM $table WHERE id = $id OR parent_discussion = $id;");

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

 ?>