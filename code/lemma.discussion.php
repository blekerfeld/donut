
<?php 

if(isset($_REQUEST['ajax'], $_REQUEST['reply_thread'], $_REQUEST['content'])){


	if(pReplyThread($_REQUEST['reply_thread'], $_REQUEST['discuss-lemma'], $_REQUEST['content'], $_SESSION['pUser']) AND trim($_REQUEST['content']) != '')
		echo "<script>loadfunction('".pUrl('?discuss-lemma='.$_REQUEST['discuss-lemma'])."');</script>";
	elseif(trim($_REQUEST['content']) == '')
		echo "<div class='notice danger-notice'><i class='fa fa-warning'></i> ".WD_REPLY_EMPTY."</div>";
	else
		echo "<div class='notice danger-notice'><i class='fa fa-warning'></i> ".WD_REPLY_ERROR."</div>";
	
	die();
}
elseif(isset($_REQUEST['ajax'], $_REQUEST['delete_thread'])){

	if(pDeleteThread($_REQUEST['delete_thread'])){
		echo "<script>loadfunction('".pUrl('?discuss-lemma='.$_REQUEST['discuss-lemma'])."');</script>";
	}


}

if(is_numeric($_REQUEST['discuss-lemma']))
	$lemma = $_REQUEST['discuss-lemma'];
elseif(ctype_alnum($_REQUEST['discuss-lemma'])){
	$lemma = pHashId($_REQUEST['discuss-lemma'], true);
	if(!($lemma = $lemma[0]))
		pUrl('', true);
}

$word = pGetWord($lemma);
	//pUrl('', true);

// Search area
if(isset($_GET['searchresult']))
	pDictionaryHeader($_SESSION['search']);
else
	pDictionaryHeader('');






// Start the layout stuff

pOut('<div class="home-margin"><div class="notice hide" style="display: none;" id="loading"><i class="fa fa-spinner fa-spin"></i> '.LOADING.'</div>
	<br id="cl loading" style="display: none;"/><div class="ajaxload" style="display: none;"></div>
      <div class="drop">');

	// Lemma-code
	pOut('<a class="lemma-code float-right" href="'.pUrl('?lemma='.pHashId($word->id)).'"title="'.$word->id.'"><i class="fa fa-bookmark-o"></i> '.pHashId($word->id).'</a>');
	// Discussion button
	if(pLogged())
		pOut('
			<a class="lemma-code discussion float-right" href="'.pUrl('?edit-lemma='.$_REQUEST['discuss-lemma']).'"><i class="fa fa-pencil-square-o "></i> '.LEMMA_EDIT.'</a><a class="lemma-code discussion float-right" href="'.pUrl('?discuss-lemma='.$_REQUEST['discuss-lemma']).'"><i class="fa fa-comments"></i> '.WD_TITLE.'</a>
			');

pOut('<div class="title"><div class="icon-box throw"><i class="fa fa-comments"></i></div> '.sprintf(WD_TITLE_MORE, pWordLinks("[[".$word->id."]]")).'</div><br />'.
	"<a class='float-left back-mini search' href='".pUrl('?lemma='.pHashId($word->id))."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i></a>");


// // Template stuff
// pOut('<div class="title"><div class="icon-box throw"><i class="fa fa-comments"></i></div> '..'</div>');
      
// // Back buttons
// 		pOut("<a class='float-left back-mini'  href='".pUrl('?lemma='.$_REQUEST['discuss-lemma'])."'><i class='fa fa-arrow-left' ></i></a>");




	if(isset($_REQUEST['reply_thread']) AND !isset($_REQUEST['ajax'])){

		// Title
		
		pOut("<div class='wdComments'>");
		pDiscussionThreadGet($_REQUEST['reply_thread'], true);
		pOut("<div class='children'>
				<div class='wdComment'>
				<div class='ajaxLoad'></div>
				<div class='title small'><div class='icon-box fetch small'><i class='fa fa-pencil-square-o fa-10'></i></div> ".WD_REPLY_TITLE."</div><textarea class='nWord small elastic message' placeholder='".WD_REPLY_PLACEHOLDER."'></textarea><br /><a class='actionbutton throw small' id='send-action'><i class='fa fa-paper-plane'></i> ".WD_REPLY_SEND."</a><br /></div>
			</div>
			<script>
				$('#send-action').click(function(){
					$('.ajaxLoad').slideUp().load('".pUrl('?discuss-lemma='.$lemma.'&ajax&reply_thread='.$_REQUEST['reply_thread'])."', {'content': $('.message').val()}).slideDown();
				})
				$(document).ready(function(){
			        $('.elastic').elastic();
			    });

			</script>");
		pOut("</div>");
						pOut("<br /><a href='".pUrl('?discuss-lemma='.$_REQUEST['discuss-lemma'])."' class='editing small actionbutton'><i class='fa fa-12 fa-arrow-left'></i> ".WD_BACK_TO_THREAD."</a>");

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
					$('.ajaxLoad').slideUp().load('".pUrl('?discuss-lemma='.$lemma.'&ajax&reply_thread=0')."', {'content': $('.message').val()}).slideDown();
				})
				$(document).ready(function(){
			        $('.elastic').elastic();
			    });

			</script>");
		pOut("</div>");
				pOut("<br /><a href='".pUrl('?discuss-lemma='.$_REQUEST['discuss-lemma'])."' class='editing small actionbutton'><i class='fa fa-12 fa-arrow-left'></i> ".WD_BACK_TO_THREAD."</a>");

	}
	else{

		// Getting the dicussions for this word
		pOut("<br /><a class='actionbutton' href='".pUrl('?discuss-lemma='.$lemma."&new_thread")."'><i class='fa fa-plus'></i> ".WD_NEW_THREAD."</a><br id='cl' />");
		pOut("<div class='wdComments'>");
		pDiscussionThreadGet($word->id);
		pOut("</div>");

	}






	pOut(pSearchScript("&wordsearch=".$_REQUEST['discuss-lemma']));

	pOut('</div></div><br id="cl"/>');





