<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: ExampleTemplate.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pThreadTemplate extends pTemplate{

	public function renderAllMessages(){
		$id = spl_object_hash($this);
		p::Out("<div class='ajax$id-loader'></div>");
		p::Out("<div class='entryThreadHolder'>");
		if(isset($this->_data[0]))
			foreach($this->_data[0] as $message)
				p::Out($this->renderMessage($id, $message));
		else
			p::Out(pMainTemplate::NoticeBox('fa-info-circle', WD_NO_THREADS, 'warning-notice'));
		p::Out("<br />");
		p::Out($this->newParentForm());
		p::Out("</div>");
		// Throwing this object's script into a session
		pAdress::session($id, $this->javascript($id));
		p::Out("<script type='text/javascript' src='".p::Url('pol://library/assets/js/key.js.php?key='.$id)."'></script>");
	}


	public function renderMessage($id, $message, $children = false){

		$loggedInUser = new pUser();
		$user = new pUser($message['user_id']);
		$output = '<div class="entryThread message" data-messageid="'.$message['id'].'"><div class="parent">';

		$content = '<img class="avatar" src="'.p::Url($user->avatar()).'" /><div class="content">'.p::Markdown($message['content'], false).'</div><br id="cl" />';

		$title = '';

		// If role is administrator or this is the logged in users message
		if($loggedInUser->checkPermission(-4) OR $loggedInUser->read('id') == $message['user_id'])
			$title .= "<span class='xsmall float-right'><a data-deleteid='".$message['id']."' class='delete".$id."' href='javascript:void(0)'>[".strtolower(WD_DELETE)."]</a></span>";

		$title .= "<a href='".p::Url('?auth/profile/'.$user->read('id'))."' class='username'>".($user->read('longname') != '' ? $user->read('longname') : $user->read('username'))."</a> – <span class='small' style='font-weight: normal'>".p::Date($message['post_date'])."</span>";

		$output .= (string)(new pEntrySection($title, $content));

		$output .= "</div>";

		if(isset($this->_data[$message['id']]) and is_array($this->_data[$message['id']])){
		$output .="<div class='children ".($children ? '' : 'main')."'>";
			foreach($this->_data[$message['id']] as $message)
				$output .= $this->renderMessage($id, $message, true);
			$output.="</div>";
		}
		$output .= "</div>";

		unset($user);
		return $output;

	}

	public function newParentForm(){

		return "<div class='entryThread'>
			<div class='parent'>
			".(new pEntrySection('New entry', 'x'))."
			</div>
		</div>";
	}

	public function javascript($id){
		return "$('.delete$id').click(function(){
			if(confirm('".WD_DELETE_CONFIRM."') == true){
				$('.ajax$id-loader').load('".p::Url("thread/".pAdress::arg()['section']."/".pAdress::arg()['id']."/remove/")."' + $(this).data('deleteid') + '/ajax');
				$('.message[data-messageid=\"' + $(this).data('deleteid') + '\"]').fadeOut();
			}
		
		});";

	}


}