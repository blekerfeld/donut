<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: global.functions.php
*/

	function pSiRo($table, $id) #Single Row
	{
		global $donut;
		$result = pQuery("SELECT * FROM ".$table." WHERE id = '$id' LIMIT 1;");
		if($result->rowCount() == 1)
		{
			return $result->fetchObject();
		}
		else
			return false;
	}

	function pCountTable($table, $where = 1){

		global $donut;

		$rs = pQuery("SELECT count(id) AS count FROM $table WHERE 1;");

		$rr  = $rs->fetchObject();

		return $rr->count;

	}

	function pSimpleOffsetSystem($total_number, $items_per_page, $url){

			// Do we already have a offset
			if(isset($_GET['offset'])){
				$offset = $_GET['offset'];
			}
			else{
				$offset = 0;
			}

			// Validating the offset

			if(!pCheckOffset($offset, $items_per_page, $total_number)){
				$old_offset = $offset;
				$offset = pValidateOffset($offset, $items_per_page, $total_number);
				$restore_offset_script = "<script>
					var url = window.location.href;
					var new_url = url.replace('&offset=".$old_offset."', '&offset=".$offset."');
					loadfunction(new_url);
				</script>";
			}
			else
				$restore_offset_script = '';


			// Do we need a next offset?
			$total_number = pCountTable('submode_groups');

			// Making the select box

			$number_of_pages = $total_number / $items_per_page;
			$current_page_number  = 1;

			$select_page = "<select class='select_page_ID' onChange='loadfunction(\"".pUrl($url . '&offset=')."\" + $(this).val());'>";

			while($number_of_pages > 0){
				$calculated_offset = (($current_page_number * $items_per_page) - $items_per_page);
				$select_page .= "<option value='".$calculated_offset."' ".(($offset == $calculated_offset) ? 'selected' : '').">$current_page_number</option>";
				$number_of_pages--;
				$current_page_number++;
			}

			$select_page .= "</select>";

			if($total_number > ($offset + $items_per_page))
				$next_offset = "<a href='".pUrl($url . "&offset=".($offset + $items_per_page))."' class='actionbutton'>Next page <i class='fa fa-arrow-right' style='font-size: 12px!important;'></i></a>";
			else
				$next_offset = "";


			/// Do we need a previous offset
			if($offset >= $items_per_page)
				$back_offset = "<a href='".pUrl($url . "&offset=".($offset - $items_per_page))."' class='actionbutton'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i> Previous page</a>";
			else
				$back_offset = "";

			return array('offset' => $offset,'back_button' => $back_offset, 'next_button' => $next_offset, 'select_box' => $select_page, 'restore_offset_script' => $restore_offset_script);

	}

	// This function checks if an offset is right or not
	function pCheckOffset($offset, $items_per_page, $max_offset){

		if($offset < 0)
			return false;
		else if($offset == 0)
			return true;
		else if($offset > $max_offset)
			return false;
		else if (!($offset % $items_per_page == 0))
			return false;
		else if ($offset % $items_per_page == 0)
			return true;

	}


	// This functions makes an offset valid
	function pValidateOffset($offset, $items_per_page, $max_offset){

		if($offset < 0)
			$offset = 0;
		else if($offset > $max_offset)
			$offset = $max_offset;

		$new_offset = (ceil($offset)%$items_per_page === 0) ? ceil($offset) : round(($offset+$items_per_page/2)/$items_per_page)*$items_per_page;

		// We need to check it though
		while(pCheckOffset($new_offset, $items_per_page, $max_offset) === false){
			$new_offset = $new_offset - $items_per_page;
		}

		return $new_offset;

	}

	function pAjaxLinks($title = ''){

		global $donut;

		if(isset($_REQUEST['admin']))
			$extra = "$('html').addClass('pAdmin');";
		else
			$extra = "$('html').removeClass('pAdmin');";

		return "
			<script>

			document.title = '".$donut['page']['title']."';

			var isExternal = function(url) {
			    var domain = function(url) {
			        return url.replace('http://','').replace('https://','').split('/')[0];
			    };

			    return domain(location.href) !== domain(url);
			}

			var buildUrl = function(base, key) {
			    var sep = (base.indexOf('?') > -1) ? '&' : '?';
			    return base + sep + key;
			}

			var loadfunction = function (oldurl, skipPushState) {
				
				$('#pageload').show();

				localStorage.topper = document.body.scrollTop;

			  $('.ajaxOutLoad').load(buildUrl(oldurl, 'ajax_pOut'), function(){

			    if (!skipPushState) {
			      window.history.pushState('', 'Donut', oldurl);
			      if(localStorage.topper > 0){ 
			      		$('html, body').animate({ scrollTop:  localStorage.topper});
					  }
			    }
			    
			  });

			  $('.ajaxOutLoad').slideDown(150);
			  $('#pageload').delay(100).hide(400);
			}



			$(window).on('popstate', function () {

			    var hrefje = String(location.href);
			    

			    if(localStorage.href != hrefje){
			    	loadfunction(hrefje, true);
			   		localStorage.href = hrefje;
			    }

			  
			});



			$(document).ready(function() {

				    $('table.admin').DataTable({paging: false, searching: false, info: false});

				".$extra."


	            $('a[href!=\"javascript:void(0);\"]').click(function(e, options) {

	            	options = options || {};


					if ( !options.lots_of_stuff_done ) {
			            	if($(this).attr('href') == '".pUrl('?logout')."'){
			            		$(e.currentTarget).trigger('click', { 'lots_of_stuff_done': true });
			            	}
			            	else if(isExternal($(this).attr('href'))){
			            		$(e.currentTarget).trigger('click', { 'lots_of_stuff_done': true });
			            	}

			            	else{
			            		
			            		e.preventDefault();
			            	

			 					loadfunction($(this).attr('href'), false);
			
			            	}
					    } else {
					        /* allow default behavior to happen */
					    }

	        	});


			});

      	</script>";


	}

	function pAjaxStructure(){

		global $donut;

		// The complex javascript link system is needed for the new links loaded via AJAX
		if(isset($_REQUEST['ajax']))
			echo pAjaxLinks($donut['page']['title']);

		//	For the complex javascript link system, maybe we'll call it CoJaLiSy, we need to mimic the original page structure
		if(isset($_REQUEST['ajax_pOut'])){

			//	And... we need the link system itself!
			echo pAjaxLinks($donut['page']['title'])."<div class='nav'>".$donut['page']['menu']."</div>";

			//	Mimicing the header structure
			if(!empty($donut['page']['header']))
	            echo "<div class='header'>\n".$donut['page']['header_final']."\n </div>" ;

	        //	Mimicing the page structure
			echo "<div class='page'>".$donut['page']['content_final']."</div>";
		}

		// Time to die, bye bye

		if(isset($_REQUEST['ajax']) OR isset($_REQUEST['ajax_pOut']))
			die();

	}


	function pSearchArea($search = '', $word = false){



		pOut('
			<td style="width:280px;">
			<script>var dic = "";</script>
			<div class="title"><div class="icon-box throw"><i class="fa fa-search"></i></div> Search the dictionary</div><br />
			<input type="hidden" id="dictionary" value="engdov"/> 
			<select id="selectdic">
			');

		$languages = pGetLanguages(true);
		$native_name = pLanguageName(0);

		$lang_zero = pGetLanguageZero();

		//pOut('<option value="0_0" data-imagesrc="flags.php?flag_1=undef&flag_2='.$lang_zero->flag.'" data-description="use this dictionary" '.((isset($_SESSION['search_language']) AND ($_SESSION['search_language'] == '0_0')) ? ' selected ' : '').'>'.$native_name.'</option>');


		while($language = $languages->fetchObject()){

			pOut('<option value="'.$language->id.'_0"  data-description="search this dictionary" '.((isset($_SESSION['search_language']) AND ($_SESSION['search_language'] == $language->id.'_0')) ? ' selected ' : '').'>'.$language->name.' - '.$native_name.'</option>
			<option value="0_'.$language->id.'" data-description="search this dictionary" '.((isset($_SESSION['search_language']) AND ($_SESSION['search_language'] == '0_'.$language->id)) ? ' selected ' : '').'>'.$native_name.' - '.$language->name.'</option>');

		}


	      pOut('</select><script>$("#selectdic").ddslick({
		    onSelected: function(selectedData){
		       $("#dictionary").val(selectedData.selectedData.value);
		    }   
		});</script><br /><input type="text" id="wordsearch" placeholder="Enter a keyword" value="'.$search.'"/> <a class="button  remember" id="searchb" href="javascript:void(0);"><i class="fa fa-search" style="font-size: 12px!important;"></i> Search</a><br />
		
		<input id="wholeword" class="checkbox-wholeword" name="wholeword" type="checkbox" checked>
        <label for="wholeword"class="checkbox-wholeword-label">whole words</label>  

		<div class="moveResults"></div><br id="cl"/>
		</td>');

	      return true;

	}

	function pDictionaryHeader(){

		pOut(pAlphabetBar().'<span class="title_header"><div class="icon-box white-icon"><i class="fa fa-book"></i></div> '.DICT_TITLE.'</span><br /><br />
      ', true);

	}

	function pPrepareSelect($function_name, $class, $value, $text, $opt_val = '', $opt_text = ''){

		$get = $function_name();
		$select = "<select class='$class'>";
		if($opt_val != '' AND $opt_text != '')
			$select .= '<option value="'.$opt_val.'" selected>'.$opt_text.'</option>';
		foreach ($get->fetchAll() as $item) {
			$select .= "<option value='".$item[$value]."'>".$item[$text]."</option>";
		}
		$select .= "</select>";
		return $select;
	}


	// This functions parses the data according to norms
	function pDate($time_stamp, $show_time = true, $show_seconds = false, $timezone_when_possible = false) {
	
	// Everthing is added to this one
	$e = "";

	// Is it today? 
	if(date('d/m/Y') == date('d/m/Y', strtotime($time_stamp)))
	{
		$e .= DATE_TODAY;
	}

	// ... yesterday maybe?
	elseif(date("d/m/Y",mktime(0,0,0,date("m") ,date("d")-1,date("Y"))) == date('d/m/Y', strtotime($time_stamp)))
	{
		$e .= DATE_YESTERDAY;
	}

	// Tomorrow then?
	elseif(date("d/m/Y",mktime(0,0,0,date("m") ,date("d")+1,date("Y"))) == date('d/m/Y', strtotime($time_stamp)))
	{
		$e .= DATE_TOMORROW;
	}

	// This week? We just show the name of the day then
	elseif(date("W") == date("W", strtotime($time_stamp)) and date("Y") == date("Y", strtotime($time_stamp)))
	{
		$e .= constant("DATE_DAY_".date("w", strtotime($time_stamp)));
	}

	// If it is this year, we only have to show the month and the day
	elseif(date("Y") == date("Y", strtotime($time_stamp)))
	{
		if(DATE_DAYNUMBERAFTER == false)
			$e .= date("j", strtotime($time_stamp))." ";
		if(DATE_USESHORTMONTHS == true)
			$e .= constant("DATE_MONTH_SHORT_".date("n", strtotime($time_stamp)));
		if(DATE_USESHORTMONTHS == false)
			$e .= constant("DATE_MONTH_".date("n", strtotime($time_stamp)));
		if(DATE_DAYNUMBERAFTER == true)
			$e .= " ".date("j", strtotime($time_stamp));
		if(DATE_ADDSUFFIX == true)
			$e .= date("S", strtotime($time_stamp));
	}

	// This goes way back to another year, that's when we are now.
	else
	{
		
		if(DATE_DAYNUMBERAFTER == false)
			$e .= date("j", strtotime($time_stamp))." ";
		if(DATE_USESHORTMONTHS == true)
			$e .= constant("DATE_MONTH_SHORT_".date("n", strtotime($time_stamp)));
		if(DATE_USESHORTMONTHS == false)
			$e .= constant("DATE_MONTH_".date("n", strtotime($time_stamp)));
		if(DATE_DAYNUMBERAFTER == true)
			$e .= " ".date("j", strtotime($time_stamp));
		if(DATE_ADDSUFFIX == true)
			$e .= date("S", strtotime($time_stamp));
		if(DATE_SEPERATOR != "")
			$e .= DATE_SEPERATOR;
		$e .= date("Y", strtotime($time_stamp));
	}

	// Need to show time too?
	if($show_time == true)
	{
		$e .= DATE_PREPOSITION_TIME;
		if(DATE_USE12HOUR == true)
			$e .= date("h", strtotime($time_stamp)).":".date("i", strtotime($time_stamp)).(($show_seconds == true) ? (":".date("s", strtotime($time_stamp))) : "")." ".date("A", strtotime($time_stamp));
		else
			$e .= date("H", strtotime($time_stamp)).":".date("i", strtotime($time_stamp)).(($show_seconds == true) ? (":".date("s", strtotime($time_stamp))) : "");
		if(DATE_ADDTIMEZONEINDICATOR == true and $timezone_when_possible== true)
			$e .= " ".date("T", strtotime($time_stamp));
	}

	return $e;
}

// for easy plurals
function pPlural($amount, $singular = '', $plural = 's' ) {
    if ( $amount === 1 ) {
        return $amount." ".$singular;
    }
    return $amount." ".$plural;
}

