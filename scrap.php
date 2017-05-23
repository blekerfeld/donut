<?php

p::Out("<div class='tabsHolder'>
	<div class='ctBar titles'>
		<a href='javascript:void();'  class='active' data-tab='translations'>Translations</a>
		<a href='javascript:void();' data-tab='examples'>Examples</a>
	</div>
	<div class='ctTabs'>
		<div class='ctTab' data-tab='translations'>
			dit zijn vertalingen.
		</div>
		<div class='ctTab' data-tab='examples'>
			dit zijn voorbeelden
		</div>
	</div>
</div>

<script type='text/javascript'>
	$('.tabsHolder').cardTabs();
</script>");