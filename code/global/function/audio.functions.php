<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under MIT
	File: audio.functions.php
*/


// For audiofiles
function pAudioFramework($element, $audiofile){
	if(pStartsWith($audiofile, 'http://') or pStartsWith($audiofile, 'https://'))
		$file = $audiofile;
	else
		$file = pUrl('pol://library/audio/'.$audiofile);
	return "<script>
		$('.".$element."').click(function(){
			$.playSound('".$file."', function(){
				$(document.body).css({ 'cursor': 'default' });
			});

	});</script>";
}

// Getting audio players for each audio file


function pGetAudioPlayers($id, $idiom = false){

	

	if($idiom)
		$audiofiles = pQuery("SELECT * FROM audio_idiom WHERE idiom_id = $id LIMIT 1;");
	else
		$audiofiles = pQuery("SELECT * FROM audio_words WHERE word_id = $id;");


	if($audiofiles->rowCount() == 0)
		return false; 

	$extra = pHashId(rand(3,8));

	if(!$idiom){
		$return = '<ol>';
		foreach($audiofiles->fetchAll() as $audiofile){
			$return .= "<li><a class='small tooltip audio_".$audiofile['id'].$extra."'>[<i class='fa  fa-8 fa-play'></i> PLAY]".(($audiofile['description'] != '') ? " <em class='small'>(".$audiofile['description'] .")</em>" : "")."</a></li>".pAudioFramework("audio_".$audiofile['id'].$extra , $audiofile['audio_file']);
		}
		return $return.'</ol>';
	}
	else{
		$return = '';
		foreach($audiofiles->fetchAll() as $audiofile){
			$return .= "<a class='small tooltip audio_".$audiofile['id'].$extra."'>[<i class='fa  fa-8 fa-play'></i> PLAY]".(($audiofile['description'] != '') ? " <em class='small'>(".$audiofile['description'] .")</em>" : "")."</a>".pAudioFramework("audio_".$audiofile['id'].$extra , $audiofile['audio_file']);
		}
		return $return;
	}
	

}