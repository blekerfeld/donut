<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: index.home.php
*/

pDictionaryHeader('');

	
$id = pQuery("SELECT count(id) AS cnt_id FROM words WHERE hidden = 0;");

$id_c  = $id->fetchObject();


pOut('<div class="home-margin">'.pNoticeBox('fa-spinner fa-spin', LOADING, 'notice hide home-margin', 'loading').
	'</div><div class="ajaxload home-margin" style="display: none;"></div>
      <div class="drop home-margin">
    	

      <div class="row-center"> <div class="title"><div class="icon-box fetch"><i class="fa fa-clock-o"></i></div> Recently added</div><br />
     ');

		// Recently added
		$get_recent = pQuery("SELECT DISTINCT id FROM words WHERE hidden = 0 ORDER BY id  DESC LIMIT 10;");
		while($r_word = $get_recent->fetchObject()) {
			pOut(pWordShowNative($r_word->id, 0, false, true, 'a', 'recentwords'));
		}

pOut('</div>
      <div class="row-left"><div class="title"><div class="icon-box throw"><i class="fa fa-home"></i> </div>Welcome</div><br />
      		'.pMarkDownParse(CONFIG_HOME_TEXT).'
      </div>
      <div class="row-right" style="text-align; center!important;">
     <div class="title"><div class="icon-box fetch"><i class="fa fa-line-chart"></i></div> Statistics</div><br />
      <span class="count" style="text-align; center!important;margin: 0 auto;display: block;width: 45%;">'.$id_c->cnt_id.' words</span></div>
      <br id="clear" />
 

      </div> 
   	<br id="cl" />
     ');

 pOut(pSearchScript());

	pOut("<script>

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

	if(isset($_SESSION['wholeword'])){
		pOut("<script>$('#wholeword').attr('checked', ".$_SESSION['wholeword'].");</script>");
	}


	if(isset($_GET['search'])){

		pOut("<script>$(document).ready(function(){
			$('#wordsearch').val('".urldecode($_GET['search'])."');
			$('#searchb').click();
		});</script>");

	}