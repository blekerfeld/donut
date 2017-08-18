
<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      StatsTemplate.class.php

class pStatsTemplate extends pSimpleTemplate{

	protected $_user;

	protected function statsEntry($title, $extra, $padding = 40){
		p::Out("<div class='statsEntry' style='padding: ".$padding."px;'><span class='native'><strong class='xmedium'><a>$title</a></strong></span><br /><span class=''>".$extra."</span></div>");
	}

	public function renderSearch(){
		p::Out("<div class='home-margin hSearchResults-inner'>");
		p::Out("<div class='card-tabs-bar titles'>
			<a class='ssignore'>Hoi</a>
		</div><br />");
		p::Out("<span class='markdown-body'><h3>".(new pIcon('fa-search'))." ".sprintf(STATS_MOSTSEARCH, 50)."</h3></span><br /><div class='statsHolder'>");

		$userCollect = array();

		$max = $this->_data[0]['num_word'];

		foreach($this->_data as $word)
			$this->statsEntry(((new pLemma($word['word_id']))->renderSimpleLink()), "(".$word['num_word']." ".($word['num_word'] != 1 ? TIMES : TIME).")", (($word['num_word'] * ($max * 2) < 40) ? $word['num_word'] * 10 : 40));
		p::Out("<br id='cl'/></div></div>");
	}


	public function renderAll(){

		var_dump($this);

	}

}