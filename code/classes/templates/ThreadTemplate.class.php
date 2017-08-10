<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: ExampleTemplate.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pThreadTemplate extends pTemplate{

	public function renderAllMessages(){
		$id = spl_object_hash($this);
		p::Out("<div class='entryThreadHolder'>");
		if(isset($this->_data[0]))
			foreach($this->_data[0] as $message)
				p::Out($this->renderMessage($id, $message));
		else
			p::Out(pMainTemplate::NoticeBox('fa-info-circle', WD_NO_THREADS, 'warning-notice'));
		p::Out("<br />");
		if(pUser::checkPermission(-1))
			p::Out("");
		p::Out("</div>");
		p::Out("<a href='".p::Url("?thread/".pRegister::arg()['section'].'/new/'.pRegister::arg()['id'])."' class='btAction green no-float'>".(new pIcon('shape-square-plus', 14)).' '.WD_NEW_THREAD."</a><div class='ajax$id-loader'></div>");
		// Throwing this object's script into a session
		pRegister::session($id, $this->javascript($id));
		p::Out("<script type='text/javascript' src='".p::Url('pol://library/assets/js/key.js.php?key='.$id)."'></script>");
	}


	public function renderMessage($id, $message, $children = false){

		$loggedInUser = new pUser();
		$user = new pUser($message['user_id']);
		$output = '<div class="entryThread message" data-messageid="'.$message['id'].'"><div class="parent">';

		if($children == false)
			$output .= "<strong class='title'>".$message['title']."</strong>";

		$content = '<div class="content">'.p::Markdown($message['content'], false).'</div>'."<a href='".p::Url("?thread/".pRegister::arg()['section'].'/new/'.pRegister::arg()['id'])."/".$message['id']."' class='btAction no-float'>» Reply</a>";

		$title = '';

		// If role is administrator or this is the logged in users message
		if($loggedInUser->checkPermission(-4) OR $loggedInUser->read('id') == $message['user_id'])
			$title .= "<span class='xsmall float-right'><a data-deleteid='".$message['id']."' class='buttonTh delete".$id."' title='".WD_DELETE."' href='javascript:void(0)'>".(new pIcon('delete-circle', 22))."</a><a class='buttonTh' title='".WD_EDIT."' href='".p::Url("?thread/".pRegister::arg()['section'].'/edit/'.pRegister::arg()['id'])."/".$message['id']."'>".(new pIcon('pencil-circle', 22))."</a></span>";

		$title .= '<img class="avatar" src="'.p::Url($user->avatar()).'" /> <span class="title">'."<a href='".p::Url('?auth/profile/'.$user->read('id'))."' class='username'>".($user->read('longname') != '' ? $user->read('longname') : $user->read('username'))."</a> – <span class='small' style='font-weight: normal'>".p::Date($message['post_date'])."</span></span>";

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



	public function javascript($id){
		return "$('.delete$id').click(function(){
			if(confirm('".WD_DELETE_CONFIRM."') == true){
				$('.ajax$id-loader').load('".p::Url("thread/".pRegister::arg()['section']."/remove/0/".pRegister::arg()['id']."")."' + $(this).data('deleteid') + '/ajax');
				$('.message[data-messageid=\"' + $(this).data('deleteid') + '\"]').fadeOut();
			}
		
		});";

	}


}