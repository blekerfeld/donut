	
	// $voltooidDeelwoordUI_O = new pInflection("ge-!^ver+-!^be+-!^ge+[&UI=>o]");
	// $voltooidDeelwoordIE_O = new pInflection("ge-!^ver+-!^be+-!^ge+[&IE=>o]");
	// $voltooidDeelwoordE_E = new pInflection("ge-!^ver+-!^be+-!^ge+[&E=>e]");
	// $voltooidDeelwoordIJ_E = new pInflection("ge-!^ver+-!^be+-!^ge+[&IJ=>&EE]");

	// Rule variables: E -> e that doesn't need to be corrected, D -> becomes d or t by phonological rules

	$voltooidDeelwoord = "ge-!^ver+-!^be+[-en]&D";
	$meervoud = '[-en]&EN';
	$dim = '[]&ETJE-$m;&ETJE-$ng:;nkje-$ng;etje-$an;tje-!$m-!$n-!$an-$uin;tje-$VOW;je-&ELSE';
	
	pConsole($twolc->feed((new pInflection($voltooidDeelwoord))->inflect("maken"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($voltooidDeelwoord))->inflect("horen"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($voltooidDeelwoord))->inflect("ver+horen"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($voltooidDeelwoord))->inflect("be+horen"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("boom"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("kat"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("man"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("pan"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("zee"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("raam"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("lam"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("bom"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("stem"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("ding:"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("kip"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("foto"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("pinda"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("oma"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection($dim))->inflect("cd"))->toDebug()."<br />");
	
	global $time_start;		

	$time_end = microtime(true);

	pConsole("<br /> It took ".(($time_end - $time_start))." seconds to execute all this");