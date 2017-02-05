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
	return "<script>
		$('.".$element."').click(function(){
			$.playSound('".pUrl('pol://library/audio/'.$audiofile)."', function(){
				$(document.body).css({ 'cursor': 'default' });
			});

	});</script>";
}

// Getting audio players for each audio file


function pGetAudioPlayers($id, $idiom = false){

	$return = '<ol>';

	if($idiom)
		$audiofiles = pQuery("SELECT * FROM audio_idiom WHERE idiom_id = $id LIMIT 1;");
	else
		$audiofiles = pQuery("SELECT * FROM audio_words WHERE word_id = $id;");


	if(!$idiom)
		foreach($audiofiles->fetchAll() as $audiofile)
			$return .= "<li><a class='small tooltip audio_".$audiofile['id']."'>[<i class='fa  fa-8 fa-play'></i> PLAY]".(($audiofile['description'] != '') ? " <em class='small'>(".$audiofile['description'] .")</em>" : "")."</a></li>".pAudioFramework("audio_".$audiofile['id'] , $audiofile['audio_file']);
	else
		return $audiofiles;
	
	return $return.'</ol>';
}