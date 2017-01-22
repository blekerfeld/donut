<?php

// This is just a lazyass redirect

if(isset($_REQUEST['ajax_pOut']))
	echo "<script>loadfunction('".pUrl('index.php?home&search='.$_GET['search'])."');</script>";
else
	pUrl('index.php?home&search='.$_GET['search'], true);