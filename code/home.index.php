<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: index.home.php
*/

pDictionaryHeader();

pOut('<table class="noshow" style="width:100%;"><tr>');

pSearchArea();
	
$id = pQuery("SELECT count(id) AS cnt_id FROM words WHERE hidden = 0;");

$id_c  = $id->fetchObject();


pOut('<td style="padding-left: 20px;">
	<div class="notice hide" style="display: none;" id="loading"><i class="fa fa-spinner fa-spin"></i> Loading...</div>
	<br id="cl loading" style="display: none;"/><div class="ajaxload" style="display: none;"></div>
      <div class="drop">
      <div class="title"><div class="icon-box fetch"><i class="fa fa-quote-left"></i></div> Homepage</div><br />
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras et sem eget magna placerat sagittis nec a tortor. Sed eu tristique odio, id euismod mauris. In vehicula velit dolor, at dignissim purus congue eget. Cras sed dignissim quam, id varius lorem. Vivamus viverra, dolor eget pulvinar convallis, tellus nunc consectetur lorem, id consequat augue massa id nisl. Ut vel augue a erat accumsan placerat. Fusce ac ex lobortis leo sollicitudin mattis ac non lacus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris vitae urna dolor. Proin semper quis neque vitae lobortis. Praesent tincidunt vehicula tincidunt. Ut tempus varius dui, in sagittis dolor mattis at. Curabitur hendrerit sit amet lorem non tempor. Cras tincidunt sem volutpat tempus tincidunt. Ut nec convallis turpis, et iaculis velit. <br /><br /><div class="title"><div class="icon-box fetch"><i class="fa fa-line-chart"></i></div> Statistics</div><br />
      <span class="count">'.$id_c->cnt_id.' words</span>
     <div class="title"><div class="icon-box fetch"><i class="fa fa-clock-o"></i></div> Recently added to the dictionary</div><br />
     ');


// Recently added

$get_recent = pQuery("SELECT DISTINCT id FROM words WHERE hidden = 0 ORDER BY id  DESC LIMIT 5;");


while($r_word = $get_recent->fetchObject()) {
	pOut(pWordShowNative($r_word->id, 0, false, true, 'a', 'recentwords'));
}


pOut('  	
	</td></tr></table>
      <script>
      	$("#searchb").click(function(){
      		$("#pageload").show();
      		$("#loading").slideDown();
      		$(".ajaxload").slideUp();
      		$(".drop").slideUp();
	      		$(".ajaxload").load("'.pUrl('?getword&ajax').'", {"word": $("#wordsearch").val(), "dict": $("#dictionary").val(), "wholeword":  $("#wholeword").is(":checked")}, function(){$(".ajaxload").slideDown(function(){
	      								 $("#pageload").delay(100).hide(400);
	      								 $("#loading").slideUp();
	      		})}, function(){
      		});
      		if($("#wordsearch").val() != ""){
      			window.history.pushState("string", "", "index.php?search=" + $("#wordsearch").val());
      		}
      		else{
      			window.history.pushState("string", "", "index.php?home");
      		}
      		
      	});
      </script>
      <br id="cl"/>');

	pOut("<script>$('#wordsearch').keydown(function(e) {
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

 ?>