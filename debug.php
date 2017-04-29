<?php

$time_start = microtime(true); 


	// l&Ezen [-en;&E=a]

	$rules = array("CON.VOW_CON_+.VOW=>%%", "CON_[aeou]_CON.+.&T,D=>%%", "CON_&A,E,O,U_CON.+.&T,D=>%%", "[x,k,f,s,c,h,p].+_&D_=>t", "_z_+=>s", "VOW_v_+=>f", "_z_:>=>s", "_v_:>=>f",
		"CON.VOW._b.b_+.&D,T=>b", "CON.VOW_b.b_+.t=>b", "*.CON_VOW_CON.:>=>%%", "CON_&EE_z=>e", "<:CON._VOW.VOW__CON.+=>$1");

	$rules2 = array(
		"<:_o_CON.VOW=>oʊ", "CON_o_CON.VOW=>oʊ", "_o.o_=>oʊ",
		"<:_o_CON.CON=>ɔ", "CON_o_CON.CON=>ɔ", "CON_o_CON.:>=>ɔ",
		"<:_a_CON.VOW=>a:", "CON_a_CON.VOW=>a:", "_a.a_=>a:",
		"<:_a_CON.CON=>ɑ", "CON_a_CON.CON=>ɑ", "CON_a_CON.:>=>ɑ",
		"_s.c.h_=>sχ",
		"_n.g_=>ŋ",
		"_o.e_=>u",
		"_i.e_=>i:",
		"_u.i_=>œy̯:",
		"_[:].[:]_=> · "
		);

	$twolc = new pTwolc($rules);
	$twolc2 = new pTwolc($rules2);

	$twolc->compile();
	$twolc2->compile();

	pOut("<div class='debugConsole'>");

	pOut("<span style='color: #fff'>".new pIcon('fa-book', 256)." hoi</span>");

	pConsole((new pIcon('fa-terminal', 12))." DONUT CONSOLE. donut ɑ.1 file: debug.php");
	pConsole("...");
	pConsole("<br/>");

	pConsole($twolc2->feed("school schol kat koning+s::huis")->toSurface()."<br />");

	pConsole($twolc->feed('kat+en')->toSurface()."<br />");

	$voltooidDeelwoord = new pInflection("ge-!^ver+-!^be+-!^ge+[-en]&D");	
	$voltooidDeelwoordUI_O = new pInflection("ge-!^ver+-!^be+-!^ge+[&UI=>o]");
	$voltooidDeelwoordIE_O = new pInflection("ge-!^ver+-!^be+-!^ge+[&IE=>o]");
	$voltooidDeelwoordE_E = new pInflection("ge-!^ver+-!^be+-!^ge+[&E=>e]");
	$voltooidDeelwoordIJ_E = new pInflection("ge-!^ver+-!^be+-!^ge+[&IJ=>&EE]");

	// Rule variables: E -> e that doesn't need to be corrected, D -> becomes d or t by phonological rules

	pConsole($twolc->feed((new pInflection("[]s"))->inflect("aardappel"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[]en"))->inflect("kind+er"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[]en"))->inflect("ei+er"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[]en"))->inflect("boek"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[]en"))->inflect("leed"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]&T"))->inflect("l&Ezen"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[-en;&E=>a]"))->inflect("l&Ezen"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[-en;&IJ=>&EE]"))->inflect("w&IJzen"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[-en;]t"))->inflect("hebben"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]&De"))->inflect("werken"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]&De"))->inflect("tobben"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]&De"))->inflect("delen"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]&De"))->inflect("fotograferen"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]&De"))->inflect("schilde#ren"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[-a]ą-\$a"))->inflect("kobieta"))->toSurface()."<br />");
	pConsole($twolc->feed((new pInflection("[]mi-\$a"))->inflect("kobieta"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("ver+huizen"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("duwen"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("maken"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("be+wonen"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("gooien"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("ver+draaien"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("ver+plaatsen"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("be+keren"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("ge+beuren"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("beven"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("geeuwen"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("tobben"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("horen"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordUI_O->inflect("be+sl&UIten"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordUI_O->inflect("sl&UIten"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordIE_O->inflect("b&IEden"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordIE_O->inflect("ge+n&IEten"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordIE_O->inflect("k&IEzen"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordE_E->inflect("l&Ezen"))->toSurface()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordIJ_E->inflect("w&IJzen"))->toSurface()."<br />");

	$time_end = microtime(true);

	pConsole("<br /> It took ".(($time_end - $time_start))." seconds to execute all this");

	pOut("</div>");

