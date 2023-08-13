<?php
// Donut: open source dictionary toolkit
// version    0.13-dev
// author     Emma de Roo
// license    MIT
// file:      dictionary.admin.struct.php

// The structure of the dictionary admin panel

return array(
		'MAGIC_META' => array(
			'title' => DA_TITLE,
			'icon' => 'fa-key',
			'default_permission' => -1,
			'save_strings' => array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK),
			'return' => array(
				'default' => '?thread/SECT/view/TID', 
				'lemma' => '?entry/lemma/ID/discuss',
			),
			'permission' => array(
				'default' => -1,
				'lemma' => -2,
			)
		),
	);