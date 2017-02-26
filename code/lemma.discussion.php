
<?php 

pDiscussionStructure($_REQUEST['discuss-lemma'], '', 'discuss-lemma', true, false);

if(is_numeric($_REQUEST['discuss-lemma']))
	$lemma = $_REQUEST['discuss-lemma'];
elseif(ctype_alnum($_REQUEST['discuss-lemma'])){
	$lemma = pHashId($_REQUEST['discuss-lemma'], true);
	if(!($lemma = $lemma[0]))
		pUrl('', true);
}

$word = pGetWord($lemma);
	//pUrl('', true);

// Search area
if(isset($_GET['searchresult']))
	pDictionaryHeader($_SESSION['search']);
else
	pDictionaryHeader('');






// Start the layout stuff

pOut('<div class="home-margin"><div class="notice hide" style="display: none;" id="loading"><i class="fa fa-spinner fa-spin"></i> '.LOADING.'</div>
	<br id="cl loading" style="display: none;"/><div class="ajaxload" style="display: none;"></div>
      <div class="drop">');

	// Lemma-code
	pOut('<a class="lemma-code float-right" href="'.pUrl('?lemma='.pHashId($word->id)).'"title="'.$word->id.'"><i class="fa fa-bookmark-o"></i> '.pHashId($word->id).'</a>');
	// Discussion button
	if(pLogged())
		pOut('
			<a class="lemma-code discussion float-right" href="'.pUrl('?edit-lemma='.$_REQUEST['discuss-lemma']).'"><i class="fa fa-pencil-square-o "></i> '.LEMMA_EDIT.'</a><a class="lemma-code discussion float-right" href="'.pUrl('?discuss-lemma='.$_REQUEST['discuss-lemma']).'"><i class="fa fa-comments"></i> '.WD_TITLE.'</a>
			');

pOut('<div class="title"><div class="icon-box throw"><i class="fa fa-comments"></i></div> '.sprintf(WD_TITLE_MORE, pWordLinks("{{".$word->id."}}")).'</div><br />'.
	"<a class='float-left back-mini search' href='".pUrl('?lemma='.pHashId($word->id))."'><i class='fa fa-arrow-left' style='font-size: 12px!important;'></i></a>");


// // Template stuff
// pOut('<div class="title"><div class="icon-box throw"><i class="fa fa-comments"></i></div> '..'</div>');
      
// // Back buttons
// 		pOut("<a class='float-left back-mini'  href='".pUrl('?lemma='.$_REQUEST['discuss-lemma'])."'><i class='fa fa-arrow-left' ></i></a>");





	pDiscussionStructure($_REQUEST['discuss-lemma'], '', 'discuss-lemma', false, true);


	pOut(pSearchScript("&wordsearch=".$_REQUEST['discuss-lemma']));

	pOut('</div></div><br id="cl"/>');





