<?php
// Donut: open source dictionary toolkit
// version    0.12-dev
// author     Thomas de Roo
// license    MIT
// file:      dictionary.admin.struct.php

// The structure of the dictionary admin panel

return [
		'MAGIC_META' => [
			'title' => DA_TITLE,
			'icon' => 'fa-key',
			'default_permission' => 0,
			'permission' => [
				'article' => 0,
			],
			'tabs' => new pTabBar('','', false, 'titles above wordsearch nomargin'),
			'tab_search' => true,
			'tab_home' => true,
		],
		'article' => [
			'section_key' => 'article',
			'type' => 'pArticleHandler',
			'view' => 'pArticleView',
			'table' => 'articles',
			'icon' => 'fa-font',
			'surface' => "Inflection",
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'show_tab' => true,
			'actions_item' => [],
			'actions_bar' => [],
			'tab_sections' => ['article'],
			'tab_name' => LEMMA_VIEW_SHORT,
			'tab_link' => p::Url('?wiki/article/'),
			'tab_addargs' => ['url', 'language'],
		],
		'history' => [
			'section_key' => 'history',
			'type' => 'pArticleHandler',
			'view' => 'pArticleView',
			'table' => 'articles',
			'icon' => 'fa-font',
			'surface' => "Inflection",
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'actions_item' => [],
			'show_tab' => true,
			'actions_bar' => [],
			'tab_name' =>  WIKI_HISTORY,
			'tab_link' => p::Url('?wiki/history/'),
			'tab_addargs' => ['url', 'language'],
		],
		'editor' => [
			'section_key' => 'editor',
			'type' => 'pArticleHandler',
			'view' => 'pArticleView',
			'table' => 'articles',
			'icon' => 'fa-font',
			'surface' => "Inflection",
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'actions_item' => [],
			'show_tab' => true,
			'actions_bar' => [],
			'tab_name' =>  WIKI_EDIT,
			'tab_link' => p::Url('?wiki/editor/'),
			'tab_addargs' => ['url', 'language'],
		],
	];