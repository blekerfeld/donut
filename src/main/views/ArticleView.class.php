<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: Entry.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pArticleView extends pView{


	private function languageList($allLang = false){
		$output ="<a class='ttip_wikiL wSideButton' href='javascript:void(0);'>".(new pIcon('translate', 12))." ".WIKI_OTHER_LANGS."</a><div class='hide wLaContent'><div class='tooltipster-inner-menu'>";
		foreach(($allLang ? pLanguage::allActive(-1) : $this->_data->_articleLanguages) as $lang){
			if($this->_data->_activeLocale == $lang->read('locale'))
				$output .= "<a class='ttip-sub active' href='javascript:void(0)'>".(new pDataField(null, null, null, 'flag'))->parse($lang->read('flag'))." ".$lang->read('name')."</a>";
			else
				$output .= "<a class='ttip-sub' href='".p::Url('?wiki/'.pRegister::arg()['section'].'/'.$this->_data->_articleMeta['url'].'/'.strtolower($lang->read('locale')))."'>".(new pDataField(null, null, null, 'flag'))->parse($lang->read('flag'))." ".$lang->read('name')."</a>";
		}
		return $output."</div></div><script type='text/javascript'>
			$('.ttip_wikiL').tooltipster({animation: 'grow', animationDuration: 100,  distance: 0, content: $('.wLaContent').html(), contentAsHTML: true, interactive: true, side:'bottom', trigger: 'click'});
		</script>";
	}

	private function actionUrl($action, $revision = null){
		if($revision == null)
			$revision = $this->_data->_activeRevision;
		return p::Url('?wiki/article/'.$this->_data->_articleMeta['url'].'/'.$this->_data->_activeLocale.'/revision:'.p::hashId($revision).'/'.$action);
	}

	private function sideBar($languageList = false){
		$output = '';
		$output .= $this->languageList($languageList);
		return $output;
	}

	public function renderView(){

		// Show a perma link warning
		if($this->_data->_isPermaLink)
			p::Out(pTemplate::NoticeBox('fa-', WIKI_PERMA_WARN, 'notice medium'));

		if(isset($this->_data->_articleProper['name']) AND trim($this->_data->_articleProper['name']) != '')
			$title = $this->_data->_articleProper['name'];
		else
			$title = $this->_data->_articleMeta['name'];

		p::Out("<div class='wiki'><div class='side'>".$this->sideBar()."</div></div><div class='main'><div class='home-margin wiki'><h2>".$title."</h2>");
		p::Out("<p>".p::Markdown($this->_data->_articleProper['content'])."</p></div></div>");

		return true;

	}

	public function renderEditor(){
		if(isset($this->_data->_articleProper['name']) AND trim($this->_data->_articleProper['name']) != '')
			$title = $this->_data->_articleProper['name'];
		else
			$title = $this->_data->_articleMeta['name'];

		p::Out("<div class='wiki'><div class='side'>".$this->sideBar(true)."</div><div class='main'>");
		
		// Let's make a nice form! :D 
		p::Out("<h2>".$title."</h2><div class='home-margin wiki'>
					<div class='btRegister_inner'>
					<div class='btTitle'>".(new pIcon('fa-pencil'))." ".sprintf(WIKI_EDIT_ARTICLE, $title)."</div>
						<div class='btForm'>
						<div class='dotsc hide'>".pTemplate::loadDots()."</div>	
						<div class='ajaxLoad'></div>
						<div class='btSource'><span class='btLanguage'>".WIKI_CONTENT.": <span class='xsmall darkred'>*</span></span><br />
						<span class='btNative'><textarea class='gtEditor content elastic allowtabs'>".$this->_data->_articleProper['content']."</textarea></span></div>
						<div class='btSource'><span class='btLanguage'>".WIKI_TITLE.":</span><br />
						<span class='btNative'><input class='btInput nWord medium  normal-font title' value='".p::Escape($title)."' /></span></div>
						<div class='btSource accent'><span class='btLanguage'>".WIKI_REV_NOTES." / URL</span><br />
						<span class='btNative'><input class='btInput nWord medium  normal-font revnote' placeholder='".WIKI_REV_NOTES_DESC."' style='width:55%'/>    <input style='width:40%' class='btInput nWord medium  normal-font url'/></span></div>
						<div class='btButtonBar'>
						<a  class='btAction medium blue no-float not-smooth register-button'>".(new pIcon('publish', 12))." ".WIKI_PUBLISH."</a> ".STR_OR." <a href='".p::Url('?auth/login')."'>".WIKI_PREVIEW."</a>
			
		</div>
					</div>
		</div><script type='text/javascript'>
		$(document).ready(function(){
    		$('.elastic').elastic();
		});
		".pTemplate::allowTabs()."
		$('.saving').hide();
		$('.register-button').click(function(){
			$('.dotsc').slideDown();
			$('.ajaxMessage').slideUp();
			$('.ajaxLoad').delay(1000).load('".p::Url("?auth/register/ajax")."', {
					'username': $('.username').val(),
					'password': $('.password').val(),
					'password2': $('.password2').val(),
					'email': $('.email').val(),
					'fullname': $('.fullname').val(),
				});
			});
		</script>");


		p::Out("</div>");

		return true;
	}

	public function renderHistory(){
		
		// Show a perma link warning
		if($this->_data->_isPermaLink)
			p::Out(pTemplate::NoticeBox('fa-link', WIKI_PERMA_WARN, 'notice medium'));

		if(isset($this->_data->_articleProper['name']) AND trim($this->_data->_articleProper['name']) != '')
			$title = $this->_data->_articleProper['name'];
		else
			$title = $this->_data->_articleMeta['name'];

		p::Out("<div class='wiki'><div class='side'>".$this->sideBar()."</div></div><div class='main'><div class='home-margin wiki'><h2>".$title."</h2><strong><span class='xmedium'>".WIKI_HISTORY_OVERVIEW."</span></strong><br /><br /><table class='admin'>
			<tr class='title'><td>".WIKI_DATE."</td><td>".ACTIONS."</td><td>".WIKI_REV_SIZE."</td><td>".WIKI_REV_AUTHOR."</td><td>".WIKI_REV_NOTES."</td></tr>");
		
		$endHistory = key(array_slice($this->_data->_history, -1, 1, true));

		foreach($this->_data->_history as $i => $revision){
			if(isset($this->_data->_history[$i + 1]))
				$previousChars = strlen($this->_data->_history[$i + 1]['content']);
			else
				$previousChars = 0;
			if(strlen($revision['content']) > $previousChars)
				$mutationString = "<span class='wMutation green'>+".(strlen($revision['content']) - $previousChars)."</span>";
			elseif(strlen($revision['content']) < $previousChars)
				$mutationString = "<span class='wMutation red'>-".($previousChars - strlen($revision['content']))."</span>";
			else
				$mutationString = "<span class='wMutation gray'>0</span>";

			p::Out("<tr><td><a href='".p::Url('?wiki/article/'.$this->_data->_articleMeta['url'].'/'.strtolower($this->_data->_activeLocale).'/'.($this->_data->_activeRevision == $revision['id'] ? '' : 'revision:'.p::hashId($revision['id'])))."'>".p::Date($revision['revision_date'])."</td>".(($endHistory != $i) ? "<td>".($revision['is_undone'] == 0 ? "<a href='".$this->actionUrl('undo', $revision['id'])."' class='tooltip'>".(new pIcon('undo'))." ".WIKI_UNDO."</a>" : "—")."</td>" : '<td>—</td>')."<td>".strlen($revision['content'])." ".WIKI_REV_BYTES." (".$mutationString.")</td><td>".(new pUser($revision['user_id']))->linkOOP()."</td><td><span class='wNote'><em class='small'>".$revision['revision_note']."</em></span></td></tr>");
		}

		p::Out("</table></div></div>");

		return true;
	}
	
}