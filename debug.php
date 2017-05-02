<?php

$time_start = microtime(true); 

	// l&Ezen [-en;&E=a]

	$rules = array("CON.VOW_CON_+.VOW=>%%", 
		"CON_[aeou]_CON.+.&T,D=>%%", 
		"CON_&A,E,O,U_CON.+.&T,D=>%%", 
		"[x,k,f,s,c,h,p].+_&D_=>t",
		 "_z_+=>s",
		  "VOW_v_+=>f", 
		  "_z_:>=>s", 
		  "_v_:>=>f",
		"CON.VOW._b.b_+.&D,T=>b",
		 "CON.VOW_b.b_+.t=>b",
		  "*.CON_VOW_CON.:>=>%%",
		   "CON_&EE_z=>e",
		    "<:CON._VOW.VOW__CON.+=>$1");

	

	(new pMenuTemplate)->render();

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

	$twolc = new pTwolc((new pTwolcRules('phonology_contexts'))->toArray());
	$twolc2 = new pTwolc($rules2);

	$twolc->compile();
	$twolc2->compile();

	pOut("<div class='debugConsole'>");


	pConsole((new pIcon('fa-terminal', 12))." DONUT CONSOLE. donut ɑ.1 file: debug.php");
	pConsole("...");
	pConsole("<br/>");

	pConsole($twolc2->feed("school schol kat koning+s::huis")->toDebug()."<br />");

	pConsole($twolc->feed('kat+en')->toDebug()."<br />");

	$voltooidDeelwoord = new pInflection("ge-!^ver+-!^be+-!^ge+[-en]&D");	
	$voltooidDeelwoordUI_O = new pInflection("ge-!^ver+-!^be+-!^ge+[&UI=>o]");
	$voltooidDeelwoordIE_O = new pInflection("ge-!^ver+-!^be+-!^ge+[&IE=>o]");
	$voltooidDeelwoordE_E = new pInflection("ge-!^ver+-!^be+-!^ge+[&E=>e]");
	$voltooidDeelwoordIJ_E = new pInflection("ge-!^ver+-!^be+-!^ge+[&IJ=>&EE]");

	// Rule variables: E -> e that doesn't need to be corrected, D -> becomes d or t by phonological rules

	pConsole($twolc->feed((new pInflection("[]s"))->inflect("aardappel"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]en-!/je;s-/je"))->inflect("mannetje"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]en-!/je;s-/je"))->inflect("huis"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[]en"))->inflect("kind+er"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[]en"))->inflect("man"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[]en"))->inflect("ei+er"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[]en"))->inflect("boek"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[]en"))->inflect("leed"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]&T"))->inflect("l&Ezen"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[-en;&E=>a]"))->inflect("l&Ezen"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[-en;&IJ=>&EE]"))->inflect("w&IJzen"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[-en;]t"))->inflect("hebben"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]&De"))->inflect("werken"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]&De"))->inflect("tobben"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]&De"))->inflect("delen"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]&De"))->inflect("fotograferen"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[-en]&De"))->inflect("schilde#ren"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[-a]ą-\$a"))->inflect("kobieta"))->toDebug()."<br />");
	pConsole($twolc->feed((new pInflection("[]mi-\$a"))->inflect("kobieta"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("ver+huizen"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("duwen"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("maken"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("be+wonen"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("gooien"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("ver+draaien"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("ver+plaatsen"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("be+keren"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("ge+beuren"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("beven"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("geeuwen"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("tobben"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoord->inflect("horen"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordUI_O->inflect("be+sl&UIten"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordUI_O->inflect("sl&UIten"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordIE_O->inflect("b&IEden"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordIE_O->inflect("ge+n&IEten"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordIE_O->inflect("k&IEzen"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordE_E->inflect("l&Ezen"))->toDebug()."<br />");
	pConsole($twolc->feed($voltooidDeelwoordIJ_E->inflect("w&IJzen"))->toDebug()."<br />");

	$time_end = microtime(true);

	pConsole("<br /> It took ".(($time_end - $time_start))." seconds to execute all this");

	pOut("</div>");

