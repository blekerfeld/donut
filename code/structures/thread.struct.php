<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      dictionary.admin.struct.php

// The structure of the dictionary admin panel

return array(
		'MAGIC_META' => array(
			'title' => DA_TITLE,
			'icon' => 'fa-key',
			'default_permission' => 0,
			'save_strings' => array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK),
			'return' => array(
				'default' => '?thread/SECT/view/TID', 
				'lemma' => '?entry/lemma/ID/discuss',
			),
		),
	);