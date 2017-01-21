<?php
/*
|| Aulis
|| Organisation:		Aulis International
|| Website:				http://germanics.org/aulis
|| Developed by:	 	Robert Monden
						Thomas de Roo
|| License: 			MIT
|| Version: 			0.01
||
||------------------------------------------------
|| ENGLISH TRANSLATION - BLOG
||------------------------------------------------
|| Software version:	0.01
|| Version:				1.0.0
|| Dialect:				American English
|| Translators:			Thomas de Roo
||						Robert Monden
|| License:				MIT
*/

// We can't access this file, if not from index.php, so let's check
if(!defined('aulis'))
	header("Location: index.php");
	
// Global blog strings
define('BLOG_ENTRY', "entry");
define('BLOG_ENTRIES', "entries");
define('BLOG_NO_ENTRIES_FOUND', "No entries have been found.");
define('BLOG_NOT_FOUND', "The requested blog entry has not been found, we are sorry.");
define('BLOG_EMPTY', "The requested blog entry is empty and cannot be shown.");
define('BLOG_ABOUT', "About this blog");
define('BLOG_READMORE', "Read more...");

// Blog information
define('BLOG_POSTED_IN', "Posted in %s");

// The links
define('BLOG_OLDER_ENTRIES', '￩ Older Entries');
define('BLOG_NEWER_ENTRIES', 'Newer Entries ￫');
define('BLOG_BACK_TO_INDEX', ' Back to blog');

// Search results
define('BLOG_SEARCH', 'Search');
define('BLOG_SEARCH_BTN', 'Search');
define('BLOG_SEARCH_TITLE', 'Search results for %s');
define('BLOG_SEARCH_NO_ENTRIES', 'Searching for \'%s\' returned nothing.');
define('BLOG_SEARCH_FOUND_HITS', 'Your search query returned %s %s');
define('BLOG_SEARCH_FOUND_HITS_PLURAL', 'results');
define('BLOG_SEARCH_FOUND_HITS_SINGULAR', 'result');

// Category view
define('BLOG_CATEGORIES', 'Categories');
define('BLOG_CATEGORIES_NONE', 'There are no categories');
define('BLOG_CATEGORY_TITLE', 'Entries categorized as %s');
define('BLOG_CATEGORY_NO_ENTRIES', 'This category is empty.');


define('BLOG_FOUND_HITS', 'Displaying %s %s');
define('BLOG_FOUND_HITS_SINGULAR', BLOG_ENTRY);
define('BLOG_FOUND_HITS_PLURAL', BLOG_ENTRIES);

// Comments
define('BLOG_COMMENT', 'Comment');
define('BLOG_COMMENTS', 'Comments');
define('BLOG_NO_COMMENTS', 'No comments yet');