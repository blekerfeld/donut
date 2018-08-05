<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: SetView.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pSetView extends pView{



	public function renderTable(){

		$data = $this->_data->_properData;

		$newLink = "<a href='javascript:void();' class='btAction small blue no-float action-button ttip_file' title='<div class=\"tooltipster-inner\">
		<a href=\"javascript:newFolder();\" class=\"new-folder ttip-sub nav\">".(new pIcon('fa-folder', 12))." Folder</a><div class=\"hide loadNewFolder\">".LOADING."</div>";
						foreach($this->_data->_activeSection['sets'] as $set){
								$newLink .= "<a href=\"".p::Url("?".$this->_data->_activeSection['editor'].'/'.$set[1].'/new:'.$data['id'])."\" class=\"ttip-new-item ttip-sub nav\">".(new pIcon($set[2], 12))." ". htmlspecialchars($this->_data->_activeSection['sets_strings'][$set[1]])."</a>";
						}
						$newLink .= "</div>'";	

		$newLink .= "><span class='dType'><strong>".ADD_ITEM." ".(new pIcon('fa-caret-down', 10))."</strong></span></a>";


		p::Out("<div class='rulesheet-margin'><br /><span class='float-right small'><br />".$newLink."</span><br /><span class='pSectionTitle extra medium notosans'>".(new pIcon('fa-folder', 12))." <strong>".$this->breakDownName($data['name'], $this->_data->_app)."</strong></span><div class='pSectionWrapper'><table class='admin'>
			<tr class='title'>
				<td></td>
				<td></td>
				<td>Name</td>
				<td>File type</td>
				<td></td>
			</tr>
					");
		// One level up


		if($data['parent'] != -1){
			p::Out("<tr><td style='width:15px;'></td><td colspan='4'><a href='".p::Url('?'.$this->_data->_app.'/browser/view/'.str_replace('/', ":", dirname($data['name'])))."'>..</a></td></tr>");
		}

		p::Out($this->renderFolders());
		p::Out($this->renderRules());

		// A placeholder for emptiness
		if(count($this->_data->_rules) == 0 AND count($this->_data->_ruleSets) == 0)
			p::Out("<tr><td style='width:15px;'></td><td><span class='dType'>".DA_NO_RECORDS."</span></td><td></td></tr>");




		p::Out("</table></div>".$newLink."<a href='javascript:void()' class='bulk-delete btAction no-float redbt red-link'>Delete selected</a>
			</div>
			<script type='text/javascript'>
			$('table.rules').checkboxes('range', true);
			$('a.bulk-delete').click(function(){
				var checkedItems = []
				var checkedFolders = []
				$('input[name=\"selecteditems[]\"]:checked').each(function ()
				{
    				checkedItems.push(parseInt($(this).val()));
				});
				$('input[name=\"selectedfolders[]\"]:checked').each(function ()
				{
    				checkedFolders.push(parseInt($(this).val()));
				});
				if(confirm('".RS_DELETE_CONFIRM."') == true){
					if(confirm('".RS_DELETE_CONFIRM."') == true){
						$('.deleteBulkLoad').load($(this).data('url'));	
					}
				}
			});
			$('.ttip_file').tooltipster({animation: 'grow', animationDuration: 150,  distance: 0, contentAsHTML: true, interactive: true, side: 'bottom', trigger: 'click',
				zIndex: 1000,
				functionAfter: function(origin, continueTooltip){
					$('.select2').trigger('select2:close');
				},
				functionReady: function(origin, continueTooltip){
					$('a.edit-folder').click(function(){
						if($(this).data('toggle') == 0){
							if($(this).data('loaded') != 1){
								$('.loadEditFolder-' + $(this).data('id')).slideDown().load($(this).data('url'));
								$(this).data('loaded', 1);
							}
							$('.loadEditFolder-' + $(this).data('id')).slideDown();
							$('.delete-item').slideUp();
							$(this).data('toggle', 1);
						}else{
							$('.loadEditFolder-' + $(this).data('id')).slideUp();
							$('.delete-item').slideDown();
							$(this).data('toggle', 0);
						}
						
					});
					$('a.delete-folder').click(function(){
						if(confirm('".RS_DELETE_CONFIRM."') == true){
							$('.deleteFolderLoad-' + $(this).data('id')).load($(this).data('url'));	
						}
					});
					$('a.delete-item').click(function(){
						if(confirm('".RS_DELETE_CONFIRM_ITEM."') == true){
							$('.deleteFolderLoad-' + $(this).data('id')).load($(this).data('url'));	
						}
					});
				}
		});
			var toggleNewFolder = false;
			function newFolder(){
				if(toggleNewFolder == false){
					$('.loadNewFolder').slideDown().load('".p::Url("?".$this->_data->_app.'/'.$this->_data->_section.'/new/'.str_replace('/', ':', $data['name']).'/ajaxLoad')."').slideDown();
					$('a.ttip-new-item').slideUp();
					toggleNewFolder = true;
				}else{
					$('.loadNewFolder').slideUp();
					$('a.ttip-new-item').slideDown();
					toggleNewFolder = false;
				}	
			}
			</script>");
	}

	protected function renderFolders(){
		$output = '';

		foreach($this->_data->_ruleSets AS $ruleset){

		$editLink = "<span class='float-right'><a href='javascript:void();' class='ttip_file' title='<div class=\"tooltipster-inner\">
		<a href=\"javascript:void(0)\" data-toggle=\"0\" data-loaded=\"0\" data-id=\"".$ruleset['id']."\" data-url=\"".p::Url("?".$this->_data->_app.'/'.$this->_data->_section.'/edit/'.str_replace('/', ":", $ruleset['name']).'/ajaxLoad')."\" class=\"ttip-sub nav edit-folder\">".(new pIcon('fa-external-link', 14))." Rename or move</a><div class=\"hide loadEditFolder-".$ruleset['id']."\">".LOADING."</div>
		<a href=\"javascript:void(0);\" class=\"delete-folder ttip-sub nav\" data-id=\"".$ruleset['id']."\" data-url=\"".p::Url("?".$this->_data->_app.'/'.$this->_data->_section.'/remove/'.$ruleset['id'].'/ajax')."\">".(new pIcon('fa-times', 12))."  Delete</a><div class=\"deleteFolderLoad-".$ruleset['id']."\"></div></div>'><span class='dType'>".(new pIcon('menu'))."".(new pIcon('menu-down', 20))."</span></a></span>";

		$output .= "<tr><td><input name='selectedfolders[]' value='".$ruleset['id']."' class='setselect' type='checkbox' /></td><td style='width: 15px;'>".(new pIcon('fa-folder', 12))."</td>
						<td style='width: 40%;'>".$this->breakDownName($ruleset['name'], $this->_data->_app, true)."</td>
			<td>".$editLink."</td><td></td></tr>";
		
		}

		return $output."<script type='text/javascript'>

		</script>";
	}

	public static function breakDownName($name, $app, $last = false){
		$output = "";
		$count = 0;
		// No slash at start then
		if(p::StartsWith($name, '/'))
			$name = substr($name, 1);
		$explode = explode('/', $name);
		$size = count($explode);
		$string = ':';
		if($last){
			$explode = array($explode[max(array_keys($explode))]);
			$size = 0;
		}
		foreach($explode as $subname){
			$output .= "<a href='".p::Url('?'.$app.'/browser/view/'.($last ? str_replace('/', ':', $name) : $string.$subname.":"))."'>".($last ? '' : '/')."$subname</a>";
			$string .= $subname.":";
			if($size > 1 AND $count != $size - 1)
				$output .=" → ";
			$count++;
		}
		return $output;
	}

	protected function renderRules(){

		$output = '';

		foreach($this->_data->_rules AS $rule){

			$editLink = "<span class='float-right'><a href='javascript:void();' class='ttip_file' title='<div class=\"tooltipster-inner\">
			
			<a href=\"javascript:void(0);\" class=\"delete-item ttip-sub nav\" data-id=\"".$rule['id']."\" data-url=\"".p::Url("?".$this->_data->_activeSection['editor'].'/'.$rule['set_type'].'/remove/'.$rule['id'].'/ajax')."\">".(new pIcon('fa-times', 12))."  ".DA_DELETE."</a><div class=\"deleteFolderLoad-".$rule['id']."\"></div></div>'><span class='dType'>".(new pIcon('menu'))."".(new pIcon('menu-down', 20))."</span></a></span>";

			$output .= "<tr><td><input name='selecteditems[]' value='".$rule['id']."' class='setselect' type='checkbox' /></td><td style='width: 15px;'>".(new pIcon($this->_data->_activeSection['sets'][$rule['set_type']][2], 12))."</td>
						<td style='width:40%'><a href='".p::Url('?'.$this->_data->_activeSection['editor'].'/'.$rule['set_type'].'/edit/'.$rule['id'])."'>".$rule[$this->_data->_activeSection['sets_name'][$rule['set_type']]]."</a></td>
			<td>".$editLink."<span class='dType'>".$this->_data->_activeSection['sets_strings'][$rule['set_type']]."</span></td><td></tr>";
		}

		return $output;

	}	

}