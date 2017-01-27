<?php

// This is just a lazyass redirect

if(isset($donut['request']['ajax_pOut']))
	echo "<script>loadfunction('".pUrl('index.php?home&search='.$donut['get']['search'])."');</script>";
else
	pUrl('index.php?home&search='.$donut['get']['search'], true);