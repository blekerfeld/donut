<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: index.home.php
*/

	$pol['page']['menu'] = "";
	$pol['page']['head']['content'] = '<meta name="viewport" content="width=device-width, user-scalable=no">';
        pOut('<style>.topnav{margin: 0px;padding: 0px;z-index: 2000; position: fixed;width: 100%;
right: 0px;
top: 0px;} .page{margin-top: 75px;width: 100%; padding: 2px;padding-top: 10px;}</style>');
	pOut('<div class="title"><div class="icon-box throw"><i class="fa fa-search"></i></div> Search the dictionary</div><br />
		<input type="hidden" id="dictionary" value="engdov"/> 
		<select id="selectdic">
        <option value="engdov" data-imagesrc="library/flags/enty.png" data-description="use this dictionary">English - Armosian</option>
        <option value="doveng" data-imagesrc="library/flags/tyen.png" data-description="use this dictionary">Armosian - English</option>
        <option value="nlddov" data-imagesrc="library/flags/nlty.png" data-description="use this dictionary">Dutch - Armosian</option>
        <option value="dovnld" data-imagesrc="library/flags/tynl.png" data-description="use this dictionary">Armosian - Dutch</option>
      </select><script>$("#selectdic").ddslick({
	    onSelected: function(selectedData){
	       $("#dictionary").val(selectedData.selectedData.value);
	    }   
	});</script> <Br /><input type="text" id="wordsearch" /> <a class="button  remember" id="searchb" href="javascript:void(0);"><i class="fa fa-search" style="font-size: 12px!important;"></i> Search</a>
      <br /><br /><div class="ajaxload"></div>
     

      <script>
      	$("#searchb").click(function(){
      		$(".drop").fadeOut();
			$(".ajaxload").fadeOut().load("'.pUrl('?getword&ajax').'", {"word": $("#wordsearch").val(), "dict": $("#dictionary").val()}, function(){$(".ajaxload").fadeIn()});
      	});
      </script>
      
      &copy; 2015 Ethskotefarmosho
      <br id="cl"/>');


 ?>	