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


pOut(pNoticeBox('fa-spinner fa-spin', LOADING, 'notice hide home-margin', 'loading').
	'<br id="cl loading" style="display: none;"/><div class="ajaxload home-margin" style="display: none;"></div>
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
      <img src="http://louisianaconsularcorps.com/wp-content/gallery/norway/norway-1.jpg" style="width: 50%; float: left; height: auto; margin-right: 30px;" />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum mauris turpis, feugiat non nulla vel, iaculis tincidunt erat. Aenean ac euismod mi. Nullam feugiat felis sed venenatis laoreet. Vestibulum sodales nisl vitae ex dignissim maximus. Nam hendrerit sed dolor et convallis. Phasellus nec ipsum eget eros porttitor accumsan. Duis pretium malesuada dui, vitae lobortis dolor faucibus sit amet. Donec interdum, turpis id pretium interdum, ante eros sagittis elit, vel aliquam elit est vel ex. Nullam nulla risus, fringilla ac posuere ut, convallis pretium magna. Fusce pellentesque quis erat vel dignissim. Curabitur in augue vel nisi laoreet placerat. Phasellus dapibus augue sed ex interdum, vulputate tristique nunc congue. Aenean efficitur sapien at libero tempor efficitur. Pellentesque facilisis posuere leo at elementum. Donec ac lectus nec lorem consequat dictum. Nulla facilisi. 
      </div>
      <div class="row-right">HOI</div>
      <br id="clear" />
      <br /><br />

     <div class="title"><div class="icon-box fetch"><i class="fa fa-line-chart"></i></div> Statistics</div><br />
      <span class="count">'.$id_c->cnt_id.' words</span>
      </div> 	
     ');

 pOut(pSearchScript());

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