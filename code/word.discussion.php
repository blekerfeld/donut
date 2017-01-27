
<?php 

if(isset($donut['request']['ajax'], $donut['request']['reply_thread'], $donut['request']['content'])){


	if(pReplyThread($donut['request']['reply_thread'], $donut['request']['word_discussion'], $donut['request']['content'], $_SESSION['pUser']) AND trim($donut['request']['content']) != '')
		echo "<script>loadfunction('".pUrl('?word_discussion='.$donut['request']['word_discussion'])."');</script>";
	elseif(trim($donut['request']['content']) == '')
		echo "<div class='notice danger-notice'><i class='fa fa-warning'></i> ".WD_REPLY_EMPTY."</div>";
	else
		echo "<div class='notice danger-notice'><i class='fa fa-warning'></i> ".WD_REPLY_ERROR."</div>";
	
	die();
}
elseif(isset($donut['request']['ajax'], $donut['request']['delete_thread'])){

	if(pDeleteThread($donut['request']['delete_thread'])){
		echo "<script>loadfunction('".pUrl('?word_discussion='.$donut['request']['word_discussion'])."');</script>";
	}


}


if(!($word = pGetWord($donut['request']['word_discussion'])));

// Template stuff
pOut(pAlphabetBar().' 
      <span class="title_header"><div class="icon-box white-icon"><i class="fa fa-comments"></i></div> '.WD_TITLE.'</span><br id="cl" /><br />
      ', true);

pOut('<table class="noshow" style="width:100%;"><tr>');

// Search area
if(isset($donut['get']['searchresult']))
	pSearchArea($_SESSION['search'], true);
else
	pSearchArea('', true);

// Start the layout stuff

pOut('<td style="padding-left: 20px;"><div class="notice hide" style="display: none;" id="loading"><i class="fa fa-spinner fa-spin"></i> Loading...</div>
	<br id="cl loading" style="display: none;"/><div class="ajaxload" style="display: none;"></div>
      <div class="drop">');



	if(isset($donut['request']['reply_thread']) AND !isset($donut['request']['ajax'])){

		// Title
		
		pOut("<a href='".pUrl('?word_discussion='.$donut['request']['word_discussion'])."' class='actionbutton'><i class='fa fa-12 fa-arrow-left'></i> ".WD_BACK_TO_THREAD."</a>");
		pOut("<div class='wdComments'>");
		pDiscussionThreadGet($donut['request']['reply_thread'], true);
		pOut("<div class='children'>
				<div class='wdComment'>
				<div class='ajaxLoad'></div>
				<div class='title small'><div class='icon-box fetch small'><i class='fa fa-pencil-square-o'></i></div> ".WD_REPLY_TITLE."</div><textarea class='nWord small elastic message' placeholder='".WD_REPLY_PLACEHOLDER."'></textarea><br /><a class='actionbutton throw small' id='send-action'><i class='fa fa-paper-plane'></i> ".WD_REPLY_SEND."</a><br /></div>
			</div>
			<script>
				$('#send-action').click(function(){
					$('.ajaxLoad').slideUp().load('".pUrl('?word_discussion='.$donut['request']['word_discussion'].'&ajax&reply_thread='.$donut['request']['reply_thread'])."', {'content': $('.message').val()}).slideDown();
				})
				$(document).ready(function(){
			        $('.elastic').elastic();
			    });

			</script>");
		pOut("</div>");

	}
	elseif(isset($donut['request']['new_thread']) AND !isset($donut['request']['ajax'])){

		// Title
		
		pOut("<a href='".pUrl('?word_discussion='.$donut['request']['word_discussion'])."' class='floatright actionbutton'><i class='fa fa-12 fa-arrow-left'></i> ".WD_BACK_TO_THREAD."</a>");
		pOut("<div class='wdComments'>");
		pOut('<span class="title_header"><div class="icon-box throw"><i class="fa fa-comments"></i></div> '.WD_TITLE.'</span><BR /><span class="small">'.sprintf(WD_TITLE_MORE, "<em><a href='".pUrl('?word='.$word->id)."'><span class='native'>".$word->native."</span></a></em>").'</span>');
		pOut("<div class='children'>
				<div class='wdComment'>
				<div class='ajaxLoad'></div>
				<div class='title small'><div class='icon-box fetch small'><i class='fa fa-plus'></i></div> ".WD_NEW_THREAD_TITLE."</div><textarea class='nWord small elastic message' placeholder='".WD_REPLY_PLACEHOLDER."'></textarea><br /><a class='actionbutton throw small' id='send-action'><i class='fa fa-paper-plane'></i> ".WD_REPLY_SEND."</a><br /></div>
			</div>
			<script>
				$('#send-action').click(function(){
					$('.ajaxLoad').slideUp().load('".pUrl('?word_discussion='.$donut['request']['word_discussion'].'&ajax&reply_thread=0')."', {'content': $('.message').val()}).slideDown();
				})
				$(document).ready(function(){
			        $('.elastic').elastic();
			    });

			</script>");
		pOut("</div>");

	}
	else{

		// Getting the dicussions for this word
		pOut("<a class='actionbutton' href='".pUrl('?word='.$donut['request']['word_discussion']."&new_thread")."'><i class='fa fa-arrow-left'></i> ".WD_BACK_TO_WORD."</a> <a class='actionbutton' href='".pUrl('?word_discussion='.$donut['request']['word_discussion']."&new_thread")."'><i class='fa fa-plus'></i> ".WD_NEW_THREAD."</a><br id='cl' />");
		pOut("<div class='wdComments'>");
		pDiscussionThreadGet($word->id);
		pOut("</div>");

	}









	// Regetting older results as a help

	if(isset($_SESSION['wholeword'])){
		pOut("<script>$('#wholeword').attr('checked', ".$_SESSION['wholeword'].");</script>");
	}

	if(isset($donut['get']['searchresult']))
	{
		pOut("<script>$(document).ready(function(){

			".'$(".moveResults").load("'.pUrl('?getword&wordsonly&ajax').'", {"word": $("#wordsearch").val(), "dict": $("#dictionary").val(), "wholeword":  $("#wholeword").is(":checked")}, function(){
					$(".dWordWrapper ol p.desc").hide();
			});'."

		});</script>");
	}

	

	pOut('<script>
      	$("#searchb").click(function(){
      		$("#pageload i").show();
      		$(".ajaxload").slideUp();
      		$(".drop").hide();
	      		$(".ajaxload").load("'.pUrl('?getword&ajax&wordsearch='.$donut['get']['word_discussion']).'", {"word": $("#wordsearch").val(), "dict": $("#dictionary").val(), "wholeword":  $("#wholeword").is(":checked")}, function(){$(".ajaxload").slideDown(function(){
	      								 $("#pageload i").delay(100).hide(400);
	      		})}, function(){
      		});
      		if($("#wordsearch").val() != ""){
      			window.history.pushState("string", "", "index.php?search=" + $("#wordsearch").val());
      		}
      		else{
      			window.history.pushState("string", "", "index.php?home");
      		}
      		
      	});
      </script>'."<script>
		$(document).ready(function(){

			$('.drop').slideDown();

		});
		$('#wordsearch').keydown(function(e) {
			    switch (e.keyCode) {
			        case 13:
			        if($('#wordsearch').is(':focus'))
			        {
			        	$('#searchb').click();
			        }

			    }
			    return; 
			});</script>");


	pOut('</div></td></tr></table><br id="cl"/>');

	pOut('<br id="cl" />');




 ?>


