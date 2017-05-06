<?php


	if(isset(pAdress::arg()['ajax'])){
		
		die((new pTerminal)->ajax());

		if($line == 'clear')
			echo "<script>window.location = window.location;</script>";
		if($line == 'test')
			echo "test requires arguments: <br />test { inflection } { lexical form } <br/> ";
		if(pStartsWith($line, 'test ')){
			$explode = explode(' ', $line);
			$twolc = new pTwolc((new pTwolcRules('phonology_contexts'))->toArray());
			$twolc->compile();
			echo @$twolc->feed((new pInflection($explode[1]))->inflect($explode[2]))->toDebug()."<br />";
		}
		if(pStartsWith($line, 'pctest ')){
			$explode = explode(' ', $line);
			if(isset($explode[2]))
				$twolc = new pTwolc(array($explode[1]));
			else
				$twolc = new pTwolc((new pTwolcRules('phonology_contexts'))->toArray());
			$twolc->compile();
			if(isset($explode[2]))
				echo $twolc->feed($explode[2])->toDebug()."<br />";
			else
				echo $twolc->feed($explode[1])->toDebug()."<br />";
		}

		
	}


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

$voltooidDeelwoord = "ge-!^ver+-!^be+[-en]&D";
	$meervoud = '[-en]&EN';
	$dim = '[]&ETJE?$m;&ETJE?$ng:;nkje?$ng;etje?$an;tje?!$m?!$n?!$an?$uin;tje?$VOW;je?&ELSE';

	(new pTerminal)->initialState();