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
		global $pol;
		$result = $pol['db']->query("SELECT * FROM ".$table." WHERE id = '$id' LIMIT 1;");
		if($result->rowCount() == 1)
		{
			return $result->fetchObject();
		}
		else
			return false;
	}

	function pCountTable($table, $where = 1){

		global $pol;

		$rs = $pol['db']->query("SELECT count(id) AS count FROM $table WHERE 1;");

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

		global $pol;

		if(isset($_REQUEST['admin']))
			$extra = "$('html').addClass('pAdmin');";
		else
			$extra = "$('html').removeClass('pAdmin');";

		return "
			<script>

			document.title = '".$pol['page']['title']."';

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

			   	loadfunction(hrefje, true);
			  
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

		pOut('    
      <span class="title_header">Dictionary</span>'. pAlphabetBar().'<br />
      ', true);

	}

	function pPrepareSelect($function_name, $class, $value, $text, $opt_val = '', $opt_text = ''){

		$get = $function_name();
		$select = "<select class='$class'>";
		if($opt_val != '' AND $opt_text != '')
			$select .= '<option value="'.$opt_val.'" selected>'.$opt_text.'</option>';
		foreach ($get as $item) {
			$select .= "<option value='".$item[$value]."'>".$item[$text]."</option>";
		}
		$select .= "</select>";
		return $select;
	}

 ?>