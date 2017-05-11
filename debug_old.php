	
	// $voltooidDeelwoordUI_O = new pInflection("ge-!^ver+-!^be+-!^ge+[&UI=>o]");
	// $voltooidDeelwoordIE_O = new pInflection("ge-!^ver+-!^be+-!^ge+[&IE=>o]");
	// $voltooidDeelwoordE_E = new pInflection("ge-!^ver+-!^be+-!^ge+[&E=>e]");
	// $voltooidDeelwoordIJ_E = new pInflection("ge-!^ver+-!^be+-!^ge+[&IJ=>&EE]");

	// Rule variables: E -> e that doesn't need to be corrected, D -> becomes d or t by phonological rules

	$voltooidDeelwoord = "ge-!^ver+-!^be+[-en]&D";
	$meervoud = '[-en]&EN';
	$dim = '	';
	
	p::Out($twolc->feed((new pInflection($voltooidDeelwoord))->inflect("maken"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($voltooidDeelwoord))->inflect("horen"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($voltooidDeelwoord))->inflect("ver+horen"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($voltooidDeelwoord))->inflect("be+horen"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("boom"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("kat"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("man"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("pan"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("zee"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("raam"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("lam"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("bom"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("stem"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("ding:"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("kip"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("foto"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("pinda"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("oma"))->toDebug()."<br />");
	p::Out($twolc->feed((new pInflection($dim))->inflect("cd"))->toDebug()."<br />");
	
	global $time_start;		

	$time_end = microtime(true);

	p::Out("<br /> It took ".(($time_end - $time_start))." seconds to execute all this");