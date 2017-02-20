<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: dashboard.index.php
*/

	// WE ABSOLUTLY NEED TO BE LOGGED IN!!! (and to be either admin, editor or translator)
	if(!pLogged() OR !pCheckRole(pUser(), 1))
		pUrl('', true);

	pOut("<div class='title_header'><div class='icon-box fetch'><i class='fa fa-dashboard'></i></div> ".DB_TITLE."</div>", true);

	pOut('<div class="home-margin">');

	pOut('<div class="row-center"></div>');
	pOut('<div class="row-right"><strong></strong><br />
		</div>');
	pOut('<div class="row-left"><span class="pSectionTitle extra">Actions</span><div class="pSectionWrapper"><a href="'.pUrl('?addword').'" class="actionbutton"><i class="fa fa-plus-circle"></i> New word</a><a href="'.pUrl('?batch_translate').'" class="actionbutton"><i class="fa fa-language"></i> Batch translating</a><br /><br /><a href="'.pUrl('?generate').'" class="actionbutton"><i class="fa fa-history"></i> Generate LaTeX file</a></div></div><br id="cl" />');

	pOut('</div>');


	